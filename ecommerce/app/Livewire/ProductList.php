<?php

namespace App\Livewire;

use App\Models\Product;
use DB;
use Livewire\Component;

class ProductList extends Component
{
    public function render()
    {
        $products = DB::select("
    SELECT 
        p.id,
        p.name,
        p.image,
        p.description,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                'price', pv.price,
                'attributes', (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'attribute_code', at.code, 
                            'value', av.value
                        )
                    ) 
                    FROM product_variant_attributes pva
                    JOIN attribute_values av ON pva.attribute_value_code = av.code
                    JOIN attributes at ON av.attribute_code = at.code
                    WHERE pva.product_variant_code = pv.code
                )
            )
        ) AS variants
    FROM products p
    JOIN product_variants pv ON p.code = pv.product_code
    GROUP BY p.id, p.name
    ORDER BY p.id
");
        foreach ($products as $product) {
            $product->variants = json_decode($product->variants, true);
        }
        return view('livewire.product-list', compact('products'))->layout('layouts.app');
    }

}
