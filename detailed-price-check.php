<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "\n=== DETAILED PRICE CHECK ===\n\n";

$products = Product::with('category')->take(5)->get();

foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "  DB Price: " . $product->getRawOriginal('price') . "\n";
    echo "  DB Sale Price: " . $product->getRawOriginal('sale_price') . "\n";
    echo "  Model Price: {$product->price}\n";
    echo "  Model Sale Price: {$product->sale_price}\n";
    echo "  Formatted: " . number_format($product->price) . " đ\n";
    echo "  Has Variants: " . $product->variants->count() . "\n";
    echo "\n";
}
