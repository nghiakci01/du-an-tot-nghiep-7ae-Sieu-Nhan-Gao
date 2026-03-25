<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display search results page
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');

        /** @var \Illuminate\Database\Eloquent\Builder $queryBuilder */
        $queryBuilder = Product::query()->where('is_active', true);
        
        /** @var mixed $searchFilter */
        $searchFilter = function (\Illuminate\Database\Eloquent\Builder $q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('short_description', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhereHas('category', function (\Illuminate\Database\Eloquent\Builder $categoryQuery) use ($query) {
                        $categoryQuery->where('name', 'LIKE', "%{$query}%");
                    });
            };
            
        $products = $queryBuilder->{'where'}($searchFilter)
            ->with(['category', 'images', 'reviews', 'variants'])
            ->orderByRaw('CASE 
                WHEN name LIKE ? THEN 1 
                WHEN name LIKE ? THEN 2 
                ELSE 3 
            END', [$query, "%{$query}%"])
            ->paginate(12);

        return view('frontend.search.index', compact('products', 'query'));
    }

    /**
     * Get autocomplete suggestions (AJAX)
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q', '');

        // Minimum 2 characters
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        /** @var \Illuminate\Database\Eloquent\Builder $queryBuilder */
        $queryBuilder = Product::query()->where('is_active', true);

        /** @var mixed $suggestionFilter */
        $suggestionFilter = function (\Illuminate\Database\Eloquent\Builder $q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('short_description', 'LIKE', "%{$query}%");
            };

        $products = $queryBuilder->{'where'}($suggestionFilter)
            ->select('id', 'name', 'slug', 'image', 'price', 'sale_price')
            ->orderByRaw('CASE 
                WHEN name LIKE ? THEN 1 
                WHEN name LIKE ? THEN 2 
                ELSE 3 
            END', [$query, "%{$query}%"])
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image_url,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'url' => route('product.detail', $product->slug),
                ];
            });

        return response()->json($products);
    }
}
