<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index(): View|RedirectResponse
    {
        // Redirect to cart if empty
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your cart is empty. Please add some products before checkout.');
        }

        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $tax = Cart::tax();
        $total = Cart::total();
        $user = Auth::user();

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'user' => $user,
        ]);
    }

    /**
     * Process the checkout form submission.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $validated = $request->validated();
        $cartItems = Cart::content();
        $subtotal = (float) str_replace(',', '', Cart::subtotal());
        $tax = (float) str_replace(',', '', Cart::tax());
        $total = (float) str_replace(',', '', Cart::total());

        try {
            $order = DB::transaction(function () use ($validated, $cartItems, $subtotal, $tax, $total) {
                // Verify and deduct stock with pessimistic locking
                foreach ($cartItems as $item) {
                    $product = Product::where('id', $item->id)->lockForUpdate()->first();

                    if (! $product || ! $product->is_active) {
                        throw new Exception("Product '{$item->name}' is no longer available.");
                    }

                    if ($product->stock < $item->qty) {
                        throw new Exception("Product '{$item->name}' is out of stock or has insufficient quantity (only {$product->stock} available).");
                    }

                    $product->decrement('stock', $item->qty);
                }

                // Create Order record
                $order = Order::create([
                    'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total_amount' => $total,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'pending',
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'country' => $validated['country'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Create Order Items
                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id' => $item->id,
                        'product_name' => $item->name,
                        'price' => (float) $item->price,
                        'quantity' => (int) $item->qty,
                        'total' => (float) $item->total,
                    ]);
                }

                // Clear the cart
                Cart::destroy();

                return $order;
            });

            // Store order details in session
            session([
                'order_id' => $order->order_number,
                'shipping_address' => [
                    'name' => $validated['first_name'].' '.$validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'country' => $validated['country'],
                ],
                'payment_method' => ucfirst(str_replace('_', ' ', $validated['payment_method'])),
            ]);

            return redirect()->route('checkout.success')
                ->with('success', 'Your order has been placed successfully!');

        } catch (Exception $e) {
            Log::error('Checkout error: '.$e->getMessage());

            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the checkout success page.
     */
    public function success(): View|RedirectResponse
    {
        if (! session('success') && ! session('order_id')) {
            return redirect()->route('home');
        }

        return view('checkout.success');
    }
}
