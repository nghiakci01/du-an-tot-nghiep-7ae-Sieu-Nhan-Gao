<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh mục cha
        $nam = Category::firstOrCreate(
            ['slug' => 'thoi-trang-nam'],
            ['name' => 'Thời trang Nam', 'parent_id' => null]
        );

        $nu = Category::firstOrCreate(
            ['slug' => 'thoi-trang-nu'],
            ['name' => 'Thời trang Nữ', 'parent_id' => null]
        );

        // Danh mục con - Thời trang Nam
        Category::firstOrCreate(
            ['slug' => 'ao-thun'],
            ['name' => 'Áo Thun', 'parent_id' => $nam->id]
        );

        Category::firstOrCreate(
            ['slug' => 'ao-so-mi'],
            ['name' => 'Áo Sơ Mi', 'parent_id' => $nam->id]
        );

        // Danh mục con - Thời trang Nữ
        Category::firstOrCreate(
            ['slug' => 'vay-dam'],
            ['name' => 'Váy Đầm', 'parent_id' => $nu->id]
        );
    }
}
