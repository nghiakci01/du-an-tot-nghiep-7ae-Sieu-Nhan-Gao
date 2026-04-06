<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); // Only allowing 2 levels for simplicity initially, or list all for parent selection
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);


        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->whereNull('parent_id')->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        if ($request->filled('parent_id') && $category->children()->count() > 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Không thể chuyển danh mục có danh mục con thành danh mục con.');
        }


        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    /**
     * Display the specified resource.
     * Fallback for Pjax/URL issues
     */
    public function show(Category $category)
    {
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Thay vì báo lỗi, ta sẽ đệ quy xóa tất cả danh mục con của nó
        foreach ($category->children as $child) {
            $response = $this->destroy($child);
            // Nếu việc xóa danh mục con bị lỗi (ví dụ do có sản phẩm đang hoạt động), dừng lại và báo lỗi
            if ($response && session()->has('error') && session('error')) {
                return $response;
            }
        }

        // Nếu danh mục vẫn có sản phẩm đang hoạt động -> Chặn
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục vì vẫn còn sản phẩm đang hoạt động bên trong.');
        }

        // Nếu danh mục chỉ còn các sản phẩm "trong thùng rác" (soft deleted)
        // -> Dọn sạch thùng rác vĩnh viễn trước khi xóa danh mục để tránh lỗi Database
        $trashedProducts = $category->products()->onlyTrashed();
        if ($trashedProducts->count() > 0) {
            $trashedProductIds = $trashedProducts->pluck('id')->toArray();

            // Xóa cứng (force delete) các liên kết khóa ngoại
            \App\Models\ProductImage::whereIn('product_id', $trashedProductIds)->delete();
            \App\Models\Review::whereIn('product_id', $trashedProductIds)->delete();
            \App\Models\Wishlist::whereIn('product_id', $trashedProductIds)->delete();
            \Illuminate\Support\Facades\DB::table('product_tag')->whereIn('product_id', $trashedProductIds)->delete();
            \Illuminate\Support\Facades\DB::table('order_items')->whereIn('product_id', $trashedProductIds)->delete();
            
            // Xóa các bảng liên kết với variants
            $trashedVariants = \App\Models\ProductVariant::withTrashed()->whereIn('product_id', $trashedProductIds);
            $trashedVariantIds = $trashedVariants->pluck('id')->toArray();
            
            // Xóa biến thể (variants) của những sản phẩm đang ở trong thùng rác
            $trashedVariants->forceDelete(); 
            
            // Cuối cùng xóa cứng sản phẩm
            $trashedProducts->forceDelete();
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công.');
    }
}
