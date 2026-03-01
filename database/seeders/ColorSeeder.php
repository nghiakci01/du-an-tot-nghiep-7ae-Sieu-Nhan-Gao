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
            ['name' => 'Trắng', 'code' => '#FFFFFF'],
            ['name' => 'Đen', 'code' => '#000000'],
            ['name' => 'Đỏ', 'code' => '#FF0000'],
            ['name' => 'Xanh dương', 'code' => '#0000FF'],
            ['name' => 'Xanh lá', 'code' => '#00FF00'],
            ['name' => 'Vàng', 'code' => '#FFFF00'],
            ['name' => 'Xám', 'code' => '#808080'],
            ['name' => 'Hồng', 'code' => '#FFC0CB'],
            ['name' => 'Cam', 'code' => '#FFA500'],
            ['name' => 'Nâu', 'code' => '#A52A2A'],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(['name' => $color['name']], $color);
        }
    }
}
