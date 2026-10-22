<?php

namespace App\Livewire\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Dropdown extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function removeItem($rowId)
    {
        Cart::remove($rowId);
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart.dropdown', [
            'items' => Cart::content(),
            'total' => Cart::total(),
            'count' => Cart::count(),
        ]);
    }
}
