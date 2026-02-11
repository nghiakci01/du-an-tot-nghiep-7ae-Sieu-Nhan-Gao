<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = Product::withCount('images')->get();

echo sprintf("%-5s | %-40s | %-10s | %-15s\n", "ID", "Name", "Gallery", "Main Image");
echo str_repeat("-", 80) . "\n";

foreach ($products as $product) {
    echo sprintf("%-5d | %-40s | %-10d | %-15s\n", 
        $product->id, 
        substr($product->name, 0, 40), 
        $product->images_count,
        $product->image ? 'Yes' : 'No'
    );
}
