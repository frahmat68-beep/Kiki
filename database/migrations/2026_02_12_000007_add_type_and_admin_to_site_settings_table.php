<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'type')) {
                $table->string('type')->default('text')->after('value');
            }

            if (! Schema::hasColumn('site_settings', 'updated_by_admin_id')) {
                $table->foreignId('updated_by_admin_id')
                    ->nullable()
                    ->after('type')
                    ->constrained('admins')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'updated_by_admin_id')) {
                $table->dropConstrainedForeignId('updated_by_admin_id');
            }

            if (Schema::hasColumn('site_settings', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
