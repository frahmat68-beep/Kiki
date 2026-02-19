<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class AvailabilityBoardController extends Controller
{
    public function index(Request $request, AvailabilityService $availability): \Illuminate\View\View
    {
        $monthDate = $this->resolveMonthDate((string) $request->query('month', ''));
        $monthStart = $monthDate->copy()->startOfMonth();
        $monthEnd = $monthDate->copy()->endOfMonth();
        $calendarStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
        $selectedDate = $this->resolveSelectedDate((string) $request->query('date', ''), $calendarStart, $calendarEnd);
        $search = trim((string) $request->query('q', ''));

        if (! Schema::hasTable('equipments')) {
            return view('availability.board', [
                'search' => $search,
                'monthDate' => $monthDate,
                'monthStart' => $monthStart,
                'monthEnd' => $monthEnd,
                'calendarStart' => $calendarStart,
                'calendarEnd' => $calendarEnd,
                'selectedDate' => $selectedDate,
                'dateKeys' => [],
                'calendarDays' => collect(),
                'equipmentRows' => collect(),
                'selectedBusyRows' => collect(),
                'selectedFreeRows' => collect(),
                'monthlySchedules' => collect(),
                'dailySchedulesByDate' => [],
                'summary' => [
                    'total_equipments' => 0,
                    'busy_equipments' => 0,
                    'available_equipments' => 0,
                    'reserved_units' => 0,
                ],
            ]);
        }

        $dateKeys = [];
        $calendarTotals = [];
        for ($cursor = $calendarStart->copy(); $cursor->lte($calendarEnd); $cursor->addDay()) {
            $dateKey = $cursor->toDateString();
            $dateKeys[] = $dateKey;
            $calendarTotals[$dateKey] = [
                'reserved_units' => 0,
                'busy_equipments' => 0,
            ];
        }

        $equipmentQuery = Equipment::query()
            ->with('category')
            ->orderBy('name');

        if ($search !== '') {
            $equipmentQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $equipments = $equipmentQuery
            ->limit(28)
            ->get();

        $selectedDateKey = $selectedDate->toDateString();
        $equipmentRows = collect();
        $selectedBusyRows = collect();
        $selectedFreeRows = collect();

        foreach ($equipments as $equipment) {
            $daily = $availability->getDailyReservedUnits($equipment, $calendarStart, $calendarEnd);
            $statusValue = (string) ($equipment->status ?? 'ready');
            $statusLabel = $this->resolveEquipmentStatusLabel($statusValue);

            $dayCells = [];
            foreach ($dateKeys as $dateKey) {
                $reserved = (int) data_get($daily, $dateKey . '.qty', 0);
                $available = max((int) $equipment->stock - $reserved, 0);
                $isBlockedByStatus = $statusValue !== 'ready';
                $isBlocked = $isBlockedByStatus || $available <= 0;

                $dayCells[$dateKey] = [
                    'reserved' => $reserved,
                    'available' => $available,
                    'is_blocked' => $isBlocked,
                ];

                if ($reserved > 0) {
                    $calendarTotals[$dateKey]['reserved_units'] += $reserved;
                }
                if ($isBlocked || $reserved > 0) {
                    $calendarTotals[$dateKey]['busy_equipments']++;
                }
            }

            $selectedCell = $dayCells[$selectedDateKey] ?? [
                'reserved' => 0,
                'available' => (int) $equipment->stock,
                'is_blocked' => $statusValue !== 'ready',
            ];

            $sources = collect(data_get($daily, $selectedDateKey . '.sources', []));
            $sourceLabels = $sources
                ->pluck('type')
                ->filter(fn ($type) => is_string($type) && $type !== '')
                ->map(fn (string $type) => $this->resolveSourceLabel($type))
                ->unique()
                ->values();

            if ($sourceLabels->isEmpty() && $statusValue !== 'ready') {
                $sourceLabels = collect([$statusLabel]);
            }

            $orderNumbers = $sources
                ->pluck('order_number')
                ->filter(fn ($value) => is_string($value) && trim($value) !== '')
                ->unique()
                ->values();

            $row = [
                'id' => (int) $equipment->id,
                'name' => (string) $equipment->name,
                'category' => (string) ($equipment->category?->name ?? '-'),
                'stock' => (int) ($equipment->stock ?? 0),
                'status' => $statusValue,
                'status_label' => $statusLabel,
                'status_badge_class' => $this->resolveStatusBadgeClass($statusValue),
                'day_cells' => $dayCells,
                'selected_reserved' => (int) ($selectedCell['reserved'] ?? 0),
                'selected_available' => (int) ($selectedCell['available'] ?? 0),
                'selected_is_blocked' => (bool) ($selectedCell['is_blocked'] ?? false),
                'source_labels' => $sourceLabels,
                'order_numbers' => $orderNumbers,
            ];

            $equipmentRows->push($row);

            if (($row['selected_is_blocked'] ?? false) || ($row['selected_reserved'] ?? 0) > 0) {
                $selectedBusyRows->push($row);
            } else {
                $selectedFreeRows->push($row);
            }
        }

        $totalEquipments = $equipmentRows->count();
        $selectedBusyCount = $selectedBusyRows->count();
        $selectedReservedUnits = (int) $selectedBusyRows->sum('selected_reserved');

        $calendarDays = collect($dateKeys)->map(function (string $dateKey) use ($calendarTotals, $calendarStart, $monthStart, $selectedDateKey, $totalEquipments) {
            $date = Carbon::parse($dateKey)->startOfDay();
            $busyEquipments = (int) data_get($calendarTotals, $dateKey . '.busy_equipments', 0);
            $reservedUnits = (int) data_get($calendarTotals, $dateKey . '.reserved_units', 0);
            $busyRatio = $totalEquipments > 0 ? ($busyEquipments / max($totalEquipments, 1)) : 0;

            $tone = 'calm';
            if ($busyRatio >= 0.7) {
                $tone = 'critical';
            } elseif ($busyRatio >= 0.35) {
                $tone = 'busy';
            } elseif ($busyRatio > 0) {
                $tone = 'light';
            }

            return [
                'date' => $dateKey,
                'day' => $date->day,
                'in_month' => $date->month === $monthStart->month,
                'is_today' => $date->isSameDay(now()),
                'is_selected' => $dateKey === $selectedDateKey,
                'reserved_units' => $reservedUnits,
                'busy_equipments' => $busyEquipments,
                'available_equipments' => max($totalEquipments - $busyEquipments, 0),
                'tone' => $tone,
                'week_index' => $date->diffInDays($calendarStart) / 7,
            ];
        });

        $monthlySchedules = $this->loadMonthlySchedules(
            $equipmentRows->pluck('id')->filter()->values(),
            $calendarStart,
            $calendarEnd
        );
        $dailySchedulesByDate = $this->buildDailySchedulesByDate($monthlySchedules, $calendarStart, $calendarEnd);

        return view('availability.board', [
            'search' => $search,
            'monthDate' => $monthDate,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
            'calendarStart' => $calendarStart,
            'calendarEnd' => $calendarEnd,
            'selectedDate' => $selectedDate,
            'dateKeys' => $dateKeys,
            'calendarDays' => $calendarDays,
            'equipmentRows' => $equipmentRows,
            'selectedBusyRows' => $selectedBusyRows
                ->sortByDesc('selected_reserved')
                ->values(),
            'selectedFreeRows' => $selectedFreeRows
                ->sortBy('name')
                ->values(),
            'monthlySchedules' => $monthlySchedules,
            'dailySchedulesByDate' => $dailySchedulesByDate,
            'summary' => [
                'total_equipments' => $totalEquipments,
                'busy_equipments' => $selectedBusyCount,
                'available_equipments' => max($totalEquipments - $selectedBusyCount, 0),
                'reserved_units' => $selectedReservedUnits,
            ],
        ]);
    }

    private function loadMonthlySchedules(Collection $equipmentIds, Carbon $calendarStart, Carbon $calendarEnd): Collection
    {
        if (
            $equipmentIds->isEmpty()
            || ! Schema::hasTable('order_items')
            || ! Schema::hasTable('orders')
        ) {
            return collect();
        }

        return OrderItem::query()
            ->with([
                'equipment:id,name',
                'order:id,order_number,status_pesanan,rental_start_date,rental_end_date',
            ])
            ->whereIn('equipment_id', $equipmentIds->all())
            ->whereHas('order', function ($query) use ($calendarStart, $calendarEnd) {
                $query
                    ->whereIn('status_pesanan', Order::HOLD_SLOT_STATUSES)
                    ->whereDate('rental_start_date', '<=', $calendarEnd->toDateString())
                    ->whereDate('rental_end_date', '>=', $calendarStart->toDateString());
            })
            ->latest('id')
            ->limit(180)
            ->get(['id', 'order_id', 'equipment_id', 'qty', 'rental_start_date', 'rental_end_date'])
            ->map(function (OrderItem $item) {
                $startDate = $item->rental_start_date ?: $item->order?->rental_start_date;
                $endDate = $item->rental_end_date ?: $item->order?->rental_end_date;
                if (! $startDate || ! $endDate) {
                    return null;
                }

                return [
                    'equipment_name' => (string) ($item->equipment?->name ?? 'Equipment'),
                    'order_number' => (string) ($item->order?->order_number ?? ('ORD-' . ($item->order?->id ?? 0))),
                    'status_pesanan' => (string) ($item->order?->status_pesanan ?? '-'),
                    'qty' => max((int) ($item->qty ?? 0), 1),
                    'start_date' => Carbon::parse($startDate)->toDateString(),
                    'end_date' => Carbon::parse($endDate)->toDateString(),
                ];
            })
            ->filter()
            ->sortBy([
                ['start_date', 'asc'],
                ['equipment_name', 'asc'],
            ])
            ->values();
    }

    private function buildDailySchedulesByDate(Collection $monthlySchedules, Carbon $calendarStart, Carbon $calendarEnd): array
    {
        if ($monthlySchedules->isEmpty()) {
            return [];
        }

        $result = [];

        foreach ($monthlySchedules as $schedule) {
            $startDate = $this->parseDate(data_get($schedule, 'start_date'));
            $endDate = $this->parseDate(data_get($schedule, 'end_date'));

            if (! $startDate || ! $endDate || $endDate->lt($startDate)) {
                continue;
            }

            $rangeStart = $startDate->lt($calendarStart) ? $calendarStart->copy() : $startDate->copy();
            $rangeEnd = $endDate->gt($calendarEnd) ? $calendarEnd->copy() : $endDate->copy();

            for ($cursor = $rangeStart->copy(); $cursor->lte($rangeEnd); $cursor->addDay()) {
                $dateKey = $cursor->toDateString();
                if (! array_key_exists($dateKey, $result)) {
                    $result[$dateKey] = [];
                }

                $result[$dateKey][] = [
                    'equipment_name' => (string) data_get($schedule, 'equipment_name', 'Equipment'),
                    'order_number' => (string) data_get($schedule, 'order_number', '-'),
                    'status_pesanan' => (string) data_get($schedule, 'status_pesanan', '-'),
                    'status_label' => $this->resolveOrderStatusLabel((string) data_get($schedule, 'status_pesanan', '')),
                    'qty' => max((int) data_get($schedule, 'qty', 1), 1),
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                ];
            }
        }

        foreach ($result as $dateKey => $rows) {
            usort($rows, function (array $left, array $right): int {
                $leftName = (string) ($left['equipment_name'] ?? '');
                $rightName = (string) ($right['equipment_name'] ?? '');
                $nameCompare = strcasecmp($leftName, $rightName);
                if ($nameCompare !== 0) {
                    return $nameCompare;
                }

                return strcasecmp((string) ($left['order_number'] ?? ''), (string) ($right['order_number'] ?? ''));
            });

            $result[$dateKey] = $rows;
        }

        return $result;
    }

    private function resolveMonthDate(string $monthValue): Carbon
    {
        if ($monthValue !== '' && preg_match('/^\d{4}-\d{2}$/', $monthValue) === 1) {
            try {
                return Carbon::createFromFormat('Y-m', $monthValue)->startOfMonth();
            } catch (\Throwable $exception) {
                // Fallback to current month.
            }
        }

        return now()->startOfMonth();
    }

    private function resolveSelectedDate(string $selectedValue, Carbon $calendarStart, Carbon $calendarEnd): Carbon
    {
        $defaultDate = now()->startOfDay();
        $selectedDate = null;

        if ($selectedValue !== '') {
            try {
                $selectedDate = Carbon::parse($selectedValue)->startOfDay();
            } catch (\Throwable $exception) {
                $selectedDate = null;
            }
        }

        if (! $selectedDate) {
            $selectedDate = $defaultDate;
        }

        if ($selectedDate->lt($calendarStart) || $selectedDate->gt($calendarEnd)) {
            return $calendarStart->copy()->startOfDay();
        }

        return $selectedDate;
    }

    private function resolveEquipmentStatusLabel(string $status): string
    {
        return match ($status) {
            'ready' => 'Ready',
            'maintenance' => 'Maintenance',
            'unavailable' => 'Unavailable',
            default => strtoupper($status),
        };
    }

    private function resolveStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'ready' => 'bg-emerald-100 text-emerald-700',
            'maintenance' => 'bg-amber-100 text-amber-700',
            'unavailable' => 'bg-rose-100 text-rose-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    private function resolveOrderStatusLabel(string $status): string
    {
        return match ($status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'diproses' => 'Diproses',
            'lunas' => 'Lunas',
            'barang_dipinjam' => 'Sedang Disewa',
            'barang_diambil' => 'Sedang Disewa',
            'dikembalikan' => 'Dikembalikan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'barang_rusak' => 'Klaim Kerusakan',
            default => str($status)->replace('_', ' ')->title()->value(),
        };
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse((string) $value)->startOfDay();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function resolveSourceLabel(string $type): string
    {
        return match ($type) {
            'booking' => 'Dipakai',
            'buffer_before' => 'Buffer Sebelum',
            'buffer_after' => 'Buffer Sesudah',
            'maintenance' => 'Maintenance',
            default => str($type)->replace('_', ' ')->title()->value(),
        };
    }
}
