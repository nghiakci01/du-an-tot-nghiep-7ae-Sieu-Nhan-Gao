<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "\n=== VARIANT PRICE CHECK ===\n\n";

$products = Product::with('variants')->take(5)->get();

foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "  Product Price: {$product->price}\n";
    echo "  Variants Count: " . $product->variants->count() . "\n";
    
    if ($product->variants->count() > 0) {
        echo "  Variant Prices:\n";
        foreach ($product->variants as $variant) {
            echo "    - {$variant->name}: {$variant->price}\n";
        }
        echo "  Min Variant Price: " . $product->variants->min('price') . "\n";
        echo "  Max Variant Price: " . $product->variants->max('price') . "\n";
    }
    echo "\n";
}
