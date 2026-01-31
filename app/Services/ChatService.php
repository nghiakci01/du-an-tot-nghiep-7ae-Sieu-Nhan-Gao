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

    public function generateResponse(string $message): array
    {
        $mode = $this->getSetting('chatbot_mode', 'rules');

        if ($mode === 'rules') {
            return $this->generateRuleResponse($message);
        }

        // Handle legacy 'gemini' mode as 'ai'
        $provider = $this->getSetting('ai_provider', 'gemini');

        if ($provider === 'openai') {
            return $this->generateOpenAIResponse($message);
        }

        return $this->generateGeminiResponse($message);
    }

    /**
     * Rule-based response logic (existing logic moved here)
     */
    private function generateRuleResponse(string $message): array
    {
        $messageLower = mb_strtolower($message);
        
        // 1. Check for greeting
        if ($this->isMatch($messageLower, ['chào', 'hello', 'hi', 'xin chào'])) {
            return $this->textResponse($this->getSetting('greeting_message', 'Xin chào! Tôi có thể giúp gì cho bạn?'));
        }
        
        // 2. Check for help
        if ($this->isMatch($messageLower, ['help', 'trợ giúp', 'hỗ trợ', 'hướng dẫn'])) {
            return $this->textResponse("Tôi có thể giúp bạn:\n• Tìm sản phẩm (VD: 'Tìm iPhone')\n• Xem danh mục (VD: 'Danh mục')\n• Thông tin liên hệ (VD: 'Hotline')");
        }
        
        // 3. Check for contact info
        if ($this->isMatch($messageLower, ['hotline', 'liên hệ', 'số điện thoại', 'sđt', 'email'])) {
            $hotline = $this->getSetting('hotline', '1900-xxxx');
            $email = $this->getSetting('email', 'support@electronicsstore.com');
            return $this->textResponse("Bạn có thể liên hệ với chúng tôi qua:\n📞 Hotline: {$hotline}\n📧 Email: {$email}");
        }
        
        // 4. Check for category list
        if ($this->isMatch($messageLower, ['danh mục', 'loại sản phẩm', 'có những gì'])) {
            $categories = Category::pluck('name')->toArray();
            return $this->textResponse("Hiện tại chúng tôi có các danh mục sản phẩm: " . implode(', ', $categories));
        }
        
        // 5. Check for gratitude
        if ($this->isMatch($messageLower, ['cảm ơn', 'thanks', 'thank you', 'tks'])) {
            return $this->textResponse("Không có chi! Rất vui được hỗ trợ bạn. 😊");
        }

        // 6. Check for simple closing
        if ($this->isMatch($messageLower, ['tạm biệt', 'bye', 'hẹn gặp lại'])) {
            return $this->textResponse("Tạm biệt! Chúc bạn một ngày tốt lành! 👋 Hẹn gặp lại bạn sớm!");
        }
        
        // 8. Try to extract product name and search
        $productResult = $this->intelligentProductSearch($messageLower);
        if ($productResult) {
            return $productResult;
        }
        
        // 9. Default response with suggestions (Fallback)
        $fallback = $this->getSetting('fallback_message', 'Tôi chưa hiểu rõ yêu cầu của bạn. Vui lòng liên hệ hotline {hotline} để được hỗ trợ!');
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        $fallback = str_replace('{hotline}', $hotline, $fallback);
        
        return $this->textResponse($fallback);
    }

    /**
     * Gemini AI response based on settings
     */
    private function generateGeminiResponse(string $message): array
    {
        // Prioritize finding a key explicitly for Gemini, or fallback to generic AI key
        $apiKey = $this->getSetting('gemini_api_key');

        if (!$apiKey) {
            return $this->textResponse("Chưa cấu hình Gemini API Key. Vui lòng liên hệ Admin! 🤖");
        }

        try {
            $context = $this->getAiContext($message);
            $fullPrompt = $context . "\n\nUser Question: " . $message;
            
            // Updated to use 'gemini-flash-latest' and 'v1beta' based on successful diagnostics
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

            $response = \Illuminate\Support\Facades\Http::withOptions([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
                'timeout' => 30,
            ])->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $responseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Tôi không nhận được phản hồi từ AI. 😅";
                return $this->textResponse($responseText);
            }

            Log::error("Gemini API Error: " . $response->body());
            return $this->textResponse("Có lỗi khi kết nối với AI (Gemini). Vui lòng thử lại sau! 🤖");

        } catch (\Exception $e) {
            Log::error("Gemini Service Exception: " . $e->getMessage());
            return $this->textResponse("Hệ thống AI đang gặp sự cố nhỏ. 😅");
        }
    }

    private function generateOpenAIResponse(string $message): array
    {
        $apiKey = $this->getSetting('openai_api_key');

        if (!$apiKey) {
            return $this->textResponse("Chưa cấu hình OpenAI API Key. Vui lòng liên hệ Admin! 🤖");
        }

        try {
            $context = $this->getAiContext($message);
            
            $response = \Illuminate\Support\Facades\Http::withOptions([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
                'timeout' => 30,
            ])->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post("https://api.openai.com/v1/responses", [
                'model' => 'gpt-3.5-turbo',
                'instructions' => $context,
                'input' => $message,
                'temperature' => 0.7,
                'max_output_tokens' => 800,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // v1/responses structure: output[0].content[0].text
                $responseText = $data['output'][0]['content'][0]['text'] ?? "Tôi không nhận được phản hồi từ OpenAI. 😅";
                return $this->textResponse($responseText);
            }

            Log::error("OpenAI API Error: " . $response->body());
            return $this->textResponse("Có lỗi khi kết nối với AI (OpenAI). Vui lòng thử lại sau! 🤖");

        } catch (\Exception $e) {
            Log::error("OpenAI Service Exception: " . $e->getMessage());
            return $this->textResponse("Hệ thống AI OpenAI đang gặp sự cố nhỏ. 😅");
        }
    }

    /**
     * Get context for AI (Product list, Shop info, etc.)
     */
    private function getAiContext(string $userMessage = ''): string
    {
        $categories = Category::pluck('name')->toArray();
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        $email = $this->getSetting('email', 'support@electronicsstore.com');
        
        // --- RAG: Intelligent Product Context ---
        $productContext = "";
        $keywords = $this->extractKeywords($userMessage);
        
        if (!empty($keywords)) {
            $products = Product::where('is_active', true)
                ->where(function($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->orWhere('name', 'LIKE', "%{$keyword}%");
                    }
                })
                ->limit(5)
                ->get();

            if ($products->isNotEmpty()) {
                $productContext = "DƯỚI ĐÂY LÀ DANH SÁCH SẢN PHẨM THỰC TẾ TRONG KHO (HÃY DÙNG ĐỂ TRẢ LỜI):\n";
                foreach ($products as $p) {
                    $price = number_format($p->price);
                    $productContext .= "- {$p->name} (Giá: {$price} VND, Tình trạng: " . ($p->quantity > 0 ? 'Còn hàng' : 'Hết hàng') . ")\n";
                }
            } else {
                $productContext = "Không tìm thấy sản phẩm nào khớp với từ khóa trong kho.";
            }
        }
        // ----------------------------------------

        $instruction = $this->getSetting('system_instruction', 
            "Bạn là nhân viên bán hàng thời trang chuyên nghiệp, thân thiện và ngắn gọn.\n" .
            "[QUY TẮC QUAN TRỌNG]:\n" .
            "1. Chỉ trả lời tối đa 3 câu.\n" .
            "2. Tập trung vào bán hàng, không chào hỏi dài dòng.\n" .
            "3. Nếu có thông tin sản phẩm ở trên, hãy dùng nó để tư vấn chính xác.\n" .
            "4. Giọng điệu hào hứng, dùng emoji phù hợp (👗, 👠, 🛍️).\n" .
            "[THÔNG TIN CỬA HÀNG]:\n" .
            "- Hotline: {hotline}\n" .
            "- Danh mục: {categories}"
        );
        
        // Thay thế các biến
        $instruction = str_replace('{hotline}', $hotline, $instruction);
        $instruction = str_replace('{email}', $email, $instruction);
        $instruction = str_replace('{categories}', implode(', ', $categories), $instruction);
        
        return $instruction . "\n\n" . $productContext;
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
