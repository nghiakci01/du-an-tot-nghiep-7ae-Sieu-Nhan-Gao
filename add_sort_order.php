<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::statement('ALTER TABLE product_images ADD COLUMN sort_order INT DEFAULT 0 AFTER image_path');
    echo "✅ Successfully added sort_order column\n";
    
    // Verify
    $columns = DB::select('SHOW COLUMNS FROM product_images');
    echo "\nCurrent columns:\n";
    foreach ($columns as $col) {
        echo "- {$col->Field} ({$col->Type})\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
