<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProductColor extends Model
{
    protected $fillable = [
        'name',
        'hex_code',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_color_id');
    }
}
