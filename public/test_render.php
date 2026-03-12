<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Product::where('slug', 'ao-jacket-xl28931-22')->first();
if (!$p) {
    echo "Product not found\n";
    exit;
}
$html = view('frontend.products.show', ['product' => $p])->render();
file_put_contents(__DIR__.'/rendered_show.html', $html);
echo "Rendered HTML\n";
