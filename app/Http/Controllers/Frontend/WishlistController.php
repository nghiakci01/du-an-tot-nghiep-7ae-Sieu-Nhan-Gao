<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', __('messages.please_login'));
        }
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('frontend.wishlist.index', compact('wishlists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thêm vào danh sách yêu thích!'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->exists();

        if ($exists) {
            return response()->json(['status' => 'info', 'message' => 'Sản phẩm đã có trong danh sách yêu thích!']);
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Đã thêm vào danh sách yêu thích!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $wishlist->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
    }
}
