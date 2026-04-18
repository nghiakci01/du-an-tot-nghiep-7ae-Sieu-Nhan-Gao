<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = \App\Models\PostCategory::withCount('posts')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.post-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.post-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Generated\PostCategoryRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        \App\Models\PostCategory::create($validated);

        return redirect()->route('admin.post-categories.index')->with('success', 'Danh mục tin tức đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = \App\Models\PostCategory::findOrFail($id);

        return view('admin.post-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Generated\PostCategoryRequest $request, $id)
    {
        $category = \App\Models\PostCategory::findOrFail($id);

        $validated = $request->validated();

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.post-categories.index')->with('success', 'Danh mục tin tức đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = \App\Models\PostCategory::findOrFail($id);

        if ($category->posts()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục đang có bài viết.');
        }

        $category->delete();

        return redirect()->route('admin.post-categories.index')->with('success', 'Danh mục tin tức đã được xóa thành công.');
    }
}
