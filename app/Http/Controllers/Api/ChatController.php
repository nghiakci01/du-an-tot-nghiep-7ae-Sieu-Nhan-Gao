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

        \Illuminate\Support\Facades\Log::info("ChatController hit: " . $request->input('message'));

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
        
        // 2. Check if Bot is enabled for this session
        $chatSession = \App\Models\ChatSession::where('session_id', $sessionId)->first();
        $isBotEnabled = $chatSession ? $chatSession->is_bot_enabled : true;

        if (!$isBotEnabled) {
            return response()->json([
                'status' => 'success',
                'reply' => null, // No bot reply
                'is_muted' => true
            ]);
        }

        // 3. Get structured response from ChatService
        $result = $this->chatService->generateResponse($message);

        // 3. Save bot reply
        \App\Models\ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'message' => $result['message'],
            'sender_type' => 'bot',
            'payload' => [
                'products' => $result['products'] ?? []
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'reply' => $result['message'],
            'products' => $result['products'] ?? [], // Product cards data
            'type' => $result['type'] ?? 'text' // 'text' or 'products'
        ]);
    }

    public function getMessages(Request $request)
    {
        $sessionId = $request->session()->getId();
        
        $messages = \App\Models\ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'messages' => $messages->map(function($msg) {
                return [
                    'text' => $msg->message,
                    'isUser' => $msg->sender_type === 'user',
                    'sender_type' => $msg->sender_type,
                    'time' => $msg->created_at->format('H:i'),
                ];
            })
        ]);
    }
}
