<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiProvider implements AIProviderInterface
{
    private string $apiKey;
    private string $model;
    private string $version;

    public function __construct(string $apiKey, string $model = 'gemini-1.5-flash-latest', string $version = 'v1beta')
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->version = $version;
    }

    public function generateResponse(string $prompt, array $options = []): ?string
    {
        try {
            $url = "https://generativelanguage.googleapis.com/{$this->version}/models/{$this->model}:generateContent?key={$this->apiKey}";

            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
                'timeout' => $options['timeout'] ?? 30,
            ])->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            Log::error("Gemini API Error ({$this->model}): ".$response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Gemini Provider Exception ({$this->model}): ".$e->getMessage());
            return null;
        }
    }

    public function getName(): string
    {
        return "Gemini ({$this->model})";
    }
}
