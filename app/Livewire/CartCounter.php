<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartCounter extends Component
{
    public $count;
    public $subtotal;

    protected $listeners = ['cartUpdated' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        $this->count = Cart::count();
        $this->subtotal = Cart::subtotal();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
