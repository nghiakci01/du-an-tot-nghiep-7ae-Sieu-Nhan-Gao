<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('display_order')->paginate(15);

        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(\App\Http\Requests\Generated\SizeRequest $request)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        Size::create($validated);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(\App\Http\Requests\Generated\SizeRequest $request, Size $size)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $size->update($validated);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        // Check if size is being used
        if ($size->productVariants()->count() > 0) {
            return redirect()->route('admin.sizes.index')
                ->with('error', 'Cannot delete size that is being used by products.');
        }

        $size->delete();

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size deleted successfully.');
    }
}
