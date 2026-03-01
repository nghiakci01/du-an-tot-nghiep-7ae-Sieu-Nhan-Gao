<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Trắng', 'hex_code' => '#FFFFFF', 'is_active' => true],
            ['name' => 'Đen', 'hex_code' => '#000000', 'is_active' => true],
            ['name' => 'Đỏ', 'hex_code' => '#FF0000', 'is_active' => true],
            ['name' => 'Xanh dương', 'hex_code' => '#0000FF', 'is_active' => true],
            ['name' => 'Xanh lá', 'hex_code' => '#00FF00', 'is_active' => true],
            ['name' => 'Vàng', 'hex_code' => '#FFFF00', 'is_active' => true],
            ['name' => 'Xám', 'hex_code' => '#808080', 'is_active' => true],
            ['name' => 'Hồng', 'hex_code' => '#FFC0CB', 'is_active' => true],
            ['name' => 'Cam', 'hex_code' => '#FFA500', 'is_active' => true],
            ['name' => 'Nâu', 'hex_code' => '#A52A2A', 'is_active' => true],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(['name' => $color['name']], $color);
        }
    }
}
