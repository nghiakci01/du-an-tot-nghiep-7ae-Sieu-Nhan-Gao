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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
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
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary btn-sm"><i class="feather icon-plus"></i> Thêm đơn hàng</a>
                </div>
                
                <div class="card-body border-bottom bg-light bg-opacity-50">
                    <form action="{{ route('admin.orders.trigger-auto-cancel') }}" method="POST" class="d-flex align-items-center flex-wrap gap-2">
                        @csrf
                        <div class="d-flex align-items-center">
                            <label for="auto_cancel_unpaid_order_minutes" class="form-label mb-0 me-2 text-nowrap font-weight-bold text-danger">
                                <i class="feather icon-trash-2"></i> Tự động Hủy đơn chưa thanh toán sau (Phút):
                            </label>
                            <input type="number" class="form-control form-control-sm" name="auto_cancel_unpaid_order_minutes" id="auto_cancel_unpaid_order_minutes" 
                                value="{{ \App\Models\Setting::where('key', 'auto_cancel_unpaid_order_minutes')->value('value') ?? '60' }}" 
                                style="width: 80px;" min="5" required>
                        </div>
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hệ thống sẽ lưu cấu hình này và quét để HỦY các đơn hàng chưa thanh toán quá thời gian quy định theo hệ thống giờ bạn vừa nhập. Dữ liệu đơn hàng VẪN ĐƯỢC GIỮ LẠI với trạng thái Đã Hủy, sản phẩm sẽ được hoàn lại vào kho. Bạn có chắc chắn?')">
                            Lưu cấu hình & Chạy kiểm tra hủy đơn ngay
                        </button>
                    </form>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-3 d-flex align-items-center">
                        <label for="status" class="form-label mb-0 me-2 text-nowrap">Trạng thái:</label>
                        <select name="status" id="status" class="form-select form-select-sm w-auto me-2">
                            <option value="">Tất cả</option>
                            <option value="{{ \App\Models\Order::STATUS_PENDING }}" {{ request('status') == \App\Models\Order::STATUS_PENDING ? 'selected' : '' }}>Chờ xác nhận
                            </option>
                            <option value="{{ \App\Models\Order::STATUS_CONFIRMED }}" {{ request('status') == \App\Models\Order::STATUS_CONFIRMED ? 'selected' : '' }}>Đã xác nhận
                            </option>
                            <option value="{{ \App\Models\Order::STATUS_SHIPPED }}" {{ request('status') == \App\Models\Order::STATUS_SHIPPED ? 'selected' : '' }}>Đang giao hàng
                            </option>
                            <option value="{{ \App\Models\Order::STATUS_COMPLETED }}" {{ request('status') == \App\Models\Order::STATUS_COMPLETED ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="{{ \App\Models\Order::STATUS_CANCELLED }}" {{ request('status') == \App\Models\Order::STATUS_CANCELLED ? 'selected' : '' }}>Đã hủy</option>
                            <option value="{{ \App\Models\Order::STATUS_RETURNED }}" {{ request('status') == \App\Models\Order::STATUS_RETURNED ? 'selected' : '' }}>Khách hoàn hàng</option>
                            <option value="{{ \App\Models\Order::STATUS_PARTIALLY_RETURNED }}" {{ request('status') == \App\Models\Order::STATUS_PARTIALLY_RETURNED ? 'selected' : '' }}>Hoàn hàng một phần</option>
                            <option value="{{ \App\Models\Order::STATUS_FAILED }}" {{ request('status') == \App\Models\Order::STATUS_FAILED ? 'selected' : '' }}>Thất bại</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
                    </form>
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
                                    <th class="sticky-action-column">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($orders) > 0)
                                    @foreach($orders as $order) @php /** @var \App\Models\Order $order */ @endphp
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>
                                            @if($order->user)
                                                <strong>{{ $order->user->name }}</strong><br>
                                                <small>{{ $order->user->email }}</small>
                                            @else
                                                <strong>Khách vãng lai</strong><br>
                                                <small>Không có tài khoản</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td>
                                            <span class="badge {{ $order->status_badge }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="sticky-action-column">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="btn btn-info btn-sm">Xem</a>
                                            <a href="{{ route('admin.orders.edit', $order) }}"
                                                class="btn btn-warning btn-sm">Sửa</a>
                                            <!-- @if($order->status === \App\Models\Order::STATUS_CANCELLED || $order->status === \App\Models\Order::STATUS_COMPLETED)
                                                <form id="delete-form-odr-{{ $order->id }}"
                                                    action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete('delete-form-odr-{{ $order->id }}')">Xóa</button>
                                                </form>
                                            @endif -->
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">Chưa có đơn hàng nào.</td>
                                    </tr>
                                @endif
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