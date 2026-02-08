<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::with('category')->get();

$missingData = [
    'description' => [],
    'short_description' => [],
];

foreach ($products as $product) {
    if (!$product->description || strlen($product->description) == 0) {
        $missingData['description'][] = $product->id;
    }
    if (!$product->short_description || strlen($product->short_description) == 0) {
        $missingData['short_description'][] = $product->id;
    }
}

echo "\n=== MISSING DATA SUMMARY ===\n\n";
echo "Total Products: {$products->count()}\n\n";

echo "Missing Description: " . count($missingData['description']) . " products\n";
if (count($missingData['description']) > 0) {
    echo "  IDs: " . implode(', ', $missingData['description']) . "\n";
}

echo "\nMissing Short Description: " . count($missingData['short_description']) . " products\n";
if (count($missingData['short_description']) > 0) {
    echo "  IDs: " . implode(', ', $missingData['short_description']) . "\n";
}

echo "\n";
