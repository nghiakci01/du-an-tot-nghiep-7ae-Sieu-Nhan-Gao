<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = Review::with(['user', 'product']);

        // Search by User Name or Product Name
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', '%' . $search . '%')
                       ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter by Rating
        if (request()->filled('rating')) {
            $query->where('rating', request('rating'));
        }

        // Sorting
        $sort = request('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'rating_high') {
            $query->orderBy('rating', 'desc');
        } elseif ($sort === 'rating_low') {
            $query->orderBy('rating', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Statistics for Summary Cards
        $stats = [
            'total' => Review::count(),
            'average' => round(Review::avg('rating') ?? 0, 1),
            'critical' => Review::whereIn('rating', [1, 2])->count(),
        ];

        $reviews = $query->paginate(20)->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Đã xóa đánh giá thành công!');
    }
}
