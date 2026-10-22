<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\Cart\Cart instance(string $instance = null) Get the cart instance
 * @method static \App\Services\Cart\CartItem add(string|array $id, string $name = null, int $qty = 1, float $price = 0.0, array $options = []) Add an item to the cart
 * @method static \App\Services\Cart\CartItem|null get(string $rowId) Get an item from the cart by its rowId
 * @method static \App\Services\Cart\CartItem|null update(string $rowId, mixed $qty) Update the quantity of an item in the cart
 * @method static bool remove(string $rowId) Remove an item from the cart
 * @method static void destroy() Empty the cart
 * @method static \Illuminate\Support\Collection content() Get the cart content
 * @method static int count() Get the number of items in the cart
 * @method static string total(int $decimals = 2, string $decimalPoint = '.', string $thousandSeparator = '') Get the total price of the items in the cart
 * @method static string subtotal(int $decimals = 2, string $decimalPoint = '.', string $thousandSeparator = '') Get the subtotal of the items in the cart
 * @method static string tax(int $decimals = 2, string $decimalPoint = '.', string $thousandSeparator = '') Get the tax amount for the items in the cart
 * @method static void setGlobalTax(float $taxRate) Set the global tax rate
 * @method static \Illuminate\Support\Collection search(\Closure $closure) Search the cart for items
 * @method static void store(string $identifier) Store the cart
 * @method static void restore(string $identifier) Restore a cart
 *
 * @see \App\Services\Cart\Cart
 */
class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cart';
    }
}
