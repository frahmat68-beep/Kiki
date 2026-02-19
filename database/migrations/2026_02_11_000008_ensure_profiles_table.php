<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
                $table->string('full_name')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('city')->nullable();
                $table->text('notes')->nullable();
                $table->string('identity_number')->nullable();
                $table->string('emergency_contact')->nullable();
                $table->boolean('is_completed')->default(false);
                $table->timestamps();
            });

            return;
        }

        Schema::table('profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('profiles', 'full_name')) {
                $table->string('full_name')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('profiles', 'phone')) {
                $table->string('phone')->nullable()->after('full_name');
            }
            if (! Schema::hasColumn('profiles', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('profiles', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (! Schema::hasColumn('profiles', 'notes')) {
                $table->text('notes')->nullable()->after('city');
            }
            if (! Schema::hasColumn('profiles', 'identity_number')) {
                $table->string('identity_number')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('profiles', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('identity_number');
            }
            if (! Schema::hasColumn('profiles', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('emergency_contact');
            }
        });
    }

    public function down(): void
    {
        // Intentionally left minimal to avoid data loss.
    }
};
