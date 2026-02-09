<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'position' => 'required|string',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');
        $data['image'] = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->getRealPath() ?: $file->getPathname();
            if ($file->isValid() && !empty($path)) {
                $filename = $file->hashName();
                $stream = fopen($path, 'r');
                Storage::disk('public')->put('banners/' . $filename, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
                $data['image'] = 'banners/' . $filename;
            }
        }

        $data['is_active'] = $request->has('is_active');

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'position' => 'required|string',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->getRealPath() ?: $file->getPathname();
            if ($file->isValid() && !empty($path)) {
                // Delete old image
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                
                $filename = $file->hashName();
                $stream = fopen($path, 'r');
                Storage::disk('public')->put('banners/' . $filename, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
                $data['image'] = 'banners/' . $filename;
            }
        }

        $data['is_active'] = $request->has('is_active');

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
