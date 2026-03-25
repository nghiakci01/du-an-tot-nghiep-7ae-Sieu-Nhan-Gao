<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';      
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$categories = Category::all();

foreach ($categories as $category) {
    $count = $category->products()->where('is_active', true)->count();
    echo "Category: " . $category->name . " - Active Products: " . $count . PHP_EOL;
}
