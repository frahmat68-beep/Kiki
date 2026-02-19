<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable()->after('provider');
            }
            if (! Schema::hasColumn('payments', 'gross_amount')) {
                $table->unsignedBigInteger('gross_amount')->default(0)->after('transaction_status');
            }
            if (! Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('payments', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('paid_at');
            }
        });

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            try {
                DB::statement('CREATE INDEX IF NOT EXISTS payments_order_status_idx ON payments (order_id, status)');
            } catch (\Throwable $exception) {
                // Keep migration idempotent.
            }
            try {
                DB::statement('CREATE INDEX IF NOT EXISTS payments_midtrans_order_id_idx ON payments (midtrans_order_id)');
            } catch (\Throwable $exception) {
                // Keep migration idempotent.
            }
            try {
                DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS payments_transaction_id_unique ON payments (transaction_id)');
            } catch (\Throwable $exception) {
                // Keep migration idempotent.
            }
            return;
        }

        try {
            DB::statement('ALTER TABLE payments ADD INDEX payments_order_status_idx (order_id, status)');
        } catch (\Throwable $exception) {
            // Keep migration idempotent.
        }

        try {
            DB::statement('ALTER TABLE payments ADD INDEX payments_midtrans_order_id_idx (midtrans_order_id)');
        } catch (\Throwable $exception) {
            // Keep migration idempotent.
        }

        try {
            DB::statement('ALTER TABLE payments ADD UNIQUE INDEX payments_transaction_id_unique (transaction_id)');
        } catch (\Throwable $exception) {
            // Keep migration idempotent.
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'expired_at')) {
                $table->dropColumn('expired_at');
            }
            if (Schema::hasColumn('payments', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('payments', 'gross_amount')) {
                $table->dropColumn('gross_amount');
            }
            if (Schema::hasColumn('payments', 'midtrans_order_id')) {
                $table->dropColumn('midtrans_order_id');
            }
        });
    }
};
