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

        try {
            $message = $request->input('message');
            $sessionId = $request->session()->getId();
            $userId = auth()->id();

            // 1. Save user message
            $userMessage = \App\Models\ChatMessage::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'message' => $message,
                'sender_type' => 'user',
            ]);
            
            event(new \App\Events\ChatMessageSent($userMessage));

            // 2. Check if Bot is enabled for this session
            $chatSession = \App\Models\ChatSession::where('session_id', $sessionId)->first();
            $isBotEnabled = $chatSession ? $chatSession->is_bot_enabled : true;

            if (! $isBotEnabled) {
                return response()->json([
                    'status' => 'success',
                    'reply' => null, // No bot reply
                    'is_muted' => true,
                ]);
            }

            // 3. Get structured response from ChatService
            $result = $this->chatService->generateResponse($message);

            // 4. Save bot reply
            $botMessage = \App\Models\ChatMessage::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'message' => $result['message'],
                'sender_type' => 'bot',
                'payload' => [
                    'products' => $result['products'] ?? [],
                ],
            ]);
            
            event(new \App\Events\ChatMessageSent($botMessage));

            return response()->json([
                'status' => 'success',
                'reply' => $result['message'],
                'products' => $result['products'] ?? [],
                'type' => $result['type'] ?? 'text',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Chat API Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function getMessages(Request $request)
    {
        $sessionId = $request->session()->getId();

        $messages = \App\Models\ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'messages' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'text' => $msg->message,
                    'isUser' => $msg->sender_type === 'user',
                    'sender_type' => $msg->sender_type,
                    'time' => $msg->created_at->format('H:i'),
                    'products' => $msg->payload['products'] ?? [],
                ];
            }),
        ]);
    }
}
