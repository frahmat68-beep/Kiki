<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const POLICY_NAME = 'laravel_full_access_postgres';

    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $tables = DB::select("
            SELECT tablename
            FROM pg_tables
            WHERE schemaname = 'public'
            ORDER BY tablename
        ");

        if ($tables === []) {
            return;
        }

        $roleRow = DB::selectOne('SELECT current_user AS role_name');
        $roleName = (string) ($roleRow->role_name ?? 'postgres');

        foreach ($tables as $tableRow) {
            $table = (string) ($tableRow->tablename ?? '');
            if ($table === '') {
                continue;
            }

            DB::statement(
                sprintf('ALTER TABLE public.%s ENABLE ROW LEVEL SECURITY', $this->quoteIdentifier($table))
            );

            $policyExists = DB::selectOne(
                "
                SELECT 1
                FROM pg_policies
                WHERE schemaname = 'public'
                  AND tablename = ?
                  AND policyname = ?
                LIMIT 1
                ",
                [$table, self::POLICY_NAME]
            );

            if ($policyExists) {
                continue;
            }

            DB::statement(
                sprintf(
                    'CREATE POLICY %s ON public.%s AS PERMISSIVE FOR ALL TO %s USING (true) WITH CHECK (true)',
                    $this->quoteIdentifier(self::POLICY_NAME),
                    $this->quoteIdentifier($table),
                    $this->quoteIdentifier($roleName)
                )
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $tables = DB::select("
            SELECT tablename
            FROM pg_tables
            WHERE schemaname = 'public'
            ORDER BY tablename
        ");

        foreach ($tables as $tableRow) {
            $table = (string) ($tableRow->tablename ?? '');
            if ($table === '') {
                continue;
            }

            DB::statement(
                sprintf(
                    'DROP POLICY IF EXISTS %s ON public.%s',
                    $this->quoteIdentifier(self::POLICY_NAME),
                    $this->quoteIdentifier($table)
                )
            );

            DB::statement(
                sprintf('ALTER TABLE public.%s DISABLE ROW LEVEL SECURITY', $this->quoteIdentifier($table))
            );
        }
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '"'.str_replace('"', '""', $identifier).'"';
    }
};
