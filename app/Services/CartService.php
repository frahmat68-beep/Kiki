<?php

namespace App\Services;

class CartService
{
    private const SESSION_KEY = 'cart.items';

    public function items(): array
    {
        $items = session()->get(self::SESSION_KEY, []);

        if (! is_array($items)) {
            return [];
        }

        return array_values($items);
    }

    public function getItems(): array
    {
        return $this->items();
    }

    public function add(array $item, int $qty = 1, array $meta = []): void
    {
        $items = session()->get(self::SESSION_KEY, []);
        if (empty($item['equipment_id']) && ! empty($item['product_id'])) {
            $item['equipment_id'] = $item['product_id'];
        }
        $key = $this->resolveKey($item);
        $qty = $this->sanitizeQty($qty);

        if (isset($items[$key])) {
            $items[$key]['qty'] = $this->sanitizeQty(((int) ($items[$key]['qty'] ?? 0)) + $qty);
        } else {
            $items[$key] = array_merge($item, $meta);
            $items[$key]['qty'] = $qty;
        }

        $items[$key]['key'] = $key;

        session()->put(self::SESSION_KEY, $items);
    }

    public function addItem(array $item): void
    {
        $qty = (int) ($item['qty'] ?? 1);
        unset($item['qty']);

        $this->add($item, $qty);
    }

    public function increase(string $key): void
    {
        $items = session()->get(self::SESSION_KEY, []);

        if (! isset($items[$key])) {
            return;
        }

        $items[$key]['qty'] = $this->sanitizeQty(((int) ($items[$key]['qty'] ?? 0)) + 1);
        $items[$key]['key'] = $key;

        session()->put(self::SESSION_KEY, $items);
    }

    public function decrease(string $key): void
    {
        $items = session()->get(self::SESSION_KEY, []);

        if (! isset($items[$key])) {
            return;
        }

        $currentQty = (int) ($items[$key]['qty'] ?? 1);
        $newQty = $currentQty - 1;

        if ($newQty < 1) {
            unset($items[$key]);
        } else {
            $items[$key]['qty'] = $this->sanitizeQty($newQty);
            $items[$key]['key'] = $key;
        }

        session()->put(self::SESSION_KEY, $items);
    }

    public function updateQty(string $key, int $qty): void
    {
        $items = session()->get(self::SESSION_KEY, []);

        if (! isset($items[$key])) {
            return;
        }

        if ($qty < 1) {
            unset($items[$key]);
        } else {
            $items[$key]['qty'] = $this->sanitizeQty($qty);
            $items[$key]['key'] = $key;
        }

        session()->put(self::SESSION_KEY, $items);
    }

    public function remove(string $key): void
    {
        $items = session()->get(self::SESSION_KEY, []);

        if (! isset($items[$key])) {
            return;
        }

        unset($items[$key]);
        session()->put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function subtotalPerDay(): int
    {
        $total = 0;

        foreach ($this->items() as $item) {
            $total += (int) $item['price'] * (int) $item['qty'];
        }

        return $total;
    }

    public function count(): int
    {
        $count = 0;

        foreach ($this->items() as $item) {
            $count += (int) $item['qty'];
        }

        return $count;
    }

    public function totalItems(): int
    {
        return $this->count();
    }

    private function sanitizeQty(int $qty): int
    {
        return max(1, min(99, $qty));
    }

    private function resolveKey(array $item): string
    {
        $dateKey = '';
        if (! empty($item['rental_start_date']) && ! empty($item['rental_end_date'])) {
            $dateKey = '@' . $item['rental_start_date'] . '_' . $item['rental_end_date'];
        }

        if (! empty($item['equipment_id'])) {
            return 'equipment:' . $item['equipment_id'] . $dateKey;
        }

        if (! empty($item['product_id'])) {
            return 'product:' . $item['product_id'] . $dateKey;
        }

        if (! empty($item['slug'])) {
            return 'slug:' . $item['slug'] . $dateKey;
        }

        return 'item:' . ($item['name'] ?? uniqid('', true)) . $dateKey;
    }
}
