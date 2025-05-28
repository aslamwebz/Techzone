<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Gloudemans\Shoppingcart\Cart instance(string $instance = null) Get the cart instance
 * @method static \Gloudemans\Shoppingcart\Cart add(string|array $id, string $name = null, int|float $qty = null, float $price = null, array $options = []) Add an item to the cart
 * @method static \Gloudemans\Shoppingcart\CartItem get(string $rowId) Get an item from the cart by its rowId
 * @method static \Gloudemans\Shoppingcart\Cart update(string $rowId, int|float $qty) Update the quantity of an item in the cart
 * @method static \Gloudemans\Shoppingcart\Cart remove(string $rowId) Remove an item from the cart
 * @method static \Gloudemans\Shoppingcart\Cart destroy() Empty the cart
 * @method static \Illuminate\Support\Collection content() Get the cart content
 * @method static int|float count() Get the number of items in the cart
 * @method static float total(int $decimals = null, string $decimalPoint = null, string $thousandSeperator = null) Get the total price of the items in the cart
 * @method static float subtotal(int $decimals = null, string $decimalPoint = null, string $thousandSeperator = null) Get the subtotal of the items in the cart
 * @method static float tax(int $decimals = null, string $decimalPoint = null, string $thousandSeperator = null) Get the tax amount for the items in the cart
 * @method static float taxRate() Get the tax rate
 * @method static \Gloudemans\Shoppingcart\Cart setTax(string $rowId, int|float $taxRate) Set the tax rate for an item
 * @method static \Gloudemans\Shoppingcart\Cart setGlobalTax(int|float $taxRate) Set the global tax rate
 * @method static \Gloudemans\Shoppingcart\Cart setGlobalDiscount(int|float $discount) Set the global discount
 * @method static \Illuminate\Support\Collection search(mixed $search) Search the cart for items
 * @method static \Gloudemans\Shoppingcart\Cart associate(string $rowId, string $model) Associate a model with an item
 * @method static \Gloudemans\Shoppingcart\Cart store(string $identifier) Store the cart
 * @method static \Gloudemans\Shoppingcart\Cart restore(string $identifier) Restore a cart
 * @method static \Gloudemans\Shoppingcart\Cart erase(string $identifier) Erase a cart
 * @method static \Gloudemans\Shoppingcart\Cart restoreLastActiveCart() Restore the last active cart
 * 
 * @see \Gloudemans\Shoppingcart\Cart
 */
class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cart';
    }
}
