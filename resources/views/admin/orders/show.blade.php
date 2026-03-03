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
            <div class="col-md-12 text-end mt-3 d-flex justify-content-end align-items-center">
                @php
                    $qrContent = "DonHang:" . $order->id . "|SDT:" . ($order->user ? ($order->user->phone ?? 'N/A') : ($order->phone ? $order->phone : 'N/A')) . "|Tien:" . number_format($order->final_total ?? $order->total_price, 0, '', '');
                    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=" . urlencode($qrContent);
                @endphp
                <div class="me-3 text-center border p-1 bg-white rounded shadow-sm d-inline-block">
                    <img src="{{ $qrCodeUrl }}" alt="Mã QR Đơn Hàng" style="width: 60px; height: 60px;">
                    <div style="font-size: 10px; color: #555; margin-top: 2px;">Mã theo dõi</div>
                </div>
                <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-primary">
                    <i class="feather icon-printer"></i> In Hóa Đơn
                </a>
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
                <p><strong>Tên:</strong> {{ $order->user ? $order->user->name : 'Khách vãng lai' }}</p>
                <p><strong>Email:</strong> {{ $order->user ? $order->user->email : 'N/A' }}</p>
                <p><strong>SĐT:</strong> {{ $order->user ? ($order->user->phone ?? 'N/A') : 'N/A' }}</p>
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
                <div class="mb-3">
                    <p><strong>Phương thức:</strong> {{ $order->payment_method }}</p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="badge {{ $order->status_badge }}">{{ $order->status_text }}</span>
                    </p>
                </div>

                @if($order->canTransitionTo($order->status)) 
                {{-- Only show form if there are transitions available (excluding self) --}}
                @php
                    $allowed = $order->getAllowedTransitions();
                @endphp
                
                @if(count($allowed) > 0)
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Cập nhật trạng thái</label>
                        <select name="status" class="form-select select-sm">
                            <option value="">-- Chọn trạng thái --</option>
                            @foreach($allowed as $status)
                                <option value="{{ $status }}">
                                    @switch($status)
                                        @case(\App\Models\Order::STATUS_CONFIRMED) Đã xác nhận @break
                                        @case(\App\Models\Order::STATUS_SHIPPED) Đang giao hàng @break
                                        @case(\App\Models\Order::STATUS_COMPLETED) Hoàn thành @break
                                        @case(\App\Models\Order::STATUS_CANCELLED) Hủy đơn @break
                                        @case(\App\Models\Order::STATUS_FAILED) Thất bại @break
                                        @case(\App\Models\Order::STATUS_RETURNED) Trả hàng @break
                                        @default {{ $status }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Lưu thay đổi</button>
                </form>
                @endif
                @endif

                <hr>
                <h6>Lịch sử đơn hàng</h6>
                <ul class="list-unstyled">
                    @forelse($order->histories as $history)
                        <li class="mb-2">
                            <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                            <br>
                            <strong>{{ $history->user ? $history->user->name : 'Hệ thống' }}</strong>: 
                            Chuyển từ <span class="badge bg-secondary">{{ $history->previous_status ?? 'N/A' }}</span> 
                            sang <span class="badge bg-primary">{{ $history->new_status }}</span>
                            @if($history->note)
                                <br><em>{{ $history->note }}</em>
                            @endif
                        </li>
                    @empty
                        <li>Chưa có lịch sử.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
