<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://levihsu-ootdiffusion.hf.space/call/process_hd';

$base64Image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

$payload = [
    'data' => [
        ['url' => $base64Image, 'meta' => ['_type' => 'gradio.FileData']],
        ['url' => $base64Image, 'meta' => ['_type' => 'gradio.FileData']],
        'Upper-body',
        1,
        20,
        2.0,
        -1
    ]
];

echo "Posting...\n";
$req = Http::timeout(60)->post($url, $payload);
$eventId = $req->json('event_id');
echo "Event ID: $eventId\n";

if (!$eventId) {
    echo "Failed to get event ID. Body: " . $req->body() . "\n";
    exit(1);
}

echo "Getting stream...\n";
$res = Http::timeout(120)->get($url . '/' . $eventId);

echo "Stream finished. Length: " . strlen($res->body()) . "\n";
echo substr($res->body(), -500);
