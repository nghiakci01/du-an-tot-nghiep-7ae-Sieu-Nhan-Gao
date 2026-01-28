<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Thời trang Nam', 'slug' => 'thoi-trang-nam', 'parent_id' => null, 'image' => null],
            ['name' => 'Thời trang Nữ', 'slug' => 'thoi-trang-nu', 'parent_id' => null, 'image' => null],
            ['name' => 'Áo Thun', 'slug' => 'ao-thun', 'parent_id' => 1, 'image' => null], // Child of Nam
            ['name' => 'Áo Sơ Mi', 'slug' => 'ao-so-mi', 'parent_id' => 1, 'image' => null], // Child of Nam
            ['name' => 'Váy Đầm', 'slug' => 'vay-dam', 'parent_id' => 2, 'image' => null], // Child of Nu
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
