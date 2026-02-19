<?php

namespace App\Services;

use App\Models\Equipment;
use App\Models\EquipmentMaintenanceWindow;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class AvailabilityService
{
    public const HOLD_WINDOW_MINUTES = 60;

    public const BUFFER_DAYS = 1;

    private const BLOCKING_ORDER_STATUSES = [
        'diproses',
        'lunas',
        'barang_diambil',
        'barang_rusak',
    ];

    public function evaluateRange(Equipment $equipment, Carbon|string $startDate, Carbon|string $endDate, int $requestedQty = 1, ?int $ignoreOrderId = null): array
    {
        $start = $this->normalizeDate($startDate);
        $end = $this->normalizeDate($endDate);
        $requestedQty = max($requestedQty, 1);

        if (! $start || ! $end || $end->lt($start)) {
            return [
                'ok' => false,
                'reason' => 'invalid_date_range',
                'conflicts' => [],
                'daily' => [],
            ];
        }

        if (($equipment->status ?? 'ready') !== 'ready') {
            return [
                'ok' => false,
                'reason' => ($equipment->status ?? 'unavailable') === 'maintenance' ? 'maintenance' : 'equipment_unavailable',
                'conflicts' => $this->dateRangeKeys($start, $end),
                'daily' => [],
            ];
        }

        $daily = $this->getDailyReservedUnits($equipment, $start, $end, $ignoreOrderId);
        $conflicts = [];

        foreach ($this->dateRangeKeys($start, $end) as $dateKey) {
            $reserved = (int) data_get($daily, $dateKey . '.qty', 0);
            $available = max((int) $equipment->stock - $reserved, 0);

            if ($requestedQty > $available) {
                $conflicts[] = $dateKey;
            }
        }

        return [
            'ok' => $conflicts === [],
            'reason' => $conflicts === [] ? null : 'insufficient_stock',
            'conflicts' => $conflicts,
            'daily' => $daily,
        ];
    }

    public function getDailyReservedUnits(
        Equipment $equipment,
        Carbon|string $startDate,
        Carbon|string $endDate,
        ?int $ignoreOrderId = null,
        ?int $excludeUserId = null
    ): array {
        $start = $this->normalizeDate($startDate);
        $end = $this->normalizeDate($endDate);
        if (! $start || ! $end || $end->lt($start)) {
            return [];
        }

        $windowStart = $start->copy()->subDay();
        $windowEnd = $end->copy();
        $daily = [];

        $items = $this->fetchBlockingOrderItems($equipment->id, $windowStart, $windowEnd, $ignoreOrderId, $excludeUserId);
        foreach ($items as $item) {
            $order = $item->order;
            $bookingStart = $this->normalizeDate($item->rental_start_date ?? $order?->rental_start_date);
            $bookingEnd = $this->normalizeDate($item->rental_end_date ?? $order?->rental_end_date);
            if (! $bookingStart || ! $bookingEnd || $bookingEnd->lt($bookingStart)) {
                continue;
            }

            $qty = max((int) ($item->qty ?? 0), 1);
            $orderNumber = (string) ($order?->order_number ?: $order?->midtrans_order_id ?: ('ORD-' . $order?->id));

            for ($cursor = $bookingStart->copy(); $cursor->lte($bookingEnd); $cursor->addDay()) {
                $this->appendDailyReservation($daily, $cursor, $start, $end, $qty, [
                    'type' => 'booking',
                    'order_number' => $orderNumber,
                ]);
            }

            $bufferDate = $bookingEnd->copy()->addDays(self::BUFFER_DAYS);
            $this->appendDailyReservation($daily, $bufferDate, $start, $end, $qty, [
                'type' => 'buffer',
                'order_number' => $orderNumber,
            ]);
        }

        if (Schema::hasTable('equipment_maintenance_windows')) {
            $maintenanceWindows = EquipmentMaintenanceWindow::query()
                ->where('equipment_id', $equipment->id)
                ->whereDate('start_date', '<=', $end->toDateString())
                ->whereDate('end_date', '>=', $start->toDateString())
                ->get();

            foreach ($maintenanceWindows as $window) {
                $maintenanceStart = $this->normalizeDate($window->start_date);
                $maintenanceEnd = $this->normalizeDate($window->end_date);
                if (! $maintenanceStart || ! $maintenanceEnd || $maintenanceEnd->lt($maintenanceStart)) {
                    continue;
                }

                for ($cursor = $maintenanceStart->copy(); $cursor->lte($maintenanceEnd); $cursor->addDay()) {
                    $this->appendDailyReservation($daily, $cursor, $start, $end, max((int) $equipment->stock, 1), [
                        'type' => 'maintenance',
                        'reason' => $window->reason,
                    ]);
                }
            }
        }

        return $daily;
    }

    public function getBlockedSchedules(
        Equipment $equipment,
        ?int $excludeUserId = null,
        ?Carbon $from = null,
        ?Carbon $to = null
    ): Collection {
        $start = $from?->copy()->startOfDay() ?: now()->startOfDay();
        $end = $to?->copy()->startOfDay() ?: now()->addDays(120)->startOfDay();

        $entries = collect();

        $items = $this->fetchBlockingOrderItems($equipment->id, $start->copy()->subDay(), $end, null, $excludeUserId);
        foreach ($items as $item) {
            $order = $item->order;
            $bookingStart = $this->normalizeDate($item->rental_start_date ?? $order?->rental_start_date);
            $bookingEnd = $this->normalizeDate($item->rental_end_date ?? $order?->rental_end_date);
            if (! $bookingStart || ! $bookingEnd || $bookingEnd->lt($bookingStart)) {
                continue;
            }

            if ($bookingEnd->lt($start) || $bookingStart->gt($end)) {
                continue;
            }

            $orderNumber = (string) ($order?->order_number ?: $order?->midtrans_order_id ?: ('ORD-' . $order?->id));
            $entries->push([
                'type' => 'booking',
                'order_number' => $orderNumber,
                'start_date' => $bookingStart->toDateString(),
                'end_date' => $bookingEnd->toDateString(),
                'qty' => max((int) ($item->qty ?? 0), 1),
                'label' => 'Booked',
                'reason' => null,
            ]);

            $bufferDate = $bookingEnd->copy()->addDays(self::BUFFER_DAYS);
            if ($bufferDate->betweenIncluded($start, $end)) {
                $entries->push([
                    'type' => 'buffer',
                    'order_number' => $orderNumber,
                    'start_date' => $bufferDate->toDateString(),
                    'end_date' => $bufferDate->toDateString(),
                    'qty' => max((int) ($item->qty ?? 0), 1),
                    'label' => 'Buffer 1 Hari',
                    'reason' => null,
                ]);
            }
        }

        if (Schema::hasTable('equipment_maintenance_windows')) {
            $maintenanceWindows = EquipmentMaintenanceWindow::query()
                ->where('equipment_id', $equipment->id)
                ->whereDate('start_date', '<=', $end->toDateString())
                ->whereDate('end_date', '>=', $start->toDateString())
                ->get();

            foreach ($maintenanceWindows as $window) {
                $maintenanceStart = $this->normalizeDate($window->start_date);
                $maintenanceEnd = $this->normalizeDate($window->end_date);
                if (! $maintenanceStart || ! $maintenanceEnd || $maintenanceEnd->lt($maintenanceStart)) {
                    continue;
                }

                $entries->push([
                    'type' => 'maintenance',
                    'order_number' => null,
                    'start_date' => $maintenanceStart->toDateString(),
                    'end_date' => $maintenanceEnd->toDateString(),
                    'qty' => max((int) $equipment->stock, 1),
                    'label' => 'Maintenance',
                    'reason' => $window->reason,
                ]);
            }
        }

        return $entries
            ->sortBy([
                ['start_date', 'asc'],
                ['type', 'asc'],
            ])
            ->values();
    }

    private function fetchBlockingOrderItems(
        int $equipmentId,
        Carbon $windowStart,
        Carbon $windowEnd,
        ?int $ignoreOrderId = null,
        ?int $excludeUserId = null
    ): Collection {
        if (! Schema::hasTable('order_items') || ! Schema::hasTable('orders')) {
            return collect();
        }

        $holdCutoff = now()->subMinutes(self::HOLD_WINDOW_MINUTES);

        return OrderItem::query()
            ->with(['order:id,user_id,order_number,midtrans_order_id,status_pesanan,status_pembayaran,rental_start_date,rental_end_date,created_at'])
            ->where('equipment_id', $equipmentId)
            ->whereHas('order', function ($query) use ($windowStart, $windowEnd, $holdCutoff, $ignoreOrderId, $excludeUserId) {
                $query
                    ->when($ignoreOrderId, fn ($subQuery) => $subQuery->where('id', '!=', $ignoreOrderId))
                    ->when($excludeUserId, fn ($subQuery) => $subQuery->where('user_id', '!=', $excludeUserId))
                    ->whereDate('rental_start_date', '<=', $windowEnd->toDateString())
                    ->whereDate('rental_end_date', '>=', $windowStart->toDateString())
                    ->where(function ($statusQuery) use ($holdCutoff) {
                        $statusQuery->where(function ($pendingQuery) use ($holdCutoff) {
                            $pendingQuery->where('status_pesanan', 'menunggu_pembayaran')
                                ->where('created_at', '>=', $holdCutoff);
                        })->orWhereIn('status_pesanan', self::BLOCKING_ORDER_STATUSES);
                    });
            })
            ->get();
    }

    private function appendDailyReservation(
        array &$daily,
        Carbon $cursor,
        Carbon $windowStart,
        Carbon $windowEnd,
        int $qty,
        array $source
    ): void {
        if (! $cursor->betweenIncluded($windowStart, $windowEnd)) {
            return;
        }

        $dateKey = $cursor->toDateString();
        if (! isset($daily[$dateKey])) {
            $daily[$dateKey] = [
                'qty' => 0,
                'sources' => [],
            ];
        }

        $daily[$dateKey]['qty'] += max($qty, 0);
        $daily[$dateKey]['sources'][] = $source;
    }

    private function normalizeDate(mixed $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return $value->copy()->startOfDay();
        }

        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse((string) $value)->startOfDay();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function dateRangeKeys(Carbon $start, Carbon $end): array
    {
        $dates = [];
        for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addDay()) {
            $dates[] = $cursor->toDateString();
        }

        return $dates;
    }
}
