<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Log;

class ChatbotSettingController extends Controller
{
    public function index()
    {
        $chatbotSettings = ChatbotSetting::all()->pluck('value', 'key');
        $questions = \App\Models\ChatbotSuggestedQuestion::orderBy('order')->orderBy('created_at', 'desc')->get();

        return view('admin.settings.chatbot', compact('chatbotSettings', 'questions'));
    }

    public function update(\App\Http\Requests\Generated\ChatbotSettingRequest $request)
    {
        $data = $request->validated();

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

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập API Key trước khi kiểm tra!',
            ]);
        }

        if ($provider === 'gemini') {
            return $this->testGemini($apiKey);
        }

        return response()->json(['success' => false, 'message' => 'Nhà cung cấp không hợp lệ!']);
    }

    private function testGemini($apiKey)
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = \Illuminate\Support\Facades\Http::withOptions([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
                'timeout' => 30,
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => 'Hello']]]],
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Kết nối Gemini thành công! ✅']);
            }

            $error = $response->json()['error']['message'] ?? 'Lỗi không xác định';

            return response()->json(['success' => false, 'message' => "Gemini: {$error} (Mã: {$response->status()})"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi Gemini: '.$e->getMessage()]);
        }
    }

}
