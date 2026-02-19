<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_media')) {
            return;
        }

        Schema::create('site_media', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable()->index();
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('alt_text')->nullable();
            $table->string('group')->nullable()->index();
            $table->foreignId('uploaded_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_media');
    }
};

