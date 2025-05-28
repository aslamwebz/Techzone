<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Gloudemans\Shoppingcart\ShoppingcartServiceProvider as BaseShoppingcartServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * The shopping cart instance.
     *
     * @var \Gloudemans\Shoppingcart\Cart
     */
    protected $cart;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('cart', function($app) {
            $storage = $app['session'];
            $events = $app['events'];
            $instanceName = 'default';
            $session_key = config('app.key');
            
            // Get the cart configuration
            $config = config('cart', [
                'tax' => 0,
                'database' => [
                    'connection' => null,
                    'table' => 'shopping_cart',
                ],
                'destroy_on_logout' => false,
                'format' => [
                    'decimals' => 2,
                    'decimal_point' => '.',
                    'thousand_separator' => '',
                ],
            ]);
            
            // Create a new cart instance with our configuration
            return new \Gloudemans\Shoppingcart\Cart(
                $storage,
                $events,
                $instanceName,
                $session_key,
                $config
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration file if it doesn't exist
        if (!file_exists(config_path('cart.php'))) {
            $this->publishes([
                __DIR__.'/../../vendor/hardevine/shoppingcart/config/cart.php' => config_path('cart.php'),
            ], 'config');
        }
    }
}
