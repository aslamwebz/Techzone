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
        // Get the current columns in the users table
        $existingColumns = \DB::getSchemaBuilder()->getColumnListing('users');

        Schema::table('users', function (Blueprint $table) use ($existingColumns) {
            if (! in_array('banned_at', $existingColumns)) {
                $table->timestamp('banned_at')->nullable()->after('is_admin');
            }

            if (! in_array('password_changed_at', $existingColumns)) {
                $table->timestamp('password_changed_at')->nullable()->after('banned_at');
            }

            if (! in_array('two_factor_secret', $existingColumns)) {
                $table->string('two_factor_secret')->nullable()->after('password');
            }

            if (! in_array('two_factor_recovery_codes', $existingColumns)) {
                $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            }

            if (! in_array('two_factor_confirmed_at', $existingColumns)) {
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            }

            if (! in_array('last_login_ip', $existingColumns)) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }

            if (! in_array('force_password_reset', $existingColumns)) {
                $table->boolean('force_password_reset')->default(false)->after('password_changed_at');
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
                'banned_at',
                'password_changed_at',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'last_login_ip',
                'force_password_reset',
            ]);
        });
    }
};
