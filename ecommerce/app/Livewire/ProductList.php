<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductList extends Component
{
    public function render()
    {
        $products = Product::get(); 
        if ($products->isEmpty()) {
            return view('livewire.product-list', ['products' => []]);
        }
        return view('livewire.product-list', compact('products'))->layout('layouts.app');
    }

}
