<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

$columns = Schema::getColumnListing('products');

echo "\n=== PRODUCTS TABLE COLUMNS ===\n\n";
echo implode("\n", $columns);
echo "\n\n";

$hasColumns = [
    'sale_price' => in_array('sale_price', $columns),
    'short_description' => in_array('short_description', $columns),
    'is_featured' => in_array('is_featured', $columns),
];

foreach ($hasColumns as $col => $exists) {
    echo ($exists ? '✅' : '❌') . " {$col}\n";
}

echo "\n";
