<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
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

        // Banners
        $sliders = Banner::where('position', 'slider')->where('is_active', true)->orderBy('sort_order')->get();
        // Banner Top (Bên phải Slider - lấy 3 cái)
        $bannerTop = Banner::where('position', 'banner_top')->where('is_active', true)->orderBy('sort_order')->take(3)->get();
        // Banner Bottom (Cuối trang - lấy 1 cái)
        $bannerBottom = Banner::where('position', 'banner_bottom')->where('is_active', true)->orderBy('sort_order')->first();

        // Sản phẩm nổi bật cho section "New Arrivals"
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'variants', 'images', 'reviews'])
            ->take(30)
            ->get();

        // Sản phẩm mới nhất cho section dưới banner
        $newProducts = Product::where('is_active', true)
            ->with(['category', 'variants', 'reviews', 'images'])
            ->latest()
            ->take(12)
            ->get();

        // Sản phẩm được yêu thích nhất (Top Wishlisted)
        $topWishlisted = Product::where('is_active', true)
            ->withCount('wishlistedBy')
            ->with(['reviews', 'images', 'variants'])
            ->orderByDesc('wishlisted_by_count')
            ->take(10)
            ->get();

        return view('frontend.home', compact('categories', 'featuredProducts', 'newProducts', 'topWishlisted', 'sliders', 'bannerTop', 'bannerBottom'));
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
