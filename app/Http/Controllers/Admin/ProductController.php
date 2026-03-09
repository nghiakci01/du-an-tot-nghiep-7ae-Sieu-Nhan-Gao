<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $query = Product::with(['category', 'variants'])->withCount('variants');

        if (request()->filled('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request()->filled('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        $products = $query->latest()->paginate(10)->appends(request()->all());

        return view('admin.products.index', compact('products', 'categories'));
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

            $data = $request->except(['variants', 'image']);
            if (empty($data['price'])) {
                $data['price'] = 0;
            }
            $data['slug'] = Str::slug($data['name']).'-'.uniqid(); // Ensure unique slug
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
            $data['image'] = null; // Default to null

            // Handle main product image
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->getRealPath() ?: $file->getPathname();
                if ($file->isValid() && ! empty($path)) {
                    try {
                        $filename = $file->hashName();
                        $stream = fopen($path, 'r');
                        $storedPath = Storage::disk('public')->put('products/'.$filename, $stream);
                        if (is_resource($stream)) {
                            fclose($stream);
                        }

                        if ($storedPath) {
                            $data['image'] = 'products/'.$filename;
                        }
                    } catch (\Exception $e) {
                        \Log::error('Image upload failed: '.$e->getMessage());
                    }
                } else {
                    \Log::warning('Main image upload attempted but file is invalid or path is empty: '.$file->getClientOriginalName());
                }
            }

            $product = Product::create($data);

            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    $size = \App\Models\Size::find($variantData['size_id']);
                    $color = \App\Models\Color::find($variantData['color_id']);

                    $sizeName = $size?->name ?? 'Unknown';
                    $colorName = $color?->name ?? 'Unknown';

                    $sku = $variantData['sku'] ?? null;
                    if (empty($sku)) {
                        $sku = strtoupper(Str::slug($product->name.'-'.$sizeName.'-'.$colorName.'-'.uniqid()));
                    }

                    $product->variants()->create([
                        'size_id' => $variantData['size_id'],
                        'color_id' => $variantData['color_id'],
                        'size' => $sizeName,
                        'color' => $colorName,
                        'price' => $variantData['price'] ?? null,
                        'sale_price' => $variantData['sale_price'] ?? null,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 100, // Default value since it's removed from UI
                        'sku' => $sku,
                    ]);
                }
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->getRealPath() ?: $image->getPathname();
                    if ($image->isValid() && ! empty($path)) {
                        try {
                            $filename = $image->hashName();
                            $stream = fopen($path, 'r');
                            $storedPath = Storage::disk('public')->put('products/gallery/'.$filename, $stream);
                            if (is_resource($stream)) {
                                fclose($stream);
                            }

                            if ($storedPath) {
                                $product->images()->create([
                                    'image_path' => 'products/gallery/'.$filename,
                                    'sort_order' => $index,
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Gallery image upload failed: '.$e->getMessage());
                        }
                    } else {
                        \Log::warning('Gallery image upload attempted but file is invalid or path is empty: '.$image->getClientOriginalName());
                    }
                }
            }

            // Update product base price from variants
            if ($product->variants->isNotEmpty()) {
                $product->price = $product->variants->where('price', '>', 0)->min('price') ?? 0;
                $product->sale_price = $product->variants->where('sale_price', '>', 0)->min('sale_price');
                $product->save();
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error creating product: '.$e->getMessage())->withInput();
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

            $data = $request->except(['variants', 'image']);
            if (empty($data['price'])) {
                $data['price'] = 0;
            }
            $data['slug'] = Str::slug($data['name']).'-'.$product->id;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

            // Handle main product image
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->getRealPath() ?: $file->getPathname();
                if ($file->isValid() && ! empty($path)) {
                    try {
                        // Delete old image
                        if ($product->image) {
                            Storage::disk('public')->delete($product->image);
                        }

                        $filename = $file->hashName();
                        $stream = fopen($path, 'r');
                        $storedPath = Storage::disk('public')->put('products/'.$filename, $stream);
                        if (is_resource($stream)) {
                            fclose($stream);
                        }

                        if ($storedPath) {
                            $data['image'] = 'products/'.$filename;
                        }
                    } catch (\Exception $e) {
                        \Log::error('Image update failed: '.$e->getMessage());
                    }
                } else {
                    \Log::warning('Main image update attempted but file is invalid or path is empty: '.$file->getClientOriginalName());
                }
            }

            $product->update($data);

            // Handle Variants
            $submittedVariants = $request->variants ?? [];
            $submittedIds = array_filter(array_column($submittedVariants, 'id'));

            // Delete variants not in the submitted list
            $product->variants()->whereNotIn('id', $submittedIds)->delete();

            foreach ($submittedVariants as $variantData) {
                $size = \App\Models\Size::find($variantData['size_id']);
                $color = \App\Models\Color::find($variantData['color_id']);

                $sizeName = $size?->name ?? 'Unknown';
                $colorName = $color?->name ?? 'Unknown';

                $sku = $variantData['sku'] ?? null;
                if (empty($sku)) {
                    $sku = strtoupper(Str::slug($product->name.'-'.$sizeName.'-'.$colorName.'-'.uniqid()));
                }

                $variantAttributes = [
                    'size_id' => $variantData['size_id'],
                    'color_id' => $variantData['color_id'],
                    'size' => $sizeName,
                    'color' => $colorName,
                    'price' => $variantData['price'] ?? null,
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 100, // Default value since it's removed from UI
                    'sku' => $sku,
                ];

                if (isset($variantData['id']) && $variantData['id']) {
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update($variantAttributes);
                    }
                } else {
                    $product->variants()->create($variantAttributes);
                }
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                $currentCount = $product->images()->count();
                $newCount = count($request->file('gallery_images'));

                if ($currentCount + $newCount <= 6) {
                    foreach ($request->file('gallery_images') as $index => $image) {
                        $path = $image->getRealPath() ?: $image->getPathname();
                        if ($image->isValid() && ! empty($path)) {
                            try {
                                $filename = $image->hashName();
                                $stream = fopen($path, 'r');
                                $storedPath = Storage::disk('public')->put('products/gallery/'.$filename, $stream);
                                if (is_resource($stream)) {
                                    fclose($stream);
                                }

                                if ($storedPath) {
                                    $product->images()->create([
                                        'image_path' => 'products/gallery/'.$filename,
                                        'sort_order' => $currentCount + $index,
                                    ]);
                                }
                            } catch (\Exception $e) {
                                \Log::error('Gallery image update failed: '.$e->getMessage());
                            }
                        } else {
                            \Log::warning('Gallery image update attempted but file is invalid or path is empty: '.$image->getClientOriginalName());
                        }
                    }
                }
            }

            // Update product base price from variants
            if ($product->variants->isNotEmpty()) {
                $product->price = $product->variants->where('price', '>', 0)->min('price') ?? 0;
                $product->sale_price = $product->variants->where('sale_price', '>', 0)->min('sale_price');
                $product->save();
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error updating product: '.$e->getMessage())->withInput();
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

    /**
     * Search product variants for autocomplete (e.g., in Order Creation)
     */
    public function variantsSearch(Request $request)
    {
        $q = $request->get('q');
        if (empty($q)) {
            return response()->json([]);
        }

        $variants = ProductVariant::with(['product', 'sizeRelationship', 'colorRelationship'])
            ->where('sku', 'like', "%{$q}%")
            ->orWhereHas('product', function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->get();

        $results = $variants->map(function($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->product->name,
                'sku' => $variant->sku,
                'price' => (float)$variant->price,
                'size' => $variant->size ?: ($variant->sizeRelationship ? $variant->sizeRelationship->name : ''),
                'color' => $variant->color ?: ($variant->colorRelationship ? $variant->colorRelationship->name : ''),
                'product' => [
                    'name' => $variant->product->name,
                    'image' => $variant->product->image_url,
                ]
            ];
        });

        return response()->json($results);
    }
}
