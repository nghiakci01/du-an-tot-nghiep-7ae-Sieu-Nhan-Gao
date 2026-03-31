<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$o = \App\Models\Order::find(4);
if (!$o) {
    die("Order not found\n");
}

echo "Order Status: " . $o->status . "\n";
echo "Shipper Name: " . ($o->shipper ? $o->shipper->name : "None") . "\n";
echo "Audit Log:\n";
foreach($o->histories as $h) {
    echo "  - " . $h->created_at->format('H:i:s') . " - Status: " . $h->new_status . " (Author: " . ($h->user ? $h->user->name : "System") . ")\n";
}
