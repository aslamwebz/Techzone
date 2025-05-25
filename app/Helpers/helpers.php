<?php

use Illuminate\Support\Facades\Session;

if (!function_exists('cartCount')) {
    /**
     * Get the total number of items in the cart
     *
     * @return int
     */
    function cartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
}

if (!function_exists('formatPrice')) {
    /**
     * Format price with currency symbol
     * 
     * @param float $price
     * @return string
     */
    function formatPrice($price)
    {
        return '₦' . number_format($price, 2);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format price with currency symbol (alias for formatPrice)
     * 
     * @param float $amount
     * @param int $decimals
     * @return string
     */
    function format_currency($amount, $decimals = 2)
    {
        return '₦' . number_format($amount, $decimals);
    }
}

if (!function_exists('calculateTax')) {
    /**
     * Calculate tax amount
     * 
     * @param float $amount
     * @param float $rate
     * @return float
     */
    function calculateTax($amount, $rate = 0.1)
    {
        return $amount * $rate;
    }
}

if (!function_exists('getCartSubtotal')) {
    /**
     * Get cart subtotal
     * 
     * @return float
     */
    function getCartSubtotal()
    {
        $cart = Session::get('cart', []);
        return array_reduce($cart, function($carry, $item) {
            $price = $item['sale_price'] ?? $item['price'];
            return $carry + ($price * $item['quantity']);
        }, 0);
    }
}

if (!function_exists('getCartTotal')) {
    /**
     * Get cart total including tax
     * 
     * @param float $taxRate
     * @return array
     */
    function getCartTotal($taxRate = 0.1)
    {
        $subtotal = getCartSubtotal();
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;
        
        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'tax_rate' => $taxRate * 100, // as percentage
            'total' => $total
        ];
    }
}
