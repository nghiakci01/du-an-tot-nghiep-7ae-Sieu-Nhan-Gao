<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function create()
    {
        return view('frontend.account.address_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_name' => 'required|string|max:100',
            'phone'         => ['required', 'regex:/^(03|05|07|08|09)\d{8}$/'],
            'province'      => 'required|string|max:100',
            'commune'       => 'required|string|max:100',
            'address'       => 'required|string|max:255',
            'is_default'    => 'nullable|boolean',
        ], [
            'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng 03, 05, 07, 08 hoặc 09.',
        ]);

        $user = Auth::user();
        $isDefault = $request->boolean('is_default');

        // Bỏ default của các địa chỉ cũ nếu set default mới
        if ($isDefault) {
            UserAddress::where('user_id', $user->id)->update(['is_default' => false]);
        }

        // Nếu là địa chỉ đầu tiên → tự động set default
        $hasExisting = UserAddress::where('user_id', $user->id)->exists();
        $validated['user_id']    = $user->id;
        $validated['is_default'] = $isDefault || !$hasExisting;

        UserAddress::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'address' => UserAddress::where('user_id', $user->id)
                    ->orderByDesc('id')->first(),
            ]);
        }

        return redirect()->route('account.index')
            ->with('success', 'Đã thêm địa chỉ mới thành công!');
    }

    public function edit($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        return view('frontend.account.address_edit', compact('address'));
    }

    public function update(Request $request, $id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'receiver_name' => 'required|string|max:100',
            'phone'         => ['required', 'regex:/^(03|05|07|08|09)\d{8}$/'],
            'province'      => 'required|string|max:100',
            'commune'       => 'required|string|max:100',
            'address'       => 'required|string|max:255',
            'is_default'    => 'nullable|boolean',
        ], [
            'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng 03, 05, 07, 08 hoặc 09.',
        ]);

        if ($request->boolean('is_default')) {
            UserAddress::where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $address->update($validated);
        $address->refresh();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'address' => $address,
            ]);
        }

        return redirect()->route('account.index')
            ->with('success', 'Địa chỉ đã được cập nhật!');
    }

    public function destroy($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        // Nếu xoá địa chỉ mặc định → set địa chỉ còn lại đầu tiên làm default
        if ($wasDefault) {
            UserAddress::where('user_id', Auth::id())->oldest()->first()?->update(['is_default' => true]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xoá địa chỉ!',
            ]);
        }

        return back()->with('success', 'Đã xoá địa chỉ!');
    }

    public function setDefault($id)
    {
        $user = Auth::user();
        UserAddress::where('user_id', $user->id)->update(['is_default' => false]);
        UserAddress::where('user_id', $user->id)->findOrFail($id)->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt làm địa chỉ mặc định!');
    }
}
