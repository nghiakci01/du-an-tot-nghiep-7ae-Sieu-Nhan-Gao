<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

echo "Scanning for missing images...\n";

$products = Product::whereNotNull('image')->get();
$cleaned = 0;

foreach ($products as $product) {
    if (!Storage::disk('public')->exists($product->image)) {
        echo "Missing: {$product->image} (Product: {$product->name})\n";
        $product->update(['image' => null]);
        $cleaned++;
    }
}

echo "\n✅ Cleaned {$cleaned} products with missing images.\n";
