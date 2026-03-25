<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ProductVariant;

$product = Product::with('variants.sizeRelationship', 'variants.colorRelationship')->find(18);
echo "Product: " . $product->name . " (ID: " . $product->id . ")\n";
foreach ($product->variants as $variant) {
    echo "Variant ID: " . $variant->id . "\n";
    echo "Size: " . ($variant->sizeRelationship ? $variant->sizeRelationship->name : $variant->size) . " (ID: " . $variant->size_id . ")\n";
    echo "Color: " . ($variant->colorRelationship ? $variant->colorRelationship->name : $variant->color) . " (ID: " . $variant->color_id . ")\n";
    echo "Stock: " . $variant->stock_quantity . "\n";
    echo "-------------------\n";
}

if ($outOfStockVariants->isEmpty()) {
    echo "No out of stock variants found. Looking for a product with multiple variants to pick one and set it to 0 temporary if needed.\n";
    $productWithVariants = Product::has('variants', '>', 1)->with('variants')->first();
    if ($productWithVariants) {
        echo "Product: " . $productWithVariants->name . "\n";
        foreach ($productWithVariants->variants as $v) {
            echo " - Variant ID: " . $v->id . " | Size: " . $v->size . " | Color: " . $v->color . " | Stock: " . $v->stock_quantity . "\n";
        }
    }
}
