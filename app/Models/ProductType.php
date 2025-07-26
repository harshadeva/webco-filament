<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'unique_identifier',
    ];

    public function products(): BelongsToMany
    {
        return $this->morphedByMany(Product::class, 'typeable');
    }

    public function categories(): BelongsToMany
    {
        return $this->morphedByMany(ProductCategory::class, 'typeable');
    }

    public function colors(): BelongsToMany
    {
        return $this->morphedByMany(ProductColor::class, 'typeable');
    }
}
