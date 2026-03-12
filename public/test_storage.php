<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Product::whereNotNull('image')->first();
echo "Product ID: " . $p->id . "\n";
echo "Image string: " . $p->image . "\n";
echo "Public disk exists? " . (\Storage::disk('public')->exists($p->image) ? 'Yes' : 'No') . "\n";
echo "Absolute path: " . \Storage::disk('public')->path($p->image) . "\n";

echo "\nListing products folder:\n";
$files = \Storage::disk('public')->files('products');
print_r(array_slice($files, 0, 5));
