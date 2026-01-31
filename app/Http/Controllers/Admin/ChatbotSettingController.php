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
        return view('admin.settings.chatbot', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'chatbot_mode' => 'required|in:rules,gemini',
            'greeting_message' => 'required|string',
            'fallback_message' => 'required|string',
            'hotline' => 'required|string',
            'email' => 'required|email',
            'gemini_api_key' => 'nullable|string',
        ]);

        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            ChatbotSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget("chatbot_setting_{$key}");
        }

        return redirect()->back()->with('success', 'Cập nhật cấu hình Chatbot thành công!');
    }

    public function testConnection(Request $request)
    {
        $apiKey = $request->input('gemini_api_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập API Key trước khi kiểm tra!'
            ]);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Hello, respond with "OK" if you are working.']
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kết nối thành công! Gemini AI đang hoạt động tốt. ✅'
                ]);
            }

            $error = $response->json()['error']['message'] ?? 'Lỗi không xác định';
            $status = $response->status();
            
            $suggestion = "";
            if ($status == 400 && str_contains($error, 'API key not valid')) {
                $suggestion = " (API Key không hợp lệ)";
            } elseif ($status == 429) {
                $suggestion = " (Quá hạn mức hoặc hết credit)";
            }

            return response()->json([
                'success' => false,
                'message' => "Kết nối thất bại: {$error}{$suggestion} ❌ (Mã lỗi: {$status})"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối hệ thống: ' . $e->getMessage()
            ]);
        }
    }
}
