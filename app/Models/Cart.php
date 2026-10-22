<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
        'options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'options' => 'array',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that owns the cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the current cart items count for the authenticated user or session.
     *
     * @param  string|int  $identifier  User ID or session ID
     * @return int
     */
    public static function countItems($identifier = null)
    {
        if (Auth::check()) {
            return self::where('user_id', Auth::id())->sum('quantity');
        }

        $sessionId = $identifier ?? session()->getId();

        return self::where('session_id', $sessionId)->sum('quantity');
    }

    /**
     * Get the current cart items for the authenticated user or session.
     *
     * @param  string|int  $identifier  User ID or session ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCartItems($identifier = null)
    {
        $query = self::with('product');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $sessionId = $identifier ?? session()->getId();
            $query->where('session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Add an item to the cart.
     *
     * @param  array  $itemData
     * @return Cart
     */
    public static function addItem($itemData)
    {
        $itemData = array_merge([
            'quantity' => 1,
            'options' => [],
        ], $itemData);

        $cartItem = self::where('product_id', $itemData['product_id']);

        if (Auth::check()) {
            $cartItem = $cartItem->where('user_id', Auth::id());
        } else {
            $sessionId = session()->getId();
            $cartItem = $cartItem->where('session_id', $sessionId);
            $itemData['session_id'] = $sessionId;
        }

        $cartItem = $cartItem->first();

        if ($cartItem) {
            // Update quantity if item already exists
            $cartItem->increment('quantity', $itemData['quantity']);

            return $cartItem;
        }

        // Create new cart item
        if (Auth::check()) {
            $itemData['user_id'] = Auth::id();
        }

        return self::create($itemData);
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int  $id  Cart item ID
     * @return bool
     */
    public static function removeItem($id)
    {
        $cartItem = self::find($id);

        if (! $cartItem) {
            return false;
        }

        // Check if the user owns this cart item
        if (Auth::check() && $cartItem->user_id !== Auth::id()) {
            return false;
        }

        if (! Auth::check() && $cartItem->session_id !== session()->getId()) {
            return false;
        }

        return $cartItem->delete();
    }

    /**
     * Update cart item quantity.
     *
     * @param  int  $id  Cart item ID
     * @param  int  $quantity  New quantity
     * @return bool
     */
    public static function updateQuantity($id, $quantity)
    {
        $cartItem = self::find($id);

        if (! $cartItem) {
            return false;
        }

        // Check if the user owns this cart item
        if (Auth::check() && $cartItem->user_id !== Auth::id()) {
            return false;
        }

        if (! Auth::check() && $cartItem->session_id !== session()->getId()) {
            return false;
        }

        if ($quantity <= 0) {
            return $cartItem->delete();
        }

        $cartItem->quantity = $quantity;

        return $cartItem->save();
    }

    /**
     * Clear the cart for the current user or session.
     *
     * @return void
     */
    public static function clear()
    {
        if (Auth::check()) {
            self::where('user_id', Auth::id())->delete();
        } else {
            self::where('session_id', session()->getId())->delete();
        }
    }

    /**
     * Merge session cart with user cart after login.
     *
     * @param  string  $sessionId
     * @param  int  $userId
     * @return void
     */
    public static function mergeCarts($sessionId, $userId)
    {
        $sessionItems = self::where('session_id', $sessionId)->get();

        foreach ($sessionItems as $item) {
            // Check if user already has this product in cart
            $existingItem = self::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->first();

            if ($existingItem) {
                // Update quantity if product exists
                $existingItem->increment('quantity', $item->quantity);
                $item->delete();
            } else {
                // Assign to user if product doesn't exist
                $item->update([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        }
    }
}
