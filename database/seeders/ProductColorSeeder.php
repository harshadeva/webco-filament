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
            ['name' => 'Red',     'color_code' => 'rgba(255, 0, 0, 1.0)'],
            ['name' => 'Green',   'color_code' => 'rgba(0, 255, 0, 1.0)'],
            ['name' => 'Blue',    'color_code' => 'rgba(0, 0, 255, 1.0)'],
            ['name' => 'Yellow',  'color_code' => 'rgba(255, 255, 0, 1.0)'],
            ['name' => 'Cyan',    'color_code' => 'rgba(0, 255, 255, 1.0)'],
            ['name' => 'Magenta', 'color_code' => 'rgba(255, 0, 255, 1.0)'],
        ];
        foreach ($colors as $color) {
            ProductColor::create($color);
        }
    }
}
