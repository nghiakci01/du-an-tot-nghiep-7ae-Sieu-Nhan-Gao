<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs dynamically
        $aoThunNam = Category::where('slug', 'ao-thun-nam')->first()?->id ?? 1;
        $aoSoMiNam = Category::where('slug', 'ao-so-mi-nam')->first()?->id ?? 1;
        $quanJeansNam = Category::where('slug', 'quan-jeans-nam')->first()?->id ?? 1;
        $aoKhoacNam = Category::where('slug', 'ao-khoac-nam')->first()?->id ?? 1;
        $vayDam = Category::where('slug', 'vay-dam')->first()?->id ?? 2;
        $aoThunNu = Category::where('slug', 'ao-thun-nu')->first()?->id ?? 2;
        $quanNu = Category::where('slug', 'quan-nu')->first()?->id ?? 2;
        $aoSoMiNu = Category::where('slug', 'ao-so-mi-nu')->first()?->id ?? 2;
        $tuiXach = Category::where('slug', 'tui-xach')->first()?->id ?? 3;
        $muNon = Category::where('slug', 'mu-non')->first()?->id ?? 3;
        $thatLung = Category::where('slug', 'that-lung')->first()?->id ?? 3;
        $kinhMat = Category::where('slug', 'kinh-mat')->first()?->id ?? 3;

        $products = [
            // Áo Thun Nam (5 products)
            ['name' => 'Áo Thun Basic Trắng Premium', 'category_id' => $aoThunNam, 'price' => 199000, 'description' => 'Áo thun cotton 100%, form basic dễ phối đồ, chất liệu thấm hút mồ hôi tốt'],
            ['name' => 'Áo Thun Oversize Đen Streetwear', 'category_id' => $aoThunNam, 'price' => 250000, 'description' => 'Áo thun oversize phong cách Hàn Quốc, chất vải dày dặn, form rộng thoải mái'],
            ['name' => 'Áo Thun Polo Nam Xanh Navy', 'category_id' => $aoThunNam, 'price' => 350000, 'description' => 'Áo polo lịch sự, phù hợp đi làm và dạo phố, chất liệu pique cotton cao cấp'],
            ['name' => 'Áo Thun Graphic Print Limited', 'category_id' => $aoThunNam, 'price' => 280000, 'description' => 'Áo thun in hình họa tiết độc đáo, limited edition, chất cotton USA'],
            ['name' => 'Áo Thun Raglan Tay Dài', 'category_id' => $aoThunNam, 'price' => 320000, 'description' => 'Áo thun tay raglan phối màu trẻ trung, chất vải cotton pha spandex co giãn tốt'],

            // Áo Sơ Mi Nam (4 products)
            ['name' => 'Áo Sơ Mi Oxford Xanh Nhạt', 'category_id' => $aoSoMiNam, 'price' => 450000, 'description' => 'Áo sơ mi vải oxford cao cấp, form slim fit hiện đại, dễ là và giữ form'],
            ['name' => 'Áo Sơ Mi Trắng Công Sở', 'category_id' => $aoSoMiNam, 'price' => 420000, 'description' => 'Sơ mi trắng basic không bao giờ lỗi mốt, phù hợp môi trường công sở'],
            ['name' => 'Áo Sơ Mi Flannel Caro', 'category_id' => $aoSoMiNam, 'price' => 480000, 'description' => 'Sơ mi flannel họa tiết caro vintage, chất vải mềm mại ấm áp'],
            ['name' => 'Áo Sơ Mi Linen Xanh Mint', 'category_id' => $aoSoMiNam, 'price' => 550000, 'description' => 'Sơ mi vải linen thoáng mát, phong cách resort, màu sắc pastel nhẹ nhàng'],

            // Quần Jeans Nam (4 products)
            ['name' => 'Quần Jeans Skinny Đen', 'category_id' => $quanJeansNam, 'price' => 450000, 'description' => 'Quần jeans skinny ôm vừa phải, chất denim co giãn thoải mái khi vận động'],
            ['name' => 'Quần Jeans Baggy Xanh Nhạt', 'category_id' => $quanJeansNam, 'price' => 520000, 'description' => 'Jeans baggy form rộng trendy, màu xanh wash vintage đặc trưng'],
            ['name' => 'Quần Jeans Rách Gối Style', 'category_id' => $quanJeansNam, 'price' => 480000, 'description' => 'Quần jeans rách nhẹ cá tính, phong cách streetwear năng động'],
            ['name' => 'Quần Jeans Ống Loe Retro', 'category_id' => $quanJeansNam, 'price' => 550000, 'description' => 'Jeans ống loe phong cách retro 70s đang quay trở lại, chất denim cao cấp'],

            // Áo Khoác Nam (4 products)
            ['name' => 'Áo Khoác Bomber Đen Pilot', 'category_id' => $aoKhoacNam, 'price' => 650000, 'description' => 'Áo bomber jacket kiểu dáng phi công, chất liệu polyester chống gió nhẹ'],
            ['name' => 'Áo Khoác Jeans Xanh Wash', 'category_id' => $aoKhoacNam, 'price' => 580000, 'description' => 'Áo khoác denim wash cổ điển, dễ phối đồ, không bao giờ lỗi mốt'],
            ['name' => 'Áo Hoodie Zip Nỉ Bông', 'category_id' => $aoKhoacNam, 'price' => 420000, 'description' => 'Hoodie zip nỉ bông dày ấm áp, form rộng thoải mái cho mùa đông'],
            ['name' => 'Áo Cardigan Len Cài Khuy', 'category_id' => $aoKhoacNam, 'price' => 680000, 'description' => 'Cardigan len cao cấp phong cách Hàn Quốc, ấm áp và thanh lịch'],

            // Váy Đầm (5 products)
            ['name' => 'Váy Hoa Nhí Vintage Pastel', 'category_id' => $vayDam, 'price' => 450000, 'description' => 'Váy hoa nhí họa tiết vintage ngọt ngào, chất vải voan mỏng nhẹ thoáng mát'],
            ['name' => 'Đầm Suông Công Sở Xanh Navy', 'category_id' => $vayDam, 'price' => 550000, 'description' => 'Đầm suông form A thanh lịch, phù hợp môi trường công sở chuyên nghiệp'],
            ['name' => 'Váy Maxi Dạ Hội Đỏ Rượu', 'category_id' => $vayDam, 'price' => 890000, 'description' => 'Váy maxi dài sang trọng cho tiệc tối, chất lụa satin cao cấp'],
            ['name' => 'Đầm Babydoll Hồng Pastel', 'category_id' => $vayDam, 'price' => 420000, 'description' => 'Đầm babydoll dáng xoè trẻ trung, phom ngắn xinh xắn dễ thương'],
            ['name' => 'Váy Midi Xếp Ly Đen', 'category_id' => $vayDam, 'price' => 520000, 'description' => 'Váy midi xếp ly thanh lịch, chất vải tuyết mưa cao cấp không nhăn'],

            // Áo Thun Nữ (4 products)
            ['name' => 'Áo Thun Croptop Trắng', 'category_id' => $aoThunNu, 'price' => 180000, 'description' => 'Áo croptop basic dễ phối đồ, chất cotton mềm mịn thấm hút tốt'],
            ['name' => 'Áo Thun Form Rộng Màu Be', 'category_id' => $aoThunNu, 'price' => 220000, 'description' => 'Áo thun oversize tông màu trung tính dễ mặc, phong cách tối giản'],
            ['name' => 'Áo Thun Phối Ren Hồng', 'category_id' => $aoThunNu, 'price' => 280000, 'description' => 'Áo thun phối viền ren nữ tính, chi tiết tinh tế tôn nét duyên dáng'],
            ['name' => 'Áo Ba Lỗ Cotton Đen', 'category_id' => $aoThunNu, 'price' => 150000, 'description' => 'Áo ba lỗ basic mặc nhà hoặc tập gym, chất cotton 4 chiều co giãn tốt'],

            // Quần Nữ (3 products)
            ['name' => 'Quần Culottes Ống Rộng Xám', 'category_id' => $quanNu, 'price' => 380000, 'description' => 'Quần culottes dáng suông rộng thanh lịch, chất vải kaki cao cấp'],
            ['name' => 'Quần Jeans Nữ Xanh Nhạt', 'category_id' => $quanNu, 'price' => 420000, 'description' => 'Quần jeans nữ skinny ôm dáng, chất denim cao cấp có co giãn'],
            ['name' => 'Quần Tây Công Sở Đen', 'category_id' => $quanNu, 'price' => 450000, 'description' => 'Quần tây ống đứng lịch sự, form chuẩn phù hợp môi trường công sở'],

            // Áo Sơ Mi Nữ (3 products)
            ['name' => 'Áo Sơ Mi Trắng Tay Bồng', 'category_id' => $aoSoMiNu, 'price' => 380000, 'description' => 'Sơ mi tay bồng nữ tính, thiết kế tay phồng trendy đang được yêu thích'],
            ['name' => 'Áo Kiểu Voan Họa Tiết', 'category_id' => $aoSoMiNu, 'price' => 320000, 'description' => 'Áo kiểu voan mỏng nhẹ họa tiết hoa nhí, thanh lịch và bay bổng'],
            ['name' => 'Áo Sơ Mi Lụa Xanh Nhạt', 'category_id' => $aoSoMiNu, 'price' => 480000, 'description' => 'Sơ mi vải lụa mềm mại cao cấp, màu sắc nhẹ nhàng dễ phối'],

            // Phụ kiện (túi, mũ, thắt lưng, kính)
            ['name' => 'Túi Tote Canvas Đen Trơn', 'category_id' => $tuiXach, 'price' => 150000, 'description' => 'Túi tote vải canvas bền bỉ, thiết kế đơn giản đa năng đựng nhiều đồ'],
            ['name' => 'Túi Xách Da PU Nâu', 'category_id' => $tuiXach, 'price' => 380000, 'description' => 'Túi xách da PU cao cấp, thiết kế sang trọng phù hợp đi làm'],
            ['name' => 'Mũ Lưỡi Trai Snapback', 'category_id' => $muNon, 'price' => 180000, 'description' => 'Mũ lưỡi trai snapback phong cách hip hop, vải cotton thoáng khí'],
            ['name' => 'Mũ Bucket Vải Kaki Beige', 'category_id' => $muNon, 'price' => 220000, 'description' => 'Mũ bucket trendy phong cách Hàn Quốc, chống nắng tốt đi biển, picnic'],
            ['name' => 'Thắt Lưng Da Bò Cao Cấp', 'category_id' => $thatLung, 'price' => 350000, 'description' => 'Thắt lưng da bò thật 100%, khóa inox bền đẹp sang trọng'],
            ['name' => 'Kính Mát Aviator Gọng Vàng', 'category_id' => $kinhMat, 'price' => 250000, 'description' => 'Kính mát kiểu phi công cổ điển, tròng phân cực chống tia UV bảo vệ mắt'],
            ['name' => 'Kính Mát Vuông Đen Unisex', 'category_id' => $kinhMat, 'price' => 280000, 'description' => 'Kính gọng vuông trendy phù hợp cả nam và nữ, phong cách hiện đại'],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'category_id' => $productData['category_id'],
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'is_featured' => rand(0, 4) == 0, // 20% chance featured
                'is_active' => true,
                'image' => 'products/IwtktEFiLgAbezYA4i8TUFhc3jy7v0i4jmlnuX0S.png',
            ]);

            // Add variants
            $sizes = ['S', 'M', 'L', 'XL'];
            $colors = ['Đen', 'Trắng', 'Xanh Navy', 'Xanh Denim', 'Đỏ', 'Hồng', 'Be', 'Xám'];
            
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => $colors[array_rand($colors)],
                    'stock_quantity' => rand(10, 100),
                    'sku' => strtoupper(Str::random(3)) . '-' . $size . '-' . str_pad($product->id, 4, '0', STR_PAD_LEFT),
                ]);
            }

            // Add images
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/IwtktEFiLgAbezYA4i8TUFhc3jy7v0i4jmlnuX0S.png',
                'sort_order' => 1,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/a5cWz47edUEV9oIEl6LCyM48xpA7jbvKbzquliLg.png',
                'sort_order' => 2,
            ]);
        }
        
        echo "\n✅ Đã thêm thành công " . count($products) . " sản phẩm với " . (count($products) * 4) . " biến thể!\n";
    }
}
