<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Lấy danh sách thông báo chưa đọc (nếu muốn gọi bằng AJAX)
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->take(10)->get();
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Đánh dấu 1 thông báo là đã đọc
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
    }

    /**
     * Đánh dấu tất cả là đã đọc
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }
}
