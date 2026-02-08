<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::with('category')->get();

echo "\n=== PRODUCT INFORMATION AUDIT ===\n\n";
echo "Total Products: {$products->count()}\n";
echo str_repeat('=', 80) . "\n\n";

$missingData = [
    'description' => 0,
    'short_description' => 0,
    'price' => 0,
    'category' => 0,
    'image' => 0,
];

foreach ($products as $product) {
    echo "ID: {$product->id} | {$product->name}\n";
    echo "  Category: " . ($product->category ? $product->category->name : 'MISSING') . "\n";
    echo "  Price: " . ($product->price ? number_format($product->price) . ' VND' : 'MISSING') . "\n";
    echo "  Sale Price: " . ($product->sale_price ? number_format($product->sale_price) . ' VND' : 'None') . "\n";
    echo "  Image: " . ($product->image ? $product->image : 'MISSING') . "\n";
    echo "  Short Desc: " . (strlen($product->short_description ?? '') > 0 ? 'YES (' . strlen($product->short_description) . ' chars)' : 'MISSING') . "\n";
    echo "  Description: " . (strlen($product->description ?? '') > 0 ? 'YES (' . strlen($product->description) . ' chars)' : 'MISSING') . "\n";
    echo "  Active: " . ($product->is_active ? 'YES' : 'NO') . "\n";
    echo "  Featured: " . ($product->is_featured ? 'YES' : 'NO') . "\n";
    
    // Track missing data
    if (!$product->description || strlen($product->description) == 0) $missingData['description']++;
    if (!$product->short_description || strlen($product->short_description) == 0) $missingData['short_description']++;
    if (!$product->price) $missingData['price']++;
    if (!$product->category) $missingData['category']++;
    if (!$product->image) $missingData['image']++;
    
    echo str_repeat('-', 80) . "\n";
}

echo "\n=== MISSING DATA SUMMARY ===\n";
foreach ($missingData as $field => $count) {
    if ($count > 0) {
        echo "❌ {$field}: {$count} products missing\n";
    } else {
        echo "✅ {$field}: All products have data\n";
    }
}
echo "\n";
