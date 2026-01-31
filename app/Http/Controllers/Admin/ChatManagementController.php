<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatManagementController extends Controller
{
    public function index()
    {
        // Get unique sessions with last message
        $conversations = ChatMessage::select('session_id', 'user_id', \DB::raw('MAX(created_at) as last_activity'))
            ->with('user')
            ->groupBy('session_id', 'user_id')
            ->orderBy('last_activity', 'desc')
            ->paginate(15);

        return view('admin.chat.index', compact('conversations'));
    }

    public function show($sessionId)
    {
        $messages = ChatMessage::where('session_id', $sessionId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark as read
        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'user')
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('messages', 'sessionId'));
    }

    public function reply(Request $request, $sessionId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'sender_type' => 'staff'
        ]);

        return redirect()->back()->with('success', 'Đã gửi phản hồi!');
    }
}
