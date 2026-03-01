<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '28', '29', '30', '31', '32', '33', '34', '35', '36'];

        foreach ($sizes as $size) {
            Size::firstOrCreate(['name' => $size], ['is_active' => true]);
        }
    }
}
