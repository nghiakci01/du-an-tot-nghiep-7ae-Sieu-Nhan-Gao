<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$slugs = [
    'ao-khoac-l28896-69ba32e872993',
    'ao-khoac-xl28310-69ba312151c5f'
];

foreach ($slugs as $slug) {
    $product = \App\Models\Product::where('slug', $slug)->with('variants')->first();

    if (!$product) {
        echo "Product not found: $slug\n";
        continue;
    }

    echo "Product: {$product->name}\n";
    echo "Base Price: {$product->price}\n";
    foreach ($product->variants as $v) {
        echo "Variant ID: {$v->id} | Price: {$v->price} | Sale: {$v->sale_price}\n";
    }
    echo "-------------------\n";
}
