<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'equipment_id')) {
                $table->foreignId('equipment_id')->nullable()->after('order_id')->constrained('equipments')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('order_items', 'product_id')) {
            DB::statement('UPDATE order_items SET equipment_id = product_id WHERE equipment_id IS NULL');
        }

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'product_id')) {
                $table->dropConstrainedForeignId('product_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'product_id')) {
                $table->foreignId('product_id')->nullable()->after('order_id')->constrained('equipments')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('order_items', 'equipment_id')) {
            DB::statement('UPDATE order_items SET product_id = equipment_id WHERE product_id IS NULL');
        }

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'equipment_id')) {
                $table->dropConstrainedForeignId('equipment_id');
            }
        });
    }
};
