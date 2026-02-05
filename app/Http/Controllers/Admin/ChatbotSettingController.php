<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatbotSettingController extends Controller
{
    public function index()
    {
        $settings = ChatbotSetting::all()->pluck('value', 'key');
        $questions = \App\Models\ChatbotSuggestedQuestion::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.settings.chatbot', compact('settings', 'questions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'chatbot_enabled' => 'nullable|in:1,0',
            'chatbot_mode' => 'required|in:rules,ai',
            'ai_provider' => 'required|in:gemini,openai',
            'greeting_message' => 'required|string',
            'fallback_message' => 'required|string',
            'hotline' => 'required|string',
            'email' => 'required|email',
            'system_instruction' => 'required|string',
            'gemini_api_key' => 'nullable|string',
            'openai_api_key' => 'nullable|string',
            'keyword_rules' => 'nullable|string',
        ]);

        $data = $request->except('_token');
        
        // Handle checkbox (if not checked, Laravel request doesn't include it)
        $data['chatbot_enabled'] = $request->has('chatbot_enabled') ? '1' : '0';

        foreach ($data as $key => $value) {
            ChatbotSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
            Cache::forget("chatbot_setting_{$key}");
            
            // Special case for enabled status which might be used in providers
            if ($key === 'chatbot_enabled') {
                 Cache::forget('chatbot_enabled'); 
            }
        }

        return redirect()->back()->with('success', 'Cập nhật cấu hình Chatbot thành công!');
    }

    public function testConnection(Request $request)
    {
        $provider = $request->input('ai_provider');
        $apiKey = $request->input('api_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập API Key trước khi kiểm tra!'
            ]);
        }

        if ($provider === 'gemini') {
            return $this->testGemini($apiKey);
        } elseif ($provider === 'openai') {
            return $this->testOpenAI($apiKey);
        }

        return response()->json(['success' => false, 'message' => 'Nhà cung cấp không hợp lệ!']);
    }

    private function testGemini($apiKey)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::withOptions([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
                'timeout' => 30,
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => 'Hello']]]]
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Kết nối Gemini thành công! ✅']);
            }

            $error = $response->json()['error']['message'] ?? 'Lỗi không xác định';
            return response()->json(['success' => false, 'message' => "Gemini: {$error} (Mã: {$response->status()})"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi Gemini: ' . $e->getMessage()]);
        }
    }

    private function testOpenAI($apiKey)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::withOptions([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
                'timeout' => 30,
            ])->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post("https://api.openai.com/v1/chat/completions", [
                'model' => 'gpt-3.5-turbo',
                'messages' => [['role' => 'user', 'content' => 'Hello']],
                'max_tokens' => 20
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Kết nối OpenAI thành công! ✅']);
            }

            $error = $response->json()['error']['message'] ?? 'Lỗi không xác định';
            return response()->json(['success' => false, 'message' => "OpenAI: {$error} (Mã: {$response->status()})"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi OpenAI: ' . $e->getMessage()]);
        }
    }
}
