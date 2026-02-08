<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->take(6)->get();

        // Sản phẩm nổi bật cho section "New Arrivals"
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'variants'])
            ->take(10)
            ->get();

        // Sản phẩm mới nhất cho section dưới banner
        $newProducts = Product::where('is_active', true)
            ->with(['category', 'variants'])
            ->latest()
            ->take(12)
            ->get();

        // Sản phẩm được yêu thích nhất (Top Wishlisted)
        $topWishlisted = Product::where('is_active', true)
            ->withCount('wishlistedBy')
            ->orderByDesc('wishlisted_by_count')
            ->take(10)
            ->get();

        return view('frontend.home', compact('categories', 'featuredProducts', 'newProducts', 'topWishlisted'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function news()
    {
        return view('frontend.news');
    }
}
