@extends('layouts.admin')

@section('title', 'Cập nhật Đơn hàng #' . $order->id)

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Cập nhật Đơn hàng #{{ $order->id }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item"><a href="#!">Cập nhật</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Thay đổi trạng thái đơn hàng</h5>
            </div>
            <div class="card-body text-center">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="alert alert-info py-2 mb-4">
                        Đơn hàng của <strong>{{ $order->user->name }}</strong><br>
                        Tổng tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold">Chọn trạng thái mới:</label>
                        <select name="status" class="form-select form-select-lg">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg">Cập nhật ngay</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-lg">Quay lại danh sách</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
