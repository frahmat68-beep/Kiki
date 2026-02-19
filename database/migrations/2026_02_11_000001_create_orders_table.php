<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status_pembayaran')->default('pending');
            $table->string('status_pesanan')->default('menunggu_pembayaran');
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status_pembayaran']);
            $table->index(['user_id', 'status_pesanan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
