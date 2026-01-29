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
        
        $response = $this->chatService->generateResponse($message);

        return response()->json([
            'status' => 'success',
            'reply' => $response
        ]);
    }
}
