<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'product_category_id' => ProductCategory::factory(),
            'product_color_id' => ProductColor::factory(),
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->optional()->address(),
            'status' => $this->faker->numberBetween(0, 1),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
