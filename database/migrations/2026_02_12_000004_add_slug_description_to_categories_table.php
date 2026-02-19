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
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }

            if (! Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
        });

        if (Schema::hasColumn('categories', 'slug')) {
            $existing = DB::table('categories')->select('id', 'name', 'slug')->get();
            $used = DB::table('categories')->pluck('slug')->filter()->values()->all();

            foreach ($existing as $row) {
                if (! empty($row->slug)) {
                    continue;
                }

                $base = Str::slug($row->name ?: 'category');
                $slug = $base !== '' ? $base : 'category';
                $counter = 2;

                while (in_array($slug, $used, true)) {
                    $slug = $base . '-' . $counter;
                    $counter++;
                }

                $used[] = $slug;
                DB::table('categories')->where('id', $row->id)->update(['slug' => $slug]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('categories', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
