<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestGeminiDirect extends Command
{
    protected $signature = 'test:gemini-direct';

    protected $description = 'Test Gemini API directly';

    public function handle()
    {
        $apiKey = config('services.gemini.key');

        $this->info('Testing Gemini API...');
        $this->info('API Key: '.substr($apiKey, 0, 10).'...');
        $this->newLine();

        // Test different API versions and models
        $tests = [
            [
                'name' => 'v1 with gemini-1.5-flash',
                'url' => 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent',
            ],
            [
                'name' => 'v1 with gemini-1.5-pro',
                'url' => 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent',
            ],
            [
                'name' => 'v1beta with gemini-pro',
                'url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent',
            ],
        ];

        foreach ($tests as $test) {
            $this->info("Testing: {$test['name']}");

            try {
                $response = Http::timeout(10)
                    ->post($test['url'].'?key='.$apiKey, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => 'Say hello in Vietnamese'],
                                ],
                            ],
                        ],
                    ]);

                $statusCode = $response->status();
                $this->line("Status: $statusCode");

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                        $this->line("✅ SUCCESS: $reply");
                    } else {
                        $this->error('❌ Unexpected response format');
                    }
                } else {
                    $error = $response->json();
                    $this->error('❌ FAILED: '.($error['error']['message'] ?? 'Unknown error'));
                }

            } catch (\Exception $e) {
                $this->error('❌ EXCEPTION: '.$e->getMessage());
            }

            $this->newLine();
        }

        return Command::SUCCESS;
    }
}
