<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VtonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VtonModelController extends Controller
{
    public function index()
    {
        $models = VtonModel::latest()->paginate(10);
        return view('admin.vton_models.index', compact('models'));
    }

    public function create()
    {
        return view('admin.vton_models.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gender' => 'required|in:male,female,kid',
        ]);

        $data = $request->only(['name', 'gender']);
        $data['is_default'] = $request->has('is_default');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->hashName();
            $tempPath = $file->getRealPath() ?: $file->getPathname();
            
            if ($file->isValid() && !empty($tempPath)) {
                $stream = fopen($tempPath, 'r');
                $stored = Storage::disk('public')->put('vton-models/' . $filename, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }

                if ($stored) {
                    $data['image'] = 'vton-models/' . $filename;
                } else {
                    return redirect()->back()->with('error', 'Không thể lưu tệp tin vào đĩa.')->withInput();
                }
            } else {
                return redirect()->back()->with('error', 'Ảnh tải lên không hợp lệ hoặc không có dữ liệu.')->withInput();
            }
        }

        if ($data['is_default']) {
            VtonModel::where('gender', $data['gender'])->update(['is_default' => false]);
        }

        VtonModel::create($data);

        return redirect()->route('admin.vton-models.index')->with('success', 'Người mẫu đã được thêm thành công.');
    }

    public function edit(VtonModel $vtonModel)
    {
        return view('admin.vton_models.edit', compact('vtonModel'));
    }

    public function update(Request $request, VtonModel $vtonModel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gender' => 'required|in:male,female,kid',
        ]);

        $data = $request->only(['name', 'gender']);
        $data['is_default'] = $request->has('is_default');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $tempPath = $file->getRealPath() ?: $file->getPathname();
            
            if ($file->isValid() && !empty($tempPath)) {
                if ($vtonModel->image) {
                    Storage::disk('public')->delete($vtonModel->image);
                }
                
                $filename = $file->hashName();
                $stream = fopen($tempPath, 'r');
                $stored = Storage::disk('public')->put('vton-models/' . $filename, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }

                if ($stored) {
                    $data['image'] = 'vton-models/' . $filename;
                }
            }
        }

        if ($data['is_default']) {
            VtonModel::where('gender', $data['gender'])
                ->where('id', '!=', $vtonModel->id)
                ->update(['is_default' => false]);
        }

        $vtonModel->update($data);

        return redirect()->route('admin.vton-models.index')->with('success', 'Người mẫu đã được cập nhật thành công.');
    }

    public function destroy(VtonModel $vtonModel)
    {
        if ($vtonModel->image) {
            Storage::disk('public')->delete($vtonModel->image);
        }
        $vtonModel->delete();

        return redirect()->route('admin.vton-models.index')->with('success', 'Người mẫu đã được xóa thành công.');
    }
}
