<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'external_url',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    public function productTypes(): MorphToMany
    {
        return $this->morphToMany(ProductType::class, 'typeable');
    }
}
