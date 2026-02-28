<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy category theo slug
        $aoThunNam = Category::where('slug', 'ao-thun')->first();
        $vayDamNu = Category::where('slug', 'vay-dam')->first();

        // Nếu không tìm thấy category thì srr skip hoặc tạo mới (tùy logic, ở đây assume có rồi từ CategorySeeder)
        // Tuy nhiên tốt nhất là check null
        if (! $aoThunNam || ! $vayDamNu) {
            $this->command->error('Categories not found. Please run CategorySeeder first.');

            return;
        }

        $products = [
            [
                'category_id' => $aoThunNam->id,
                'name' => 'Áo Thun Basic Trắng',
                'slug' => 'ao-thun-basic-trang',
                'description' => 'Áo thun cotton 100% thoáng mát.',
                'short_description' => 'Áo thun cotton trắng thoáng mát dành cho nam ngầu.',
                'price' => 150000,
                'is_active' => true,
                'variants' => [
                    ['size' => 'S', 'color' => 'Trắng', 'stock_quantity' => 10, 'sku' => 'TS-W-S'],
                    ['size' => 'M', 'color' => 'Trắng', 'stock_quantity' => 20, 'sku' => 'TS-W-M'],
                    ['size' => 'L', 'color' => 'Trắng', 'stock_quantity' => 15, 'sku' => 'TS-W-L'],
                ],
            ],
            [
                'category_id' => $aoThunNam->id,
                'name' => 'Áo Thun Basic Đen',
                'slug' => 'ao-thun-basic-den',
                'description' => 'Áo thun đen phong cách.',
                'short_description' => 'Áo thun đen năng động phong cách lịch sự.',
                'price' => 150000,
                'is_active' => true,
                'variants' => [
                    ['size' => 'S', 'color' => 'Đen', 'stock_quantity' => 10, 'sku' => 'TS-B-S'],
                    ['size' => 'M', 'color' => 'Đen', 'stock_quantity' => 20, 'sku' => 'TS-B-M'],
                ],
            ],
            [
                'category_id' => $vayDamNu->id,
                'name' => 'Váy Hoa Nhí Vintage',
                'slug' => 'vay-hoa-nhi-vintage',
                'description' => 'Váy hoa nhẹ nhàng cho mùa hè.',
                'short_description' => 'Váy hoa nhí vintage xinh xắn dành cho nữ.',
                'price' => 350000,
                'is_active' => true,
                'variants' => [], // Add variants if needed
            ],
        ];

        foreach ($products as $data) {
            $variants = $data['variants'];
            unset($data['variants']);

            $product = Product::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Create variants
            foreach ($variants as $variant) {
                // Ensure unique SKU per variant
                ProductVariant::firstOrCreate(
                    ['sku' => $variant['sku'].'-'.$product->id],
                    array_merge($variant, [
                        'product_id' => $product->id,
                        'sku' => $variant['sku'].'-'.$product->id, // update SKU to include product ID as per original logic if needed, or just keep unique SKU
                    ])
                );
            }
        }

        echo "\n✅ Đã thêm thành công ".count($products).' sản phẩm với '.(count($products) * 4)." biến thể!\n";
    }
}
