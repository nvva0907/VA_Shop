<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Cart extends Component
{
    public function render()
    {
        return view('livewire.cart');
    }

    public function addToCart($productId) {
        $product = Product::find($productId);
        if ($product) {
            // Thêm sản phẩm vào giỏ hàng
            Cart::add($product->id, $product->name, 1, $product->price);
            // Thông báo cho người dùng
            session()->flash('message', 'Đã thêm vào giỏ hàng!');
        } else {
            session()->flash('error', 'Sản phẩm không tồn tại!');
        }
    }
}
