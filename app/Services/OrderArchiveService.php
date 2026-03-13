<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrderArchiveService
{
    public function archiveCompletedOrders(): int
    {
        if (! schema_table_exists_cached('orders')) {
            return 0;
        }

        $archivedCount = 0;

        Order::query()
            ->with('damagePayment')
            ->where('status_pembayaran', Order::PAYMENT_PAID)
            ->whereIn('status_pesanan', [
                Order::STATUS_RETURNED_OK,
                Order::STATUS_RETURNED_DAMAGED,
                Order::STATUS_RETURNED_LOST,
                Order::STATUS_OVERDUE_DAMAGE_INVOICE,
            ])
            ->orderBy('id')
            ->chunkById(100, function ($orders) use (&$archivedCount) {
                foreach ($orders as $order) {
                    if ($order->hasOutstandingDamageFee()) {
                        continue;
                    }

                    $beforeStatus = (string) ($order->status_pesanan ?? '');
                    $beforeState = (string) ($order->status ?? '');
                    if ($beforeStatus === Order::STATUS_COMPLETED && $beforeState === 'completed') {
                        continue;
                    }

                    $order->forceFill([
                        'status_pesanan' => Order::STATUS_COMPLETED,
                        'status' => 'completed',
                    ])->saveQuietly();

                    admin_audit('order.auto_archive', 'orders', $order->id, [
                        'before_status_pesanan' => $beforeStatus,
                        'after_status_pesanan' => Order::STATUS_COMPLETED,
                        'month_key' => $this->resolveArchiveDate($order)->format('Y-m'),
                    ], null);

                    $archivedCount++;
                }
            });

        return $archivedCount;
    }

    public function buildMonthlyRecaps(int $months = 6): Collection
    {
        if (! schema_table_exists_cached('orders')) {
            return collect();
        }

        $months = max($months, 1);
        $rangeStart = now()->copy()->startOfMonth()->subMonthsNoOverflow($months - 1)->startOfMonth();
        $rangeEnd = now()->copy()->endOfMonth();

        $completedOrders = Order::query()
            ->with('user:id,name')
            ->withSum('items as units_total', 'qty')
            ->where('status_pembayaran', Order::PAYMENT_PAID)
            ->where('status_pesanan', Order::STATUS_COMPLETED)
            ->where(function ($query) use ($rangeStart, $rangeEnd) {
                $query->whereBetween('returned_at', [$rangeStart, $rangeEnd])
                    ->orWhere(function ($fallbackQuery) use ($rangeStart, $rangeEnd) {
                        $fallbackQuery->whereNull('returned_at')
                            ->whereBetween('updated_at', [$rangeStart, $rangeEnd]);
                    });
            })
            ->orderByDesc('updated_at')
            ->get();

        return $completedOrders
            ->groupBy(fn (Order $order) => $this->resolveArchiveDate($order)->format('Y-m'))
            ->sortKeysDesc()
            ->map(function (Collection $orders, string $monthKey) {
                $anchor = Carbon::createFromFormat('Y-m', $monthKey)->startOfMonth();
                $sortedOrders = $orders
                    ->sortByDesc(fn (Order $order) => $this->resolveArchiveDate($order)->timestamp)
                    ->values();

                return [
                    'month_key' => $monthKey,
                    'label' => $anchor->translatedFormat('F Y'),
                    'period_label' => $anchor->copy()->startOfMonth()->translatedFormat('d M') . ' - ' . $anchor->copy()->endOfMonth()->translatedFormat('d M Y'),
                    'orders_count' => $orders->count(),
                    'units_total' => (int) $orders->sum(fn (Order $order) => (int) ($order->units_total ?? 0)),
                    'revenue_total' => (int) $orders->sum(fn (Order $order) => (int) $order->grand_total),
                    'latest_orders' => $sortedOrders
                        ->take(8)
                        ->values()
                        ->map(function (Order $order, int $index) {
                            $customerName = trim((string) ($order->user?->name ?? 'Pelanggan'));
                            $customerLabel = Str::of($customerName)
                                ->squish()
                                ->limit(28, '')
                                ->value();

                            return [
                                'id' => $order->id,
                                'archive_label' => 'P' . ($index + 1) . '-' . $customerLabel,
                                'customer_name' => $customerName,
                                'order_number' => $order->order_number ?? ('ORD-' . $order->id),
                                'archive_date' => $this->resolveArchiveDate($order)->copy(),
                            ];
                        }),
                ];
            })
            ->values();
    }

    public function resolveArchiveDate(Order $order): Carbon
    {
        return $order->returned_at?->copy()
            ?? $order->updated_at?->copy()
            ?? $order->created_at?->copy()
            ?? now();
    }
}
