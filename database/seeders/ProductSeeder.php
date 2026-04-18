<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Lấy dữ liệu cần thiết
        $category = Category::where('name', 'Áo Nỉ / Áo Thun Dài Tay')->first() ?? Category::first();
        $brand = Brand::first();
        $colorBlack = Color::where('name', 'Đen')->first() ?? Color::first();
        $colorWhite = Color::where('name', 'Trắng')->first() ?? Color::first();
        $sizes = Size::whereIn('name', ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL'])->get();

        // 2. TẠO SẢN PHẨM: Áo Nỉ Fitted L.2.7812
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Nỉ Fitted L.2.7812',
            'slug' => Str::slug('Áo Nỉ Fitted L.2.7812') . '-' . uniqid(),
            'short_description' => 'Áo nỉ phong cách fitted gọn gàng, chất vải mềm mịn.',
            'description' => 'Sản phẩm Áo Nỉ Fitted L.2.7812 mang lại cảm giác thoải mái và giữ ấm tốt trong mùa thu đông.',
            'price' => 89000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product1.jpeg',
        ]);

        // 2.1 TẠO GALLERY ẢNH (Bạn có thể tự thay đổi danh sách đường dẫn bên dưới)
        $galleryImages = [
            'products/gallery/product1-1.jpeg',
            'products/gallery/product1-2.jpeg',
            'products/gallery/product1-3.jpeg',
            'products/gallery/product1-4.jpeg',
            'products/gallery/product1-5.jpeg',
            'products/gallery/product1-6.jpeg',
        ];

        foreach ($galleryImages as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // 3. TẠO CÁC BIẾN THỂ (SIZE & MÀU)
        // Tạo biến thể cho cả màu Đen và Trắng với các size S, M, L, XL
        $colors = [$colorBlack, $colorWhite];
        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'ANI-' . strtoupper($color->name === 'Đen' ? 'BLK' : 'WHT') . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(20, 50),
                    'price' => 89000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 4. TẠO SẢN PHẨM THỨ 2: Áo Jacket XL.2.8931 (Theo ảnh)
        // ---------------------------------------------------------
        $catJacket = Category::where('slug', 'ao-khoac')->first() ?? $category;
        $colorGray = Color::where('name', 'Xám')->first() ?? Color::first();
        $jacketSizes = Size::whereIn('name', ['L', 'XL', '2XL', '3XL'])->get();

        $product2 = Product::create([
            'category_id' => $catJacket->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Jacket XL.2.8931',
            'slug' => Str::slug('Áo Jacket XL.2.8931') . '-' . uniqid(),
            'short_description' => 'Áo Jacket phong cách sang trọng, chất liệu cao cấp.',
            'description' => 'Sản phẩm Áo Jacket XL.2.8931 với thiết kế phối cổ lông, form dáng bomber hiện đại, giữ ấm cực tốt.',
            'price' => 749000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product2.jpeg',
        ]);

        // Gallery cho SP 2
        $gallery2 = [
            'products/gallery/product2-1.jpeg',
            'products/gallery/product2-2.jpeg',
        ];

        foreach ($gallery2 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product2->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 2 (2 Màu x 4 Size)
        $colors2 = [$colorGray, $colorBlack];
        foreach ($colors2 as $color) {
            foreach ($jacketSizes as $size) {
                ProductVariant::create([
                    'product_id' => $product2->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'JKT-' . strtoupper($color->name === 'Xám' ? 'GRY' : 'BLK') . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(10, 30),
                    'price' => 749000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 5. TẠO SẢN PHẨM THỨ 3: Áo Phao M.2.8561 (Theo ảnh)
        // ---------------------------------------------------------
        $catPhao = Category::where('slug', 'ao-phao')->first() ?? $category;
        $colorBrown = Color::where('name', 'Nâu')->first() ?? Color::first();
        $phaoSizes = Size::whereIn('name', ['S', 'M', 'L', 'XL'])->get();

        $product3 = Product::create([
            'category_id' => $catPhao->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Phao M.2.8561',
            'slug' => Str::slug('Áo Phao M.2.8561') . '-' . uniqid(),
            'short_description' => 'Áo phao siêu nhẹ, giữ nhiệt cực tốt, chống thấm nước.',
            'description' => 'Sản phẩm Áo Phao M.2.8561 với thiết kế cổ đứng phối khóa kéo, form dáng trẻ trung, phù hợp cho mùa lạnh.',
            'price' => 599000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product3.jpeg',
        ]);

        // Gallery cho SP 3
        $gallery3 = [
            'products/gallery/product3-1.jpeg',
            'products/gallery/product3-2.jpeg',
            'products/gallery/product3-3.jpeg',
            'products/gallery/product3-4.jpeg',
            'products/gallery/product3-5.jpeg',
            'products/gallery/product3-6.jpeg',
        ];

        foreach ($gallery3 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product3->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 3 (2 Màu x 4 Size)
        $colors3 = [$colorBrown, $colorBlack];
        foreach ($colors3 as $color) {
            foreach ($phaoSizes as $size) {
                ProductVariant::create([
                    'product_id' => $product3->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'AP-' . strtoupper($color->name === 'Nâu' ? 'BRW' : 'BLK') . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(15, 60),
                    'price' => 599000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 6. TẠO SẢN PHẨM THỨ 4: Áo Jacket L.2.8893 (Theo ảnh)
        // ---------------------------------------------------------
        $catJacket4 = Category::where('slug', 'ao-khoac')->first() ?? $category;
        $jacket4Sizes = Size::whereIn('name', ['M', 'L', 'XL', '2XL'])->get();

        $product4 = Product::create([
            'category_id' => $catJacket4->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Jacket L.2.8893',
            'slug' => Str::slug('Áo Jacket L.2.8893') . '-' . uniqid(),
            'short_description' => 'Áo Jacket cổ bẻ cao cấp, phong cách tối giản.',
            'description' => 'Sản phẩm Áo Jacket L.2.8893 với chất liệu vải bền đẹp, thiết kế túi sườn tiện lợi, phù hợp phối đồ đa dạng.',
            'price' => 669000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product4.jpeg',
        ]);

        // Gallery cho SP 4
        $gallery4 = [
            'products/gallery/product4-1.jpeg',
            'products/gallery/product4-2.jpeg',
            'products/gallery/product4-3.jpeg',
            'products/gallery/product4-4.jpeg',
            'products/gallery/product4-5.jpeg',
            'products/gallery/product4-6.jpeg',
        ];

        foreach ($gallery4 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product4->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 4 (2 Màu x 4 Size)
        $colors4 = [$colorBrown, $colorBlack];
        foreach ($colors4 as $color) {
            foreach ($jacket4Sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product4->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'JK4-' . strtoupper($color->name === 'Nâu' ? 'BRW' : 'BLK') . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(20, 50),
                    'price' => 669000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 7. TẠO SẢN PHẨM THỨ 5: Áo Cardigan Regular L.4.5214 (Theo ảnh)
        // ---------------------------------------------------------
        $catCardigan = Category::where('slug', 'cardigan')->first() ?? $category;
        $cardiganSizes = Size::whereIn('name', ['M', 'L', 'XL', '2XL'])->get();
        $colorOlive = Color::firstOrCreate(['name' => 'Xanh Rêu'], ['hex_code' => '#556B2F', 'is_active' => true]);
        $colorKeKem = Color::firstOrCreate(['name' => 'Kẻ Kem'], ['hex_code' => '#F5F5DC', 'is_active' => true]);
        $colorKeNau = Color::firstOrCreate(['name' => 'Kẻ Nâu'], ['hex_code' => '#A52A2A', 'is_active' => true]);
        $colorKeDen = Color::firstOrCreate(['name' => 'Kẻ Đen'], ['hex_code' => '#000000', 'is_active' => true]);

        $product5 = Product::create([
            'category_id' => $catCardigan->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Cardigan Regular L.4.5214',
            'slug' => Str::slug('Áo Cardigan Regular L.4.5214') . '-' . uniqid(),
            'short_description' => 'Áo Cardigan chất liệu nỉ mỏng, nhẹ nhàng và ấm áp.',
            'description' => 'Sản phẩm Áo Cardigan Regular L.4.5214 thiết kế không cổ, cài cúc, phù hợp mặc khoác nhẹ bên ngoài cho phong cách thư thái.',
            'price' => 159000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product5.jpeg',
        ]);

        // Gallery cho SP 5
        $gallery5 = [
            'products/gallery/product5-1.jpeg',
            'products/gallery/product5-2.jpeg',
            'products/gallery/product5-3.jpeg',
            'products/gallery/product5-4.jpeg',
        ];

        foreach ($gallery5 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product5->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 5 (3 Màu x 4 Size)
        $colors5 = [$colorBrown, $colorOlive, $colorBlack];
        foreach ($colors5 as $color) {
            foreach ($cardiganSizes as $size) {
                ProductVariant::create([
                    'product_id' => $product5->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'CDG-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(30, 100),
                    'price' => 159000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 8. TẠO SẢN PHẨM THỨ 6: Áo Hoodie Loose L.3.6982 (Theo ảnh)
        // ---------------------------------------------------------
        $catHoodie = Category::where('slug', 'ao-hoodie')->first() ?? $category;
        $colorNavy = Color::where('name', 'Xanh navy')->first() ?? Color::first();
        $hoodieSizes = Size::whereIn('name', ['M', 'L', 'XL', '2XL'])->get();

        $product6 = Product::create([
            'category_id' => $catHoodie->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Hoodie Loose L.3.6982',
            'slug' => Str::slug('Áo Hoodie Loose L.3.6982') . '-' . uniqid(),
            'short_description' => 'Áo Hoodie form Loose rộng rãi, thoải mái cho cả nam và nữ.',
            'description' => 'Sản phẩm Áo Hoodie Loose L.3.6982 với chất liệu nỉ bông dày dặn, hình in sắc nét, nón rộng hiện đại.',
            'price' => 349000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product6.jpeg',
        ]);

        // Gallery cho SP 6
        $gallery6 = [
            'products/gallery/product6-1.jpeg',
            'products/gallery/product6-2.jpeg',
            'products/gallery/product6-3.jpeg',
        ];

        foreach ($gallery6 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product6->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 6 (3 Màu x 4 Size)
        $colors6 = [$colorWhite, $colorBlack, $colorNavy];
        foreach ($colors6 as $color) {
            foreach ($hoodieSizes as $size) {
                ProductVariant::create([
                    'product_id' => $product6->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'HDD-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(25, 80),
                    'price' => 349000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 9. TẠO SẢN PHẨM THỨ 7: Áo Nỉ Fitted L.4.7862 (Theo ảnh)
        // ---------------------------------------------------------
        $catSweatshirt = Category::where('slug', 'ao-ni-ao-thun-dai-tay')->first() ?? $category;
        $colorCream = Color::where('name', 'Kem')->first() ?? Color::first();
        $colorGray7 = Color::where('name', 'Xám')->first() ?? Color::first();
        $fittedSizes = Size::whereIn('name', ['M', 'L', 'XL', '2XL'])->get();

        $product7 = Product::create([
            'category_id' => $catSweatshirt->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Nỉ Fitted L.4.7862',
            'slug' => Str::slug('Áo Nỉ Fitted L.4.7862') . '-' . uniqid(),
            'short_description' => 'Áo nỉ phong cách Fitted, chất liệu mềm mại, giữ ấm tốt.',
            'description' => 'Sản phẩm Áo Nỉ Fitted L.4.7862 với thiết kế basic, form dáng ôm vừa vặn, thích hợp mặc hằng ngày trong tiết trời thu đông.',
            'price' => 129000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product7.jpg',
        ]);

        // Gallery cho SP 7
        $gallery7 = [
            'products/gallery/product7-1.jpg',
            'products/gallery/product7-2.jpg',
            'products/gallery/product7-3.jpg',
            'products/gallery/product7-4.jpg',
            'products/gallery/product7-5.jpg',
            'products/gallery/product7-6.jpeg',
            'products/gallery/product7-7.jpeg',
        ];

        foreach ($gallery7 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product7->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 7 (4 Màu x 4 Size)
        $colors7 = [$colorGray7, $colorCream, $colorWhite, $colorBlack];
        foreach ($colors7 as $color) {
            foreach ($fittedSizes as $size) {
                ProductVariant::create([
                    'product_id' => $product7->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'SWT-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(40, 120),
                    'price' => 129000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 10. TẠO SẢN PHẨM THỨ 8: Áo Khoác XL.2.8310 (Theo ảnh)
        // ---------------------------------------------------------
        $catJacket8 = Category::where('slug', 'ao-khoac')->first() ?? $category;
        $jacket8Sizes = Size::whereIn('name', ['L', 'XL', '2XL', '3XL'])->get();

        $product8 = Product::create([
            'category_id' => $catJacket8->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Khoác XL.2.8310',
            'slug' => Str::slug('Áo Khoác XL.2.8310') . '-' . uniqid(),
            'short_description' => 'Áo khoác gió cao cấp, phong cách mạnh mẽ và cá tính.',
            'description' => 'Sản phẩm Áo Khoác XL.2.8310 với thiết kế khóa kéo mượt mà, chất liệu vải dù bền bỉ, thích hợp cho thời tiết giao mùa.',
            'price' => 479000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product8.jpeg',
        ]);

        // Gallery cho SP 8
        $gallery8 = [
            'products/gallery/product8-1.jpeg',
            'products/gallery/product8-2.png',
        ];

        foreach ($gallery8 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product8->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 8 (2 Màu x 4 Size)
        $colors8 = [$colorBlack, $colorBrown];
        foreach ($colors8 as $color) {
            foreach ($jacket8Sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product8->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'JK8-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(15, 45),
                    'price' => 479000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 11. TẠO SẢN PHẨM THỨ 9: Áo Phao AKP 2XL.2.8560 (Theo ảnh)
        // ---------------------------------------------------------
        $catPhao9 = Category::where('slug', 'ao-phao')->first() ?? $category;
        $phao9Sizes = Size::whereIn('name', ['XL', '2XL', '3XL', '4XL'])->get();

        $product9 = Product::create([
            'category_id' => $catPhao9->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Phao AKP 2XL.2.8560',
            'slug' => Str::slug('Áo Phao AKP 2XL.2.8560') . '-' . uniqid(),
            'short_description' => 'Áo phao dày dặn, cản gió và giữ nhiệt cực tốt cho mùa đông lạnh giá.',
            'description' => 'Sản phẩm Áo Phao AKP 2XL.2.8560 thiết kế có mũ trùm đầu tiện lợi, bo gấu tay chống gió lùa, chất liệu cao cấp bền đẹp.',
            'price' => 749000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product9.jpeg',
        ]);

        // Gallery cho SP 9
        $gallery9 = [
            'products/gallery/product9-1.jpeg',
            'products/gallery/product9-2.jpeg',
            'products/gallery/product9-3.jpeg',
            'products/gallery/product9-4.jpeg',
            'products/gallery/product9-5.jpeg',
            'products/gallery/product9-6.jpeg',
        ];

        foreach ($gallery9 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product9->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 9 (2 Màu x 4 Size)
        $colors9 = [$colorOlive, $colorBlack]; // Olive (Xanh Rêu) và Đen
        foreach ($colors9 as $color) {
            foreach ($phao9Sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product9->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'AP9-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(10, 40),
                    'price' => 749000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 12. TẠO SẢN PHẨM THỨ 10: Áo Cardigan L.3.5211 (Theo ảnh)
        // ---------------------------------------------------------
        $catLen = Category::where('slug', 'ao-len')->first() ?? $category;
        $colorKeKem = Color::where('name', 'Kẻ Kem')->first() ?? Color::first();
        $colorKeNau = Color::where('name', 'Kẻ Nâu')->first() ?? Color::first();
        $colorKeDen = Color::where('name', 'Kẻ Đen')->first() ?? Color::first();
        $cardigan10Sizes = Size::whereIn('name', ['M', 'L', 'XL', '2XL'])->get();

        $product10 = Product::create([
            'category_id' => $catLen->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Cardigan L.3.5211',
            'slug' => Str::slug('Áo Cardigan L.3.5211') . '-' . uniqid(),
            'short_description' => 'Áo Cardigan họa tiết kẻ ngang thời trang, chất liệu len mềm mại.',
            'description' => 'Sản phẩm Áo Cardigan L.3.5211 với thiết kế cổ bẻ, cài cúc lịch lãm kết hợp họa tiết kẻ ngang trẻ trung, phù hợp cho nhiều dịp.',
            'price' => 349000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product10.jpg',
        ]);

        // Gallery cho SP 10
        $gallery10 = [
            'products/gallery/product10-1.jpg',
        ];

        foreach ($gallery10 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product10->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 10 (3 Màu x 4 Size)
        $colors10 = [$colorKeKem, $colorKeNau, $colorKeDen];
        foreach ($colors10 as $color) {
            foreach ($cardigan10Sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product10->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'CDG10-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(15, 40),
                    'price' => 349000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 13. TẠO SẢN PHẨM THỨ 11: Áo Cardigan L.3.5211 (Theo ảnh)
        // ---------------------------------------------------------
        $product11 = Product::create([
            'category_id' => $catLen->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Cardigan L.3.5211',
            'slug' => Str::slug('Áo Cardigan L.3.5211') . '-' . uniqid(),
            'short_description' => 'Áo Cardigan họa tiết kẻ ngang thời trang, chất liệu len dày dặn hơn mẫu 10.',
            'description' => 'Sản phẩm Áo Cardigan L.3.5211 thiết kế classic, là mẫu bổ sung cho bộ sưu tập len thu đông năm nay.',
            'price' => 349000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product11.png',
        ]);

        // Gallery cho SP 11
        $gallery11 = [
            'products/gallery/product11-1.png',
            'products/gallery/product11-2.png'
        ];

        foreach ($gallery11 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product11->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 11 (3 Màu x 4 Size)
        foreach ($colors10 as $color) {
            foreach ($cardigan10Sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product11->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'CDG11-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(10, 35),
                    'price' => 349000,
                ]);
            }
        }

        // ---------------------------------------------------------
        // 14. TẠO SẢN PHẨM THỨ 12: Áo Len Cổ Lọ L.6.5081 (Theo ảnh)
        // ---------------------------------------------------------
        $product12 = Product::create([
            'category_id' => $catLen->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Len Cổ Lọ L.6.5081',
            'slug' => Str::slug('Áo Len Cổ Lọ L.6.5081') . '-' . uniqid(),
            'short_description' => 'Áo len cổ lọ giữ ấm cực tốt, phong cách thời trang đơn giản.',
            'description' => 'Sản phẩm Áo Len Cổ Lọ L.6.5081 với chất liệu len dệt kim co giãn tốt, form dáng ôm vừa vặn, thích hợp làm lớp áo lót bên trong hoặc mặc ngoài.',
            'price' => 199000,
            'is_active' => true,
            'is_featured' => true,
            'image' => 'products/product12.jpg',
        ]);

        // Gallery cho SP 12
        $gallery12 = [
            'products/gallery/product12-1.jpg'
        ];

        foreach ($gallery12 as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product12->id,
                'image_path' => $imagePath,
                'sort_order' => $index + 1,
            ]);
        }

        // Biến thể cho SP 12 (6 Màu x 4 Size)
        $colors12 = [$colorGray, $colorBlack, $colorCream, $colorBrown, $colorWhite, $colorNavy];
        foreach ($colors12 as $color) {
            foreach ($cardigan10Sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product12->id,
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                    'sku' => 'ALCL-' . strtoupper(Str::slug($color->name)) . '-' . $size->name . '-' . rand(100, 999),
                    'stock_quantity' => rand(20, 60),
                    'price' => 199000,
                ]);
            }
        }

        echo "\n✅ Đã tạo thành công SP 12 (Áo Len Cổ Lọ L.6.5081)!\n";
    }
}
