<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            $notifications = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            return view('admin.notifications.index', compact('notifications'));
        }
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $user->unreadNotifications->markAsRead();
        }
        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }

    public function markAsRead($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (isset($notification->data['link'])) {
            return redirect($notification->data['link']);
        }

        return back();
    }
    public function unreadCount()
    {
        /** @var User $user */
        $user = Auth::user();
        $count = Auth::check() ? $user->unreadNotifications()->count() : 0;
        return response()->json(['count' => $count]);
    }
}
