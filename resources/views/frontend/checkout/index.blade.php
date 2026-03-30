@extends('layouts.public')

@section('title', __('messages.checkout') . ' | Elite')

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

    <!--checkout progress bar start-->
    <div class="container" style="padding: 30px 0 10px;">
        <div class="d-flex justify-content-center align-items-center" style="gap: 0;">
            <div class="text-center checkout-step-item active" id="step-item-1" style="flex: 1; max-width: 160px;">
                <div class="step-icon" style="width: 40px; height: 40px; border-radius: 50%; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-weight: 700; transition: all 0.3s;">
                    1
                </div>
                <div class="step-label" style="font-size: 12px; font-weight: 700; color: #007bff;">Giao hàng</div>
            </div>
            <div class="step-line" id="step-line-1" style="flex: 1; max-width: 100px; height: 3px; background: #dee2e6; margin-bottom: 20px; transition: all 0.3s;"></div>
            <div class="text-center checkout-step-item" id="step-item-2" style="flex: 1; max-width: 160px;">
                <div class="step-icon" style="width: 40px; height: 40px; border-radius: 50%; background: #dee2e6; color: #999; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-weight: 700; transition: all 0.3s;">
                    2
                </div>
                <div class="step-label" style="font-size: 12px; color: #999;">Thanh toán</div>
            </div>
            <div class="step-line" id="step-line-2" style="flex: 1; max-width: 100px; height: 3px; background: #dee2e6; margin-bottom: 20px; transition: all 0.3s;"></div>
            <div class="text-center checkout-step-item" id="step-item-3" style="flex: 1; max-width: 160px;">
                <div class="step-icon" style="width: 40px; height: 40px; border-radius: 50%; background: #dee2e6; color: #999; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-weight: 700; transition: all 0.3s;">
                    3
                </div>
                <div class="step-label" style="font-size: 12px; color: #999;">Xác nhận</div>
            </div>
        </div>
    </div>
    <!--checkout progress bar end-->

    <!--Checkout page section-->
    <div class="Checkout_section" id="accordion">
        <div class="container">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <a href="{{ route('password.request') }}">{{ __('messages.lost_password') }}?</a>
                                        <a href="javascript:void(0)" onclick="$('#checkout_login').collapse('hide')" class="text-secondary" style="text-decoration: none;"><i class="fa fa-angle-up"></i> Thu gọn</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <div class="user-actions">
                        <h3 style="margin-bottom: 15px;">
                            <i class="fa fa-tag" aria-hidden="true"></i>
                            {{ __('messages.have_coupon') }}?
                        </h3>
                        <div id="checkout_coupon">
                            <div class="checkout_info" style="border-top: 1px solid #dee2e6;">
                                <div id="couponMessage"></div>
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="checkout_form">
                    <!-- STEP 1: SHIPPING DETAILS -->
                    <div id="checkout-step-1" class="checkout-step-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>{{ __('messages.billing_details') }}</h3>
                                <div class="row">
                                <div class="col-lg-6 mb-20">
                                    <label>{{ __('messages.full_name') }} <span>*</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" required
                                        class="@error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-20">
                                    <label>{{ __('messages.phone_number') }} <span>*</span></label>
                                    <input type="tel" name="phone"
                                        value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}" required
                                        pattern="^(03|05|07|08|09)\d{8}$" class="@error('phone') is-invalid @enderror">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-20">
                                    <label>{{ __('messages.email') }} <span>*</span></label>
                                    <input type="email" name="email"
                                        value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required
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
                                            <option value="{{ $province }}" {{ old('province') ? (old('province') == $province ? 'selected' : '') : (Auth::check() && str_contains(Auth::user()->address, $province) ? 'selected' : '') }}>
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
                                        value="{{ old('address', Auth::check() ? Auth::user()->address : '') }}" required
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
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary btn-lg px-5 float-end" id="btn-next-step-1">
                                    {{ __('messages.continue') }} <i class="fa fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: PAYMENT & SHIPPING METHOD -->
                    <div id="checkout-step-2" class="checkout-step-content" style="display: none;">
                        <div class="row">
                            <div class="col-lg-7 col-md-6">
                                <div class="shipping_method mb-4" id="shipping_method_container">
                                    <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #007bff;">Phương thức vận chuyển</h3>
                                    <div id="shipping_options">
                                        <!-- Options will be loaded via AJAX -->
                                        <p class="text-muted">Vui lòng hoàn tất thông tin địa chỉ ở bước trước.</p>
                                    </div>
                                    <input type="hidden" name="shipping_fee" id="hidden_shipping_fee" value="0">
                                    <input type="hidden" name="shipping_service_name" id="hidden_shipping_service_name" value="">
                                </div>

                                    <div class="payment_method">
                                        <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #007bff;">Phương thức thanh toán</h3>


                                        <div class="panel-default">
                                            <input id="payment_cod" name="payment_method" type="radio" value="COD"
                                                data-bs-target="#method_cod" checked required />
                                            <label for="payment_cod" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#method_cod"
                                                aria-controls="method_cod">
                                                {{ __('messages.cash_on_delivery') }}
                                            </label>

                                            <div id="method_cod" class="collapse show" data-bs-parent="#accordion">
                                            </div>
                                        </div>


                                        <div class="panel-default mt-3">
                                            <input id="payment_bank" name="payment_method" type="radio" value="BANK_TRANSFER"
                                                data-bs-target="#method_bank" required />
                                            <label for="payment_bank" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#method_bank"
                                                aria-controls="method_bank">
                                                Chuyển khoản ngân hàng trực tiếp (VietQR)
                                            </label>
                                            <div id="method_bank" class="collapse" data-bs-parent="#accordion">
                                                <div class="card-body1 p-3 bg-light rounded mt-2">
                                                    <div class="alert alert-info py-2 mb-0 small">
                                                        <i class="fa fa-info-circle"></i> Bạn sẽ nhận được thông tin số tài khoản và mã QR để chuyển khoản sau khi nhấn "Đặt hàng".
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel-default mt-3">
                                            <input id="payment_vnpay" name="payment_method" type="radio" value="VNPAY"
                                                data-bs-target="#method_vnpay" required />
                                            <label for="payment_vnpay" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#method_vnpay"
                                                aria-controls="method_vnpay">
                                                <i class="fa fa-credit-card"></i> VNPay (Thẻ ATM, Ví điện tử, QR Code)
                                            </label>
                                            <div id="method_vnpay" class="collapse" data-bs-parent="#accordion">
                                                <div class="card-body1 p-3 bg-light rounded mt-2">
                                                    <div class="alert alert-info py-2 mb-0 small">
                                                        <i class="fa fa-info-circle"></i> Thanh toán an toàn qua VNPay. Bạn sẽ được chuyển hướng đến trang thanh toán VNPay sau khi nhấn "Đặt hàng".
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <h3>Tóm tắt đơn hàng</h3>
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
                                                <td>{{ $details['name'] }} <strong>&times; {{ $details['quantity'] }}</strong></td>
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
                                                <td id="shipping_fee_display"><strong>Chưa tính</strong></td>
                                            </tr>
                                            <tr class="order_total">
                                                <th>{{ __('messages.order_total') }}</th>
                                                <td id="final_total_display"><strong>{{ number_format($finalTotal) }} VND</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-lg px-5" id="btn-prev-step-2">
                                    <i class="fa fa-arrow-left me-2"></i> Quay lại
                                </button>
                                <button type="button" class="btn btn-primary btn-lg px-5" id="btn-next-step-2">
                                    Tiếp tục <i class="fa fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: FINAL CONFIRMATION -->
                    <div id="checkout-step-3" class="checkout-step-content" style="display: none;">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>{{ __('messages.your_order') }}</h3>
                                <div class="alert alert-info mb-4">
                                    <i class="fa fa-info-circle"></i> Vui lòng kiểm tra kỹ thông tin đơn hàng lần cuối trước khi đặt.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold">Thông tin nhận hàng</h5>
                                                <p class="mb-1"><strong>Người nhận:</strong> <span id="confirm-name"></span></p>
                                                <p class="mb-1"><strong>SĐT:</strong> <span id="confirm-phone"></span></p>
                                                <p class="mb-1"><strong>Email:</strong> <span id="confirm-email"></span></p>
                                                <p class="mb-1"><strong>Địa chỉ:</strong> <span id="confirm-address"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold">Thanh toán & Vận chuyển</h5>
                                                <p class="mb-1"><strong>Vận chuyển:</strong> <span id="confirm-shipping"></span></p>
                                                <p class="mb-1"><strong>Thanh toán:</strong> <span id="confirm-payment"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                            <tr class="order_total">
                                                <th>{{ __('messages.order_total') }}</th>
                                                <td id="final_total_display_confirm"><strong>{{ number_format($finalTotal) }} VND</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Bank Transfer Detail & QR Code -->
                                <div id="bank-transfer-info" class="mt-4 p-4 border rounded bg-white shadow-sm" style="display: none;">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                                <i class="fa fa-university me-2 text-primary"></i> Thông tin chuyển khoản
                                            </h5>
                                            <div class="mb-2">
                                                <span class="text-muted">Ngân hàng:</span>
                                                <div class="fw-bold">{{ $defaultBank->bank_name ?? 'N/A' }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted">Số tài khoản:</span>
                                                <div class="fw-bold fs-5 text-primary">{{ $defaultBank->account_number ?? 'N/A' }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted">Chủ tài khoản:</span>
                                                <div class="fw-bold">{{ $defaultBank->account_name ?? 'N/A' }}</div>
                                            </div>
                                            <div class="mb-0">
                                                <span class="text-muted">Nội dung chuyển khoản:</span>
                                                <div class="fw-bold text-danger">THANHTOAN ELITE</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center border-start">
                                            <h6 class="fw-bold mb-2">Quét mã QR để thanh toán nhanh</h6>
                                            <div class="qr-container bg-light p-2 rounded d-inline-block border">
                                                <img id="bank_qr_image" src="" alt="VietQR" style="max-width: 250px; height: auto;">
                                            </div>
                                            <p class="text-muted small mt-2 mb-0">Sử dụng ứng dụng Ngân hàng hoặc Ví điện tử để quét</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="order_button mt-4">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary btn-lg px-5" id="btn-prev-step-3" style="width: auto;">
                                            <i class="fa fa-arrow-left me-2"></i> Quay lại
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg px-5" style="width: auto; background: #28a745; border-color: #28a745;">
                                            <i class="fa fa-check-circle me-2"></i> {{ __('messages.place_order') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
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
    <div id="checkout-config" style="display: none;"
        data-csrf="{{ csrf_token() }}"
        data-route-inventory="{{ route('api.checkout.checkInventory') }}"
        data-route-coupon-apply="{{ route('checkout.applyCoupon') }}"
        data-route-coupon-remove="{{ route('checkout.removeCoupon') }}"
        data-route-cart="{{ route('cart.index') }}"
        data-route-shipping="{{ url('/api/checkout/shipping-fees') }}"
        data-base-total="{{ $total - $discount }}"
        data-bank-account="{{ isset($defaultBank) ? $defaultBank->account_number : '0' }}"
        data-bank-id="{{ isset($defaultBank) ? $defaultBank->bank_id : 'X' }}"
        data-bank-name="{{ isset($defaultBank) ? urlencode($defaultBank->account_name) : 'X' }}"
        data-msg-continue="{{ __('messages.continue') }}"
    ></div>
    <script>
        $(document).ready(function () {
            const config = document.getElementById('checkout-config').dataset;
            // ============ MULTI-STEP LOGIC ============
            let currentStep = {{ session('checkout_step', 1) }};

            if (currentStep === 2) {
                $('#checkout-step-1').hide();
                $('#checkout-step-2').show();
                updateProgressBar(2);
                
                // Trigger check inventory and shipping setup in background
                checkInventoryAsync().then(response => {
                    if ($('select[name="province"]').val()) {
                        calculateShippingFees($('select[name="province"]').val());
                    }
                }).catch(xhr => {
                    // if fails silently go back to step 1
                    $('#checkout-step-2').hide();
                    $('#checkout-step-1').show();
                    currentStep = 1;
                    updateProgressBar(1);
                });
            }

            function updateProgressBar(step) {
                $('.checkout-step-item').each(function(index) {
                    const itemStep = index + 1;
                    const $item = $(this);
                    const $icon = $item.find('.step-icon');
                    const $label = $item.find('.step-label');
                    const $line = $(`#step-line-${itemStep}`);

                    if (itemStep < step) {
                        // Completed steps
                        $icon.css({'background': '#28a745', 'color': 'white', 'box-shadow': 'none'}).html('<i class="fa fa-check"></i>');
                        $label.css({'color': '#28a745', 'font-weight': '600'});
                        $line.css('background', '#28a745');
                    } else if (itemStep === step) {
                        // Current step
                        $icon.css({'background': '#007bff', 'color': 'white', 'box-shadow': '0 0 0 4px rgba(0,123,255,0.2)'}).html(itemStep);
                        $label.css({'color': '#007bff', 'font-weight': '700'});
                        $line.css('background', '#dee2e6');
                    } else {
                        // Future steps
                        $icon.css({'background': '#dee2e6', 'color': '#999', 'box-shadow': 'none'}).html(itemStep);
                        $label.css({'color': '#999', 'font-weight': 'normal'});
                        $line.css('background', '#dee2e6');
                    }
                });
            }

            function checkInventoryAsync() {
                return $.ajax({
                    url: config.routeInventory,
                    method: 'POST',
                    data: { _token: config.csrf }
                });
            }

            function handleInventoryErrors(errors) {
                let errorHtml = '<ul class="text-start mb-0" style="list-style-type: disc; padding-left: 20px;">';
                errors.forEach(err => {
                    errorHtml += `<li class="mb-2"><strong>${err.name}</strong>: ${err.message}</li>`;
                });
                errorHtml += '</ul>';

                Swal.fire({
                    title: 'Lỗi tồn kho!',
                    html: errorHtml,
                    icon: 'error',
                    confirmButtonText: 'Quay lại giỏ hàng',
                    showCancelButton: true,
                    cancelButtonText: 'Chỉnh sửa lại',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = config.routeCart;
                    }
                });
            }

            $('#btn-next-step-1').click(async function() {
                // Validation Step 1
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

                if (nameError || phoneError || emailError || provinceError || addressError) return;

                // Real-time Inventory Check
                const $btn = $(this);
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Đang kiểm tra kho...');

                try {
                    const response = await checkInventoryAsync();
                    if (response.success) {
                        $('#checkout-step-1').fadeOut(300, function() {
                            $('#checkout-step-2').fadeIn(300);
                            currentStep = 2;
                            updateProgressBar(2);
                            $('html, body').animate({ scrollTop: 0 }, 500);
                        });
                    }
                } catch (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        handleInventoryErrors(xhr.responseJSON.errors);
                    } else {
                        Swal.fire('Lỗi!', 'Không thể kiểm tra tồn kho lúc này.', 'error');
                    }
                } finally {
                    $btn.prop('disabled', false).html(config.msgContinue + ' <i class="fa fa-arrow-right ms-2"></i>');
                }
            });

            $('#btn-prev-step-2').click(function() {
                $('#checkout-step-2').fadeOut(300, function() {
                    $('#checkout-step-1').fadeIn(300);
                    currentStep = 1;
                    updateProgressBar(1);
                    $('html, body').animate({ scrollTop: 0 }, 500);
                });
            });

            $('#btn-next-step-2').click(async function() {
                if (!$('input[name="payment_method"]:checked').length) {
                    Swal.fire({ icon: 'warning', title: 'Cảnh báo', text: 'Vui lòng chọn phương thức thanh toán.' });
                    return;
                }

                if (!$('input[name="shipping_provider"]:checked').length) {
                    Swal.fire({ icon: 'warning', title: 'Cảnh báo', text: 'Vui lòng chọn đơn vị vận chuyển.' });
                    return;
                }

                // Inventory Check Again
                const $btn = $(this);
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Kiểm tra kho lần 2...');

                try {
                    await checkInventoryAsync();
                    // Update confirmation data
                    $('#confirm-name').text($('input[name="name"]').val());
                    $('#confirm-phone').text($('input[name="phone"]').val());
                    $('#confirm-email').text($('input[name="email"]').val());
                    $('#confirm-address').text($('input[name="address"]').val() + ', ' + $('select[name="province"]').val());
                    $('#confirm-shipping').text($('input[name="shipping_provider"]:checked').data('service-name'));

                    const pMethod = $('input[name="payment_method"]:checked').val();
                    let pMethodText = 'Tiền mặt khi nhận hàng';
                    if (pMethod === 'VNPAY') pMethodText = 'VNPAY (ATM/Banking)';
                    if (pMethod === 'BANK_TRANSFER') pMethodText = 'Chuyển khoản ngân hàng';

                    $('#confirm-payment').text(pMethodText);

                    // Show/hide bank transfer info
                    if (pMethod === 'BANK_TRANSFER') {
                        $('#bank-transfer-info').show();
                    } else {
                        $('#bank-transfer-info').hide();
                    }

                    $('#final_total_display_confirm').html($('#final_total_display').html());

                    $('#checkout-step-2').fadeOut(300, function() {
                        $('#checkout-step-3').fadeIn(300);
                        currentStep = 3;
                        updateProgressBar(3);
                        $('html, body').animate({ scrollTop: 0 }, 500);
                    });
                } catch (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        handleInventoryErrors(xhr.responseJSON.errors);
                    }
                } finally {
                    $btn.prop('disabled', false).html('Tiếp tục <i class="fa fa-arrow-right ms-2"></i>');
                }
            });

            $('#btn-prev-step-3').click(function() {
                $('#checkout-step-3').fadeOut(300, function() {
                    $('#checkout-step-2').fadeIn(300);
                    currentStep = 2;
                    updateProgressBar(2);
                    $('html, body').animate({ scrollTop: 0 }, 500);
                });
            });

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
                    url: config.routeCouponApply,
                    method: 'POST',
                    data: {
                        _token: config.csrf,
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
                            url: config.routeCouponRemove,
                            method: 'POST',
                            data: { _token: config.csrf },
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

            // ============ SHIPPING FEES CALCULATION ============
            let baseTotal = parseInt(config.baseTotal);

            function calculateShippingFees(province) {
                if (!province) {
                    $('#shipping_method_container').hide();
                    updateTotals(0);
                    return;
                }

                $('#shipping_method_container').show();
                $('#shipping_options').html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-primary"></span> Đang tính phí vận chuyển...</div>');

                $.ajax({
                    url: config.routeShipping,
                    method: 'POST',
                    data: {
                        _token: config.csrf,
                        province: province,
                        district: 'Quận/Huyện',
                        ward: 'Phường/Xã',
                        weight: 1000
                    },
                    success: function (response) {
                        if (response.success && response.data && response.data.length > 0) {
                            let html = '';
                            response.data.forEach((option, index) => {
                                let checked = index === 0 ? 'checked' : '';
                                html += `
                                    <div class="panel-default mb-2 border rounded p-3" style="border: 1px solid #dee2e6; margin-bottom: 10px;">
                                        <input id="shipping_${option.provider}" name="shipping_provider" type="radio" value="${option.provider}"
                                            data-fee="${option.fee}" data-service-name="${option.service_name}" ${checked} required style="margin-right: 10px;" />
                                        <label for="shipping_${option.provider}" class="mb-0" style="cursor: pointer; font-weight: 500; display: inline-block;">
                                            ${option.service_name} - <span class="text-primary fw-bold">${new Intl.NumberFormat('vi-VN').format(option.fee)} đ</span>
                                        </label>
                                        <small class="d-block text-muted" style="margin-left: 25px; margin-top: 5px;">Thời gian dự kiến: ${option.expected_delivery_time}</small>
                                    </div>
                                `;
                            });
                            $('#shipping_options').html(html);

                            // Trigger selection for the first one
                            $('input[name="shipping_provider"]:checked').trigger('change');
                        } else {
                            $('#shipping_options').html('<div class="alert alert-warning">Không thể tính phí vận chuyển lúc này.</div>');
                        }
                    },
                    error: function () {
                        $('#shipping_options').html('<div class="alert alert-danger">Lỗi kết nối khi tính phí vận chuyển.</div>');
                    }
                });
            }

            $(document).on('change', 'input[name="shipping_provider"]', function() {
                let fee = $(this).data('fee');
                let serviceName = $(this).data('service-name');

                $('#hidden_shipping_fee').val(fee);
                $('#hidden_shipping_service_name').val(serviceName);

                updateTotals(fee);
            });

            function updateTotals(shippingFee) {
                let finalTotal = baseTotal + parseInt(shippingFee);

                // Update shipping display
                if (shippingFee > 0) {
                    $('#shipping_fee_display').html('<strong>' + new Intl.NumberFormat('vi-VN').format(shippingFee) + ' đ</strong>');
                } else {
                    $('#shipping_fee_display').html('<strong>Miễn phí</strong>');
                }

                // Update final total display
                $('#final_total_display').html('<strong>' + new Intl.NumberFormat('vi-VN').format(finalTotal) + ' VND</strong>');

                // Update QR code amount if banking selected
                let bankAccount = config.bankAccount;
                let bankId = config.bankId;
                let bankName = config.bankName;
                let qrUrl = `https://img.vietqr.io/image/${bankId}-${bankAccount}-compact.png?amount=${finalTotal}&addInfo=THANHTOAN%20ELITE&accountName=${bankName}`;

                if (bankAccount !== '0') {
                    $('#bank_qr_image').attr('src', qrUrl);
                }
            }

            // Trigger on load if province is already selected
            if ($('select[name="province"]').val()) {
                calculateShippingFees($('select[name="province"]').val());
            }

            $('select[name="province"]').on('change', function () {
                showValidation(this, validateProvince($(this).val()));
                calculateShippingFees($(this).val());
            });

            $('input[name="payment_method"]').on('change', function() {
                var targetId = $(this).attr('data-bs-target');

                // Ẩn tất cả các panel thanh toán
                $('#method_cod, #method_bank, #method_vnpay').collapse('hide');

                // Hiện panel của phương thức được chọn
                $(targetId).collapse('show');
            });

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

