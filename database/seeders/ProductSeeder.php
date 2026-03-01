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
        $categories = Category::whereNotNull('parent_id')->get();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();

        if ($categories->isEmpty() || $brands->isEmpty() || $colors->isEmpty() || $sizes->isEmpty()) {
            $this->command->error('Missing required seeders (Category, Brand, Color, Size).');
            return;
        }

        $productNames = [
            'Áo Thun', 'Áo Sơ Mi', 'Quần Jean', 'Quần Tây', 'Váy Hoa Nam', 'Đầm Dạ Hội',
            'Áo Khoác Gió', 'Áo Hoodie', 'Quần Short', 'Áo Polo', 'Váy Chân Bút Chì'
        ];

        $adjectives = ['Cao Cấp', 'Basic', 'Năng Động', 'Thanh Lịch', 'Vintage', 'Mùa Hè'];

        for ($i = 1; $i <= 20; $i++) {
            $name = $productNames[array_rand($productNames)] . ' ' . $adjectives[array_rand($adjectives)] . ' ' . $i;
            $slug = Str::slug($name) . '-' . uniqid();

            $product = Product::create([
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'name' => $name,
                'slug' => $slug,
                'description' => 'Mô tả chi tiết cho sản phẩm ' . $name . '. Chất liệu bền đẹp, form dáng chuẩn, phù hợp với nhiều phong cách thời trang khác nhau.',
                'short_description' => 'Sản phẩm ' . $name . ' chất lượng cao cho phong cách hiện đại.',
                'price' => rand(100, 1000) * 1000,
                'is_active' => true,
            ]);

            // Create 3-6 variants per product
            $numVariants = rand(3, 6);
            $usedConfigs = [];

            for ($j = 0; $j < $numVariants; $j++) {
                $color = $colors->random();
                $size = $sizes->random();
                $configKey = $color->id . '-' . $size->id;

                if (in_array($configKey, $usedConfigs)) continue;
                $usedConfigs[] = $configKey;

                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => strtoupper(substr($slug, 0, 4)) . '-' . $color->id . $size->id . '-' . $i . $j,
                    'stock_quantity' => rand(10, 100),
                    'price' => $product->price + (rand(0, 5) * 10000), // Slightly different price for variants
                ]);
            }
        }

        echo "\n✅ Đã tạo thành công 20 sản phẩm với các biến thể ngẫu nhiên!\n";
    }
}
