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
            // Màu cơ bản
            ['name' => 'Trắng', 'hex_code' => '#FFFFFF', 'is_active' => true],
            ['name' => 'Đen', 'hex_code' => '#000000', 'is_active' => true],
            ['name' => 'Đỏ', 'hex_code' => '#FF0000', 'is_active' => true],
            ['name' => 'Xanh dương', 'hex_code' => '#0000FF', 'is_active' => true],
            ['name' => 'Xanh lá', 'hex_code' => '#00FF00', 'is_active' => true],
            ['name' => 'Vàng', 'hex_code' => '#FFFF00', 'is_active' => true],

            // Màu trung tính (rất quan trọng cho thời trang)
            ['name' => 'Xám', 'hex_code' => '#808080', 'is_active' => true],
            ['name' => 'Xám nhạt', 'hex_code' => '#D3D3D3', 'is_active' => true],
            ['name' => 'Xám đậm', 'hex_code' => '#505050', 'is_active' => true],
            ['name' => 'Be', 'hex_code' => '#F5F5DC', 'is_active' => true],
            ['name' => 'Kem', 'hex_code' => '#FFFDD0', 'is_active' => true],

            // Màu thời trang phổ biến
            ['name' => 'Hồng', 'hex_code' => '#FFC0CB', 'is_active' => true],
            ['name' => 'Hồng đậm', 'hex_code' => '#FF69B4', 'is_active' => true],
            ['name' => 'Cam', 'hex_code' => '#FFA500', 'is_active' => true],
            ['name' => 'Cam đất', 'hex_code' => '#E2725B', 'is_active' => true],
            ['name' => 'Nâu', 'hex_code' => '#A52A2A', 'is_active' => true],
            ['name' => 'Nâu nhạt', 'hex_code' => '#C4A484', 'is_active' => true],

            // Xanh thời trang
            ['name' => 'Xanh navy', 'hex_code' => '#000080', 'is_active' => true],
            ['name' => 'Xanh pastel', 'hex_code' => '#AEC6CF', 'is_active' => true],
            ['name' => 'Xanh mint', 'hex_code' => '#98FF98', 'is_active' => true],

            // Màu đặc biệt (trend)
            ['name' => 'Tím', 'hex_code' => '#800080', 'is_active' => true],
            ['name' => 'Tím lavender', 'hex_code' => '#E6E6FA', 'is_active' => true],
            ['name' => 'Vàng gold', 'hex_code' => '#FFD700', 'is_active' => true],
            ['name' => 'Xanh Rêu', 'hex_code' => '#556B2F', 'is_active' => true],
            ['name' => 'Kẻ Kem', 'hex_code' => '#F5F5DC', 'is_active' => true],
            ['name' => 'Kẻ Nâu', 'hex_code' => '#A52A2A', 'is_active' => true],
            ['name' => 'Kẻ Đen', 'hex_code' => '#000000', 'is_active' => true],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(['name' => $color['name']], $color);
        }
    }
}
