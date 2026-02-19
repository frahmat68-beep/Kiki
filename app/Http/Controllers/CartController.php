<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Services\CartService;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    public function show(CartService $cart, PricingService $pricing)
    {
        $cartItems = $cart->items();
        $subtotal = $cart->subtotalPerDay();
        $estimatedSubtotal = collect($cartItems)->sum(fn (array $item) => $this->estimatedLineSubtotal($item));
        $pricingSummary = $pricing->calculateOrderTotals([], $estimatedSubtotal);
        $suggestedEquipments = collect();

        if (Schema::hasTable('equipments')) {
            $cartEquipmentIds = collect($cartItems)
                ->map(fn (array $item) => (int) ($item['equipment_id'] ?? $item['product_id'] ?? 0))
                ->filter(fn (int $equipmentId) => $equipmentId > 0)
                ->unique()
                ->values();

            $suggestionQuery = Equipment::query()
                ->with('category')
                ->where('stock', '>', 0)
                ->orderByDesc('updated_at')
                ->orderBy('name');

            if (Schema::hasColumn('equipments', 'status')) {
                $suggestionQuery->where('status', 'ready');
            }

            if ($cartEquipmentIds->isNotEmpty()) {
                $suggestionQuery->whereNotIn('id', $cartEquipmentIds);
            }

            if (Schema::hasTable('order_items') && Schema::hasTable('orders')) {
                $suggestionQuery->withSum('activeOrderItems as reserved_units', 'qty');
            }

            $suggestedEquipments = $suggestionQuery
                ->limit(6)
                ->get()
                ->filter(fn (Equipment $equipment) => (int) ($equipment->available_units ?? $equipment->stock) > 0)
                ->take(4)
                ->values();
        }

        return view('cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'estimatedSubtotal' => $pricingSummary['subtotal'],
            'taxAmount' => $pricingSummary['tax'],
            'grandTotal' => $pricingSummary['total'],
            'suggestedEquipments' => $suggestedEquipments,
        ]);
    }

    public function add(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'equipment_id' => ['nullable', 'integer'],
            'product_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
            'rental_start_date' => ['nullable', 'date', 'required_with:rental_end_date'],
            'rental_end_date' => ['nullable', 'date', 'required_with:rental_start_date', 'after_or_equal:rental_start_date'],
        ]);

        if (empty($data['equipment_id']) && ! empty($data['product_id'])) {
            $data['equipment_id'] = $data['product_id'];
        }

        $qty = (int) ($data['qty'] ?? 1);
        unset($data['qty']);

        $cart->add($data, $qty);

        return redirect()->route('cart')->with('success', __('ui.cart.messages.added'));
    }

    public function increment(string $key, CartService $cart)
    {
        $cart->increase($key);

        return redirect()->route('cart')->with('success', __('ui.cart.messages.updated'));
    }

    public function decrement(string $key, CartService $cart)
    {
        $cart->decrease($key);

        return redirect()->route('cart')->with('success', __('ui.cart.messages.updated'));
    }

    public function update(Request $request, string $key, CartService $cart)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart->updateQty($key, (int) $data['qty']);

        return redirect()->route('cart')->with('success', __('ui.cart.messages.updated'));
    }

    public function remove(string $key, CartService $cart)
    {
        $cart->remove($key);

        return redirect()->route('cart')->with('success', __('ui.cart.messages.removed'));
    }

    private function estimatedLineSubtotal(array $item): int
    {
        $qty = max((int) ($item['qty'] ?? 1), 1);
        $price = max((int) ($item['price'] ?? 0), 0);

        try {
            if (! empty($item['rental_start_date']) && ! empty($item['rental_end_date'])) {
                $start = Carbon::parse($item['rental_start_date'])->startOfDay();
                $end = Carbon::parse($item['rental_end_date'])->startOfDay();
                if ($end->gte($start)) {
                    $days = $start->diffInDays($end) + 1;

                    return $price * $qty * max($days, 1);
                }
            }
        } catch (\Throwable $exception) {
            // Fallback ke subtotal per hari ketika tanggal item tidak valid.
        }

        return $price * $qty;
    }
}
