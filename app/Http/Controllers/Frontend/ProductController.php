<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'variants']);

        // Filter by Category
        if ($request->has('category')) {
            $slug = $request->category;
            $query->whereHas('category', function($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        // Filter by Price (using variants)
        if ($request->has('min_price') && $request->has('max_price')) {
            $min = $request->min_price;
            $max = $request->max_price;
            
            // Check if product has ANY variant within the price range
            $query->whereHas('variants', function($q) use ($min, $max) {
                $q->whereBetween('price', [$min, $max]);
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

        // Get categories for sidebar
        $categories = Category::withCount('products')->get();
        $totalActiveProducts = Product::where('is_active', true)->count();

        return view('frontend.products.index', compact('products', 'categories', 'totalActiveProducts'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                          ->where('is_active', true)
                          ->with(['category', 'variants', 'images', 'reviews.user'])
                          ->firstOrFail();

        // Get related products (same category, excluding current)
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->where('is_active', true)
                                  ->take(4)
                                  ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
