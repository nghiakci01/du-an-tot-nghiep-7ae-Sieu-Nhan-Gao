<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->withCount('variants')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->except('variants');
            $data['slug'] = Str::slug($data['name']) . '-' . uniqid(); // Ensure unique slug
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product = Product::create($data);

            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    // Auto generate SKU if empty: PROD-ID-SIZE-COLOR (simplified)
                    $sku = $variantData['sku'];
                    if (empty($sku)) {
                        $sku = strtoupper(Str::slug($product->name . '-' . $variantData['size'] . '-' . $variantData['color']));
                        // Basic check to ensure uniqueness or append random string if needed, skipping for now
                    }

                    $product->variants()->create([
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'stock_quantity' => $variantData['stock_quantity'],
                        'sku' => $sku,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $data = $request->except('variants');
            $data['slug'] = Str::slug($data['name']) . '-' . $product->id;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product->update($data);

            // Handle Variants
            $submittedVariants = $request->variants ?? [];
            $submittedIds = array_filter(array_column($submittedVariants, 'id'));

            // Delete variants not in the submitted list
            $product->variants()->whereNotIn('id', $submittedIds)->delete();

            foreach ($submittedVariants as $variantData) {
                $sku = $variantData['sku'];
                if (empty($sku)) {
                    $sku = strtoupper(Str::slug($product->name . '-' . $variantData['size'] . '-' . $variantData['color']));
                }

                if (isset($variantData['id']) && $variantData['id']) {
                    // Update existing
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'size' => $variantData['size'],
                            'color' => $variantData['color'],
                            'stock_quantity' => $variantData['stock_quantity'],
                            'sku' => $sku,
                        ]);
                    }
                } else {
                    // Create new
                    $product->variants()->create([
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'stock_quantity' => $variantData['stock_quantity'],
                        'sku' => $sku,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Variants deleted via cascade if set in DB, but manually here to be safe if not
            $product->variants()->delete();
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.products.index')->with('error', 'Error deleting product.');
        }
    }
}
