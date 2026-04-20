<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Coupon::query();

        if (request()->filled('search')) {
            $query->where('code', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('status')) {
            $status = request('status');
            if ($status === 'active') {
                $query->active()->where(function($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })->where(function($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })->where(function($q) {
                    $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
                });
            } elseif ($status === 'expired') {
                $query->whereNotNull('end_date')->where('end_date', '<', now());
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::where('role', \App\Models\User::ROLE_USER)
            ->orderBy('name')
            ->get();

        return view('admin.coupons.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request)
    {
        $data = $request->validated();

        Coupon::create($data);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $users = \App\Models\User::where('role', \App\Models\User::ROLE_USER)
            ->orderBy('name')
            ->get();

        return view('admin.coupons.edit', compact('coupon', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();

        $coupon->update($data);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        if ($coupon->used_count > 0) {
            return redirect()
                ->route('admin.coupons.index')
                ->with('error', 'Không thể xóa mã giảm giá đã được sử dụng.');
        }

        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Mã giảm giá đã được xóa thành công.');
    }
}
