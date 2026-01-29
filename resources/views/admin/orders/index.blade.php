@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Đơn hàng</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Đơn hàng</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Danh sách Đơn hàng</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>P.thức T.toán</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <strong>{{ $order->user->name }}</strong><br>
                                    <small>{{ $order->user->email }}</small>
                                </td>
                                <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                <td>{{ $order->payment_method }}</td>
                                <td>
                                    <span class="badge {{ $order->status_badge }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('H:i d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info btn-sm">Xem</a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    @if($order->status === 'cancelled' || $order->status === 'completed')
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa đơn hàng này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">Chưa có đơn hàng nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
