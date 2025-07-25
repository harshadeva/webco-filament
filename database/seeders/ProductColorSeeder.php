<?php

namespace Database\Seeders;

use App\Models\ProductColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Green', 'hex_code' => '#00FF00'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
            ['name' => 'Yellow', 'hex_code' => '#FFFF00'],
            ['name' => 'Cyan', 'hex_code' => '#00FFFF'],
            ['name' => 'Magenta', 'hex_code' => '#FF00FF'],
        ];

        foreach ($colors as $color) {
            ProductColor::create($color);
        }
    }
}
