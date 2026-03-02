@extends('layouts.public')

@section('title', 'Đặt hàng thành công | ' . ($settings['site_title'] ?? 'Elite'))

@section('content')
<div class="container py-5 text-center">
    <div class="mb-4">
        <i class="bi bi-check-circle-fill text-success display-1"></i>
    </div>
    <h2 class="fw-bold mb-3">Đặt hàng thành công!</h2>
    <p class="lead text-muted">Cảm ơn bạn đã mua sắm tại {{ $settings['site_title'] ?? 'Elite' }}.</p>
    
    <div class="card d-inline-block shadow-sm p-4 mt-3">
        <h5>Mã đơn hàng: <span class="text-primary">#{{ $order->id }}</span></h5>
        
        @php
            // Tính tổng đúng: sản phẩm + phí ship - giảm giá
            $shippingFee = $order->shipping_fee ?? 0;
            $displayTotal = ($order->final_total > 0) ? $order->final_total : ($order->total_price + $shippingFee);
        @endphp

        @if($order->discount_amount > 0)
            <p class="mb-1 text-muted"><del>Giá gốc: {{ number_format($order->total_price + $shippingFee) }} đ</del></p>
            <p class="mb-1 text-success">Giảm giá: -{{ number_format($order->discount_amount) }} đ <small>({{ $order->coupon_code }})</small></p>
        @endif

        @if($shippingFee > 0)
            <p class="mb-1 text-muted">Phí vận chuyển: {{ number_format($shippingFee) }} đ</p>
        @endif

        <h5 class="mb-3 fw-bold">Tổng thanh toán: <span class="text-danger">{{ number_format($displayTotal) }} đ</span></h5>

        <p class="mb-0">Phương thức: 
            @if($order->payment_method == 'COD') Thanh toán khi nhận hàng (COD)
            @elseif($order->payment_method == 'BANK_TRANSFER') Chuyển khoản ngân hàng
            @else {{ $order->payment_method }}
            @endif
        </p>
        
        @if($order->payment_method == 'BANK_TRANSFER')
            <div class="alert alert-info mt-3 text-start">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <strong>Thông tin chuyển khoản:</strong><br>
                        Ngân hàng: {{ $bankName }}<br>
                        Số tài khoản: <strong>{{ $bankAccount }}</strong><br>
                        Chủ tài khoản: {{ $bankOwner }}<br>
                        Số tiền: <strong class="text-danger">{{ number_format($displayTotal) }} đ</strong><br>
                        Nội dung: <span class="text-danger fw-bold">THANHTOAN DH{{ $order->id }}</span>
                    </div>
                    <div class="col-md-5 text-center mt-3 mt-md-0">
                        <img src="https://img.vietqr.io/image/{{ $bankId }}-{{ $bankAccount }}-compact.png?amount={{ $displayTotal }}&addInfo=THANHTOAN%20DH{{ $order->id }}&accountName={{ urlencode($bankOwner) }}" 
                             alt="VietQR" class="img-fluid border rounded shadow-sm" style="max-width: 200px;">
                        <p class="small text-muted mt-2 mb-0">Quét mã để thanh toán nhanh</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-5">
        <a href="{{ route('account.index') }}?tab=orders" class="btn btn-outline-secondary me-2">
            <i class="fa fa-list"></i> Xem đơn hàng của tôi
        </a>
        <a href="{{ route('shop') }}" class="btn btn-primary">
            <i class="fa fa-shopping-bag"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>
@endsection
