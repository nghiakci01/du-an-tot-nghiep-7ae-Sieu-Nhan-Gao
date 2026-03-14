<?php

namespace App\Jobs;

use App\Models\VtonHistory;
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

    /**
     * Create a new job instance.
     */
    public function __construct($vtonHistoryId, $humanBase64, $productBase64, $category)
    {
        $this->vtonHistoryId = $vtonHistoryId;
        $this->humanBase64 = $humanBase64;
        $this->productBase64 = $productBase64;
        $this->category = $category;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $history = VtonHistory::find($this->vtonHistoryId);
        if (!$history) return;

        $history->update(['status' => 'processing']);
        Log::channel('vton')->info("Processing VTON Job for History ID: {$this->vtonHistoryId}");

        try {
            $resultUrl = $this->attemptTryOn($this->humanBase64, $this->productBase64, $this->category);

            if ($resultUrl) {
                $filename = 'result_' . time() . '_' . Str::random(10) . '.png';
                $resultPath = 'vton/results/' . $filename;

                $imageContent = file_get_contents($resultUrl);
                if ($imageContent) {
                    Storage::disk('public')->put($resultPath, $imageContent);
                    $history->update([
                        'result_image' => $resultPath,
                        'status' => 'completed'
                    ]);
                    Log::channel('vton')->info("VTON Job Completed for History ID: {$this->vtonHistoryId}");
                    return;
                }
            }
        } catch (\Exception $e) {
            Log::channel('vton')->error("VTON Job Exception for History ID: {$this->vtonHistoryId} - " . $e->getMessage());
        }

        $history->update(['status' => 'failed']);
        Log::channel('vton')->error("VTON Job Failed for History ID: {$this->vtonHistoryId}");
    }

    private function attemptTryOn($humanBase64, $productBase64, $category)
    {
        $spaces = [
            [
                'url' => 'https://levihsu-ootdiffusion.hf.space/call/process_dc',
                'payload' => [
                    'data' => [
                        ['url' => $humanBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        ['url' => $productBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        $category, 1, 20, 2.0, -1
                    ]
                ]
            ],
            [
                'url' => 'https://yisol-idm-vton.hf.space/call/tryon',
                'payload' => [
                    'data' => [
                        ['url' => $humanBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        ['url' => $productBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        "Fashion garment", true, true, 30, 42
                    ]
                ]
            ]
        ];

        foreach ($spaces as $space) {
            try {
                $response = Http::timeout(60)->post($space['url'], $space['payload']);
                $eventId = $response->json('event_id');

                if ($eventId) {
                    Log::channel('vton')->info("Polling VTON Space: " . $space['url'] . " Event: " . $eventId);
                    $startTime = time();
                    while ((time() - $startTime) < 150) { // Max 150 seconds
                        $statusRes = Http::timeout(60)->get($space['url'] . '/' . $eventId);
                        $body = $statusRes->body();
                        
                        if (Str::contains($body, 'event: complete')) {
                            if (preg_match('/"url":\s*"([^"]+)"/', $body, $matches)) {
                                $resUrl = $matches[1];
                                if (Str::startsWith($resUrl, '/')) {
                                    $resUrl = rtrim(dirname(dirname($space['url'])), '/') . $resUrl;
                                }
                                return $resUrl;
                            }
                        }
                        
                        if (Str::contains($body, 'event: error') || Str::contains($body, '"error"')) {
                            Log::channel('vton')->warning("VTON Space returned error: " . $body);
                            break;
                        }
                        
                        sleep(3);
                    }
                }
            } catch (\Exception $e) {
                Log::channel('vton')->warning("VTON Space Failed in Job: " . $space['url'] . " - " . $e->getMessage());
            }
        }

        return null;
    }
}
