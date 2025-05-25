<?php

use Illuminate\Support\Facades\Session;

if (!function_exists('cartCount')) {
    /**
     * Get the number of items in the cart
     *
     * @return int
     */
    function cartCount()
    {
        $cart = Session::get('cart', []);
        return array_reduce($cart, function($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);
    }
}

if (!function_exists('cartTotal')) {
    /**
     * Calculate the total price of items in the cart
     *
     * @return float
     */
    function cartTotal()
    {
        $cart = Session::get('cart', []);
        return array_reduce($cart, function($carry, $item) {
            $price = $item['sale_price'] ?? $item['price'];
            return $carry + ($price * $item['quantity']);
        }, 0);
    }
}
