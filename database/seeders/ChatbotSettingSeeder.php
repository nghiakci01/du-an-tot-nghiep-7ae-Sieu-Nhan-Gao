<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatbotSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'chatbot_mode' => 'rules',
            'greeting_message' => 'Xin chào! 👋 Tôi là trợ lý ảo của Electronics Store. Tôi có thể giúp bạn tìm kiếm sản phẩm, tư vấn giá cả. Bạn đang tìm gì?',
            'fallback_message' => "Xin lỗi, tôi chưa hiểu rõ câu hỏi của bạn. 😅\n\nBạn có thể:\n• Hỏi về sản phẩm cụ thể (VD: 'Có iPhone không?')\n• Hỏi về giá (VD: 'Giá laptop bao nhiêu?')\n• Xem danh mục (gõ 'danh mục')\n• Nhận trợ giúp (gõ 'help')\n\nHoặc liên hệ hotline: {hotline} để được hỗ trợ trực tiếp!",
            'hotline' => '1900-xxxx',
            'email' => 'support@electronicsstore.com',
            'gemini_api_key' => '',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\ChatbotSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
