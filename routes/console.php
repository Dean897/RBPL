<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Purge database while keeping users with roles 'sekretariat' and 'pimpinan'.
 * Creates JSON backups under storage/app/backups/{timestamp}/ before truncating.
 */
Artisan::command('db:purge-keep-roles', function () {
    $timestamp = now()->format('Ymd-His');
    $backupDir = storage_path('app/backups/' . $timestamp);
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    $this->comment('Listing tables...');
    $tables = DB::select('SHOW TABLES');
    $tableNames = [];
    foreach ($tables as $row) {
        $arr = (array) $row;
        $tableNames[] = reset($arr);
    }

    $this->comment('Backing up tables to ' . $backupDir);
    foreach ($tableNames as $table) {
        try {
            $rows = DB::table($table)->get();
            file_put_contents($backupDir . '/' . $table . '.json', json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("Backed up: $table ({" . count($rows) . " rows})");
        } catch (\Exception $e) {
            $this->error("Failed to backup table $table: " . $e->getMessage());
        }
    }

    $this->comment('Disabling foreign key checks...');
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    $exclude = ['users', 'migrations'];
    foreach ($tableNames as $table) {
        if (in_array($table, $exclude)) {
            $this->comment("Skipping table: $table");
            continue;
        }
        try {
            DB::table($table)->truncate();
            $this->info("Truncated: $table");
        } catch (\Exception $e) {
            $this->error("Failed to truncate $table: " . $e->getMessage());
        }
    }

    // Remove users except sekretariat and pimpinan
    $this->comment('Removing non-sekretariat/pimpinan users...');
    $kept = ['sekretariat', 'pimpinan'];
    try {
        $deleted = DB::table('users')->whereNotIn('role', $kept)->delete();
        $this->info("Deleted $deleted users not in roles: " . implode(', ', $kept));
    } catch (\Exception $e) {
        $this->error('Failed to prune users: ' . $e->getMessage());
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    $this->info('Database purge complete. Backups are in: ' . $backupDir);
})->describe('Purge DB but keep users with roles sekretariat and pimpinan');
