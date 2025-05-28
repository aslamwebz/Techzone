<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Cart;
use Illuminate\Support\Facades\Response;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $tax = Cart::tax();
        $total = Cart::total();
        
        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add a product to the shopping cart.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart($id, Request $request)
    {
        \Log::info('Add to cart request:', [
            'product_id' => $id,
            'quantity' => $request->input('quantity'),
            'all_input' => $request->all(),
            'session_id' => session()->getId()
        ]);

        try {
            $product = Product::findOrFail($id);
            $quantity = $request->input('quantity', 1);
            
            // Validate quantity
            $quantity = max(1, min(99, (int)$quantity));
        
        // Check if product already in cart
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });
        
        if ($cartItem->isNotEmpty()) {
            // Update quantity if product already in cart
            $rowId = $cartItem->first()->rowId;
            $item = Cart::get($rowId);
            $newQty = $item->qty + $quantity;
            Cart::update($rowId, $newQty);
        } else {
            // Add new item to cart
            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $quantity,
                'price' => $product->sale_price ?? $product->price,
                'weight' => 0,
                'options' => [
                    'image' => $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150',
                    'slug' => $product->slug,
                    'description' => $product->short_description ?? $product->description,
                    'original_price' => $product->price,
                    'sale_price' => $product->sale_price,
                ]
            ]);
        }
        
            $response = [
                'success' => true,
                'cart_count' => Cart::count(),
                'message' => 'Product added to cart successfully!',
                'cart_total' => Cart::total(),
                'cart_content' => Cart::content(),
                'session_id' => session()->getId()
            ];

            \Log::info('Cart response:', $response);
            
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error adding to cart: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error adding product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified item in the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'rowId' => 'required|string',
            'qty' => 'required|integer|min:1|max:100'
        ]);

        // Check if the item exists in the cart
        $item = Cart::get($request->rowId);
        
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        // Update the quantity
        Cart::update($request->rowId, $request->qty);
        
        // Get the updated item
        $updatedItem = Cart::get($request->rowId);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => Cart::count(),
            'cart_subtotal' => Cart::subtotal(),
            'cart_tax' => Cart::tax(),
            'cart_total' => Cart::total(),
            'item_subtotal' => $updatedItem->subtotal,
            'item' => [
                'rowId' => $updatedItem->rowId,
                'qty' => $updatedItem->qty,
                'price' => $updatedItem->price,
                'options' => $updatedItem->options->toArray()
            ]
        ]);
    }

    /**
     * Remove the specified item from cart.
     *
     * @param  string  $rowId
     * @return \Illuminate\Http\Response
     */
    public function removeFromCart($rowId)
    {
        $item = Cart::get($rowId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        Cart::remove($rowId);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully!',
            'cart_count' => Cart::count(),
            'cart_total' => Cart::total(),
            'cart_subtotal' => Cart::subtotal()
        ]);
    }

    /**
     * Clear all items from the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearCart()
    {
        Cart::destroy();
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!',
            'cart_count' => 0,
            'cart_total' => '₦0.00',
            'cart_subtotal' => '₦0.00'
        ]);
    }
    
    /**
     * Get the current cart count and totals.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartCount()
    {
        return response()->json([
            'count' => Cart::count(),
            'total' => Cart::total(),
            'subtotal' => Cart::subtotal()
        ]);
    }

    /**
     * Get the number of items in the cart.
     *
     * @return int
     */
    protected function getCartCount()
    {
        return Cart::count();
    }

    /**
     * Get the cart subtotal.
     *
     * @return float
     */
    protected function getSubtotal()
    {
        return (float) str_replace(',', '', Cart::subtotal());
    }

    /**
     * Get the cart tax amount.
     *
     * @return float
     */
    protected function getTax()
    {
        return (float) str_replace(',', '', Cart::tax());
    }

    /**
     * Get the cart total.
     *
     * @return float
     */
    protected function getTotal()
    {
        return (float) str_replace(',', '', Cart::total());
    }
}
//     "app/Helpers/helpers.php"
// ]
// Then run: composer dump-autoload
