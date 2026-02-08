<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "\n=== TESTING PRODUCT MODEL ===\n\n";

// Get first product via Eloquent
$product = Product::first();

echo "Via Eloquent Model:\n";
echo "  ID: {$product->id}\n";
echo "  Name: {$product->name}\n";
echo "  Price (attribute): " . var_export($product->price, true) . "\n";
echo "  Price (getRawOriginal): " . var_export($product->getRawOriginal('price'), true) . "\n";
echo "  Sale Price (attribute): " . var_export($product->sale_price, true) . "\n";
echo "  Casts: " . json_encode($product->getCasts()) . "\n";

echo "\n";

// Get same product via DB
$dbProduct = DB::table('products')->where('id', $product->id)->first();

echo "Via Direct DB Query:\n";
echo "  Price: {$dbProduct->price}\n";
echo "  Sale Price: {$dbProduct->sale_price}\n";

echo "\n";
