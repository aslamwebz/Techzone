<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register any service providers here

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL
        Schema::defaultStringLength(191);

        // Temporarily disabled UserObserver to bypass permission issues
        // User::observe(UserObserver::class);

        // Set default timezone
        date_default_timezone_set(config('app.timezone', 'UTC'));

        // Set locale
        if (config('app.locale') !== 'en') {
            setlocale(LC_TIME, config('app.locale'));
        }
    }
}
