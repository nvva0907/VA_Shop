<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $code
 * @property string $image
 * @property string|null $description
 * @property string $category_code
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'code',
        'image',
        'description',
        'category_code'
    ];
}
