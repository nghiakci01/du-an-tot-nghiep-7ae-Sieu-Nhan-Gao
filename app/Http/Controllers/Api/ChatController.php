<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->input('message');
        $sessionId = $request->session()->getId();
        $userId = auth()->id();

        // 1. Save user message
        \App\Models\ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'message' => $message,
            'sender_type' => 'user'
        ]);
        
        // 2. Get structured response from ChatService
        $result = $this->chatService->generateResponse($message);

        // 3. Save bot reply
        \App\Models\ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'message' => $result['message'],
            'sender_type' => 'bot'
        ]);

        return response()->json([
            'status' => 'success',
            'reply' => $result['message'],
            'products' => $result['products'] ?? [], // Product cards data
            'type' => $result['type'] ?? 'text' // 'text' or 'products'
        ]);
    }
}
