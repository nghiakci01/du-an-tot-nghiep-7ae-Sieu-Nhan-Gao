<?php

// standalone debug script
// run with: C:\laragon\bin\php\php-8.2.29-nts-Win32-vs16-x64\php.exe debug_vton_api.php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$humanUrl = 'https://raw.githubusercontent.com/gradio-app/gradio/main/test/test_files/bus.png';
$productUrl = 'https://raw.githubusercontent.com/gradio-app/gradio/main/test/test_files/bus.png';

$humanData = file_get_contents($humanUrl);
$productData = file_get_contents($productUrl);

$client = new Client();

function uploadToGradio($client, $baseUrl, $binaryData, $filename) {
    echo "Uploading {$filename} to {$baseUrl}...\n";
    $response = $client->post(rtrim($baseUrl, '/') . '/upload', [
        'multipart' => [
            [
                'name'     => 'files',
                'contents' => $binaryData,
                'filename' => $filename
            ]
        ]
    ]);
    
    $body = (string)$response->getBody();
    echo "Upload Result: " . $body . "\n";
    $files = json_decode($body, true);
    return $files[0] ?? null;
}

$base = 'https://yisol-idm-vton.hf.space';
$humanPath = uploadToGradio($client, $base, $humanData, 'human.png');
$productPath = uploadToGradio($client, $base, $productData, 'product.png');

if (!$humanPath || !$productPath) {
    die("Upload failed\n");
}

echo "Calling tryon...\n";
$payload = [
    'data' => [
        [
            'background' => ['path' => $humanPath, 'meta' => ['_type' => 'gradio.FileData']],
            'layers' => [],
            'composite' => null
        ],
        ['path' => $productPath, 'meta' => ['_type' => 'gradio.FileData']],
        "Fashion garment", true, true, 30, 42
    ]
];

$response = $client->post($base . '/call/tryon', [
    'json' => $payload
]);

$body = (string)$response->getBody();
echo "Call Result: " . $body . "\n";
$resData = json_decode($body, true);
$eventId = $resData['event_id'] ?? null;

if ($eventId) {
    echo "Event ID: {$eventId}\n";
    echo "Polling...\n";
    while(true) {
        $res = $client->get($base . '/call/tryon/' . $eventId);
        $status = (string)$res->getBody();
        echo "Status: " . substr($status, 0, 100) . "...\n";
        
        if (strpos($status, 'event: complete') !== false) {
            echo "SUCCESS!\n";
            echo $status . "\n";
            break;
        }
        if (strpos($status, 'event: error') !== false) {
            echo "ERROR!\n";
            echo $status . "\n";
            break;
        }
        sleep(2);
    }
} else {
    echo "No Event ID returned\n";
}
