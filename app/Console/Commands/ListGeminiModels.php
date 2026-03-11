<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ListGeminiModels extends Command
{
    protected $signature = 'test:list-models';

    protected $description = 'List available Gemini models';

    public function handle()
    {
        $apiKey = config('services.gemini.key');

        $this->info('Listing available Gemini models...');
        $this->info('API Key: '.substr($apiKey, 0, 15).'...');
        $this->newLine();
        try {
            // Try to list models
            $response = Http::timeout(10)
                ->get('https://generativelanguage.googleapis.com/v1/models?key='.$apiKey);

            $this->line('Status Code: '.$response->status());
            $this->newLine();

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['models'])) {
                    $this->info('✅ Available Models:');
                    foreach ($data['models'] as $model) {
                        $name = $model['name'] ?? 'Unknown';
                        $displayName = $model['displayName'] ?? '';
                        $this->line("  - $name ($displayName)");

                        if (isset($model['supportedGenerationMethods'])) {
                            $methods = implode(', ', $model['supportedGenerationMethods']);
                            $this->line("    Methods: $methods");
                        }
                    }
                } else {
                    $this->warn('No models found in response');
                    $this->line(json_encode($data, JSON_PRETTY_PRINT));
                }
            } else {
                $error = $response->json();
                $this->error('❌ API Error:');
                $this->line(json_encode($error, JSON_PRETTY_PRINT));
            }

        } catch (\Exception $e) {
            $this->error('❌ Exception: '.$e->getMessage());
        }

        return Command::SUCCESS;
    }
}
