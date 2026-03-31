<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            $notifications = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            return view('admin.notifications.index', compact('notifications'));
        }
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
        }
        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (isset($notification->data['link'])) {
            return redirect($notification->data['link']);
        }

        return back();
    }
    public function unreadCount()
    {
        $count = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
        return response()->json(['count' => $count]);
    }
}
