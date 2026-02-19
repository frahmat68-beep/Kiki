<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('paid_at');
            }

            if (! Schema::hasColumn('orders', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('picked_up_at');
            }

            if (! Schema::hasColumn('orders', 'damaged_at')) {
                $table->timestamp('damaged_at')->nullable()->after('returned_at');
            }

            if (! Schema::hasColumn('orders', 'additional_fee')) {
                $table->unsignedBigInteger('additional_fee')->default(0)->after('total_amount');
            }

            if (! Schema::hasColumn('orders', 'additional_fee_note')) {
                $table->string('additional_fee_note', 500)->nullable()->after('additional_fee');
            }

            if (! Schema::hasColumn('orders', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('additional_fee_note');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'admin_note')) {
                $table->dropColumn('admin_note');
            }

            if (Schema::hasColumn('orders', 'additional_fee_note')) {
                $table->dropColumn('additional_fee_note');
            }

            if (Schema::hasColumn('orders', 'additional_fee')) {
                $table->dropColumn('additional_fee');
            }

            if (Schema::hasColumn('orders', 'damaged_at')) {
                $table->dropColumn('damaged_at');
            }

            if (Schema::hasColumn('orders', 'returned_at')) {
                $table->dropColumn('returned_at');
            }

            if (Schema::hasColumn('orders', 'picked_up_at')) {
                $table->dropColumn('picked_up_at');
            }
        });
    }
};
