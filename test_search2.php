<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/orders/customers/search', 'GET', ['q' => 'a']);
$controller = new App\Http\Controllers\Admin\OrderController();

try {
    $response = $controller->customersSearch($request);
    echo "SUCCESS\n";
    echo $response->getContent();
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
