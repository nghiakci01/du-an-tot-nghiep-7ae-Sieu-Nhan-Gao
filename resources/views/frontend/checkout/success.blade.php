@extends('layouts.public')

@section('title', 'Order Success | FashionStore')

@section('content')
<div class="container py-5 text-center">
    <div class="mb-4">
        <i class="bi bi-check-circle-fill text-success display-1"></i>
    </div>
    <h2 class="fw-bold mb-3">Order placed successfully!</h2>
    <p class="lead text-muted">Thank you for shopping at FashionStore.</p>
    
    <div class="card d-inline-block shadow-sm p-4 mt-3">
        <h5>Order Code: <span class="text-primary">#{{ $order->id }}</span></h5>
        <p class="mb-0">Total money: {{ number_format($order->total_price) }} VND</p>
        <p class="mb-0">Method: {{ $order->payment_method }}</p>
        
        @if($order->payment_method == 'BANK_TRANSFER')
            <div class="alert alert-info mt-3">
                <strong>Bank Transfer Information:</strong><br>
                Bank: Vietcombank<br>
                Account Number: 9999999999<br>
                Account Holder: NGUYEN VAN A<br>
                Content: THANHTOAN DH{{ $order->id }}
            </div>
        @endif
    </div>

    <div class="mt-5">
        <a href="{{ route('shop') }}" class="btn btn-primary">Continue shopping</a>
    </div>
</div>
@endsection
