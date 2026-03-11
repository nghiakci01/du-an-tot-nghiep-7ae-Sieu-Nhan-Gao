<?php

namespace App\Services\AI;

class AIFactory
{
    /**
     * Create an AI provider instance
     * 
     * @param string $provider
     * @param string $apiKey
     * @param string $model
     * @return AIProviderInterface
     * @throws \Exception
     */
    public static function make(string $provider, string $apiKey, string $model): AIProviderInterface
    {
        switch ($provider) {
            case 'gemini':
                return new GeminiProvider($apiKey, $model);
            case 'openai':
                return new OpenAIProvider($apiKey, $model);
            default:
                throw new \Exception("Unsupported AI provider: {$provider}");
        }
    }
}
