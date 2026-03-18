<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = \App\Models\Order::orderBy('id', 'desc')->take(5)->get();
foreach ($orders as $o) {
    echo "ID: [{$o->id}] - Email: [{$o->email}] - Phone: [{$o->phone}]\n";
}
