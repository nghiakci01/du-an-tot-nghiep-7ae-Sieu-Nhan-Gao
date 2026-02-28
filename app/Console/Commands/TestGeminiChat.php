<?php

namespace App\Console\Commands;

use App\Services\ChatService;
use Illuminate\Console\Command;

class TestGeminiChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:gemini-chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Gemini AI chatbot integration';

    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        parent::__construct();
        $this->chatService = $chatService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Gemini AI Chatbot Integration');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        // Test 1: Simple greeting
        $this->info('Test 1: Simple Greeting');
        $this->line('User: Xin chào, bạn có thể giúp gì cho tôi?');

        $response1 = $this->chatService->generateResponse('Xin chào, bạn có thể giúp gì cho tôi?');
        $this->line('AI: '.$response1);
        $this->newLine();

        // Test 2: Product query (will trigger RAG)
        $this->info('Test 2: Product Query (RAG Test)');
        $this->line('User: Có sản phẩm laptop không?');

        $response2 = $this->chatService->generateResponse('Có sản phẩm laptop không?');
        $this->line('AI: '.$response2);
        $this->newLine();

        // Test 3: Specific product search
        $this->info('Test 3: Specific Product Search');
        $this->line('User: Cho tôi xem sản phẩm iPhone');

        $response3 = $this->chatService->generateResponse('Cho tôi xem sản phẩm iPhone');
        $this->line('AI: '.$response3);
        $this->newLine();

        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('✅ All tests completed!');

        return Command::SUCCESS;
    }
}
