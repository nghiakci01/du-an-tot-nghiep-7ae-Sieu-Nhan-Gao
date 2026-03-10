@extends('layouts.public')

@section('title', 'Chi tiết đơn hàng #' . $order->id . ' | Elite')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li><a href="{{ route('order-tracking.index') }}">Tra cứu đơn hàng</a></li>
                            <li>/</li>
                            <li>Đơn hàng #{{ $order->id }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

<div class="tracking-area mt-5 mb-5">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Chi tiết đơn hàng #{{ $order->id }}</h2>
                <a href="{{ route('order-tracking.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại tra cứu
                </a>
            </div>

            <div class="row g-4">
                <!-- Thông tin trạng thái -->
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="text-muted small text-uppercase fw-bold mb-2">Trạng thái đơn hàng</h5>
                                    <h3 class="mb-0">
                                        @php
                                            $statusLabel = [
                                                'pending' => ['label' => 'Đang chờ xử lý', 'class' => 'text-warning'],
                                                'processing' => ['label' => 'Đang chuẩn bị hàng', 'class' => 'text-info'],
                                                'shipping' => ['label' => 'Đang giao hàng', 'class' => 'text-primary'],
                                                'delivered' => ['label' => 'Đã giao hàng', 'class' => 'text-success'],
                                                'completed' => ['label' => 'Hoàn tất', 'class' => 'text-success'],
                                                'cancelled' => ['label' => 'Đã hủy', 'class' => 'text-danger'],
                                            ];
                                            $currentStatus = $statusLabel[$order->status] ?? ['label' => $order->status, 'class' => 'text-secondary'];
                                        @endphp
                                        <span class="{{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
                                    </h3>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <p class="text-muted mb-1">Ngày đặt hàng: <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></p>
                                    <p class="text-muted mb-0">Phương thức: <strong>{{ $order->payment_method }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin khách hàng & Vận chuyển -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Thông tin nhận hàng</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <label class="text-muted small d-block">Người nhận</label>
                                    <div class="fw-bold">{{ $order->name }}</div>
                                </li>
                                <li class="mb-3">
                                    <label class="text-muted small d-block">Số điện thoại</label>
                                    <div class="fw-bold">{{ $order->phone }}</div>
                                </li>
                                <li class="mb-3">
                                    <label class="text-muted small d-block">Địa chỉ</label>
                                    <div class="fw-bold">{{ $order->address }}, {{ $order->province }}</div>
                                </li>
                                <li>
                                    <label class="text-muted small d-block">Ghi chú vận chuyển</label>
                                    <div class="text-muted italic">{{ $order->note ?: 'Không có' }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Danh sách sản phẩm</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Sản phẩm</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-end pe-4">Giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold">{{ $item->product->name }}</div>
                                                <small class="text-muted">
                                                    {{ $item->variant->color }} / {{ $item->variant->size }}
                                                </small>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end pe-4">{{ number_format($item->price * $item->quantity) }} đ</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-end border-0 pt-3">Tạm tính:</td>
                                            <td class="text-end pe-4 border-0 pt-3">{{ number_format($order->total_price) }} đ</td>
                                        </tr>
                                        @if($order->discount_amount > 0)
                                        <tr>
                                            <td colspan="2" class="text-end border-0 text-success">Giảm giá:</td>
                                            <td class="text-end pe-4 border-0 text-success">-{{ number_format($order->discount_amount) }} đ</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="2" class="text-end border-0">Phí vận chuyển:</td>
                                            <td class="text-end pe-4 border-0">{{ number_format($order->shipping_fee) }} đ</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end border-0 fw-bold fs-5">Tổng cộng:</td>
                                            <td class="text-end pe-4 border-0 fw-bold fs-5 text-danger">{{ number_format($order->final_total) }} đ</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($order->status == 'completed')
                <div class="col-12 mt-4">
                    <div class="alert alert-success border-0 shadow-sm p-4 d-flex align-items-center">
                        <i class="fa fa-check-circle fs-1 me-4"></i>
                        <div>
                            <h4 class="mb-1">Đơn hàng đã được hoàn tất!</h4>
                            <p class="mb-0">Cảm ơn bạn đã lựa chọn cửa hàng chúng tôi. Hy vọng sớm được phục vụ bạn lần nữa.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
