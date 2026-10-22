<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        // Get the current columns in the users table
        $existingColumns = \DB::getSchemaBuilder()->getColumnListing('users');

        // Define the columns we want to add if they don't exist
        $columnsToAdd = [
            'last_login_user_agent' => function ($table) {
                $table->text('last_login_user_agent')->nullable()->after('last_login_ip');
            },
            'login_attempts' => function ($table) {
                $table->unsignedInteger('login_attempts')->default(0)->after('last_login_user_agent');
            },
            'last_failed_attempt_at' => function ($table) {
                $table->timestamp('last_failed_attempt_at')->nullable()->after('login_attempts');
            },
        ];

        // Add only the columns that don't exist
        foreach ($columnsToAdd as $column => $callback) {
            if (! in_array($column, $existingColumns)) {
                Schema::table('users', function (Blueprint $table) use ($callback) {
                    $callback($table);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We'll leave this empty since we don't want to drop columns that might be in use
        // Users should create a fresh database if they need to roll back
    }
};
