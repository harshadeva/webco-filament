<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = [
        'name',
        'unique_identifier',
    ];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'typeable');
    }

    public function categories()
    {
        return $this->morphedByMany(ProductCategory::class, 'typeable');
    }

    public function colors()
    {
        return $this->morphedByMany(ProductColor::class, 'typeable');
    }
}
