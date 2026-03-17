<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$req = new \Illuminate\Http\Request();
$req->merge(['q' => 'a']);
$res = app()->make(\App\Http\Controllers\Admin\ProductController::class)->variantsSearch($req);
echo json_encode(['status' => $res->status(), 'content' => $res->getContent()]);
