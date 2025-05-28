<?php

use Gloudemans\Shoppingcart\Facades\Cart;
use NumberFormatter;

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency.
     *
     * @param  float  $amount
     * @param  string  $currency
     * @param  int  $decimals
     * @return string
     */
    function format_currency($amount, $currency = 'NGN', $decimals = 2)
    {
        $formatter = new NumberFormatter('en_NG', NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }
}

if (!function_exists('cart_count')) {
    /**
     * Get the number of items in the cart.
     *
     * @return int
     */
    function cart_count()
    {
        return Cart::count();
    }
}

if (!function_exists('cart_subtotal')) {
    /**
     * Get the subtotal of the cart.
     *
     * @return string
     */
    function cart_subtotal()
    {
        return Cart::subtotal();
    }
}

if (!function_exists('cart_tax')) {
    /**
     * Get the tax amount of the cart.
     *
     * @return string
     */
    function cart_tax()
    {
        return Cart::tax();
    }
}

if (!function_exists('cart_total')) {
    /**
     * Get the total of the cart.
     *
     * @return string
     */
    function cart_total()
    {
        return Cart::total();
    }
}

if (!function_exists('format_price')) {
    /**
     * Format price with currency symbol (alias for format_currency)
     * 
     * @param float $price
     * @param int $decimals
     * @return string
     */
    function format_price($price, $decimals = 2)
    {
        return format_currency($price, 'NGN', $decimals);
    }
}

if (!function_exists('calculate_tax')) {
    /**
     * Calculate tax amount
     * 
     * @param float $amount
     * @param float $rate
     * @return float
     */
    function calculate_tax($amount, $rate = 0.1)
    {
        return $amount * $rate;
    }
}
