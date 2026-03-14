<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\VtonHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VtonHistoryController extends Controller
{
    public function index()
    {
        $histories = VtonHistory::with(['product', 'vtonModel'])
            ->where(function($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', session()->getId());
                }
            })
            ->latest()
            ->paginate(12);

        return view('frontend.vton.history', compact('histories'));
    }

    public function destroy($id)
    {
        $history = VtonHistory::findOrFail($id);
        
        // Check ownership
        if (auth()->check() && $history->user_id !== auth()->id()) {
            return back()->with('error', 'Bạn không có quyền xóa mục này.');
        }
        
        if (!auth()->check() && $history->session_id !== session()->getId()) {
            return back()->with('error', 'Bạn không có quyền xóa mục này.');
        }

        // Delete images if they exist
        if ($history->result_image) {
            \Storage::disk('public')->delete($history->result_image);
        }
        
        $history->delete();
        
        return back()->with('success', 'Đã xóa lịch sử thử đồ.');
    }
}
