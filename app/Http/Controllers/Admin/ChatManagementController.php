<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatManagementController extends Controller
{
    public function index()
    {
        // Get unique sessions with last message and unread count
        $conversations = ChatMessage::select('session_id', 'user_id', \DB::raw('MAX(created_at) as last_activity'))
            ->with(['user'])
            ->groupBy('session_id', 'user_id')
            ->orderBy('last_activity', 'desc')
            ->paginate(15);

        // Attach last message and unread count manually to each conversation (or use a smarter query)
        foreach ($conversations as $chat) {
            $lastMsg = ChatMessage::where('session_id', $chat->session_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $chat->last_message = $lastMsg ? $lastMsg->message : '';
            $chat->unread_count = ChatMessage::where('session_id', $chat->session_id)
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->count();
        }

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

        $user = $messages->where('user_id', '!=', null)->where('sender_type', 'user')->first()?->user;

        return view('admin.chat.show', compact('messages', 'sessionId', 'user'));
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

    public function destroy($sessionId)
    {
        ChatMessage::where('session_id', $sessionId)->delete();
        return redirect()->route('admin.chat.index')->with('success', 'Đã chuyển hội thoại vào thùng rác!');
    }

    public function trash()
    {
        // Get sessions that ONLY have trashed messages (or at least no active ones)
        $activeSessionIds = ChatMessage::pluck('session_id')->unique();

        $conversations = ChatMessage::onlyTrashed()
            ->whereNotIn('session_id', $activeSessionIds)
            ->select('session_id', 'user_id', \DB::raw('MAX(deleted_at) as deleted_at'))
            ->with(['user'])
            ->groupBy('session_id', 'user_id')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        foreach ($conversations as $chat) {
            $lastMsg = ChatMessage::onlyTrashed()
                ->where('session_id', $chat->session_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $chat->last_message = $lastMsg ? $lastMsg->message : '';
        }

        return view('admin.chat.trash', compact('conversations'));
    }

    public function restore($sessionId)
    {
        ChatMessage::onlyTrashed()->where('session_id', $sessionId)->restore();
        return redirect()->route('admin.chat.trash')->with('success', 'Đã khôi phục hội thoại!');
    }

    public function permanentDelete($sessionId)
    {
        ChatMessage::onlyTrashed()->where('session_id', $sessionId)->forceDelete();
        return redirect()->route('admin.chat.trash')->with('success', 'Đã xóa vĩnh viễn hội thoại!');
    }

    public function destroyMessage($id)
    {
        $message = ChatMessage::findOrFail($id);
        $sessionId = $message->session_id;
        $message->delete();

        return redirect()->back()->with('success', 'Đã xóa tin nhắn!');
    }
}
