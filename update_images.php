<?php

use App\Models\Product;
use Illuminate\Support\Facades\DB;

// Images available in storage
$images = [
    'products/gb6QgKjuFlREqHBwiGieMaBd9ZZ5eFdp6R4AUj6H.png',
    // Giả sử có thêm ảnh mặc định nếu file kia không tồn tại, hoặc dùng ảnh placeholder online nếu cần
];

// Lấy danh sách sản phẩm
$products = Product::all();

foreach ($products as $index => $product) {
    // Luân phiên chọn ảnh
    $image = $images[$index % count($images)];
    
    $product->update(['image' => $image]);
    
    // Cập nhật cả bảng product_images
    DB::table('product_images')
        ->where('product_id', $product->id)
        ->update(['image_path' => $image]);
}

echo "✅ Đã cập nhật ảnh cho " . $products->count() . " sản phẩm!\n";
