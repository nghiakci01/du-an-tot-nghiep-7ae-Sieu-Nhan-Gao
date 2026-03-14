@extends('layouts.admin')

@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Mã Giảm Giá</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Mã Giảm Giá</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Danh sách Mã Giảm Giá</h5>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã Code</th>
                                <th>Loại</th>
                                <th>Giá trị</th>
                                <th>Đơn tối thiểu</th>
                                <th>Lượt dùng</th>
                                <th>Thời hạn</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($coupons) > 0)
                                @foreach($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td><strong>{{ $coupon->code }}</strong></td>
                                <td>
                                    @if($coupon->type === 'percentage')
                                        <span class="badge bg-info">Phần trăm</span>
                                    @else
                                        <span class="badge bg-primary">Cố định</span>
                                    @endif
                                </td>
                                <td>{{ $coupon->getFormattedValue() }}</td>
                                <td>
                                    @if($coupon->min_order_amount)
                                        {{ number_format($coupon->min_order_amount, 0, ',', '.') }} VNĐ
                                    @else
                                        <span class="text-muted">Không</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->usage_limit)
                                        <span class="badge {{ $coupon->hasReachedUsageLimit() ? 'bg-danger' : 'bg-success' }}">
                                            {{ $coupon->used_count }}/{{ $coupon->usage_limit }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $coupon->used_count }}/∞</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->start_date || $coupon->end_date)
                                        <small>
                                            @if($coupon->start_date)
                                                {{ $coupon->start_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                            đến
                                            @if($coupon->end_date)
                                                {{ $coupon->end_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">Vô thời hạn</span>
                                    @endif
                                </td>
                                <td>{!! $coupon->getStatusBadge() !!}</td>
                                <td>
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="text-center">Chưa có mã giảm giá nào.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
