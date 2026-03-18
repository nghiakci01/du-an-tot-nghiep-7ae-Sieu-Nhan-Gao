<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = \App\Models\Category::all();
foreach ($categories as $category) {
    $count = $category->products()->where('is_active', true)->count();
    if ($count > 1) {
        echo "Category: {$category->name} (ID: {$category->id}) - Active products: {$count}\n";
    }
}
