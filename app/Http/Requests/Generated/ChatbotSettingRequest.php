<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class ChatbotSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'chatbot_enabled' => 'nullable|in:1,0',
            'chatbot_mode' => 'required|in:rules,ai',
            'ai_provider' => 'required_if:chatbot_mode,ai|in:gemini',
            'greeting_message' => 'required|string',
            'fallback_message' => 'required|string',
            'hotline' => 'required|string',
            'email' => 'required|email',
            'system_instruction' => 'required_if:chatbot_mode,ai|nullable|string',
            'gemini_api_key' => 'nullable|string',
            'openai_api_key' => 'nullable|string',
            'keyword_rules' => 'nullable|string',
        ];
    }
}
