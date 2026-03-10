<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Nike', 'Adidas', 'Puma', 'Uniqlo', 'Gucci', 'Zara', 'H&M', 'Levis', 'Converse', 'Vans'
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($brand)],
                [
                    'name' => $brand,
                    'image' => 'brands/' . Str::slug($brand) . '.png',
                    'is_active' => true,
                ]
            );
        }
    }
}
