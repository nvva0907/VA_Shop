<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string $code
 * @property string $attribute_code
 * @property string $value
 * @property string $name
 */
class AttributeValue extends Model
{
    use HasFactory;

    protected $table = 'attribute_values';

    protected $fillable = [
        'code',
        'name',
        'attribute_code',
        'value',
    ];
}
