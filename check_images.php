<?php
$products = \DB::table('products')->select('id', 'name', 'image')->take(5)->get();
echo "=== PRODUCTS ===\n";
foreach ($products as $product) {
    echo "ID: {$product->id}, Name: {$product->name}, Image: {$product->image}\n";
}

$images = \DB::table('product_images')->select('id', 'product_id', 'image_path')->take(5)->get();
echo "\n=== PRODUCT IMAGES (Gallery) ===\n";
foreach ($images as $image) {
    echo "ID: {$image->id}, Product ID: {$image->product_id}, Path: {$image->image_path}\n";
}
