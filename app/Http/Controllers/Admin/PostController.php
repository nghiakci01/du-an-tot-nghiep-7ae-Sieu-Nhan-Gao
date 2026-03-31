<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Coupon;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PostCategory::where('is_active', true)->get();
        $activeCoupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })->get();

        return view('admin.posts.create', compact('categories', 'activeCoupons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $filename = 'posts/'.$file->hashName();
                Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));
                $data['image'] = $filename;
            }
        }

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['coupon_id'] = $request->input('coupon_id') ?: null;

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Tin tức đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::where('is_active', true)->get();
        $activeCoupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orWhere('id', $post->coupon_id)
            ->get();

        return view('admin.posts.edit', compact('post', 'categories', 'activeCoupons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $filename = 'posts/'.$file->hashName();
                Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));
                $data['image'] = $filename;
            }
        }

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $data['coupon_id'] = $request->input('coupon_id') ?: null;

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Tin tức đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Tin tức đã được xóa thành công.');
    }
}
