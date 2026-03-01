<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::orderBy('display_order')->paginate(15);

        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'hex_code' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['hex_code'] = $validated['hex_code'] ?? '#000000';

        Color::create($validated);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color created successfully.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,'.$color->id,
            'hex_code' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['hex_code'] = $validated['hex_code'] ?? '#000000';

        $color->update($validated);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color)
    {
        // Check if color is being used
        if ($color->productVariants()->count() > 0) {
            return redirect()->route('admin.colors.index')
                ->with('error', 'Cannot delete color that is being used by products.');
        }

        $color->delete();

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color deleted successfully.');
    }
}
