<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            if (! Schema::hasColumn('equipments', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }

            if (! Schema::hasColumn('equipments', 'status')) {
                $table->string('status')->default('ready')->after('price_per_day');
            }

            if (! Schema::hasColumn('equipments', 'image_path')) {
                $table->string('image_path')->nullable()->after('image');
            }
        });

        if (Schema::hasColumn('equipments', 'slug')) {
            $existing = DB::table('equipments')->select('id', 'name', 'slug', 'image', 'image_path', 'status')->get();
            $used = DB::table('equipments')->pluck('slug')->filter()->values()->all();

            foreach ($existing as $row) {
                $updates = [];

                if (empty($row->slug)) {
                    $base = Str::slug($row->name ?: 'equipment');
                    $slug = $base !== '' ? $base : 'equipment';
                    $counter = 2;

                    while (in_array($slug, $used, true)) {
                        $slug = $base . '-' . $counter;
                        $counter++;
                    }

                    $used[] = $slug;
                    $updates['slug'] = $slug;
                }

                if (empty($row->image_path) && ! empty($row->image)) {
                    $updates['image_path'] = $row->image;
                }

                if (empty($row->status)) {
                    $updates['status'] = 'ready';
                }

                if (! empty($updates)) {
                    DB::table('equipments')->where('id', $row->id)->update($updates);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            if (Schema::hasColumn('equipments', 'image_path')) {
                $table->dropColumn('image_path');
            }

            if (Schema::hasColumn('equipments', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('equipments', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
