<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $product_variant_code
 * @property string $attribute_value_code
 */
class ProductVariantAttributes extends Model
{
    use HasFactory;

    protected $table = 'product_variant_attributes';

    protected $fillable = [
        'product_variant_code',
        'attribute_value_code',
    ];
}
