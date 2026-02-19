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
            if (! Schema::hasColumn('orders', 'penalty_amount')) {
                $table->unsignedBigInteger('penalty_amount')->default(0)->after('additional_fee');
            }

            if (! Schema::hasColumn('orders', 'shipping_amount')) {
                $table->unsignedBigInteger('shipping_amount')->default(0)->after('penalty_amount');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_amount')) {
                $table->dropColumn('shipping_amount');
            }

            if (Schema::hasColumn('orders', 'penalty_amount')) {
                $table->dropColumn('penalty_amount');
            }
        });
    }
};
