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
        <p class="mb-0">Tổng tiền: {{ number_format($order->total_price) }} đ</p>
        <p class="mb-0">Phương thức: {{ $order->payment_method }}</p>
        
        @if($order->payment_method == 'BANK_TRANSFER')
            <div class="alert alert-info mt-3">
                <strong>Thông tin chuyển khoản:</strong><br>
                Ngân hàng: Vietcombank<br>
                STK: 9999999999<br>
                Chủ TK: NGUYEN VAN A<br>
                Nội dung: THANHTOAN DH{{ $order->id }}
            </div>
        @endif
    </div>

    <div class="mt-5">
        <a href="{{ route('shop') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
    </div>
</div>
@endsection
