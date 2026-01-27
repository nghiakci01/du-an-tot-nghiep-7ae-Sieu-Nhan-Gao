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
        $query = Product::where('is_active', true)->with('category');

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

        return view('frontend.products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                          ->where('is_active', true)
                          ->with(['category', 'variants'])
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
