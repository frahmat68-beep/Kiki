<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = collect();

        if (Schema::hasTable('categories')) {
            $categories = Category::query()
                ->withCount('equipments')
                ->orderBy('name')
                ->get();
        }

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    public function home()
    {
        $category = null;
        $productsReady = collect();
        $userOverview = null;
        $recentUserOrders = collect();
        $damageAlertOrder = null;
        $canResolveUsage = Schema::hasTable('order_items') && Schema::hasTable('orders');

        if (Schema::hasTable('categories')) {
            $category = Category::query()->orderBy('name')->first();
        }

        if (Schema::hasTable('equipments')) {
            $equipmentQuery = Equipment::query()->orderBy('updated_at', 'desc');
            if ($canResolveUsage) {
                $equipmentQuery->withSum('activeOrderItems as reserved_units', 'qty');
            }

            if (Schema::hasColumn('equipments', 'status')) {
                $equipmentQuery->where('status', 'ready');
            } else {
                $equipmentQuery->where('stock', '>', 0);
            }

            $productsReady = $equipmentQuery
                ->get()
                ->filter(fn ($equipment) => (int) $equipment->stock > 0)
                ->take(8)
                ->values();
        }

        if (auth('web')->check() && Schema::hasTable('orders')) {
            $userId = (int) auth('web')->id();
            $baseOrderQuery = Order::query()
                ->where('user_id', $userId)
                ->with('damagePayment');
            $selectColumns = ['id', 'order_number', 'status_pembayaran', 'status_pesanan', 'total_amount', 'rental_start_date', 'rental_end_date'];
            if (Schema::hasColumn('orders', 'additional_fee')) {
                $selectColumns[] = 'additional_fee';
            }
            if (Schema::hasColumn('orders', 'penalty_amount')) {
                $selectColumns[] = 'penalty_amount';
            }
            if (Schema::hasColumn('orders', 'additional_fee_note')) {
                $selectColumns[] = 'additional_fee_note';
            }

            $userOverview = [
                'total_orders' => (clone $baseOrderQuery)->count(),
                'pending_payment' => (clone $baseOrderQuery)->where('status_pembayaran', 'pending')->count(),
                'ready_pickup' => (clone $baseOrderQuery)->where('status_pesanan', 'lunas')->count(),
                'on_rent' => (clone $baseOrderQuery)->where('status_pesanan', 'barang_diambil')->count(),
                'returned_orders' => (clone $baseOrderQuery)->whereIn('status_pesanan', ['barang_kembali', 'selesai'])->count(),
                'damaged_orders' => (clone $baseOrderQuery)->where('status_pesanan', 'barang_rusak')->count(),
                'completed_orders' => (clone $baseOrderQuery)->where('status_pesanan', 'selesai')->count(),
            ];

            $recentUserOrders = (clone $baseOrderQuery)
                ->latest()
                ->limit(5)
                ->get($selectColumns);

            $hasPenaltyColumn = Schema::hasColumn('orders', 'penalty_amount');
            $hasAdditionalFeeColumn = Schema::hasColumn('orders', 'additional_fee');

            if ($hasPenaltyColumn || $hasAdditionalFeeColumn) {
                $damageRelatedStatuses = [
                    Order::STATUS_RETURNED_OK,
                    Order::STATUS_RETURNED_DAMAGED,
                    Order::STATUS_RETURNED_LOST,
                    Order::STATUS_OVERDUE_DAMAGE_INVOICE,
                ];

                $damageAlertCandidates = Order::query()
                    ->where('user_id', $userId)
                    ->whereIn('status_pesanan', $damageRelatedStatuses)
                    ->where(function ($query) use ($hasPenaltyColumn, $hasAdditionalFeeColumn) {
                        if ($hasPenaltyColumn) {
                            $query->where('penalty_amount', '>', 0);
                        }

                        if ($hasAdditionalFeeColumn) {
                            $method = $hasPenaltyColumn ? 'orWhere' : 'where';
                            $query->{$method}('additional_fee', '>', 0);
                        }
                    })
                    ->with('damagePayment')
                    ->orderByDesc('updated_at')
                    ->limit(12)
                    ->get();

                $damageAlertOrder = $damageAlertCandidates->first(function (Order $order) {
                    return $order->resolvePenaltyAmount() > 0
                        && (string) ($order->damagePayment?->status ?? '') !== Order::PAYMENT_PAID;
                });
            }
        }

        return view('welcome', compact('category', 'productsReady', 'userOverview', 'recentUserOrders', 'damageAlertOrder'));
    }

    public function show(string $slug)
    {
        $category = null;
        $products = collect();
        $canResolveUsage = Schema::hasTable('order_items') && Schema::hasTable('orders');

        if (Schema::hasTable('categories')) {
            $categoryQuery = Category::query();

            if (Schema::hasColumn('categories', 'slug')) {
                $categoryQuery->where('slug', $slug);
            } else {
                $nameGuess = Str::of($slug)->replace('-', ' ')->title()->value();
                $categoryQuery->where('name', $nameGuess);
            }

            $category = $categoryQuery->first();
        }

        if ($category) {
            $equipmentQuery = $category->equipments()
                ->orderByDesc('updated_at')
                ->orderBy('name');
            if ($canResolveUsage) {
                $equipmentQuery->withSum('activeOrderItems as reserved_units', 'qty');
            }
            $products = $equipmentQuery->get()->values();
        } else {
            $category = (object) [
                'name' => Str::of($slug)->replace('-', ' ')->title()->value(),
                'slug' => $slug,
                'description' => __('app.category.all_subtitle'),
            ];
        }

        return view('categories.show', compact('category', 'products'));
    }
}
