<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class SearchProducts extends Component
{
    public $search = '';

    public $results = [];

    public function search()
    {
        $this->validate([
            'search' => 'required|min:2',
        ]);

        $this->results = Product::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->take(5)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.search-products');
    }
}
