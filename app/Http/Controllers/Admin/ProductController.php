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
        $products = Product::with(['category', 'variants'])->withCount('variants')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $sizes = \App\Models\Size::active()->orderBy('display_order')->get();
        $colors = \App\Models\Color::active()->orderBy('display_order')->get();
        return view('admin.products.create', compact('categories', 'sizes', 'colors'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->except('variants');
            if (empty($data['price'])) {
                $data['price'] = 0;
            }
            $data['slug'] = Str::slug($data['name']) . '-' . uniqid(); // Ensure unique slug
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product = Product::create($data);

            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    // Lookup Size name and Color name for backward compatibility and SKU generation
                    $sizeName = \App\Models\Size::find($variantData['size_id'])?->name ?? 'Unknown';
                    $colorName = \App\Models\Color::find($variantData['color_id'])?->name ?? 'Unknown';

                    // Auto generate SKU if empty
                    $sku = $variantData['sku'];
                    if (empty($sku)) {
                        $sku = strtoupper(Str::slug($product->name . '-' . $sizeName . '-' . $colorName . '-' . uniqid()));
                    }

                    $product->variants()->create([
                        'size_id' => $variantData['size_id'],
                        'color_id' => $variantData['color_id'],
                        'size' => $sizeName,   // Backward compatibility
                        'color' => $colorName, // Backward compatibility
                        'price' => $variantData['price'] ?? null,
                        'sale_price' => $variantData['sale_price'] ?? null,
                        'stock_quantity' => $variantData['stock_quantity'],
                        'sku' => $sku,
                    ]);
                }
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'sort_order' => $index
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
        $sizes = \App\Models\Size::active()->orderBy('display_order')->get();
        $colors = \App\Models\Color::active()->orderBy('display_order')->get();
        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'colors'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $data = $request->except('variants');
            if (empty($data['price'])) {
                $data['price'] = 0;
            }
            $data['slug'] = Str::slug($data['name']) . '-' . $product->id;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

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
                // Lookup Size name and Color name
                $sizeName = \App\Models\Size::find($variantData['size_id'])?->name ?? 'Unknown';
                $colorName = \App\Models\Color::find($variantData['color_id'])?->name ?? 'Unknown';

                $sku = $variantData['sku'];
                if (empty($sku)) {
                    $sku = strtoupper(Str::slug($product->name . '-' . $sizeName . '-' . $colorName . '-' . uniqid()));
                }

                $variantAttributes = [
                    'size_id' => $variantData['size_id'],
                    'color_id' => $variantData['color_id'],
                    'size' => $sizeName,
                    'color' => $colorName,
                    'price' => $variantData['price'] ?? null,
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'sku' => $sku,
                ];

                if (isset($variantData['id']) && $variantData['id']) {
                    // Update existing
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update($variantAttributes);
                    }
                } else {
                    // Create new
                    $product->variants()->create($variantAttributes);
                }
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                $currentCount = $product->images()->count();
                $newCount = count($request->file('gallery_images'));
                
                if ($currentCount + $newCount <= 6) {
                    foreach ($request->file('gallery_images') as $index => $image) {
                        $path = $image->store('products/gallery', 'public');
                        $product->images()->create([
                            'image_path' => $path,
                            'sort_order' => $currentCount + $index
                        ]);
                    }
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

    public function deleteGalleryImage($imageId)
    {
        try {
            $image = \App\Models\ProductImage::findOrFail($imageId);
            
            // Delete file from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Delete database record
            $image->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
