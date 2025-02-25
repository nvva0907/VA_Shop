<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string $name
 * @property string $code
 */
class Attributes extends Model
{
    use HasFactory;

    protected $table = 'attributes';

    protected $fillable = [
        'name',
        'code'
    ];
}
