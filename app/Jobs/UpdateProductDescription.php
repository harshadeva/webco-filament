<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateProductDescription implements ShouldQueue
{
    use Queueable;

    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(): void
    {
        $this->product->update(['description' => $this->product->description . ' [Updated by Job]'. ' at ' . now()]);
    }
}
