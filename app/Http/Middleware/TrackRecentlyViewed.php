<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackRecentlyViewed
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->route() && $request->route()->getName() === 'product.detail') {
            $slug = $request->route('slug');
            $product = \App\Models\Product::where('slug', $slug)->first();

            if ($product) {
                $recent = session()->get('recently_viewed', []);
                $recent = array_filter($recent, fn($id) => $id !== $product->id);
                array_unshift($recent, $product->id);
                $recent = array_slice($recent, 0, 12);
                session()->put('recently_viewed', $recent);
            }
        }

        return $response;
    }
}
