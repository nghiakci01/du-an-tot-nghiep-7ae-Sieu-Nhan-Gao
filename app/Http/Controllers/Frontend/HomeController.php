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
        $latestProducts = Product::where('is_active', true)
                                 ->with('category')
                                 ->latest()
                                 ->take(8)
                                 ->get();

        return view('frontend.home', compact('categories', 'latestProducts'));
    }
}
