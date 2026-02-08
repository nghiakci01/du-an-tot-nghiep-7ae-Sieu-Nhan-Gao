<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "\n=== SIMULATING VIEW LOGIC ===\n\n";

$product = Product::with('variants')->first();

echo "Product: {$product->name}\n";
echo "Product Price: {$product->price}\n";
echo "Product Sale Price: {$product->sale_price}\n";
echo "Variants Count: " . $product->variants->count() . "\n";

if ($product->variants->count() > 0) {
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
    echo "Min Variant Price: {$minPrice}\n";
    echo "Max Variant Price: {$maxPrice}\n";
    
    echo "\nCurrent View Logic:\n";
    if ($product->variants->count() > 0) {
        echo "  → Using VARIANT price: " . number_format($minPrice) . " đ\n";
    } else {
        echo "  → Using PRODUCT price: " . number_format($product->price) . " đ\n";
    }
    
    echo "\nFixed View Logic:\n";
    if ($product->variants->count() > 0 && $minPrice > 0) {
        echo "  → Using VARIANT price: " . number_format($minPrice) . " đ\n";
    } else {
        echo "  → Using PRODUCT price: " . number_format($product->price) . " đ\n";
    }
}

echo "\n";
