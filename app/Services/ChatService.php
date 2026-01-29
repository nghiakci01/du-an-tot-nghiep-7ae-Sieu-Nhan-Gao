<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    /**
     * Tìm kiếm thông tin sản phẩm liên quan đến câu hỏi.
     */
    public function searchContext(string $query): string
    {
        // 1. Tách từ khóa đơn giản (Nếu cần)
        // Hiện tại dùng LIKE search đơn giản
        // Ví dụ: tìm "iphone" trong tên hoặc mô tả
        
        $products = Product::query()
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                // Tách query thành các từ khóa để search 'tương đối' chính xác hơn
                // Tuy nhiên với LIKE %, ta search nguyên cụm trước
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {
            // Thử search nới lỏng hơn? (Optional)
            return "";
        }

        // Format dữ liệu thành text để gửi cho AI
        $context = "Thông tin sản phẩm có sẵn:\n";
        foreach ($products as $product) {
            $price = number_format($product->price);
            $context .= "- Tên: {$product->name} | Giá: {$price} VND | Mô tả: {$product->description}\n";
        }

        return $context;
    }

    /**
     * Gửi câu hỏi + context lên OpenAI
     */
    public function generateResponse(string $userMessage): string
    {
        if (!$this->apiKey) {
            return "Lỗi: Chưa cấu hình OpenAI API Key.";
        }

        // 1. Lấy context
        $context = $this->searchContext($userMessage);

        // 2. Tạo System Prompt
        $systemPrompt = "Bạn là trợ lý ảo hỗ trợ bán hàng của cửa hàng Electronics Store. " .
            "Hãy trả lời câu hỏi của khách hàng một cách thân thiện, ngắn gọn bằng tiếng Việt. " .
            "Dưới đây là một số thông tin sản phẩm (Context) thực tế từ cửa hàng. " .
            "Nếu thông tin có trong Context, hãy dùng nó để trả lời chính xác về giá và sản phẩm. " .
            "Nếu trong Context không có, hãy nói khéo là bạn không tìm thấy thông tin và đề nghị khách liên hệ hotline." .
            "\n\nCONTEXT:\n" . ($context ?: "Không tìm thấy sản phẩm cụ thể liên quan.");

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post($this->baseUrl, [
                    'model' => 'gpt-3.5-turbo', // Hoặc gpt-4o-mini
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? 'Xin lỗi, tôi không thể trả lời ngay lúc này.';
            } else {
                Log::error("OpenAI API Error: " . $response->body());
                return "Hệ thống đang bận, vui lòng thử lại sau.";
            }
        } catch (\Exception $e) {
            Log::error("ChatService Exception: " . $e->getMessage());
            return "Đã xảy ra lỗi kết nối.";
        }
    }
}
