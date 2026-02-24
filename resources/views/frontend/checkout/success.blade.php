@extends('layouts.public')

@section('title', 'Đặt hàng thành công | FashionStore')

@section('content')
<div class="container py-5 text-center">
    <div class="mb-4">
        <i class="bi bi-check-circle-fill text-success display-1"></i>
    </div>
    <h2 class="fw-bold mb-3">Đặt hàng thành công!</h2>
    <p class="lead text-muted">Cảm ơn bạn đã mua hàng tại FashionStore.</p>
    
    <div class="card d-inline-block shadow-sm p-4 mt-3">
        <h5>Mã đơn hàng: <span class="text-primary">#{{ $order->id }}</span></h5>
        
        @if($order->discount_amount > 0)
            <p class="mb-1 text-muted"><del>Giá gốc: {{ number_format($order->total_price) }} đ</del></p>
            <p class="mb-1 text-success">Giảm giá: -{{ number_format($order->discount_amount) }} đ <small>({{ $order->coupon_code }})</small></p>
            <h5 class="mb-3 fw-bold">Tổng thanh toán: <span class="text-danger">{{ number_format($order->final_total) }} đ</span></h5>
        @else
            <h5 class="mb-3 fw-bold">Tổng thanh toán: <span class="text-danger">{{ number_format($order->total_price) }} đ</span></h5>
        @endif

        <p class="mb-0">Phương thức: {{ $order->payment_method }}</p>
        
        @if($order->payment_method == 'BANK_TRANSFER')
            <div class="alert alert-info mt-3 text-start">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <strong>Thông tin chuyển khoản:</strong><br>
                        Ngân hàng: {{ $bankName }}<br>
                        Số tài khoản: <strong>{{ $bankAccount }}</strong><br>
                        Chủ tài khoản: {{ $bankOwner }}<br>
                        Số tiền: <strong class="text-danger">{{ number_format($order->final_total > 0 ? $order->final_total : $order->total_price) }} đ</strong><br>
                        Nội dung: <span class="text-danger fw-bold">THANHTOAN DH{{ $order->id }}</span>
                    </div>
                    <div class="col-md-5 text-center mt-3 mt-md-0">
                        <img src="https://img.vietqr.io/image/{{ $bankId }}-{{ $bankAccount }}-compact.png?amount={{ $order->final_total > 0 ? $order->final_total : $order->total_price }}&addInfo=THANHTOAN%20DH{{ $order->id }}&accountName={{ urlencode($bankOwner) }}" 
                             alt="VietQR" class="img-fluid border rounded shadow-sm" style="max-width: 200px;">
                        <p class="small text-muted mt-2 mb-0">Quét mã để thanh toán nhanh</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-5">
        <a href="{{ route('shop') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
    </div>
</div>
@endsection
