<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductColor;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ProductColor::factory()->count(10)->create();
        ProductCategory::factory()->count(5)->create();
        ProductType::factory()->count(5)->create();
        // Product::factory()->count(10)->create()->each(function ($product) {
        //     $product->productTypes()->attach(
        //         ProductType::inRandomOrder()->take(rand(1, 3))->pluck('id')
        //     );
        // });
        ProductCategory::all()->each(function ($category) {
            $category->productTypes()->attach(
                ProductType::inRandomOrder()->take(rand(1, 3))->pluck('id')
            );
        });
    }
}
