@extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng #' . $order->id)

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Chi tiết Đơn hàng #{{ $order->id }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item"><a href="#!">Chi tiết</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Sản phẩm trong đơn</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name }}</strong>
                                    @if($item->variant)
                                        <br><small class="text-muted">Phân loại: {{ $item->variant->name }}</small>
                                    @endif
                                </td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->total, 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th class="text-danger">{{ number_format($order->total_price, 0, ',', '.') }}đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>SĐT:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
                <hr>
                <p><strong>Địa chỉ nhận hàng:</strong><br>{{ $order->shipping_address }}</p>
                <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Trạng thái & Thanh toán</h5>
            </div>
            <div class="card-body">
                <p><strong>Phương thức:</strong> {{ $order->payment_method }}</p>
                <p><strong>Trạng thái:</strong> 
                    <span class="badge {{ $order->status_badge }}">{{ $order->status_text }}</span>
                </p>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Cập nhật trạng thái</label>
                        <select name="status" class="form-select select-sm">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
