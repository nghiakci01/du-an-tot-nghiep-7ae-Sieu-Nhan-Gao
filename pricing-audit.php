<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::all();

echo "\n=== PRICING AUDIT ===\n\n";

$missingPrice = [];
$missingSalePrice = [];
$hasPrice = 0;
$hasSalePrice = 0;

foreach ($products as $product) {
    if (!$product->price || $product->price == 0) {
        $missingPrice[] = $product->id;
    } else {
        $hasPrice++;
    }
    
    if (!$product->sale_price || $product->sale_price == 0) {
        $missingSalePrice[] = $product->id;
    } else {
        $hasSalePrice++;
    }
}

echo "Total Products: {$products->count()}\n\n";

echo "Price Status:\n";
echo "  ✅ Has Price: {$hasPrice} products\n";
echo "  ❌ Missing Price: " . count($missingPrice) . " products\n";
if (count($missingPrice) > 0) {
    echo "     IDs: " . implode(', ', $missingPrice) . "\n";
}

echo "\nSale Price Status:\n";
echo "  ✅ Has Sale Price: {$hasSalePrice} products\n";
echo "  ⚠️  No Sale Price: " . count($missingSalePrice) . " products\n";
if (count($missingSalePrice) > 0 && count($missingSalePrice) <= 10) {
    echo "     IDs: " . implode(', ', $missingSalePrice) . "\n";
}

echo "\n";
