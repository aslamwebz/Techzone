<?php

namespace App\Providers;

use App\Services\Cart\Cart;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('cart', function ($app) {
            return new Cart(
                $app['session.store'],
                $app['events'],
                $app['db']
            );
        });

        $this->app->alias('cart', Cart::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
