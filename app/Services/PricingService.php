<?php

namespace App\Services;

class PricingService
{
    public const TAX_RATE = 0.11;

    /**
     * @param  iterable<array{price?:int|float|string,qty?:int|float|string,days?:int|float|string,subtotal?:int|float|string}>  $items
     * @return array{subtotal:int,tax:int,total:int}
     */
    public function calculateOrderTotals(iterable $items, ?int $forcedSubtotal = null): array
    {
        $subtotal = $forcedSubtotal ?? 0;

        if ($forcedSubtotal === null) {
            foreach ($items as $item) {
                $subtotal += $this->lineSubtotal($item);
            }
        }

        $subtotal = max((int) $subtotal, 0);
        $tax = (int) round($subtotal * self::TAX_RATE);

        return [
            'subtotal' => $subtotal,
            'tax' => max($tax, 0),
            'total' => max($subtotal + $tax, 0),
        ];
    }

    /**
     * @param  array{price?:int|float|string,qty?:int|float|string,days?:int|float|string,subtotal?:int|float|string}  $item
     */
    public function lineSubtotal(array $item): int
    {
        if (array_key_exists('subtotal', $item)) {
            return max((int) $item['subtotal'], 0);
        }

        $price = max((int) ($item['price'] ?? 0), 0);
        $qty = max((int) ($item['qty'] ?? 1), 1);
        $days = max((int) ($item['days'] ?? 1), 1);

        return $price * $qty * $days;
    }

    /**
     * Late fee policy:
     * - >3h: 30%
     * - >6h: 50%
     * - >9h: 100%
     *
     * @return array{hours:int,rate:float,amount:int}
     */
    public function lateFeeFromLateHours(int $baseSubtotal, int $lateHours): array
    {
        $rate = 0.0;

        if ($lateHours > 9) {
            $rate = 1.0;
        } elseif ($lateHours > 6) {
            $rate = 0.5;
        } elseif ($lateHours > 3) {
            $rate = 0.3;
        }

        return [
            'hours' => max($lateHours, 0),
            'rate' => $rate,
            'amount' => (int) round(max($baseSubtotal, 0) * $rate),
        ];
    }
}
