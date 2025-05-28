<?php

use Gloudemans\Shoppingcart\Facades\Cart;

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
        // Convert string to float if needed
        $amount = is_string($amount) ? (float) str_replace([',', ' '], ['.', ''], $amount) : $amount;
        
        $formatter = new \NumberFormatter('en_NG', \NumberFormatter::CURRENCY);
        $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $decimals);
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
        
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

// Aliases for backward compatibility
if (!function_exists('cartCount')) {
    function cartCount() { 
        return cart_count(); 
    }
}

if (!function_exists('cartSubtotal')) {
    function cartSubtotal() { 
        return (float) str_replace([',', '€', ' '], '', cart_subtotal()); 
    }
}

if (!function_exists('cartTax')) {
    function cartTax() { 
        return (float) str_replace([',', '€', ' '], '', cart_tax()); 
    }
}

if (!function_exists('cartTotal')) {
    function cartTotal() { 
        return (float) str_replace([',', '€', ' '], '', cart_total()); 
    }
}
