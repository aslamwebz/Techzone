<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Redirect to cart if empty
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your cart is empty. Please add some products before checkout.');
        }

        // Get cart items and totals
        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $tax = Cart::tax();
        $total = Cart::total();

        // Get user details if logged in
        $user = Auth::user();
        
        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'user' => $user
        ]);
    }

    /**
     * Process the checkout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Process the checkout form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|string|in:cash_on_delivery',
        ]);

        try {
            // Here you would typically:
            // 1. Create an order record in the database
            // 2. Process payment if needed
            // 3. Send order confirmation email
            // 4. Clear the cart
            // 5. Redirect to success page

            // For now, we'll just clear the cart and redirect to success
            $orderId = 'ORD-' . strtoupper(uniqid());
            
            // Store order details in session for the success page
            session([
                'order_id' => $orderId,
                'shipping_address' => [
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'country' => $validated['country'],
                ],
                'payment_method' => 'Cash on Delivery',
            ]);

            // Clear the cart
            Cart::destroy();

            return redirect()->route('checkout.success')
                ->with('success', 'Your order has been placed successfully!');
                
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Checkout error: ' . $e->getMessage());
            
            // Redirect back with error
            return redirect()->back()
                ->with('error', 'There was an error processing your order. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the checkout success page.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        if (!session('success')) {
            return redirect()->route('home');
        }
        
        return view('checkout.success');
    }
}
