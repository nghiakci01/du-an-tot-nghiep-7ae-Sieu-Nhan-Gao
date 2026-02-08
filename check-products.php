<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::all(['id', 'name', 'slug', 'image']);

echo "\n=== PRODUCTS IN DATABASE ===\n\n";

foreach ($products as $product) {
    echo "ID: {$product->id}\n";
    echo "Name: {$product->name}\n";
    echo "Slug: {$product->slug}\n";
    echo "Image: " . ($product->image ?? 'NULL') . "\n";
    echo str_repeat('-', 50) . "\n";
}

echo "\nTotal: {$products->count()} products\n\n";
