@extends('layouts.public')

@section('title', __('messages.checkout') . ' | FashionStore')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">{{ __('messages.checkout') }}</h2>

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
                            <h5 class="card-title mb-0 fw-bold">{{ __('messages.shipping_information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('messages.full_name') }}</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ Auth::check() ? Auth::user()->name : old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('messages.phone_number') }}</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('messages.shipping_address') }}</label>
                                <textarea class="form-control" id="address" name="address" rows="3"
                                    required>{{ Auth::check() ? Auth::user()->address : old('address') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">{{ __('messages.order_notes') }}</label>
                                <textarea class="form-control" id="note" name="note" rows="2">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 fw-bold">{{ __('messages.your_order') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($cart as $details)
                                    <li class="list-group-item d-flex justify-content-between lh-sm">
                                        <div>
                                            <h6 class="my-0">{{ $details['name'] }}</h6>
                                            <small class="text-muted">Qty: {{ $details['quantity'] }}
                                                ({{ $details['size'] }}/{{ $details['color'] }})</small>
                                        </div>
                                        <span class="text-muted">{{ number_format($details['price'] * $details['quantity']) }}
                                            đ</span>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ __('messages.total') }}</span>
                                    <strong>{{ number_format($total) }} đ</strong>
                                </li>
                            </ul>

                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('messages.payment_method') }}</label>
                                <div class="form-check">
                                    <input id="credit" name="payment_method" type="radio" class="form-check-input"
                                        value="COD" checked required>
                                    <label class="form-check-label"
                                        for="credit">{{ __('messages.cash_on_delivery') }}</label>
                                </div>
                                <div class="form-check">
                                    <input id="debit" name="payment_method" type="radio" class="form-check-input"
                                        value="BANK_TRANSFER" required>
                                    <label class="form-check-label" for="debit">{{ __('messages.bank_transfer') }}</label>
                                </div>
                            </div>

                            <hr class="my-4">

                            <button class="w-100 btn btn-primary btn-lg"
                                type="submit">{{ __('messages.place_order') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection