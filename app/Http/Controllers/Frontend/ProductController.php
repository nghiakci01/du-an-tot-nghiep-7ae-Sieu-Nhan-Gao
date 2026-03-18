<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\Size;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'variants', 'reviews', 'images']);

        // Search by keyword
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Category
        $currentCategory = null;
        if ($request->has('category')) {
            $slug = $request->category;
            $currentCategory = Category::where('slug', $slug)->first();
            $query->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        // Filter by Search Keyword
        if ($request->has('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        // Filter by Price (including promotional prices)
        if ($request->has('min_price') && $request->has('max_price')) {
            $min = (float) $request->min_price;
            $max = (float) $request->max_price;

            $query->where(function ($q) use ($min, $max) {
                // 1. Check if product has ANY variant with an active price in range
                $q->whereHas('variants', function ($v_q) use ($min, $max) {
                    $v_q->where(function ($sub) use ($min, $max) {
                        // If sale price exists and > 0, check it
                        $sub->where(function ($q1) use ($min, $max) {
                            $q1->whereNotNull('sale_price')
                                ->where('sale_price', '>', 0)
                                ->whereBetween('sale_price', [$min, $max]);
                        })
                        // Otherwise check regular price
                        ->orWhere(function ($q2) use ($min, $max) {
                            $q2->where(function ($q_null) {
                                $q_null->whereNull('sale_price')
                                      ->orWhere('sale_price', '<=', 0);
                            })->whereBetween('price', [$min, $max]);
                        });
                    });
                })
                // 2. OR if it has NO variants (or they are invalid), check the product's own price
                ->orWhere(function ($p_q) use ($min, $max) {
                    $p_q->doesntHave('variants')
                        ->where(function ($sub) use ($min, $max) {
                            $sub->where(function ($q1) use ($min, $max) {
                                $q1->whereNotNull('sale_price')
                                    ->where('sale_price', '>', 0)
                                    ->whereBetween('sale_price', [$min, $max]);
                            })->orWhere(function ($q2) use ($min, $max) {
                                $q2->where(function ($q_null) {
                                    $q_null->whereNull('sale_price')
                                          ->orWhere('sale_price', '<=', 0);
                                })->whereBetween('price', [$min, $max]);
                            });
                        });
                });
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

        // Filter by Size
        if ($request->has('size')) {
            $sizeName = $request->size;
            $query->whereHas('variants.sizeRelationship', function ($q) use ($sizeName) {
                $q->where('name', $sizeName);
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
        $categories = Cache::remember('shop_sidebar_categories', 3600, function () {
            return Category::withCount([
                'products' => function ($q) {
                    $q->where('products.is_active', true);
                },
            ])->get();
        });

        $brands = Cache::remember('shop_sidebar_brands', 3600, function () {
            return Brand::where('is_active', true)->withCount([
                'products' => function ($q) {
                    $q->where('products.is_active', true);
                },
            ])->get();
        });

        // Colors with product counts
        $colors = Color::whereHas('productVariants.product', function ($q) {
            $q->where('products.is_active', true);
        })->withCount([
            'productVariants as products_count' => function ($q) {
                $q->whereHas('product', function ($pq) {
                    $pq->where('products.is_active', true);
                });
            },
        ])->limit(10)->get();

        // Sizes with product counts
        $sizes = Size::where('is_active', true)->whereHas('productVariants.product', function ($q) {
            $q->where('products.is_active', true);
        })->withCount([
            'productVariants as products_count' => function ($q) {
                $q->whereHas('product', function ($pq) {
                    $pq->where('products.is_active', true);
                });
            },
        ])->orderBy('display_order', 'asc')->get();

        $tags = Tag::withCount([
            'products' => function ($q) {
                $q->where('products.is_active', true);
            }
        ])->limit(10)->get();

        $tags = Cache::remember('shop_sidebar_tags', 3600, function () {
            return Tag::withCount([
                'products' => function ($q) {
                    $q->where('products.is_active', true);
                },
            ])->limit(15)->get();
        });

        $totalActiveProducts = Cache::remember('shop_total_active', 3600, function () {
            return Product::where('is_active', true)->count();
        });

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'brands',
            'colors',
            'sizes',
            'tags',
            'totalActiveProducts',
            'currentCategory'
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
                ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
                ->exists();
        }

        // Get related products (same category, excluding current)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images', 'variants', 'reviews'])
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts', 'hasPurchased'));
    }
}
