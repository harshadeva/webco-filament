<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->word(),
            'description'=>$this->faker->optional()->sentence(),
            'external_url'=>$this->faker->optional()->url(),
            'created_at'=> $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at'=> $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
