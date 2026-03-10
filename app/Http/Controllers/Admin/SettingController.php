<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        // Handle file uploads for settings
        foreach ($request->allFiles() as $key => $file) {
            $path = $file->store('settings', 'public');
            $data[$key] = $path;
            
            // Delete old file if exists
            $oldSetting = Setting::where('key', $key)->first();
            if ($oldSetting && $oldSetting->value) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldSetting->value);
            }
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear settings cache
        \Illuminate\Support\Facades\Cache::forget('global_settings');

        return back()->with('success', 'Cài đặt đã được cập nhật thành công!');
    }
}
