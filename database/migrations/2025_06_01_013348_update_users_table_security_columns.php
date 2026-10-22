<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to check for and add columns if they don't exist
        $db = DB::connection()->getPdo();

        // Get the SQL for the users table
        $stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'");
        $tableSql = $stmt->fetchColumn();

        // Check and add each column if it doesn't exist
        $columns = [
            'last_login_at' => 'ALTER TABLE users ADD COLUMN last_login_at TIMESTAMP NULL AFTER remember_token',
            'last_login_ip' => 'ALTER TABLE users ADD COLUMN last_login_ip VARCHAR(45) NULL AFTER last_login_at',
            'last_login_user_agent' => 'ALTER TABLE users ADD COLUMN last_login_user_agent TEXT NULL AFTER last_login_ip',
            'login_attempts' => 'ALTER TABLE users ADD COLUMN login_attempts INTEGER UNSIGNED NOT NULL DEFAULT 0 AFTER last_login_user_agent',
            'last_failed_attempt_at' => 'ALTER TABLE users ADD COLUMN last_failed_attempt_at TIMESTAMP NULL AFTER login_attempts',
            'banned_at' => 'ALTER TABLE users ADD COLUMN banned_at TIMESTAMP NULL AFTER last_failed_attempt_at',
            'password_changed_at' => 'ALTER TABLE users ADD COLUMN password_changed_at TIMESTAMP NULL AFTER banned_at',
        ];

        foreach ($columns as $column => $sql) {
            if (strpos($tableSql, '"'.$column.'"') === false) {
                DB::statement($sql);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We can't easily drop columns in SQLite, so we'll leave this empty
        // Users should create a fresh database if they need to roll back
    }
};
