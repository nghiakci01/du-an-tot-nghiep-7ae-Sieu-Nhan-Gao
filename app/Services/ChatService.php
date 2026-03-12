<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatService
{
    /**
     * Get setting value by key with cache
     */
    private function getSetting(string $key, $default = null)
    {
        return Cache::remember("chatbot_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = DB::table('chatbot_settings')->where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    public function generateResponse(string $message): array
    {
        try {
            $mode = $this->getSetting('chatbot_mode', 'rules');

            // Treat empty value as 'rules'
            if (empty($mode) || $mode === 'rules') {
                return $this->generateRuleResponse($message);
            }

            // Handle AI modes
            return $this->generateAIResponse($message);
        } catch (\Exception $e) {
            Log::error('ChatService Exception: '.$e->getMessage());
            $mode = $this->getSetting('chatbot_mode', 'rules');

            if (empty($mode) || $mode === 'rules') {
                return $this->textResponse('Rất tiếc, hệ thống đang bận. Vui lòng thử lại sau một lát! 😊');
            }

            return $this->textResponse('Hệ thống AI đang gặp sự cố kỹ thuật. Vui lòng thử lại sau! 🤖');
        }
    }

    /**
     * Rule-based response logic (existing logic moved here)
     */
    private function generateRuleResponse(string $message): array
    {
        $messageLower = mb_strtolower(trim($message));

        // 0. Check for Suggested Questions (Exact Match Preference)
        $suggestedQuestion = \App\Models\ChatbotSuggestedQuestion::where('is_active', true)
            ->where('question', $message) // Check exact case first
            ->orWhere('question', $messageLower)
            ->first();

        if ($suggestedQuestion && ! empty($suggestedQuestion->answer)) {
            $parsed = $this->parseResponseTags($suggestedQuestion->answer, $suggestedQuestion->question);

            return [
                'message' => $parsed['text'],
                'products' => $parsed['products'],
                'type' => 'text',
            ];
        }

        // 0.5 Check for custom keyword rules from settings
        $customRulesJson = $this->getSetting('keyword_rules', '[]');
        $customRules = json_decode($customRulesJson, true);

        if (is_array($customRules)) {
            foreach ($customRules as $rule) {
                if (! empty($rule['keyword']) && ! empty($rule['response'])) {
                    // Split keywords by comma if multiple provided
                    $keywords = array_map(function ($kw) {
                        return mb_strtolower(trim($kw));
                    }, explode(',', $rule['keyword']));

                    if ($this->matchesPattern($messageLower, $keywords)) {
                        $parsed = $this->parseResponseTags($rule['response'], $keywords[0] ?? '');

                        return [
                            'message' => $parsed['text'],
                            'products' => $parsed['products'],
                            'type' => 'text',
                        ];
                    }
                }
            }
        }

        // 1. Check for greeting
        if ($this->matchesPattern($messageLower, ['chào', 'hello', 'hi', 'xin chào'])) {
            return $this->textResponse($this->getSetting('greeting_message', 'Xin chào! Tôi có thể giúp gì cho bạn?'));
        }

        // 2. Check for help
        if ($this->matchesPattern($messageLower, ['help', 'trợ giúp', 'hỗ trợ', 'hướng dẫn'])) {
            return $this->textResponse("Tôi có thể giúp bạn:\n• Tìm sản phẩm (VD: 'Tìm iPhone')\n• Xem danh mục (VD: 'Danh mục')\n• Thông tin liên hệ (VD: 'Hotline')");
        }

        // 3. Check for contact info
        if ($this->matchesPattern($messageLower, ['hotline', 'liên hệ', 'số điện thoại', 'sđt', 'email'])) {
            $hotline = $this->getSetting('hotline', '1900-xxxx');
            $email = $this->getSetting('email', 'support@electronicsstore.com');

            return $this->textResponse("Bạn có thể liên hệ với chúng tôi qua:\n📞 Hotline: {$hotline}\n📧 Email: {$email}");
        }

        // 4. Check for category list
        if ($this->matchesPattern($messageLower, ['danh mục', 'loại sản phẩm', 'có những gì'])) {
            $categories = Category::pluck('name')->toArray();

            return $this->textResponse('Hiện tại chúng tôi có các danh mục sản phẩm: '.implode(', ', $categories));
        }

        // 5. Check for gratitude
        if ($this->matchesPattern($messageLower, ['cảm ơn', 'thanks', 'thank you', 'tks'])) {
            return $this->textResponse('Không có chi! Rất vui được hỗ trợ bạn. 😊');
        }

        // 6. Check for simple closing
        if ($this->matchesPattern($messageLower, ['tạm biệt', 'bye', 'hẹn gặp lại'])) {
            return $this->textResponse('Tạm biệt! Chúc bạn một ngày tốt lành! 👋 Hẹn gặp lại bạn sớm!');
        }

        // 8. Try to extract product name and search
        $productResult = $this->intelligentProductSearch($messageLower);
        if ($productResult) {
            return $productResult;
        }

        // 9. Default response with suggestions (Fallback)
        $fallback = $this->getSetting('fallback_message', 'Hiện tại tôi chưa có thông tin về yêu cầu này. Vui lòng chờ nhân viên tư vấn sẽ hỗ trợ bạn ngay nhé! 😊');
        $hotline = $this->getSetting('hotline', '1900-xxxx');
        $fallback = str_replace('{hotline}', $hotline, $fallback);

        return $this->textResponse($fallback);
    }

    /**
     * AI response with fallback logic
     */
    private function generateAIResponse(string $message): array
    {
        $providerType = $this->getSetting('ai_provider', 'gemini');
        $contextData = $this->getAiContext($message);
        $fullPrompt = $contextData['instruction']."\n\nUser Question: ".$message;
        $products = $contextData['products'];

        // Define fallback chains
        $fallbacks = [
            'gemini' => [
                ['model' => 'gemini-1.5-flash-latest', 'key' => $this->getSetting('gemini_api_key')],
                ['model' => 'gemini-1.5-pro-latest', 'key' => $this->getSetting('gemini_api_key')],
                ['model' => 'gemini-pro', 'key' => $this->getSetting('gemini_api_key')],
            ],
            'openai' => [
                ['model' => 'gpt-3.5-turbo', 'key' => $this->getSetting('openai_api_key')],
                ['model' => 'gpt-4o-mini', 'key' => $this->getSetting('openai_api_key')],
            ]
        ];

        $modelsToTry = $fallbacks[$providerType] ?? [];

        foreach ($modelsToTry as $config) {
            if (empty($config['key'])) continue;

            try {
                $provider = \App\Services\AI\AIFactory::make($providerType, $config['key'], $config['model']);
                
                $responseText = $provider->generateResponse($fullPrompt, [
                    'instruction' => $contextData['instruction'],
                    'timeout' => 30
                ]);

                if ($responseText) {
                    if ($products->isNotEmpty()) {
                        return $this->productResponse($responseText, $products);
                    }
                    return $this->textResponse($responseText);
                }

                Log::warning("AI Fallback: Model {$config['model']} failed, trying next...");
            } catch (\Exception $e) {
                Log::error("AI Fallback Exception ({$config['model']}): ".$e->getMessage());
            }
        }

        // Final fallback to Rules if AI fails completely
        Log::error("AI System completely failed for provider {$providerType}. Falling back to Rule-based response.");
        return $this->generateRuleResponse($message);
    }


    /**
     * Get context for AI (Product list, Shop info, etc.)
     */
    private function getAiContext(string $userMessage = ''): array
    {
        $categories = Category::pluck('name')->toArray();
        $hotline = $this->getSetting('hotline', '03575372804');
        $email = $this->getSetting('email', 'Elite@gmail.com');

        // --- RAG: Intelligent Product Context ---
        $productContext = '';
        $keywords = $this->extractKeywords($userMessage);

        $foundProducts = collect([]);
        if (! empty($keywords)) {
            // Priority 1: Search exact or partial name with AND for precision
            $foundProducts = Product::where('is_active', true)
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->where('name', 'LIKE', "%{$keyword}%");
                    }
                })
                ->limit(5)
                ->get();

            // Priority 2: If no products found, find products in related categories
            if ($foundProducts->isEmpty()) {
                $foundProducts = Product::where('is_active', true)
                    ->whereHas('category', function ($q) use ($keywords) {
                        foreach ($keywords as $kw) {
                            $q->where('name', 'LIKE', "%{$kw}%");
                        }
                    })
                    ->limit(3)
                    ->get();
            }

            if ($foundProducts->isNotEmpty()) {
                $productContext = "DANH SÁCH SẢN PHẨM HIỆN CÓ ĐỂ TƯ VẤN:\n";
                foreach ($foundProducts as $p) {
                    $price = number_format($p->price);
                    $productContext .= "- {$p->name} (Giá: {$price} VND, ".($p->quantity > 0 ? "Sẵn hàng: {$p->quantity} cái" : 'Liên hệ đặt trước')."). Đặc điểm: {$p->short_description}\n";
                }
            }
        }
        // ----------------------------------------

        $instruction = $this->getSetting(
            'system_instruction',
            "Mục tiêu: Bạn là 'Trợ lý ảo Elite' - chuyên viên tư vấn thời trang cao cấp của shop Elite. Hãy phục vụ khách hàng như một chuyên gia thời trang thực thụ.\n\n".
            "[QUY TẮC PHẢN HỒI]:\n".
            "1. Chỉ trả lời dựa trên danh sách sản phẩm THỰC TẾ được cung cấp bên dưới. Nếu không có sản phẩm khách cần, hãy trung thực và gợi ý sản phẩm tương tự.\n".
            "2. Luôn chào khách hàng lịch sự và kết thúc bằng lời mời đặt hàng hoặc hỏi xem khách cần gì thêm.\n".
            "3. Nếu khách hỏi những câu không liên quan đến thời trang/shop, hãy lịch sự từ chối và hướng họ về việc mua sắm.\n".
            "4. Nếu khách hàng tỏ ra không hài lòng hoặc muốn gặp nhân viên, hãy trả lời: 'Tôi sẽ kết nối bạn với nhân viên tư vấn ngay bây giờ. Vui lòng chờ trong giây lát! 😊'\n\n".
            "[THÔNG TIN CỬA HÀNG]:\n".
            "- Hotline: {hotline}\n".
            "- Email: {email}\n".
            '- Danh mục kinh doanh: {categories}'
        );

        // Replace variables
        $instruction = str_replace(['{hotline}', '{email}', '{categories}'], [$hotline, $email, implode(', ', $categories)], $instruction);

        return [
            'instruction' => $instruction."\n\n".$productContext,
            'products' => $foundProducts,
        ];
    }

    /**
     * Format text-only response
     */
    private function textResponse(string $message): array
    {
        return [
            'type' => 'text',
            'message' => $message,
            'products' => [],
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
                'price_formatted' => number_format($product->price).' VND',
                'image' => $product->image ? asset('storage/'.$product->image) : asset('frontend-assets/images/no-image.png'),
                'url' => route('product.detail', $product->slug ?? $product->id),
                'description' => mb_substr($product->description ?? '', 0, 100),
            ];
        }

        return [
            'type' => 'products',
            'message' => $message,
            'products' => $productCards,
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
        return $this->getSetting('greeting_message', 'Xin chào! 👋 Tôi là trợ lý ảo của Electronics Store. Tôi có thể giúp bạn tìm kiếm sản phẩm, tư vấn giá cả. Bạn đang tìm gì?');
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
                "Bạn đang tìm sản phẩm gì? Vui lòng cho tôi biết cụ thể hơn nhé! 😊\n\n".
                "Ví dụ: 'Có laptop không?', 'Tìm điện thoại iPhone', 'Bán tai nghe không?'"
            );
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%");
                }
            })
            ->limit(6)
            ->get();

        if ($products->isEmpty()) {
            return $this->textResponse(
                "Xin lỗi, tôi không tìm thấy sản phẩm '{$keywords[0]}' trong kho. 😔\n\n".
                "Bạn có thể:\n".
                "• Thử tìm sản phẩm khác\n".
                "• Xem danh mục sản phẩm (gõ 'danh mục')\n".
                "• Liên hệ hotline: {$hotline}"
            );
        }

        $message = 'Tôi tìm thấy '.$products->count().' sản phẩm phù hợp. Click vào sản phẩm để xem chi tiết:';

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
                "Bạn muốn biết giá sản phẩm nào? Vui lòng cho tôi biết tên sản phẩm nhé!\n\n".
                "Ví dụ: 'Giá iPhone 13 bao nhiêu?', 'Laptop Dell giá bao nhiêu?'"
            );
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'LIKE', "%{$keyword}%");
                }
            })
            ->limit(3)
            ->get();

        if ($products->isEmpty()) {
            return $this->textResponse(
                "Xin lỗi, tôi không tìm thấy thông tin giá cho '{$keywords[0]}'. 😔\n\n".
                "Bạn có thể thử tìm sản phẩm khác hoặc liên hệ hotline: {$hotline}"
            );
        }

        $message = 'Đây là thông tin giá sản phẩm bạn cần:';

        return $this->productResponse($message, $products);
    }

    /**
     * Get category information
     */
    private function getCategoryInfo(): string
    {
        $categories = Category::withCount('products')->get();

        if ($categories->isEmpty()) {
            return 'Hiện tại chưa có danh mục sản phẩm nào. Vui lòng quay lại sau!';
        }

        $response = "📂 **Danh mục sản phẩm của chúng tôi:**\n\n";

        foreach ($categories as $index => $category) {
            $count = $category->products_count ?? 0;
            $response .= ($index + 1).". {$category->name} ({$count} sản phẩm)\n";
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

        return "🤖 **Tôi có thể giúp bạn:**\n\n".
            "1️⃣ Tìm kiếm sản phẩm\n".
            "   VD: 'Có laptop không?', 'Tìm iPhone'\n\n".
            "2️⃣ Hỏi về giá\n".
            "   VD: 'Giá MacBook bao nhiêu?'\n\n".
            "3️⃣ Xem danh mục\n".
            "   VD: 'Danh mục sản phẩm', 'Có những loại nào?'\n\n".
            "4️⃣ Tư vấn sản phẩm\n".
            "   VD: 'Tư vấn laptop cho sinh viên'\n\n".
            "📞 Hotline: {$hotline}\n".
            "📧 Email: {$email}";
    }

    /**
     * Intelligent product search
     */
    private function intelligentProductSearch(string $message): ?array
    {
        $words = explode(' ', $message);
        $words = array_filter($words, function ($word) {
            // Tighten search: ignore very short words (now 2 min) and common Vietnamese fillers
            $stopWords = ['của', 'này', 'kia', 'đó', 'phẩm', 'shop', 'cửa', 'hàng', 'mua', 'xem', 'cho', 'với', 'tại', 'vào', 'một', 'những', 'các'];

            return mb_strlen($word) >= 2 && ! in_array(mb_strtolower($word), $stopWords);
        });

        if (empty($words)) {
            return null;
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($words) {
                // Using AND instead of OR for nonsensical queries to not trigger on single word matches
                foreach ($words as $word) {
                    $q->where('name', 'LIKE', "%{$word}%");
                }
            })
            ->limit(4)
            ->get();

        if ($products->isEmpty()) {
            return null;
        }

        $message = 'Có thể bạn đang tìm những sản phẩm này:';

        return $this->productResponse($message, $products);
    }

    /**
     * Extract keywords from message
     */
    private function extractKeywords(string $message): array
    {
        $stopWords = [
            'có',
            'không',
            'bán',
            'tìm',
            'giá',
            'bao',
            'nhiêu',
            'của',
            'là',
            'về',
            'cho',
            'và',
            'được',
            'cái',
            'này',
            'kia',
            'đó',
            'phẩm',
            'shop',
            'cửa',
            'hàng',
            'mua',
            'xem',
            'với',
            'tại',
            'hỏi',
            'cho',
            'biết',
            'thế',
            'nào',
            'đâu',
            'ở',
            'gì',
            'nào',
            'mình',
            'bạn',
            'em',
            'anh',
            'chị',
        ];

        $words = explode(' ', mb_strtolower($message));
        $keywords = [];

        foreach ($words as $word) {
            $word = trim($word);
            // Support 2-character Vietnamese words (áo, quần, ví...) while excluding stop words
            if (mb_strlen($word) >= 2 && ! in_array($word, $stopWords)) {
                $keywords[] = $word;
            }
        }

        return array_values(array_unique($keywords));
    }

    /**
     * Centralized dynamic tag replacement
     */
    private function parseResponseTags(string $responseText, string $searchKeyword = ''): array
    {
        $products = [];

        // Replace standard tags
        $responseText = str_replace('{hotline}', $this->getSetting('hotline', '1900-xxxx'), $responseText);
        $responseText = str_replace('{email}', $this->getSetting('email', 'support@example.com'), $responseText);

        if (str_contains($responseText, '{categories}')) {
            $categories = \App\Models\Category::pluck('name')->toArray();
            $responseText = str_replace('{categories}', implode(', ', $categories), $responseText);
        }

        // Handle {product} tag
        if (str_contains($responseText, '{product}')) {
            $productResponse = $this->intelligentProductSearch($searchKeyword);

            if ($productResponse && ! empty($productResponse['products'])) {
                $products = $productResponse['products'];
                // Replace {product} with a generic term or actual product name if only one
                $replacement = count($products) === 1 ? $products[0]['name'] : 'sản phẩm';
                $responseText = str_replace('{product}', $replacement, $responseText);
            } else {
                $responseText = str_replace('{product}', 'sản phẩm', $responseText);
            }
        }

        return [
            'text' => $responseText,
            'products' => $products,
        ];
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
