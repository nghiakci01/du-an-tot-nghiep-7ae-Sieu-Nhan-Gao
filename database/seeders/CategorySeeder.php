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
        // 1. Áo Thu Đông
        $aoThuDong = Category::updateOrCreate(
            ['slug' => 'ao-thu-dong'],
            ['name' => 'Áo Thu Đông', 'parent_id' => null]
        );

        $thuDongSubs = [
            ['name' => 'Áo Nỉ / Áo Thun Dài Tay', 'slug' => 'ao-ni-ao-thun-dai-tay'],
            ['name' => 'Áo Hoodie', 'slug' => 'ao-hoodie'],
            ['name' => 'Áo Phao', 'slug' => 'ao-phao'],
            ['name' => 'Áo Len', 'slug' => 'ao-len'],
            ['name' => 'Áo Khoác', 'slug' => 'ao-khoac'],
            ['name' => 'Cardigan', 'slug' => 'cardigan'],
            ['name' => 'Áo Blazer / Áo Măng Tô', 'slug' => 'ao-blazer-ao-mang-to'],
            ['name' => 'Bộ Thể Thao Thu Đông', 'slug' => 'bo-the-thao-thu-dong'],
        ];

        foreach ($thuDongSubs as $sub) {
            Category::updateOrCreate(
                ['slug' => $sub['slug']],
                ['name' => $sub['name'], 'parent_id' => $aoThuDong->id]
            );
        }

        // 2. ÁO XUÂN HÈ
        $aoXuanHe = Category::updateOrCreate(
            ['slug' => 'ao-xuan-he'],
            ['name' => 'ÁO XUÂN HÈ', 'parent_id' => null]
        );

        $xuanHeSubs = [
            ['name' => 'Áo Phông', 'slug' => 'ao-phong'],
            ['name' => 'Áo PoLo', 'slug' => 'ao-polo'],
            ['name' => 'Áo Sơ Mi Ngắn Tay', 'slug' => 'ao-so-mi-ngan-tay'],
            ['name' => 'Bộ Thể Thao Hè', 'slug' => 'bo-the-thao-he'],
            ['name' => 'Áo Tank Top', 'slug' => 'ao-tank-top'],
            ['name' => 'Áo Sơ Mi Dài Tay', 'slug' => 'ao-so-mi-dai-tay'],
        ];

        foreach ($xuanHeSubs as $sub) {
            Category::updateOrCreate(
                ['slug' => $sub['slug']],
                ['name' => $sub['name'], 'parent_id' => $aoXuanHe->id]
            );
        }

        // 3. QUẦN
        $quan = Category::updateOrCreate(
            ['slug' => 'quan'],
            ['name' => 'QUẦN', 'parent_id' => null]
        );

        $quanSubs = [
            ['name' => 'Quần dài', 'slug' => 'quan-dai'],
            ['name' => 'Quần Short', 'slug' => 'quan-short'],
        ];

        foreach ($quanSubs as $sub) {
            Category::updateOrCreate(
                ['slug' => $sub['slug']],
                ['name' => $sub['name'], 'parent_id' => $quan->id]
            );
        }

        // 4. PHỤ KIỆN
        $phuKien = Category::updateOrCreate(
            ['slug' => 'phu-kien'],
            ['name' => 'PHỤ KIỆN', 'parent_id' => null]
        );

        $phuKienSubs = [
            ['name' => 'Túi / Balo', 'slug' => 'tui-balo'],
            ['name' => 'Giày Dép', 'slug' => 'giay-dep'],
            ['name' => 'Dây Lưng', 'slug' => 'day-lung'],
            ['name' => 'Mũ', 'slug' => 'mu'],
            ['name' => 'Quần Lót', 'slug' => 'quan-lot'],
            ['name' => 'Tất', 'slug' => 'tat'],
        ];

        foreach ($phuKienSubs as $sub) {
            Category::updateOrCreate(
                ['slug' => $sub['slug']],
                ['name' => $sub['name'], 'parent_id' => $phuKien->id]
            );
        }
    }
}
