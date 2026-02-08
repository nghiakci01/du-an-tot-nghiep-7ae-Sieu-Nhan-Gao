<?php

use App\Models\Product;

// List all products
$products = Product::all(['id', 'name', 'slug', 'image']);

echo "Products in database:\n";
echo str_repeat('=', 80) . "\n";

foreach ($products as $product) {
    echo sprintf(
        "ID: %d | Slug: %s\n  Name: %s\n  Image: %s\n",
        $product->id,
        $product->slug,
        $product->name,
        $product->image ?? '(null)'
    );
    echo str_repeat('-', 80) . "\n";
}

echo "\nTotal: " . $products->count() . " products\n";
