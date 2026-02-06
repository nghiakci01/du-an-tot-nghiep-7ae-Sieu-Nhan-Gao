<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LockScreenController extends Controller
{
    public function lock()
    {
        // Don't lock if not logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Session::put('locked', true);
        return view('admin.auth.lock-screen');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (Hash::check($request->password, Auth::user()->password)) {
            Session::forget('locked');
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => 'Mật khẩu không đúng.']);
    }
}
