<?php

namespace App\Jobs;

use App\Models\VtonHistory;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessVtonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vtonHistoryId;
    public $humanBase64;
    public $productBase64;
    public $category;
    public $productId;

    /**
     * Create a new job instance.
     */
    public function __construct($vtonHistoryId, $humanBase64, $productBase64, $category, $productId = null)
    {
        $this->vtonHistoryId = $vtonHistoryId;
        $this->humanBase64 = $humanBase64;
        $this->productBase64 = $productBase64;
        $this->category = $category;
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $history = $this->vtonHistoryId ? \App\Models\VtonHistory::find($this->vtonHistoryId) : null;
        $product = $this->productId ? \App\Models\Product::find($this->productId) : null;

        if ($history) {
            $history->update(['status' => 'processing']);
        }
        
        Log::channel('vton')->info("Processing VTON Job. History: " . ($this->vtonHistoryId ?? 'N/A') . " Product: " . ($this->productId ?? 'N/A'));

        $resultUrl = $this->attemptTryOn($this->humanBase64, $this->productBase64, $this->category);

        if ($resultUrl) {
            if ($history) {
                $history->update([
                    'result_image' => $resultUrl,
                    'status' => 'completed'
                ]);
            }
            // Update product if it exists, regardless of history status
            $product = Product::find($this->productId);
            if ($product) {
                $product->update(['vton_image' => $resultUrl]);
            }
            Log::channel('vton')->info("VTON Job Completed successfully.");
        } else {
            if ($history) {
                $history->update(['status' => 'failed']);
            }
            Log::channel('vton')->error("VTON Job Failed to get result URL.");
        }
    }

    private function attemptTryOn($humanBase64, $productBase64, $category)
    {
        $spaces = [
            'weshop' => [
                'base' => 'https://weshopai-weshopai-virtual-try-on.hf.space/gradio_api',
                'api' => '/call/generate_image',
                'get_payload' => function($human, $product, $cat) {
                    $sessionHash = Str::random(11);
                    $baseUrl = 'https://weshopai-weshopai-virtual-try-on.hf.space/gradio_api';
                    
                    $humanObj = [
                        'path' => $human,
                        'url' => $baseUrl . '/file=' . ltrim($human, '/'),
                        'orig_name' => 'human.png',
                        'meta' => ['_type' => 'gradio.FileData']
                    ];
                    $productObj = [
                        'path' => $product,
                        'url' => $baseUrl . '/file=' . ltrim($product, '/'),
                        'orig_name' => 'product.png',
                        'meta' => ['_type' => 'gradio.FileData']
                    ];
                    return [
                        'data' => [
                            $productObj, // Garment first based on component ID 6
                            $humanObj,   // Human/BG second based on ID 11
                            null
                        ],
                        'session_hash' => $sessionHash
                    ];
                }
            ],
            'idm' => [
                'base' => 'https://yisol-idm-vton.hf.space',
                'api' => '/call/tryon',
                'get_payload' => function($human, $product, $cat) {
                    $sessionHash = Str::random(11);
                    $humanUrl = 'https://yisol-idm-vton.hf.space/file=' . ltrim($human, '/');
                    $productUrl = 'https://yisol-idm-vton.hf.space/file=' . ltrim($product, '/');
                    
                    $humanObj = [
                        'path' => $human,
                        'url' => $humanUrl,
                        'meta' => ['_type' => 'gradio.FileData']
                    ];
                    $productObj = [
                        'path' => $product,
                        'url' => $productUrl,
                        'meta' => ['_type' => 'gradio.FileData']
                    ];
                    return [
                        'data' => [
                            [
                                'background' => $humanObj,
                                'layers' => [$humanObj], // Try with human as mask placeholder
                                'composite' => $humanObj
                            ],
                            $productObj,
                            "Fashion garment", 
                            true, // auto-mask
                            false, // auto-crop
                            30, 
                            42
                        ],
                        'session_hash' => $sessionHash
                    ];
                }
            ],
            'oot' => [
                'base' => 'https://levihsu-ootdiffusion.hf.space',
                'api' => '/call/process_dc',
                'get_payload' => function($human, $product, $cat) {
                    $sessionHash = Str::random(11);
                    $humanUrl = 'https://levihsu-ootdiffusion.hf.space/file=' . ltrim($human, '/');
                    $productUrl = 'https://levihsu-ootdiffusion.hf.space/file=' . ltrim($product, '/');
                    
                    $humanObj = [
                        'path' => $human,
                        'url' => $humanUrl,
                        'meta' => ['_type' => 'gradio.FileData']
                    ];
                    $productObj = [
                        'path' => $product,
                        'url' => $productUrl,
                        'meta' => ['_type' => 'gradio.FileData']
                    ];
                    return [
                        'data' => [
                            $humanObj,
                            $productObj,
                            $cat, 1, 20, 2.0, -1
                        ],
                        'session_hash' => $sessionHash
                    ];
                }
            ]
        ];

        foreach ($spaces as $type => $space) {
            Log::channel('vton')->info("Trying VTON Space: {$type} ({$space['base']})");
            
            $headers = [
                'Origin' => str_replace('/gradio_api', '', $space['base']),
                'Referer' => str_replace('/gradio_api', '', $space['base']) . '/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ];

            try {
                // 1. Upload Human Image
                $humanPath = $this->uploadToGradio($space['base'], $humanBase64, 'human.png', $headers);
                if (!$humanPath) {
                    Log::channel('vton')->warning("Upload Human failed for {$type}");
                    continue;
                }

                // 2. Upload Product Image
                $productPath = $this->uploadToGradio($space['base'], $productBase64, 'product.png', $headers);
                if (!$productPath) {
                    Log::channel('vton')->warning("Upload Product failed for {$type}");
                    continue;
                }

                // 3. Call API
                $payload = $space['get_payload']($humanPath, $productPath, $category);
                $sessionHash = $payload['session_hash'] ?? Str::random(11);
                
                $response = Http::withHeaders($headers)->timeout(60)->post($space['base'] . $space['api'], $payload);
                
                if (!$response->successful()) {
                    Log::channel('vton')->warning("API Call failed for {$type}: " . $response->status() . " " . $response->body());
                    continue;
                }

                $eventId = $response->json('event_id');
                if (!$eventId) $eventId = $response->json('job_id'); // Gradio 5 call returns job_id

                if ($eventId) {
                    Log::channel('vton')->info("Polling VTON Space ({$type}): Event ID {$eventId}");
                    $startTime = time();
                    while ((time() - $startTime) < 240) { // Max 4 minutes
                        $pollUrl = $space['base'] . $space['api'] . '/' . $eventId;
                        if ($type === 'weshop') {
                            $pollUrl = $space['base'] . '/queue/data?session_hash=' . $sessionHash;
                        } else {
                            // Support for newer Gradio polling style
                            $pollUrl .= "?session_hash=" . $sessionHash;
                        }

                        $statusRes = Http::withHeaders($headers)
                            ->withOptions(['stream' => true])
                            ->timeout(60)
                            ->get($pollUrl);
                            
                        $body = $statusRes->body();
                        
                        // Capture cookies for the download (might be needed in Gradio 5)
                        $cookies = $statusRes->header('Set-Cookie');

                        if (Str::contains($body, 'process_completed') || Str::contains($body, 'event: complete') || Str::contains($body, '"msg": "process_completed"')) {
                            Log::channel('vton')->info("VTON Space ({$type}) reported completion.");

                            // Parse SSE stream robustly
                            $resUrl = null;
                            $lines = explode("\n", $body);
                            foreach ($lines as $line) {
                                if (Str::startsWith($line, 'data: ')) {
                                    $jsonData = json_decode(substr($line, 6), true);
                                    if (isset($jsonData['output']['data'][0]['url'])) {
                                        $resUrl = $jsonData['output']['data'][0]['url'];
                                        break;
                                    }
                                    // Fallback for different Gradio versions
                                    if (isset($jsonData['url'])) {
                                        $resUrl = $jsonData['url'];
                                        break;
                                    }
                                }
                            }
                            
                            // Last resort fallback regex if JSON decode failed
                            if (!$resUrl && preg_match('/"url":\s*"([^"]+)"/', $body, $m)) {
                                $resUrl = str_replace('\\/', '/', $m[1]);
                            }
                            
                            if ($resUrl) {
                                // Strip any trailing markers or mangled parts
                                if (Str::contains($resUrl, '"')) $resUrl = explode('"', $resUrl)[0];
                                
                                $origin = str_replace('/gradio_api', '', rtrim($space['base'], '/'));
                                if (Str::startsWith($resUrl, '/')) {
                                    $resUrl = $origin . $resUrl;
                                }

                                Log::channel('vton')->info("Success! Download URL: " . $resUrl);
                                try {
                                    $dlHeaders = array_merge($headers, [
                                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                                        'Referer' => $origin . '/',
                                    ]);
                                    if ($cookies) $dlHeaders['Cookie'] = $cookies;

                                    // Try multiple URL patterns for Gradio 5/4 compatibility
                                    $urlsToTry = [$resUrl];
                                    if (Str::contains($resUrl, '/gradio_api/')) {
                                        $urlsToTry[] = str_replace('/gradio_api/', '/', $resUrl);
                                    }
                                    
                                    $imageContent = null;
                                    $finalUrl = null;
                                    
                                    foreach ($urlsToTry as $tryUrl) {
                                        $dlRes = Http::withHeaders($dlHeaders)->timeout(60)->get($tryUrl);
                                        $tempContent = $dlRes->body();
                                        
                                        // Verify if it's actually an image (PNG magic number: \x89PNG)
                                        $isImage = Str::startsWith($tempContent, "\x89PNG") || 
                                                  Str::startsWith($tempContent, "\xFF\xD8\xFF") || // JPEG
                                                  Str::startsWith($tempContent, "RIFF"); // WebP
                                                  
                                        if ($isImage) {
                                            $imageContent = $tempContent;
                                            $finalUrl = $tryUrl;
                                            break;
                                        }
                                    }
                                    
                                    if ($imageContent) {
                                        $filename = 'result_' . time() . '_' . Str::random(10) . '.png';
                                        $resultPath = 'vton/results/' . $filename;
                                        Storage::disk('public')->put($resultPath, $imageContent);
                                        Log::channel('vton')->info("Successfully downloaded image from {$finalUrl}");
                                        return $resultPath;
                                    } else {
                                        Log::channel('vton')->error("Download failed: All URL patterns returned non-image data (HTML/Error).");
                                    }
                                } catch (\Exception $dlE) {
                                    Log::channel('vton')->warning("Download failed from {$resUrl}: " . $dlE->getMessage());
                                }
                            }
                        }
                        
                        if (Str::contains($body, 'event: error') || Str::contains($body, '"error"')) {
                            Log::channel('vton')->warning("VTON Space ({$type}) Error: " . $body);
                            break; 
                        }
                        
                        sleep(5);
                    }
                }
            } catch (\Exception $e) {
                Log::channel('vton')->error("VTON Space ({$type}) Exception: " . $e->getMessage());
            }
        }

        return null;
    }

    private function uploadToGradio($baseUrl, $base64Data, $filename, $headers = [])
    {
        try {
            // Extract pure base64
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            }
            $binaryData = base64_decode($base64Data);
            
            $response = Http::withHeaders($headers)
                ->attach('files', $binaryData, $filename)
                ->post(rtrim($baseUrl, '/') . '/upload');

            if ($response->successful()) {
                $files = $response->json();
                Log::channel('vton')->info("Gradio Upload Success to {$baseUrl}. Files: " . json_encode($files));
                return $files[0] ?? null;
            }
            
            Log::channel('vton')->warning("Gradio Upload Failed to {$baseUrl}: " . $response->status() . " - " . $response->body());
        } catch (\Exception $e) {
            Log::channel('vton')->warning("Gradio Upload Exception to {$baseUrl}: " . $e->getMessage());
        }
        return null;
    }
}
