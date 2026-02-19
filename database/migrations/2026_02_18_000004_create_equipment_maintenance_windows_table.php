<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('equipment_maintenance_windows')) {
            return;
        }

        Schema::create('equipment_maintenance_windows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason', 255)->nullable();
            $table->timestamps();

            $table->index(['equipment_id', 'start_date', 'end_date'], 'equipment_maintenance_range_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenance_windows');
    }
};
