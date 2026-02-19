<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'booking_id')) {
            try {
                if (DB::getDriverName() === 'sqlite') {
                    Schema::table('payments', function (Blueprint $table) {
                        $table->unsignedBigInteger('booking_id')->nullable()->change();
                    });
                } else {
                    DB::statement('ALTER TABLE payments MODIFY booking_id BIGINT UNSIGNED NULL');
                }
            } catch (\Throwable $exception) {
                // Ignore if the column cannot be altered.
            }
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'order_id')) {
                $table->foreignId('order_id')->nullable()->after('booking_id')->constrained('orders')->nullOnDelete();
            }
            if (! Schema::hasColumn('payments', 'provider')) {
                $table->string('provider')->nullable()->after('order_id');
            }
            if (! Schema::hasColumn('payments', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('provider');
            }
            if (! Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('pending')->after('snap_token');
            }
            if (! Schema::hasColumn('payments', 'payload_json')) {
                $table->longText('payload_json')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payload_json')) {
                $table->dropColumn('payload_json');
            }
            if (Schema::hasColumn('payments', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('payments', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
            if (Schema::hasColumn('payments', 'provider')) {
                $table->dropColumn('provider');
            }
            if (Schema::hasColumn('payments', 'order_id')) {
                $table->dropConstrainedForeignId('order_id');
            }
        });
    }
};
