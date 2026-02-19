<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('profiles', 'city')) {
                $table->string('city', 120)->nullable()->after('address');
            }

            if (! Schema::hasColumn('profiles', 'notes')) {
                $table->text('notes')->nullable()->after('city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('profiles', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
};
