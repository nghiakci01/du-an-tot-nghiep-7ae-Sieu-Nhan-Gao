<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assuming category IDs 1-5 exist
        $products = [
            [
                'category_id' => 3, // Áo Thun Nam
                'name' => 'Áo Thun Basic Trắng',
                'slug' => 'ao-thun-basic-trang',
                'description' => 'Áo thun cotton 100% thoáng mát.',
                'price' => 150000,
                'is_active' => true,
            ],
             [
                'category_id' => 3, // Áo Thun Nam
                'name' => 'Áo Thun Basic Đen',
                'slug' => 'ao-thun-basic-den',
                'description' => 'Áo thun đen phong cách.',
                'price' => 150000,
                'is_active' => true,
            ],
            [
                'category_id' => 5, // Váy Đầm Nữ
                'name' => 'Váy Hoa Nhí Vintage',
                'slug' => 'vay-hoa-nhi-vintage',
                'description' => 'Váy hoa nhẹ nhàng cho mùa hè.',
                'price' => 350000,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            $productId = DB::table('products')->insertGetId(array_merge($product, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Add variants
            $variants = [
                ['product_id' => $productId, 'size' => 'S', 'color' => 'Trắng', 'stock_quantity' => 10, 'sku' => 'TS-W-S-' . $productId],
                ['product_id' => $productId, 'size' => 'M', 'color' => 'Trắng', 'stock_quantity' => 20, 'sku' => 'TS-W-M-' . $productId],
                ['product_id' => $productId, 'size' => 'L', 'color' => 'Trắng', 'stock_quantity' => 15, 'sku' => 'TS-W-L-' . $productId],
            ];
             // Adjust SKU for second product to avoid duplication if loop runs quickly or similar names
             if ($product['slug'] == 'ao-thun-basic-den') {
                 $variants = [
                    ['product_id' => $productId, 'size' => 'S', 'color' => 'Đen', 'stock_quantity' => 10, 'sku' => 'TS-B-S-' . $productId],
                    ['product_id' => $productId, 'size' => 'M', 'color' => 'Đen', 'stock_quantity' => 20, 'sku' => 'TS-B-M-' . $productId],
                ];
             }

            DB::table('product_variants')->insert(array_map(function ($variant) {
                return array_merge($variant, ['created_at' => now(), 'updated_at' => now()]);
            }, $variants));
        }
    }
}
