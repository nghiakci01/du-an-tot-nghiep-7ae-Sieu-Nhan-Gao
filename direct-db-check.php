<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Check products table directly
$products = DB::table('products')
    ->select('id', 'name', 'price', 'sale_price')
    ->orderBy('id')
    ->get();

echo "\n=== DIRECT DATABASE CHECK ===\n\n";
echo "Total products: " . $products->count() . "\n\n";

$hasPrice = 0;
$noPrice = 0;

foreach ($products as $product) {
    $priceValue = $product->price ?? 0;
    $saleValue = $product->sale_price ?? 0;
    
    if ($priceValue > 0) {
        $hasPrice++;
    } else {
        $noPrice++;
    }
    
    echo sprintf(
        "ID %2d: %-40s | Price: %10s | Sale: %10s\n",
        $product->id,
        substr($product->name, 0, 40),
        $priceValue,
        $saleValue
    );
}

echo "\n";
echo "Has Price (>0): {$hasPrice}\n";
echo "No Price (=0): {$noPrice}\n";
echo "\n";
