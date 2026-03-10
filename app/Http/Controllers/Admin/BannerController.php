<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\Banner;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->latest()->get();

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
    public function store(StoreBannerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if (! $file->isValid()) {
                Log::error('Banner upload thất bại: file không hợp lệ', [
                    'error_code' => $file->getError(),
                    'original_name' => $file->getClientOriginalName(),
                ]);

                return back()->withInput()->withErrors([
                    'image' => 'Tải ảnh thất bại (lỗi server). Vui lòng thử lại.',
                ]);
            }

            $filename = 'banners/'.$file->hashName();
            Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));
            $data['image'] = $filename;
        }

        $data['is_active'] = $request->boolean('is_active', true);

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner được tạo thành công.');
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
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if (! $file->isValid()) {
                Log::error('Banner update upload thất bại: file không hợp lệ', [
                    'error_code' => $file->getError(),
                    'original_name' => $file->getClientOriginalName(),
                ]);

                return back()->withInput()->withErrors([
                    'image' => 'Tải ảnh thất bại (lỗi server). Vui lòng thử lại.',
                ]);
            }

            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }

            $filename = 'banners/'.$file->hashName();
            Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));
            $data['image'] = $filename;
        }

        $data['is_active'] = $request->boolean('is_active');

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner được cập nhật thành công.');
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
