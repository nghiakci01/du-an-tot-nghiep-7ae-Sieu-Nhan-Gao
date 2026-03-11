<?php

namespace App\Services\AI;

interface AIProviderInterface
{
    /**
     * Generate response from AI model
     * 
     * @param string $prompt
     * @param array $options
     * @return string|null
     */
    public function generateResponse(string $prompt, array $options = []): ?string;
    
    /**
     * Get provider name
     * 
     * @return string
     */
    public function getName(): string;
}
