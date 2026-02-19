<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Services\AvailabilityService;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $categories = collect();
        $activeCategory = null;
        $activeCategorySlug = $request->string('category')->trim()->value();
        $search = $request->string('q')->trim()->value();
        $hasCategoriesTable = Schema::hasTable('categories');
        $hasDescriptionColumn = false;

        if (! Schema::hasTable('equipments')) {
            return view('equipments.index', [
                'groupedEquipments' => collect(),
                'categories' => $categories,
                'activeCategory' => $activeCategory,
                'activeCategorySlug' => $activeCategorySlug,
                'search' => $search,
            ]);
        }

        if ($hasCategoriesTable) {
            $categories = Category::query()
                ->withCount('equipments')
                ->orderBy('name')
                ->get();
        }
        $hasDescriptionColumn = Schema::hasColumn('equipments', 'description');

        $query = Equipment::query()
            ->with('category')
            ->orderByDesc('updated_at')
            ->orderBy('name');
        if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
            $query->withSum('activeOrderItems as reserved_units', 'qty');
        }

        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search, $hasCategoriesTable, $hasDescriptionColumn) {
                $searchQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');

                if ($hasDescriptionColumn) {
                    $searchQuery->orWhere('description', 'like', '%' . $search . '%');
                }

                if ($hasCategoriesTable) {
                    $searchQuery->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('slug', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        if ($activeCategorySlug !== '' && $categories->isNotEmpty()) {
            $activeCategory = $categories->firstWhere('slug', $activeCategorySlug);
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        $equipments = $query->get();

        $groupedEquipments = collect();

        if ($activeCategory) {
            $groupedEquipments->push([
                'category' => $activeCategory,
                'items' => $equipments->values(),
            ]);
        } else {
            foreach ($categories as $category) {
                $items = $equipments
                    ->where('category_id', $category->id)
                    ->values();

                if ($items->isEmpty()) {
                    continue;
                }

                $groupedEquipments->push([
                    'category' => $category,
                    'items' => $items,
                ]);
            }

            $uncategorized = $equipments
                ->filter(fn ($equipment) => ! $equipment->category_id)
                ->values();

            if ($uncategorized->isNotEmpty()) {
                $groupedEquipments->push([
                    'category' => (object) [
                        'name' => 'Lainnya',
                        'slug' => 'lainnya',
                        'description' => 'Peralatan tanpa kategori.',
                    ],
                    'items' => $uncategorized,
                ]);
            }
        }

        return view('equipments.index', [
            'groupedEquipments' => $groupedEquipments,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'activeCategorySlug' => $activeCategorySlug,
            'search' => $search,
        ]);
    }

    public function show(string $slug, AvailabilityService $availabilityService)
    {
        if (! Schema::hasTable('equipments')) {
            abort(404);
        }

        $equipmentQuery = Equipment::query()
            ->with('category')
            ->where('slug', $slug);
        if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
            $equipmentQuery->withSum('activeOrderItems as reserved_units', 'qty');
        }
        $equipment = $equipmentQuery->firstOrFail();
        $bookingRanges = $availabilityService->getBlockedSchedules(
            $equipment,
            auth('web')->check() ? (int) auth('web')->id() : null
        );

        return view('equipments.show', compact('equipment', 'bookingRanges'));
    }

    public function suggestions(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2 || ! Schema::hasTable('equipments')) {
            return response()->json(['data' => []]);
        }

        $builder = Equipment::query()
            ->with('category')
            ->orderBy('name');
        $hasDescriptionColumn = Schema::hasColumn('equipments', 'description');

        if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
            $builder->withSum('activeOrderItems as reserved_units', 'qty');
        }

        $items = $builder
            ->where(function ($searchBuilder) use ($query, $hasDescriptionColumn) {
                $searchBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('slug', 'like', '%' . $query . '%')
                    ->orWhereHas('category', function ($categoryBuilder) use ($query) {
                        $categoryBuilder->where('name', 'like', '%' . $query . '%');
                    });

                if ($hasDescriptionColumn) {
                    $searchBuilder->orWhere('description', 'like', '%' . $query . '%');
                }
            })
            ->limit(8)
            ->get();

        $data = $items->map(function (Equipment $equipment) {
            $imagePath = (string) ($equipment->image_path ?? $equipment->image ?? '');
            $imageUrl = $imagePath !== ''
                ? (Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : asset('storage/' . ltrim($imagePath, '/')))
                : asset('MANAKE-FAV-M.png');

            return [
                'name' => (string) $equipment->name,
                'slug' => (string) $equipment->slug,
                'category' => (string) ($equipment->category?->name ?? 'Lainnya'),
                'image_url' => $imageUrl,
                'price_per_day' => (int) ($equipment->price_per_day ?? 0),
                'available_units' => (int) ($equipment->available_units ?? 0),
                'overview' => Str::of((string) ($equipment->description ?? ''))
                    ->squish()
                    ->limit(88)
                    ->value(),
                'detail_url' => route('product.show', $equipment->slug),
            ];
        })->values();

        return response()->json(['data' => $data]);
    }

    public function availability(Request $request, string $slug, AvailabilityService $availabilityService)
    {
        if (! Schema::hasTable('equipments')) {
            return response()->json([
                'ok' => false,
                'message' => __('ui.availability.not_available'),
            ], 404);
        }

        $equipment = Equipment::query()
            ->where('slug', $slug)
            ->first();

        if (! $equipment) {
            return response()->json([
                'ok' => false,
                'message' => __('ui.availability.not_available'),
            ], 404);
        }

        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $requestedQty = max((int) ($validated['qty'] ?? 1), 1);
        $startDate = Carbon::parse((string) $validated['start_date'])->startOfDay();
        $endDate = Carbon::parse((string) $validated['end_date'])->startOfDay();

        $evaluation = $availabilityService->evaluateRange(
            $equipment,
            $startDate,
            $endDate,
            $requestedQty
        );

        $durationDays = $startDate->diffInDays($endDate) + 1;
        $conflictCount = count($evaluation['conflicts']);
        $status = 'available';

        if (! $evaluation['ok']) {
            $status = $conflictCount >= $durationDays ? 'not_available' : 'partially_available';
        }

        $suggestions = $status !== 'available'
            ? $this->findAlternativeRanges($equipment, $availabilityService, $startDate, $durationDays, $requestedQty)
            : [];

        $daily = collect($evaluation['daily'])
            ->map(function (array $dayData, string $dateKey) use ($equipment) {
                $reserved = (int) ($dayData['qty'] ?? 0);
                $available = max((int) $equipment->stock - $reserved, 0);

                return [
                    'date' => $dateKey,
                    'reserved' => $reserved,
                    'available' => $available,
                ];
            })
            ->sortBy('date')
            ->values()
            ->all();

        $message = match ($status) {
            'available' => __('ui.availability.available'),
            'partially_available' => __('ui.availability.partial'),
            default => __('ui.availability.not_available'),
        };

        return response()->json([
            'ok' => $evaluation['ok'],
            'status' => $status,
            'message' => $message,
            'conflicts' => $evaluation['conflicts'],
            'daily' => $daily,
            'suggestions' => $suggestions,
        ]);
    }

    private function findAlternativeRanges(
        Equipment $equipment,
        AvailabilityService $availabilityService,
        Carbon $fromDate,
        int $durationDays,
        int $qty
    ): array {
        $maxSuggestions = 3;
        $searchLimitDays = 45;
        $suggestions = [];

        $cursor = $fromDate->copy()->addDay()->startOfDay();
        $deadline = $fromDate->copy()->addDays($searchLimitDays)->startOfDay();

        while ($cursor->lte($deadline) && count($suggestions) < $maxSuggestions) {
            $candidateEnd = $cursor->copy()->addDays(max($durationDays - 1, 0));
            $result = $availabilityService->evaluateRange($equipment, $cursor, $candidateEnd, $qty);

            if ($result['ok']) {
                $suggestions[] = [
                    'start_date' => $cursor->toDateString(),
                    'end_date' => $candidateEnd->toDateString(),
                ];
            }

            $cursor->addDay();
        }

        return $suggestions;
    }
}
