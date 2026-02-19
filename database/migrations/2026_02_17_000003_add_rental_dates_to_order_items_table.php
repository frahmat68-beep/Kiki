<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'rental_start_date')) {
                $table->date('rental_start_date')->nullable()->after('subtotal');
            }

            if (! Schema::hasColumn('order_items', 'rental_end_date')) {
                $table->date('rental_end_date')->nullable()->after('rental_start_date');
            }

            if (! Schema::hasColumn('order_items', 'rental_days')) {
                $table->unsignedInteger('rental_days')->nullable()->after('rental_end_date');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'rental_days')) {
                $table->dropColumn('rental_days');
            }

            if (Schema::hasColumn('order_items', 'rental_end_date')) {
                $table->dropColumn('rental_end_date');
            }

            if (Schema::hasColumn('order_items', 'rental_start_date')) {
                $table->dropColumn('rental_start_date');
            }
        });
    }
};
