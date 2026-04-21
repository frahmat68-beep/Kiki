<?php

use App\Models\AuditLog;
use App\Models\SiteContent;
use App\Models\SiteMedia;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

if (! function_exists('site_public_media_candidates')) {
    function site_public_media_candidates(string $path): array
    {
        $normalizedPath = ltrim($path, '/');
        $candidates = [
            [public_path('storage/' . $normalizedPath), public_path('storage')],
            [base_path('storage/app/public/' . $normalizedPath), base_path('storage/app/public')],
        ];

        try {
            $publicDisk = Storage::disk('public');

            if (method_exists($publicDisk, 'exists') && $publicDisk->exists($normalizedPath)) {
                $diskPath = $publicDisk->path($normalizedPath);

                if (is_file($diskPath)) {
                    $candidates[] = [$diskPath, dirname($diskPath)];
                }
            }
        } catch (\Throwable $exception) {
            // Fall through to bundled/public path checks.
        }

        return $candidates;
    }
}

if (! function_exists('schema_table_exists_cached')) {
    function schema_table_exists_cached(string $table): bool
    {
        static $tableExistsCache = [];

        if (app()->runningUnitTests()) {
            try {
                return Schema::hasTable($table);
            } catch (\Throwable $exception) {
                return false;
            }
        }

        $cacheKey = schema_exists_cache_key('table', $table);

        if (array_key_exists($cacheKey, $tableExistsCache)) {
            return $tableExistsCache[$cacheKey];
        }

        try {
            $tableExistsCache[$cacheKey] = (bool) Cache::remember(
                $cacheKey,
                now()->addMinutes(10),
                fn () => Schema::hasTable($table)
            );
        } catch (\Throwable $exception) {
            $tableExistsCache[$cacheKey] = false;
            // Immediate fallback to direct check if cache fails
            try {
                return Schema::hasTable($table);
            } catch (\Throwable $inner) {
                return false;
            }
        }

        return $tableExistsCache[$cacheKey];
    }
}

if (! function_exists('schema_column_exists_cached')) {
    function schema_column_exists_cached(string $table, string $column): bool
    {
        static $columnExistsCache = [];

        if (! schema_table_exists_cached($table)) {
            return false;
        }

        if (app()->runningUnitTests()) {
            try {
                return Schema::hasColumn($table, $column);
            } catch (\Throwable $exception) {
                return false;
            }
        }

        $cacheKey = schema_exists_cache_key('column', $table, $column);

        if (array_key_exists($cacheKey, $columnExistsCache)) {
            return $columnExistsCache[$cacheKey];
        }

        try {
            $columnExistsCache[$cacheKey] = (bool) Cache::remember(
                $cacheKey,
                now()->addMinutes(10),
                fn () => Schema::hasColumn($table, $column)
            );
        } catch (\Throwable $exception) {
            $columnExistsCache[$columnExistsCache] = false;
            try {
                return Schema::hasColumn($table, $column);
            } catch (\Throwable $inner) {
                return false;
            }
        }

        return $columnExistsCache[$cacheKey];
    }
}

if (! function_exists('schema_exists_cache_key')) {
    function schema_exists_cache_key(string $type, string $table, ?string $column = null): string
    {
        $connection = (string) config('database.default', 'default');
        $database = (string) config("database.connections.{$connection}.database", 'default');
        $signature = $connection . '|' . $database . '|' . $table . '|' . ((string) $column);

        return 'schema_exists:' . $type . ':' . sha1($signature);
    }
}
