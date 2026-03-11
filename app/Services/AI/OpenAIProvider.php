<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIProvider implements AIProviderInterface
{
    private string $apiKey;
    private string $model;

    public function __construct(string $apiKey, string $model = 'gpt-3.5-turbo')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
    }

    public function generateResponse(string $prompt, array $options = []): ?string
    {
        try {
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
                'timeout' => $options['timeout'] ?? 30,
            ])->withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $options['instruction'] ?? 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $options['temperature'] ?? 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error("OpenAI API Error ({$this->model}): ".$response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("OpenAI Provider Exception ({$this->model}): ".$e->getMessage());
            return null;
        }
    }

    public function getName(): string
    {
        return "OpenAI ({$this->model})";
    }
}
