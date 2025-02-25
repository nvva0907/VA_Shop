<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string $code
 * @property string $product_code
 * @property string $sku
 * @property float $price
 * @property int $stock
 */
class ProductVariants extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'code',
        'product_code',
        'sku',
        'price',
        'stock',
    ];
}
