<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payment_webhook_events')) {
            return;
        }

        Schema::create('payment_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 40)->default('midtrans');
            $table->string('event_key', 120);
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->longText('payload_json')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'event_key'], 'payment_webhook_events_unique');
            $table->index(['provider', 'created_at'], 'payment_webhook_events_provider_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhook_events');
    }
};
