<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestGeminiCurl extends Command
{
    protected $signature = 'test:gemini-curl';

    protected $description = 'Test Gemini with cURL';

    public function handle()
    {
        $apiKey = config('services.gemini.key');

        $this->info('Testing with cURL...');
        $this->info('API Key: '.substr($apiKey, 0, 15).'...');
        $this->newLine();

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key='.$apiKey;

        $data = json_encode([
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Say hello in Vietnamese'],
                    ],
                ],
            ],
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $this->line("HTTP Code: $httpCode");

        if ($error) {
            $this->error("cURL Error: $error");
        }

        $this->newLine();
        $this->line('Response:');
        $this->line($response);

        $this->newLine();

        if ($httpCode == 200) {
            $decoded = json_decode($response, true);
            if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                $this->info('✅ SUCCESS!');
                $this->line('AI Response: '.$decoded['candidates'][0]['content']['parts'][0]['text']);
            }
        } else {
            $this->error('❌ FAILED - Check response above');
        }

        return Command::SUCCESS;
    }
}
