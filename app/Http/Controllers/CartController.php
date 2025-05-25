<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartDetails = $this->getCartDetails($cart);
        return view('cart.index', $cartDetails);
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'quantity' => 1,
                'price' => $product->sale_price ?? $product->price,
                'original_price' => $product->price,
                'sale_price' => $product->sale_price,
                'image' => $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150',
                'description' => $product->short_description ?? $product->description
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'cart_count' => cartCount(),
            'message' => 'Product added to cart successfully!'
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            $cartDetails = $this->getCartDetails($cart);
            
            return response()->json([
                'success' => true,
                'total' => format_currency($cartDetails['total']),
                'subtotal' => format_currency($cartDetails['subtotal']),
                'tax' => format_currency($cartDetails['tax']),
                'item_total' => format_currency(($cart[$request->id]['sale_price'] ?? $cart[$request->id]['price']) * $request->quantity),
                'cart_count' => cartCount(),
                'message' => 'Cart updated successfully!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id'
        ]);

        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            $productName = $cart[$request->id]['name'];
            unset($cart[$request->id]);
            session()->put('cart', $cart);
            
            $cartDetails = $this->getCartDetails($cart);
            
            return response()->json([
                'success' => true,
                'total' => format_currency($cartDetails['total']),
                'subtotal' => format_currency($cartDetails['subtotal']),
                'tax' => format_currency($cartDetails['tax']),
                'cart_count' => cartCount(),
                'message' => '"' . $productName . '" removed from cart.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }

    /**
     * Get the current cart with calculated totals
     */
    private function getCart()
    {
        return session()->get('cart', []);
    }

    /**
     * Calculate cart details including subtotal, tax, and total
     */
    private function getCartDetails($cart)
    {
        $subtotal = 0;
        $taxRate = 0.1; // 10% tax rate
        
        foreach($cart as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
        }
        
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;
        
        return [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'tax_rate' => $taxRate * 100, // As percentage
            'total' => $total,
            'cart_count' => cartCount()
        ];
    }
}
//     "app/Helpers/helpers.php"
// ]
// Then run: composer dump-autoload
