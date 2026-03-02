@extends('layouts.public')

@section('title', __('messages.checkout') . ' | FashionStore')

@section('styles')
    <style>
        /* Reid Template Checkout Styles */
        .breadcrumbs_area {
            background: #f5f5f5;
            padding: 30px 0;
        }

        .breadcrumb_content ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .breadcrumb_content ul li {
            margin-right: 10px;
            color: #666;
        }

        .breadcrumb_content ul li a {
            color: #333;
            text-decoration: none;
        }

        .breadcrumb_content ul li a:hover {
            color: #007bff;
        }

        .Checkout_section {
            padding: 60px 0;
        }

        .user-actions {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }

        .user-actions h3 {
            font-size: 16px;
            margin: 0;
            font-weight: 600;
        }

        .user-actions h3 i {
            margin-right: 10px;
            color: #007bff;
        }

        .user-actions .Returning {
            color: #007bff;
            text-decoration: none;
            margin-left: 10px;
        }

        .user-actions .Returning:hover {
            text-decoration: underline;
        }

        .checkout_info {
            padding: 20px;
            background: white;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .checkout_info p {
            color: #666;
            margin-bottom: 20px;
        }

        .form_group {
            margin-bottom: 20px;
        }

        .form_group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form_group label span {
            color: #dc3545;
        }

        .form_group input,
        .form_group select,
        .form_group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 14px;
        }

        .form_group input:focus,
        .form_group select:focus,
        .form_group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .checkout_form h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }

        .order_table table {
            width: 100%;
            border: 1px solid #dee2e6;
        }

        .order_table table thead th {
            background: #f8f9fa;
            padding: 15px;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .order_table table tbody td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .order_table table tfoot th,
        .order_table table tfoot td {
            padding: 15px;
            font-weight: 600;
            border-top: 2px solid #dee2e6;
        }

        .order_table table tfoot .order_total th,
        .order_table table tfoot .order_total td {
            background: #f8f9fa;
            font-size: 18px;
            color: #007bff;
        }

        .payment_method {
            margin-top: 30px;
        }

        .payment_method .panel-default {
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .payment_method label {
            display: block;
            padding: 15px;
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .payment_method input[type="radio"] {
            margin-right: 10px;
        }

        .payment_method .card-body1 {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .order_button {
            margin-top: 30px;
        }

        .order_button button {
            width: 100%;
            padding: 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .order_button button:hover {
            background: #0056b3;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .card-body1 {
            padding: 15px;
        }

        /* Coupon Section Styles */
        .coupon-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .coupon-applied {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .coupon-applied .coupon-code {
            font-weight: 600;
            color: #155724;
        }

        .coupon-input-group {
            display: flex;
            gap: 10px;
        }

        .coupon-input-group input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .coupon-input-group button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .coupon-input-group button:hover {
            background: #0056b3;
        }

        .discount-row {
            color: #28a745;
            font-weight: 600;
        }

        /* Validation Styles */
        .form_group input.is-invalid,
        .form_group select.is-invalid,
        .form_group textarea.is-invalid,
        input.is-invalid,
        select.is-invalid,
        textarea.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }

        .form_group input.is-valid,
        .form_group select.is-valid,
        .form_group textarea.is-valid,
        input.is-valid,
        select.is-valid,
        textarea.is-valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .valid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #28a745;
        }
    </style>
@endsection

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
                            <li>{{ __('messages.checkout') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!--Checkout page section-->
    <div class="Checkout_section" id="accordion">
        <div class="container">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    @guest
                        <div class="user-actions">
                            <h3>
                                <i class="fa fa-file-o" aria-hidden="true"></i>
                                {{ __('messages.returning_customer') }}?
                                <a class="Returning" href="#" data-bs-toggle="collapse" data-bs-target="#checkout_login"
                                    aria-expanded="false">{{ __('messages.click_here_to_login') }}</a>
                            </h3>
                            <div id="checkout_login" class="collapse" data-bs-parent="#accordion">
                                <div class="checkout_info">
                                    <p>{{ __('messages.login_message') }}</p>
                                    <div class="form_group">
                                        <label>{{ __('messages.email') }} <span>*</span></label>
                                        <input type="text" value="{{ old('email') }}">
                                    </div>
                                    <div class="form_group">
                                        <label>{{ __('messages.password') }} <span>*</span></label>
                                        <input type="password">
                                    </div>
                                    <div class="form_group group_3">
                                        <a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.login') }}</a>
                                        <label for="remember_box" style="margin-left: 15px;">
                                            <input id="remember_box" type="checkbox">
                                            <span> {{ __('messages.remember_me') }} </span>
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}">{{ __('messages.lost_password') }}?</a>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <div class="user-actions">
                        <h3>
                            <i class="fa fa-tag" aria-hidden="true"></i>
                            {{ __('messages.have_coupon') }}?
                            <a class="Returning" href="#" data-bs-toggle="collapse" data-bs-target="#checkout_coupon"
                                aria-expanded="false">{{ __('messages.click_here_enter_code') }}</a>
                        </h3>
                        <div id="checkout_coupon" class="collapse" data-bs-parent="#accordion">
                            <div class="checkout_info">
                                @if($coupon)
                                    <div class="coupon-applied">
                                        <div>
                                            <i class="fa fa-check-circle"></i>
                                            <span class="coupon-code">{{ $coupon->code }}</span> {{ __('messages.applied') }}
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeCouponBtn">
                                            <i class="fa fa-times"></i> {{ __('messages.remove') }}
                                        </button>
                                    </div>
                                @else
                                    <div class="coupon-input-group">
                                        <input type="text" id="couponCode" placeholder="{{ __('messages.coupon_code') }}">
                                        <button type="button" id="applyCouponBtn">{{ __('messages.apply_coupon') }}</button>
                                    </div>
                                    <div id="couponMessage" style="margin-top: 15px;"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="checkout_form">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h3>{{ __('messages.billing_details') }}</h3>
                            <div class="row">
                                <div class="col-lg-6 mb-20">
                                    <label>{{ __('messages.full_name') }} <span>*</span></label>
                                    <input type="text" name="name"
                                        value="{{ Auth::check() ? Auth::user()->name : old('name') }}" required
                                        class="@error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-20">
                                    <label>{{ __('messages.phone_number') }} <span>*</span></label>
                                    <input type="tel" name="phone"
                                        value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}" required
                                        pattern="^(03|05|07|08|09)\d{8}$" class="@error('phone') is-invalid @enderror">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-20">
                                    <label>{{ __('messages.email') }} <span>*</span></label>
                                    <input type="email" name="email"
                                        value="{{ Auth::check() ? Auth::user()->email : old('email') }}" required
                                        class="@error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-20">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="mb-0">{{ __('messages.province_city') }} <span>*</span></label>
                                        <button type="button" id="btn-locate-me" class="btn btn-sm btn-outline-primary"
                                            title="Sá»­ dá»¥ng vá»‹ tr­ hiá»‡n táº¡i cá»§a báº¡n">
                                            <i class="fa fa-map-marker"></i> {{ __('messages.locate_me') }}
                                        </button>
                                    </div>
                                    <select name="province" id="province" required
                                        class="form-control @error('province') is-invalid @enderror">
                                        <option value="">{{ __('messages.select_province') }}</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province }}" {{ (Auth::check() && str_contains(Auth::user()->address, $province)) || old('province') == $province ? 'selected' : '' }}>
                                                {{ $province }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-20">
                                    <label>{{ __('messages.shipping_address') }} <span>*</span></label>
                                    <input placeholder="{{ __('messages.street_address') }}" type="text" name="address"
                                        value="{{ Auth::check() ? Auth::user()->address : old('address') }}" required
                                        minlength="5" class="@error('address') is-invalid @enderror">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="order-notes">
                                        <label for="order_note">{{ __('messages.order_notes') }}</label>
                                        <textarea id="order_note" name="note"
                                            placeholder="{{ __('messages.order_notes_placeholder') }}">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <h3>{{ __('messages.your_order') }}</h3>
                            <div class="order_table table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.product') }}</th>
                                            <th>{{ __('messages.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart as $details)
                                        <tr>
                                            <td>{{ $details['name'] }} 
                                                <strong>&times; {{ $details['quantity'] }}</strong>
                                                <br>
                                                <small class="text-muted">({{ $details['size'] }}/{{ $details['color'] }})</small>
                                            </td>
                                            <td>{{ number_format($details['price'] * $details['quantity']) }} đ</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>{{ __('messages.subtotal') }}</th>
                                            <td>{{ number_format($total) }} VND</td>
                                        </tr>
                                        @if($discount > 0)
                                        <tr class="discount-row">
                                            <th>{{ __('messages.discount') }}</th>
                                            <td>-{{ number_format($discount) }} đ</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>{{ __('messages.shipping') }}</th>
                                            <td><strong>{{ $shippingFee > 0 ? (number_format($shippingFee) . ' đ') : __('messages.free') }}</strong>
                                            </td>
                                        </tr>
                                        <tr class="order_total">
                                            <th>{{ __('messages.order_total') }}</th>
                                            <td><strong>{{ number_format($finalTotal) }} VND</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="payment_method">
                                <div class="panel-default">
                                    <input id="payment_cod" name="payment_method" type="radio" value="COD"
                                        data-bs-target="createp_account" checked required />
                                    <label for="payment_cod" data-bs-toggle="collapse" data-bs-target="#method_cod"
                                        aria-controls="method_cod">
                                        {{ __('messages.cash_on_delivery') }}
                                    </label>

                                    <div id="method_cod" class="collapse show" data-bs-parent="#accordion">
                                        <div class="card-body1">
                                            <p>{{ __('messages.cod_description') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-default">
                                    <input id="payment_bank" name="payment_method" type="radio" value="BANK_TRANSFER"
                                        data-bs-target="createp_account" required />
                                    <label for="payment_bank" data-bs-toggle="collapse" data-bs-target="#method_bank"
                                        aria-controls="method_bank">
                                        {{ __('messages.bank_transfer') }}
                                    </label>

                                    <div id="method_bank" class="collapse" data-bs-parent="#accordion">
                                        <div class="card-body1">
                                            <p>{{ __('messages.bank_transfer_description') }}</p>
                                            <div class="bank-details-qr mt-3 p-3 border rounded bg-light">
                                                <div class="row align-items-center">
                                                    <div class="col-md-7">
                                                        <ul class="list-unstyled mb-0">
                                                            <li><strong>Ngân hàng:</strong> {{ \App\Models\Setting::get('bank_name', 'MB Bank') }}</li>
                                                            <li><strong>Số tài khoản:</strong> <span class="text-primary fw-bold">{{ \App\Models\Setting::get('bank_account_number', '0359756805') }}</span></li>
                                                            <li><strong>Chủ tài khoản:</strong> {{ \App\Models\Setting::get('bank_account_name', 'NGUYEN CONG BANG') }}</li>
                                                            <li><strong>Nội dung:</strong> <span class="text-danger fw-bold">THANHTOAN DH[M đơn hàng]</span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-5 text-center mt-3 mt-md-0">
                                                        <img src="https://img.vietqr.io/image/{{ \App\Models\Setting::get('bank_id', 'MB') }}-{{ \App\Models\Setting::get('bank_account_number', '0359756805') }}-compact.png?amount={{ $finalTotal }}&addInfo=THANHTOAN%20DH&accountName={{ urlencode(\App\Models\Setting::get('bank_account_name', 'NGUYEN CONG BANG')) }}" 
                                                             alt="Bank transfer QR" class="img-fluid rounded shadow-sm" style="max-width: 150px;">
                                                        <p class="small text-muted mt-2 mb-0">Quét để thanh toán</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-default">
                                    <input id="payment_vnpay" name="payment_method" type="radio" value="VNPAY"
                                        data-bs-target="createp_account" required />
                                    <label for="payment_vnpay" data-bs-toggle="collapse" data-bs-target="#method_vnpay"
                                        aria-controls="method_vnpay">
                                        {{ __('messages.vnpay_payment') }}
                                    </label>

                                    <div id="method_vnpay" class="collapse" data-bs-parent="#accordion">
                                        <div class="card-body1">
                                            <p>{{ __('messages.vnpay_description') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-default">
                                    <input id="payment_zalopay" name="payment_method" type="radio" value="ZALOPAY"
                                        data-bs-target="createp_account" required />
                                    <label for="payment_zalopay" data-bs-toggle="collapse" data-bs-target="#method_zalopay"
                                        aria-controls="method_zalopay">
                                        Thanh toán qua Ví điện tử ZaloPay
                                    </label>

                                    <div id="method_zalopay" class="collapse" data-bs-parent="#accordion">
                                        <div class="card-body1">
                                            <p>Thanh toán nhanh chóng, an toàn qua ứng dụng ZaloPay hoặc quét mã QR.</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="order_button">
                                    <button type="submit">{{ __('messages.place_order') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Checkout page section end-->
@endsection


@section('scripts')
    <script>
        $(document).ready(function () {

            // ============ COUPON ============
            $('#applyCouponBtn').click(function () {
                const couponCode = $('#couponCode').val().trim();
                if (!couponCode) {
                    showCouponMessage('Vui lòng nhập m giảm giá', 'danger');
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
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            }).then(function () { location.reload(); });
                        } else {
                            showCouponMessage(response.message, 'danger');
                            btn.prop('disabled', false).html('Áp dụng');
                        }
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Có lỗi xảy ra, vui lòng thử lại!';
                        showCouponMessage(message, 'danger');
                        btn.prop('disabled', false).html('Áp dụng');
                    }
                });
            });

            $('#removeCouponBtn').click(function () {
                Swal.fire({
                    title: 'Xóa m giảm giá?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: '#dc3545',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("checkout.removeCoupon") }}',
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                if (response.success) location.reload();
                            },
                            error: function () {
                                Swal.fire({ icon: 'error', title: 'Lỗi!', text: 'Có lỗi xảy ra!' });
                            }
                        });
                    }
                });
            });

            $('#couponCode').keypress(function (e) {
                if (e.which === 13) { e.preventDefault(); $('#applyCouponBtn').click(); }
            });

            function showCouponMessage(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                $('#couponMessage').html(`<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);
            }

            // ============ FORM VALIDATION ============
            function validateName(value) {
                if (!value || value.trim().length < 2) return 'Vui lòng nhập họ tên (ít nhất 2 ký tự)';
                return '';
            }
            function validatePhone(value) {
                if (!value) return 'Vui lòng nhập số điện thoại';
                if (!/^(03|05|07|08|09)\d{8}$/.test(value)) return 'Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số';
                return '';
            }
            function validateEmail(value) {
                if (!value) return 'Vui lòng nhập email';
                if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)) return 'Email không hợp lệ';
                return '';
            }
            function validateAddress(value) {
                if (!value || value.trim().length < 5) return 'Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường)';
                return '';
            }
            function validateProvince(value) {
                if (!value) return 'Vui lòng chọn tỉnh thành';
                return '';
            }

            function showValidation(input, errorMessage) {
                const $input = $(input);
                const $feedback = $input.next('.invalid-feedback');
                if (errorMessage) {
                    $input.removeClass('is-valid').addClass('is-invalid');
                    if ($feedback.length) $feedback.text(errorMessage);
                    else $input.after(`<div class="invalid-feedback">${errorMessage}</div>`);
                } else {
                    $input.removeClass('is-invalid').addClass('is-valid');
                    $feedback.remove();
                }
            }

            $('input[name="name"]').on('blur', function () { showValidation(this, validateName($(this).val())); });
            $('input[name="phone"]').on('blur', function () { showValidation(this, validatePhone($(this).val())); });
            $('input[name="email"]').on('blur', function () { showValidation(this, validateEmail($(this).val())); });
            $('input[name="address"]').on('blur', function () { showValidation(this, validateAddress($(this).val())); });
            $('select[name="province"]').on('change', function () { showValidation(this, validateProvince($(this).val())); });
            $('input[name="name"],input[name="phone"],input[name="email"],input[name="address"],select[name="province"]').on('input change', function () {
                if ($(this).hasClass('is-invalid')) { $(this).removeClass('is-invalid').next('.invalid-feedback').remove(); }
            });

            $('form').on('submit', function (e) {
                const nameError     = validateName($('input[name="name"]').val());
                const phoneError    = validatePhone($('input[name="phone"]').val());
                const emailError    = validateEmail($('input[name="email"]').val());
                const provinceError = validateProvince($('select[name="province"]').val());
                const addressError  = validateAddress($('input[name="address"]').val());
                showValidation('input[name="name"]', nameError);
                showValidation('input[name="phone"]', phoneError);
                showValidation('input[name="email"]', emailError);
                showValidation('select[name="province"]', provinceError);
                showValidation('input[name="address"]', addressError);
                if (nameError || phoneError || emailError || provinceError || addressError) {
                    e.preventDefault();
                    const firstError = $('.is-invalid').first();
                    if (firstError.length) $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                    return false;
                }
                if (!$('input[name="payment_method"]:checked').length) {
                    e.preventDefault();
                    Swal.fire({ icon: 'warning', title: 'Chưa chọn thanh toán', text: 'Vui lòng chọn phương thức thanh toán.' });
                    return false;
                }
                $(this).find('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Đang xử lý...');
            });

            // ============ GEOLOCATION ============
            $('#btn-locate-me').click(function () {
                if (!navigator.geolocation) {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Trình duyệt của bạn không hỗ trợ định vị.' });
                    return;
                }
                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Đang định vị...');

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        $.ajax({
                            url: `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1&accept-language=vi`,
                            method: 'GET',
                            success: function (data) {
                                if (data && data.address) {
                                    const address = data.address;
                                    let provinceName = address.city || address.province || address.state || address.town;
                                    if (provinceName) {
                                        provinceName = provinceName.replace('Thành phố ', '').replace('Tỉnh ', '').trim();
                                        if (provinceName.toLowerCase().includes('hồ chí minh')) provinceName = 'TP Hồ Chí Minh';
                                        let matchedProvince = '';
                                        $('#province option').each(function () {
                                            const optionText = $(this).val();
                                            if (provinceName.toLowerCase() === optionText.toLowerCase() || optionText.toLowerCase().includes(provinceName.toLowerCase())) {
                                                matchedProvince = optionText; return false;
                                            }
                                        });
                                        if (matchedProvince) $('#province').val(matchedProvince).trigger('change');
                                    }
                                    const road = address.road || '', suburb = address.suburb || address.neighbourhood || '',
                                          quarter = address.quarter || '', district = address.district || address.city_district || '';
                                    const streetAddress = [road, suburb, quarter, district].filter(Boolean).join(', ');
                                    if (streetAddress) { $('input[name="address"]').val(streetAddress); showValidation($('input[name="address"]')[0], ''); }
                                    Swal.fire({ icon: 'success', title: 'Thành công', text: 'Đ cập nhật địa chỉ từ vị trí của bạn.', timer: 2000, showConfirmButton: false });
                                }
                            },
                            error: function () { Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể lấy thông tin địa chỉ từ tọa độ.' }); },
                            complete: function () { $btn.prop('disabled', false).html(originalHtml); }
                        });
                    },
                    function (error) {
                        let message = 'Không thể lấy vị trí của bạn.';
                        if (error.code === 1) message = 'Bạn đ từ chối quyền truy cập vị trí.';
                        else if (error.code === 2) message = 'Không thể xác định vị trí.';
                        else if (error.code === 3) message = 'Hết thời gian yêu cầu vị trí.';
                        Swal.fire({ icon: 'warning', title: 'Thông báo', text: message });
                        $btn.prop('disabled', false).html(originalHtml);
                    },
                    { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                );
            });
        });
    </script>
@endsection

