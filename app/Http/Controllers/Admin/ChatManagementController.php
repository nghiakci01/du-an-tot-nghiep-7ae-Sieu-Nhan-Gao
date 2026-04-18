<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatManagementController extends Controller
{
    public function index()
    {
        // Get unique sessions with last message and unread count
        // Using a more robust query to get the latest message and unread count
        $conversations = ChatMessage::select('session_id', 'user_id')
            ->selectRaw('MAX(created_at) as last_activity')
            ->selectRaw('COUNT(CASE WHEN is_read = 0 AND sender_type = "user" THEN 1 END) as unread_count')
            ->with(['user'])
            ->groupBy('session_id', 'user_id')
            ->orderBy('last_activity', 'desc')
            ->paginate(15);

        foreach ($conversations as $chat) {
            /** @var ChatMessage $chat */
            $chat->last_message = ChatMessage::where('session_id', $chat->session_id)
                ->orderBy('created_at', 'desc')
                ->value('message');
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

        // Get user if exists
        $user = $messages->where('user_id', '!=', null)->where('sender_type', 'user')->first()?->user;

        // Get session info for bot muting
        $chatSession = \App\Models\ChatSession::firstOrCreate(
            ['session_id' => $sessionId],
            ['is_bot_enabled' => true, 'last_activity' => now()]
        );

        return view('admin.chat.show', compact('messages', 'sessionId', 'user', 'chatSession'));
    }

    public function toggleBot($sessionId)
    {
        $chatSession = \App\Models\ChatSession::where('session_id', $sessionId)->first();

        if ($chatSession) {
            $chatSession->is_bot_enabled = ! $chatSession->is_bot_enabled;
            $chatSession->save();
            $status = $chatSession->is_bot_enabled ? 'đã bật' : 'đã tắt';

            return redirect()->route('admin.chat.show', $sessionId)->with('success', "Chatbot tự động {$status} cho hội thoại này!");
        }

        return redirect()->route('admin.chat.index')->with('error', 'Không tìm thấy phiên hội thoại.');
    }

    public function reply(\App\Http\Requests\Generated\ChatMessageRequest $request, $sessionId)
    {
        $validated = $request->validated();

        $staffMessage = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'sender_type' => 'staff',
            'is_read' => true,
        ]);

        event(new \App\Events\ChatMessageSent($staffMessage));

        // Auto-disable bot when staff replies to prevent interference
        \App\Models\ChatSession::updateOrCreate(
            ['session_id' => $sessionId],
            ['is_bot_enabled' => false, 'last_activity' => now()]
        );

        return redirect()->route('admin.chat.show', $sessionId)->with('success', 'Đã gửi phản hồi và tạm dừng Chatbot!');
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
            ->select('session_id', 'user_id', DB::raw('MAX(deleted_at) as deleted_at'))
            ->with(['user'])
            ->groupBy('session_id', 'user_id')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        foreach ($conversations as $chat) {
            /** @var ChatMessage $chat */
            $lastMsg = ChatMessage::onlyTrashed()
                ->where('session_id', $chat->session_id)
                ->orderBy('created_at', 'desc')
                ->first();

            /** @var ChatMessage|null $lastMsg */
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

        return redirect()->route('admin.chat.show', $sessionId)->with('success', 'Đã xóa tin nhắn!');
    }
}
