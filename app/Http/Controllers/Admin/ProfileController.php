<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->getRealPath() ?: $file->getPathname();

            if ($file->isValid() && ! empty($path)) {
                // Delete old avatar if exists
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $filename = $file->hashName();
                $stream = fopen($path, 'r');
                $storedPath = Storage::disk('public')->put('avatars/'.$filename, $stream);

                if (is_resource($stream)) {
                    fclose($stream);
                }

                if ($storedPath) {
                    $user->avatar = 'avatars/'.$filename;
                }
            }
        }

        $user->save();

        return back()->with('success', 'Cập nhật hồ sơ thành công.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }

    public function checkCurrentPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required'
        ]);

        $isValid = Hash::check($request->current_password, Auth::user()->password);

        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? '' : 'Mật khẩu hiện tại không chính xác.'
        ]);
    }
}
