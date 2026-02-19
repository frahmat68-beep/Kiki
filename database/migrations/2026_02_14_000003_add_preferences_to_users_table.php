<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'preferred_theme')) {
                $table->string('preferred_theme', 20)->nullable()->after('role');
            }

            if (! Schema::hasColumn('users', 'preferred_locale')) {
                $table->string('preferred_locale', 5)->nullable()->after('preferred_theme');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'preferred_locale')) {
                $table->dropColumn('preferred_locale');
            }

            if (Schema::hasColumn('users', 'preferred_theme')) {
                $table->dropColumn('preferred_theme');
            }
        });
    }
};

