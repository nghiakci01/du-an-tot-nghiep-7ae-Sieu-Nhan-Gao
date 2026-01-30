<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Áo Thun Basic Trắng Premium', 'category_id' => 1, 'price' => 199000],
            ['name' => 'Áo Thun Oversize Đen', 'category_id' => 1, 'price' => 250000],
            ['name' => 'Áo Thun Polo Nam Xanh Navy', 'category_id' => 1, 'price' => 350000],
            ['name' => 'Áo Thun Graphic Print', 'category_id' => 1, 'price' => 280000],
            ['name' => 'Váy Hoa Nhí Vintage', 'category_id' => 2, 'price' => 450000],
            ['name' => 'Đầm Suông Công Sở', 'category_id' => 2, 'price' => 550000],
            ['name' => 'Váy Maxi Dạ Hội', 'category_id' => 2, 'price' => 890000],
            ['name' => 'Đầm Babydoll Hồng Pastel', 'category_id' => 2, 'price' => 420000],
            ['name' => 'Quần Jeans Skinny Đen', 'category_id' => 3, 'price' => 450000],
            ['name' => 'Quần Jeans Baggy Xanh Nhạt', 'category_id' => 3, 'price' => 520000],
            ['name' => 'Quần Jeans Rách Gối', 'category_id' => 3, 'price' => 480000],
            ['name' => 'Quần Jeans Ống Loe Vintage', 'category_id' => 3, 'price' => 550000],
            ['name' => 'Áo Khoác Bomber Đen', 'category_id' => 4, 'price' => 650000],
            ['name' => 'Áo Khoác Jeans Xanh', 'category_id' => 4, 'price' => 580000],
            ['name' => 'Áo Khoác Hoodie Zip', 'category_id' => 4, 'price' => 420000],
            ['name' => 'Áo Khoác Cardigan Len', 'category_id' => 4, 'price' => 480000],
            ['name' => 'Túi Tote Canvas Trơn', 'category_id' => 5, 'price' => 150000],
            ['name' => 'Mũ Lưỡi Trai Snapback', 'category_id' => 5, 'price' => 180000],
            ['name' => 'Thắt Lưng Da Cao Cấp', 'category_id' => 5, 'price' => 350000],
            ['name' => 'Kính Mát Aviator', 'category_id' => 5, 'price' => 250000]
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'category_id' => $productData['category_id'],
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => 'Sản phẩm chất lượng cao, thiết kế hiện đại và thời trang.',
                'price' => $productData['price'],
                'is_featured' => rand(0, 1) == 1,
                'is_active' => true,
                'image' => 'products/IwtktEFiLgAbezYA4i8TUFhc3jy7v0i4jmlnuX0S.png',
            ]);

            // Add variants
            $sizes = ['S', 'M', 'L', 'XL'];
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => ['Đen', 'Trắng', 'Xanh', 'Đỏ'][array_rand(['Đen', 'Trắng', 'Xanh', 'Đỏ'])],
                    'stock_quantity' => rand(10, 50),
                    'sku' => strtoupper(Str::random(3)) . '-' . $size . '-' . $product->id,
                ]);
            }

            // Add images
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/IwtktEFiLgAbezYA4i8TUFhc3jy7v0i4jmlnuX0S.png',
                'is_primary' => true,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/a5cWz47edUEV9oIEl6LCyM48xpA7jbvKbzquliLg.png',
                'is_primary' => false,
            ]);
        }
        
        echo "\n✅ Đã thêm thành công " . count($products) . " sản phẩm!\n";
    }
}
