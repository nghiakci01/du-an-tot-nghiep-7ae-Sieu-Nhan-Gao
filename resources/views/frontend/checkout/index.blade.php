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

                            <!-- Coupon Section -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mã Giảm Giá</label>
                                @if($coupon)
                                    <!-- Applied Coupon Display -->
                                    <div class="alert alert-success d-flex justify-content-between align-items-center" id="appliedCouponAlert">
                                        <div>
                                            <i class="fa fa-check-circle"></i>
                                            <strong>{{ $coupon->code }}</strong> đã được áp dụng
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeCouponBtn">
                                            <i class="fa fa-times"></i> Xóa
                                        </button>
                                    </div>
                                @else
                                    <!-- Coupon Input -->
                                    <div class="input-group" id="couponInputGroup">
                                        <input type="text" class="form-control" id="couponCode" placeholder="Nhập mã giảm giá">
                                        <button class="btn btn-outline-primary" type="button" id="applyCouponBtn">
                                            Áp dụng
                                        </button>
                                    </div>
                                    <div id="couponMessage" class="mt-2"></div>
                                @endif
                            </div>

                            <!-- Price Breakdown -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span id="subtotal">{{ number_format($total) }} đ</span>
                                </div>
                                @if($discount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Giảm giá:</span>
                                    <span id="discountAmount">-{{ number_format($discount) }} đ</span>
                                </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng cộng:</strong>
                                    <strong class="text-primary" id="finalTotal">{{ number_format($finalTotal) }} đ</strong>
                                </div>
                            </div>

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

@section('scripts')
<script>
$(document).ready(function() {
    // Apply Coupon
    $('#applyCouponBtn').click(function() {
        const couponCode = $('#couponCode').val().trim();
        
        if (!couponCode) {
            showMessage('Vui lòng nhập mã giảm giá.', 'danger');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Đang xử lý...');

        $.ajax({
            url: '{{ route("checkout.applyCoupon") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                coupon_code: couponCode
            },
            success: function(response) {
                if (response.success) {
                    showMessage(response.message, 'success');
                    
                    // Reload page to show applied coupon
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Có lỗi xảy ra, vui lòng thử lại.';
                showMessage(message, 'danger');
                btn.prop('disabled', false).html('Áp dụng');
            }
        });
    });

    // Remove Coupon
    $('#removeCouponBtn').click(function() {
        if (!confirm('Bạn có chắc muốn xóa mã giảm giá?')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("checkout.removeCoupon") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
                btn.prop('disabled', false);
            }
        });
    });

    // Enter key to apply coupon
    $('#couponCode').keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#applyCouponBtn').click();
        }
    });

    function showMessage(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const html = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        $('#couponMessage').html(html);
    }
});
</script>
@endsection
@endsection