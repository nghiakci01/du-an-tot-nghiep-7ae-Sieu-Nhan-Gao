<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Product::first();
echo "Product ID: " . $p->id . "\n";
echo "Image URL: " . $p->image_url . "\n";
if ($p->images->count() > 0) {
    echo "Secondary Image URL: " . $p->images->first()->image_url . "\n";
} else {
    echo "No secondary image\n";
}
