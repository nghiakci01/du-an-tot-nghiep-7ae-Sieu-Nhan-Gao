<?php

/**
 * Test script để verify Gemini API integration
 * Chạy: php test_gemini_api.php
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? null;

if (!$apiKey) {
    echo "❌ GEMINI_API_KEY không tồn tại trong .env\n";
    exit(1);
}

echo "✅ API Key found: " . substr($apiKey, 0, 10) . "...\n\n";

// Test 1: Simple API call
echo "🧪 Test 1: Simple Gemini API Call\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
$testPrompt = "Xin chào! Hãy giới thiệu bản thân bằng tiếng Việt.";

try {
    $response = file_get_contents($baseUrl . '?key=' . $apiKey, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode([
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $testPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500
                ]
            ])
        ]
    ]));

    $data = json_decode($response, true);
    
    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
        echo "✅ API Response:\n";
        echo "   " . $aiResponse . "\n\n";
    } else {
        echo "❌ Unexpected response format\n";
        echo "   Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Test with product context (RAG simulation)
echo "🧪 Test 2: RAG Simulation (Product Context)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$productContext = "Thông tin sản phẩm có sẵn:\n";
$productContext .= "- Tên: iPhone 15 Pro Max | Giá: 29,990,000 VND | Mô tả: Điện thoại cao cấp với chip A17 Pro\n";
$productContext .= "- Tên: Samsung Galaxy S24 Ultra | Giá: 27,990,000 VND | Mô tả: Flagship Android với S Pen\n";

$systemPrompt = "Bạn là trợ lý ảo hỗ trợ bán hàng của cửa hàng Electronics Store. " .
    "Hãy trả lời câu hỏi của khách hàng một cách thân thiện, ngắn gọn bằng tiếng Việt. " .
    "Dưới đây là một số thông tin sản phẩm (Context) thực tế từ cửa hàng. " .
    "Nếu thông tin có trong Context, hãy dùng nó để trả lời chính xác về giá và sản phẩm.\n\n" .
    "CONTEXT:\n" . $productContext;

$userQuestion = "Cho tôi xem các sản phẩm iPhone và giá của chúng?";
$fullPrompt = $systemPrompt . "\n\nCâu hỏi của khách hàng: " . $userQuestion;

try {
    $response = file_get_contents($baseUrl . '?key=' . $apiKey, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode([
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1000
                ]
            ])
        ]
    ]));

    $data = json_decode($response, true);
    
    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
        echo "✅ User Question: $userQuestion\n";
        echo "✅ AI Response:\n";
        echo "   " . $aiResponse . "\n\n";
    } else {
        echo "❌ Unexpected response format\n";
        echo "   Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ All tests completed!\n";
