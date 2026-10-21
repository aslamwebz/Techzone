<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                // Add last login tracking columns if they don't exist
                if (! Schema::hasColumn('users', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable()->after('remember_token');
                }

                if (! Schema::hasColumn('users', 'last_login_ip')) {
                    $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
                }

                if (! Schema::hasColumn('users', 'last_login_user_agent')) {
                    $table->text('last_login_user_agent')->nullable()->after('last_login_ip');
                }

                // Add login attempt tracking
                if (! Schema::hasColumn('users', 'login_attempts')) {
                    $table->unsignedInteger('login_attempts')->default(0)->after('last_login_user_agent');
                }

                if (! Schema::hasColumn('users', 'last_failed_attempt_at')) {
                    $table->timestamp('last_failed_attempt_at')->nullable()->after('login_attempts');
                }

                if (! Schema::hasColumn('users', 'banned_at')) {
                    $table->timestamp('banned_at')->nullable()->after('last_failed_attempt_at');
                }

                if (! Schema::hasColumn('users', 'password_changed_at')) {
                    $table->timestamp('password_changed_at')->nullable()->after('banned_at');
                }
            } catch (Exception $e) {
                // Log the error and continue
                Log::error('Migration error: '.$e->getMessage());
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_at',
                'last_login_ip',
                'last_login_user_agent',
                'login_attempts',
                'last_failed_attempt_at',
                'banned_at',
                'password_changed_at',
            ]);
        });
    }
};
