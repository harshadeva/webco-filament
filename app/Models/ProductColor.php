<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProductColor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'color_code',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_color_id');
    }
}
