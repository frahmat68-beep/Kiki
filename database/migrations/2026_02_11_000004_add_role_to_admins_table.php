<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'role')) {
                $table->string('role')->default('admin')->after('password');
            }

            if (! Schema::hasColumn('admins', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }

            if (Schema::hasColumn('admins', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
