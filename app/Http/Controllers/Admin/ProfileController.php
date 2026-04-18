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

    public function update(\App\Http\Requests\Generated\ProfileUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

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

    public function updatePassword(\App\Http\Requests\Generated\ProfilePasswordRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validated();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }

    public function checkCurrentPassword(\App\Http\Requests\Generated\ProfileCheckPasswordRequest $request)
    {
        $validated = $request->validated();

        $isValid = Hash::check($validated['current_password'], Auth::user()->password);

        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? '' : 'Mật khẩu hiện tại không chính xác.'
        ]);
    }
}
