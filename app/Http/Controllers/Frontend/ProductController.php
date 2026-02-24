<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'variants', 'reviews']);

        // Search by keyword
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->has('category')) {
            $slug = $request->category;
            $query->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        // Filter by Search Keyword
        if ($request->has('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        // Filter by Price (using variants)
        if ($request->has('min_price') && $request->has('max_price')) {
            $min = $request->min_price;
            $max = $request->max_price;

            // Check if product has ANY variant within the price range
            $query->whereHas('variants', function ($q) use ($min, $max) {
                $q->whereBetween('price', [$min, $max]);
            });
        }

        // Filter by Brand
        if ($request->has('brand')) {
            $brandSlug = $request->brand;
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Filter by Color
        if ($request->has('color')) {
            $colorSlug = $request->color;
            $query->whereHas('variants.colorRelationship', function ($q) use ($colorSlug) {
                $q->where('slug', $colorSlug);
            });
        }

        // Filter by Tag
        if ($request->has('tag')) {
            $tagSlug = $request->tag;
            $query->whereHas('tags', function ($q) use ($tagSlug) {
                $q->where('slug', $tagSlug);
            });
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        // Convert pagination query params
        $products->appends($request->all());

        // Get sidebar data
        $categories = Category::withCount(['products' => function($q) {
            $q->where('products.is_active', true);
        }])->get();

        $brands = Brand::where('is_active', true)->withCount(['products' => function($q) {
            $q->where('products.is_active', true);
        }])->get();

        // Colors with product counts
        $colors = Color::whereHas('productVariants.product', function($q) {
            $q->where('products.is_active', true);
        })->withCount(['productVariants as products_count' => function($q) {
            $q->whereHas('product', function($pq) {
                $pq->where('products.is_active', true);
            });
        }])->limit(10)->get();

        $tags = Tag::withCount(['products' => function($q) {
            $q->where('products.is_active', true);
        }])->limit(15)->get();

        $totalActiveProducts = Product::where('is_active', true)->count();

        return view('frontend.products.index', compact(
            'products', 
            'categories', 
            'brands', 
            'colors', 
            'tags', 
            'totalActiveProducts'
        ));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'variants.sizeRelationship', 'variants.colorRelationship', 'images', 'reviews.user'])
            ->firstOrFail();

        // Kiểm tra user đã mua và nhận hàng thành công chưa
        $hasPurchased = false;
        if (Auth::check()) {
            $hasPurchased = Order::where('user_id', Auth::id())
                ->where('status', Order::STATUS_COMPLETED)
                ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
                ->exists();
        }

        // Get related products (same category, excluding current)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts', 'hasPurchased'));
    }
}
