<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Cache::remember('home_categories', 3600, function() {
            return Category::whereNull('parent_id')->take(6)->get();
        });

        // Banners
        $sliders = Cache::remember('home_sliders', 3600, function() {
            return Banner::where('position', 'slider')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        $midBanner = Cache::remember('home_midBanner', 3600, function() {
            return Banner::where('position', 'home_middle')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->first();
        });


        $bannerBottom = Cache::remember('home_bannerBottom', 3600, function() {
            return Banner::where('position', 'banner_bottom')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->first();
        });



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

        // Flash Sale products
        $flashSaleProducts = Product::flashSale()
            ->with(['variants', 'reviews', 'images'])
            ->take(8)
            ->get();

        return view('frontend.home', compact('categories', 'featuredProducts', 'newProducts', 'topWishlisted', 'sliders', 'midBanner', 'flashSaleProducts', 'bannerBottom'));
    }

    public function about()
    {
        $aboutBanner = Banner::where('position', 'about_us')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        return view('frontend.about', compact('aboutBanner'));
    }

    public function news()
    {
        $posts = \App\Models\Post::where('is_active', true)
            ->latest()
            ->paginate(9);

        return view('frontend.news', compact('posts'));
    }
    public function newsDetail($slug)
    {
        $post = \App\Models\Post::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Lấy các bài viết liên quan (cùng danh mục)
        $relatedPosts = \App\Models\Post::where('post_category_id', $post->post_category_id)
            ->where('id', '!=', $post->id)
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.news_detail', compact('post', 'relatedPosts'));
    }
}
