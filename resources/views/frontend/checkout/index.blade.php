@extends('layouts.public')

@section('title', 'Checkout | FashionStore')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Shipping Info -->
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 fw-bold">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                         <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::check() ? Auth::user()->name : old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ Auth::check() ? Auth::user()->address : old('address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" id="note" name="note" rows="2">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 fw-bold">Your Order</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($cart as $details)
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0">{{ $details['name'] }}</h6>
                                    <small class="text-muted">Qty: {{ $details['quantity'] }} ({{ $details['size'] }}/{{ $details['color'] }})</small>
                                </div>
                                <span class="text-muted">{{ number_format($details['price'] * $details['quantity']) }} đ</span>
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total</span>
                                <strong>{{ number_format($total) }} đ</strong>
                            </li>
                        </ul>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <div class="form-check">
                                <input id="credit" name="payment_method" type="radio" class="form-check-input" value="COD" checked required>
                                <label class="form-check-label" for="credit">Cash on Delivery (COD)</label>
                            </div>
                            <div class="form-check">
                                <input id="debit" name="payment_method" type="radio" class="form-check-input" value="BANK_TRANSFER" required>
                                <label class="form-check-label" for="debit">Bank Transfer</label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button class="w-100 btn btn-primary btn-lg" type="submit">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
