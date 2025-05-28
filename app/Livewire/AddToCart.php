<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1;
    public $product;
    public $inCart = false;
    public $cartItem = null;
    public $showQuantity = true;

    public function mount($productId, $quantity = 1, $showQuantity = true)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->showQuantity = $showQuantity;
        $this->product = Product::findOrFail($productId);
        $this->checkIfInCart();
    }

    public function checkIfInCart()
    {
        $this->cartItem = Cart::search(function ($cartItem) {
            return $cartItem->id === $this->product->id;
        })->first();

        $this->inCart = (bool) $this->cartItem;
    }

    public function addToCart()
    {

        $this->validate([
            'quantity' => 'required|numeric|min:1|max:' . $this->product->quantity
        ]);

        if ($this->inCart) {
            Cart::update($this->cartItem->rowId, $this->quantity);
            $message = 'Cart updated successfully!';
        } else {
            Cart::add([
                'id' => $this->product->id,
                'name' => $this->product->name,
                'qty' => $this->quantity,
                'price' => $this->product->price,
                'weight' => 0,
                'options' => [
                    'image' => $this->product->image,
                    'slug' => $this->product->slug,
                ]
            ]);
            $message = 'Product added to cart!';
            $this->inCart = true;
        }

        $this->emit('cartUpdated');
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function increment()
    {
        if ($this->quantity < $this->product->quantity) {
            $this->quantity++;
            $this->addToCart();
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->addToCart();
        }
    }

    public function removeFromCart()
    {
        Cart::remove($this->cartItem->rowId);
        $this->inCart = false;
        $this->emit('cartUpdated');
        $this->dispatch('notify', type: 'success', message: 'Product removed from cart!');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
