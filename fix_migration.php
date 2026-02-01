<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$outputFile = 'migration_error.txt';

try {
    Artisan::call('migrate', ['--force' => true]);
    $msg = "Migration successful!\n" . Artisan::output();
    file_put_contents($outputFile, $msg);
} catch (\Exception $e) {
    $msg = "Migration failed: " . $e->getMessage() . "\n";
    $msg .= "Trace: " . $e->getTraceAsString() . "\n";
    file_put_contents($outputFile, $msg);
}
echo "Done. Check migration_error.txt\n";
