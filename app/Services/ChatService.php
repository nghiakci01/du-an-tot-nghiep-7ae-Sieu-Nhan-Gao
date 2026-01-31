<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\ChatbotSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChatService
{
    /**
     * Get setting value by key with cache
     */
    private function getSetting(string $key, $default = null)
    {
        return Cache::remember("chatbot_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = ChatbotSetting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Rule-based or AI chatbot based on settings
     */
    public function generateResponse(string $userMessage): array
    {
        $mode = $this->getSetting('chatbot_mode', 'rules');
        $message = trim($userMessage);

        if ($mode === 'gemini') {
            return $this->generateGeminiResponse($message);
        }

        $messageLower = strtolower($message);
        
        // 1. Greeting patterns
        if ($this->matchesPattern($messageLower, ['xin chào', 'chào', 'hello', 'hi', 'hey'])) {
            return $this->textResponse($this->getGreeting());
        }
        
        // 2. Product search patterns
        if ($this->matchesPattern($messageLower, ['có', 'bán', 'tìm', 'search', 'find'])) {
            return $this->searchProducts($messageLower);
        }
        
        // 3. Price inquiry patterns
        if ($this->matchesPattern($messageLower, ['giá', 'bao nhiêu', 'price', 'cost', 'tiền'])) {
            return $this->getPriceInfo($messageLower);
        }
        
        // 4. Category inquiry
        if ($this->matchesPattern($messageLower, ['loại', 'danh mục', 'category', 'phân loại'])) {
            return $this->textResponse($this->getCategoryInfo());
        }
        
        // 5. Help/Support patterns
        if ($this->matchesPattern($messageLower, ['giúp', 'help', 'hỗ trợ', 'support', 'tư vấn'])) {
            return $this->textResponse($this->getHelp());
        }
        
        // 6. Thank you patterns
        if ($this->matchesPattern($messageLower, ['cảm ơn', 'thanks', 'thank you', 'cám ơn'])) {
            return $this->textResponse("Rất vui được hỗ trợ bạn! 😊 Nếu cần thêm thông tin gì, đừng ngần ngại hỏi nhé!");
        }
        
        // 7. Goodbye patterns
        if ($this->matchesPattern($messageLower, ['tạm biệt', 'bye', 'goodbye', 'hẹn gặp lại'])) {
            return $this->textResponse("Tạm biệt! Chúc bạn một ngày tốt lành! 👋 Hẹn gặp lại bạn sớm!");
        }
        
        // 8. Try to extract product name and search
        $productResult = $this->intelligentProductSearch($messageLower);
        if ($productResult) {
            return $productResult;
        }
        
        // 9. Default response with suggestions
        return $this->textResponse($this->getDefaultResponse());
    }

    /**
     * Gemini AI response based on settings
     */
    private function generateGeminiResponse(string $message): array
    {
        $apiKey = $this->getSetting('gemini_api_key');

        if (!$apiKey) {
            return $this->textResponse("Chưa cấu hình Gemini API Key. Vui lòng liên hệ Admin! 🤖");
        }

        try {
            $context = $this->getAiContext();
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $context . "\n\nUser: " . $message]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $responseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Tôi không nhận được phản hồi từ AI. 😅";
                return $this->textResponse($responseText);
            }

            Log::error("Gemini API Error: " . $response->body());
            return $this->textResponse("Có lỗi khi kết nối với AI. Vui lòng thử lại sau! 🤖");

        } catch (\Exception $e) {
            Log::error("Gemini Service Exception: " . $e->getMessage());
            return $this->textResponse("Hệ thống AI đang gặp sự cố nhỏ. 😅");
        }
    }

    /**
     * Get context for AI (Product list, Shop info, etc.)
     */
    private function getAiContext(): string
    {
        $categories = Category::pluck('name')->toArray();
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        $email = $this->getSetting('email', 'support@electronicsstore.com');
        
        $context = "Bạn là trợ lý ảo hỗ trợ khách hàng của Electronics Store.\n";
        $context .= "Thông tin cửa hàng:\n";
        $context .= "- Hotline: {$hotline}\n";
        $context .= "- Email: {$email}\n";
        $context .= "- Danh mục sản phẩm: " . implode(', ', $categories) . ".\n\n";
        $context .= "Hãy trả lời khách hàng một cách thân thiện, chuyên nghiệp và ngắn gọn bằng tiếng Việt. Nếu khách hàng hỏi về sản phẩm không có trong danh mục, hãy hướng dẫn họ liên hệ hotline.";
        
        return $context;
    }
    
    /**
     * Format text-only response
     */
    private function textResponse(string $message): array
    {
        return [
            'type' => 'text',
            'message' => $message,
            'products' => []
        ];
    }
    
    /**
     * Format product response with cards
     */
    private function productResponse(string $message, $products): array
    {
        $productCards = [];
        
        foreach ($products as $product) {
            $productCards[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'price_formatted' => number_format($product->price) . ' VND',
                'image' => $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/images/no-image.png'),
                'url' => route('product.detail', $product->slug ?? $product->id),
                'description' => mb_substr($product->description ?? '', 0, 100)
            ];
        }
        
        return [
            'type' => 'products',
            'message' => $message,
            'products' => $productCards
        ];
    }
    
    /**
     * Check if message matches any pattern
     */
    private function matchesPattern(string $message, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (str_contains($message, $pattern)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Greeting response
     */
    private function getGreeting(): string
    {
        return $this->getSetting('greeting_message', "Xin chào! 👋 Tôi là trợ lý ảo của Electronics Store. Tôi có thể giúp bạn tìm kiếm sản phẩm, tư vấn giá cả. Bạn đang tìm gì?");
    }
    
    /**
     * Search products based on message
     */
    private function searchProducts(string $message): array
    {
        $keywords = $this->extractKeywords($message);
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        
        if (empty($keywords)) {
            return $this->textResponse(
                "Bạn đang tìm sản phẩm gì? Vui lòng cho tôi biết cụ thể hơn nhé! 😊\n\n" .
                "Ví dụ: 'Có laptop không?', 'Tìm điện thoại iPhone', 'Bán tai nghe không?'"
            );
        }
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%");
                }
            })
            ->limit(6)
            ->get();
        
        if ($products->isEmpty()) {
            return $this->textResponse(
                "Xin lỗi, tôi không tìm thấy sản phẩm '{$keywords[0]}' trong kho. 😔\n\n" .
                "Bạn có thể:\n" .
                "• Thử tìm sản phẩm khác\n" .
                "• Xem danh mục sản phẩm (gõ 'danh mục')\n" .
                "• Liên hệ hotline: {$hotline}"
            );
        }
        
        $message = "Tôi tìm thấy " . $products->count() . " sản phẩm phù hợp. Click vào sản phẩm để xem chi tiết:";
        
        return $this->productResponse($message, $products);
    }
    
    /**
     * Get price information
     */
    private function getPriceInfo(string $message): array
    {
        $keywords = $this->extractKeywords($message);
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        
        if (empty($keywords)) {
            return $this->textResponse(
                "Bạn muốn biết giá sản phẩm nào? Vui lòng cho tôi biết tên sản phẩm nhé!\n\n" .
                "Ví dụ: 'Giá iPhone 13 bao nhiêu?', 'Laptop Dell giá bao nhiêu?'"
            );
        }
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'LIKE', "%{$keyword}%");
                }
            })
            ->limit(3)
            ->get();
        
        if ($products->isEmpty()) {
            return $this->textResponse(
                "Xin lỗi, tôi không tìm thấy thông tin giá cho '{$keywords[0]}'. 😔\n\n" .
                "Bạn có thể thử tìm sản phẩm khác hoặc liên hệ hotline: {$hotline}"
            );
        }
        
        $message = "Đây là thông tin giá sản phẩm bạn cần:";
        
        return $this->productResponse($message, $products);
    }
    
    /**
     * Get category information
     */
    private function getCategoryInfo(): string
    {
        $categories = Category::withCount('products')->get();
        
        if ($categories->isEmpty()) {
            return "Hiện tại chưa có danh mục sản phẩm nào. Vui lòng quay lại sau!";
        }
        
        $response = "📂 **Danh mục sản phẩm của chúng tôi:**\n\n";
        
        foreach ($categories as $index => $category) {
            $count = $category->products_count ?? 0;
            $response .= ($index + 1) . ". {$category->name} ({$count} sản phẩm)\n";
        }
        
        $response .= "\nBạn muốn xem sản phẩm ở danh mục nào?";
        
        return $response;
    }
    
    /**
     * Get help information
     */
    private function getHelp(): string
    {
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        $email = $this->getSetting('email', 'support@electronicsstore.com');

        return "🤖 **Tôi có thể giúp bạn:**\n\n" .
               "1️⃣ Tìm kiếm sản phẩm\n" .
               "   VD: 'Có laptop không?', 'Tìm iPhone'\n\n" .
               "2️⃣ Hỏi về giá\n" .
               "   VD: 'Giá MacBook bao nhiêu?'\n\n" .
               "3️⃣ Xem danh mục\n" .
               "   VD: 'Danh mục sản phẩm', 'Có những loại nào?'\n\n" .
               "4️⃣ Tư vấn sản phẩm\n" .
               "   VD: 'Tư vấn laptop cho sinh viên'\n\n" .
               "📞 Hotline: {$hotline}\n" .
               "📧 Email: {$email}";
    }
    
    /**
     * Intelligent product search
     */
    private function intelligentProductSearch(string $message): ?array
    {
        $words = explode(' ', $message);
        $words = array_filter($words, function($word) {
            return strlen($word) > 2;
        });
        
        if (empty($words)) {
            return null;
        }
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('name', 'LIKE', "%{$word}%");
                }
            })
            ->limit(4)
            ->get();
        
        if ($products->isEmpty()) {
            return null;
        }
        
        $message = "Có thể bạn đang tìm những sản phẩm này:";
        
        return $this->productResponse($message, $products);
    }
    
    /**
     * Extract keywords from message
     */
    private function extractKeywords(string $message): array
    {
        $stopWords = ['có', 'không', 'bán', 'tìm', 'giá', 'bao', 'nhiêu', 'của', 'là', 'về', 'cho', 'và', 'được'];
        
        $words = explode(' ', $message);
        $keywords = [];
        
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 2 && !in_array($word, $stopWords)) {
                $keywords[] = $word;
            }
        }
        
        return $keywords;
    }
    
    /**
     * Default response
     */
    private function getDefaultResponse(): string
    {
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        $fallback = $this->getSetting('fallback_message', "Xin lỗi, tôi chưa hiểu rõ câu hỏi của bạn. 😅\n\nBạn có thể:\n• Hỏi về sản phẩm cụ thể (VD: 'Có iPhone không?')\n• Hỏi về giá (VD: 'Giá laptop bao nhiêu?')\n• Xem danh mục (gõ 'danh mục')\n• Nhận trợ giúp (gõ 'help')\n\nHoặc liên hệ hotline: {hotline} để được hỗ trợ trực tiếp!");

        return str_replace('{hotline}', $hotline, $fallback);
    }
}
