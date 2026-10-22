<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's check which columns already exist
        $existingColumns = Schema::getColumnListing('users');

        Schema::table('users', function (Blueprint $table) use ($existingColumns) {
            // Add last login tracking columns if they don't exist
            if (! in_array('last_login_at', $existingColumns)) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }

            if (! in_array('last_login_ip', $existingColumns)) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }

            if (! in_array('last_login_user_agent', $existingColumns)) {
                $table->text('last_login_user_agent')->nullable()->after('last_login_ip');
            }

            // Add login attempt tracking
            if (! in_array('login_attempts', $existingColumns)) {
                $table->unsignedInteger('login_attempts')->default(0)->after('last_login_user_agent');
            }

            if (! in_array('last_failed_attempt_at', $existingColumns)) {
                $table->timestamp('last_failed_attempt_at')->nullable()->after('login_attempts');
            }

            if (! in_array('banned_at', $existingColumns)) {
                $table->timestamp('banned_at')->nullable()->after('last_failed_attempt_at');
            }

            if (! in_array('password_changed_at', $existingColumns)) {
                $table->timestamp('password_changed_at')->nullable()->after('banned_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We'll only drop the columns if they exist
        $existingColumns = Schema::getColumnListing('users');
        $columnsToDrop = [
            'last_login_at',
            'last_login_ip',
            'last_login_user_agent',
            'login_attempts',
            'last_failed_attempt_at',
            'banned_at',
            'password_changed_at',
        ];

        // Only keep columns that actually exist
        $columnsToDrop = array_intersect($columnsToDrop, $existingColumns);

        if (! empty($columnsToDrop)) {
            Schema::table('users', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};
