<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main categories
        $nam = Category::create([
            'name' => 'Thời trang Nam',
            'slug' => 'thoi-trang-nam',
            'parent_id' => null,
            'image' => null,
        ]);

        $nu = Category::create([
            'name' => 'Thời trang Nữ',
            'slug' => 'thoi-trang-nu',
            'parent_id' => null,
            'image' => null,
        ]);

        $treem = Category::create([
            'name' => 'Thời trang Trẻ Em',
            'slug' => 'thoi-trang-tre-em',
            'parent_id' => null,
            'image' => null,
        ]);

        $phukien = Category::create([
            'name' => 'Phụ Kiện',
            'slug' => 'phu-kien',
            'parent_id' => null,
            'image' => null,
        ]);

        // Subcategories for Nam
        Category::create(['name' => 'Áo Thun Nam', 'slug' => 'ao-thun-nam', 'parent_id' => $nam->id, 'image' => null]);
        Category::create(['name' => 'Áo Sơ Mi Nam', 'slug' => 'ao-so-mi-nam', 'parent_id' => $nam->id, 'image' => null]);
        Category::create(['name' => 'Quần Jeans Nam', 'slug' => 'quan-jeans-nam', 'parent_id' => $nam->id, 'image' => null]);
        Category::create(['name' => 'Áo Khoác Nam', 'slug' => 'ao-khoac-nam', 'parent_id' => $nam->id, 'image' => null]);

        // Subcategories for Nu
        Category::create(['name' => 'Váy Đầm', 'slug' => 'vay-dam', 'parent_id' => $nu->id, 'image' => null]);
        Category::create(['name' => 'Áo Thun Nữ', 'slug' => 'ao-thun-nu', 'parent_id' => $nu->id, 'image' => null]);
        Category::create(['name' => 'Quần Nữ', 'slug' => 'quan-nu', 'parent_id' => $nu->id, 'image' => null]);
        Category::create(['name' => 'Áo Sơ Mi Nữ', 'slug' => 'ao-so-mi-nu', 'parent_id' => $nu->id, 'image' => null]);

        // Subcategories for Trẻ Em
        Category::create(['name' => 'Áo Trẻ Em', 'slug' => 'ao-tre-em', 'parent_id' => $treem->id, 'image' => null]);
        Category::create(['name' => 'Quần Trẻ Em', 'slug' => 'quan-tre-em', 'parent_id' => $treem->id, 'image' => null]);

        // Subcategories for Phụ Kiện
        Category::create(['name' => 'Túi Xách', 'slug' => 'tui-xach', 'parent_id' => $phukien->id, 'image' => null]);
        Category::create(['name' => 'Mũ Nón', 'slug' => 'mu-non', 'parent_id' => $phukien->id, 'image' => null]);
        Category::create(['name' => 'Thắt Lưng', 'slug' => 'that-lung', 'parent_id' => $phukien->id, 'image' => null]);
        Category::create(['name' => 'Kính Mắt', 'slug' => 'kinh-mat', 'parent_id' => $phukien->id, 'image' => null]);

        echo "\n✅ Đã tạo thành công " . Category::count() . " danh mục!\n";
    }
}
