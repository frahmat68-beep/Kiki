<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders') || ! Schema::hasColumn('orders', 'order_number')) {
            return;
        }

        $rows = DB::table('orders')
            ->select(['id', 'created_at', 'midtrans_order_id', 'order_number'])
            ->whereNull('order_number')
            ->orWhere('order_number', '')
            ->orderBy('id')
            ->get();

        foreach ($rows as $row) {
            $createdAt = $row->created_at ? Carbon::parse($row->created_at) : now();
            $candidate = $row->midtrans_order_id ?: $this->generateOrderNumber((int) $row->id, $createdAt);

            while (DB::table('orders')->where('order_number', $candidate)->where('id', '!=', $row->id)->exists()) {
                $candidate = $this->generateOrderNumber((int) $row->id, $createdAt);
            }

            $updates = [
                'order_number' => $candidate,
            ];

            if (empty($row->midtrans_order_id)) {
                $updates['midtrans_order_id'] = $candidate;
            }

            DB::table('orders')->where('id', $row->id)->update($updates);
        }
    }

    public function down(): void
    {
        // No destructive rollback: preserve existing order numbers.
    }

    private function generateOrderNumber(int $orderId, Carbon $date): string
    {
        return 'MNK-' . $date->format('Ymd') . '-' . $orderId . '-' . Str::upper(Str::random(6));
    }
};
