<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::orderBy('id')->get(['id', 'name', 'price', 'sale_price']);

echo "\n=== CURRENT PRODUCT PRICES ===\n\n";

foreach ($products as $product) {
    $price = $product->price ? number_format($product->price, 0, ',', '.') . 'đ' : 'NULL';
    $salePrice = $product->sale_price ? number_format($product->sale_price, 0, ',', '.') . 'đ' : 'NULL';
    
    echo sprintf(
        "ID %2d: %-40s Price: %15s | Sale: %15s\n",
        $product->id,
        substr($product->name, 0, 40),
        $price,
        $salePrice
    );
}

echo "\n";
