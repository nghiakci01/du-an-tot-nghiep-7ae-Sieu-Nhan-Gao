@extends('layouts.public')

@section('title', __('messages.checkout') . ' | Elite')

@push('styles')
    <style>
        /* aristino-style checkout redesign */
        .checkout-container {
            max-width: 1100px !important;
            margin: 0 auto !important;
            padding: 40px 15px !important;
            font-family: 'Inter', sans-serif !important;
            color: #333 !important;
        }

        .checkout-title {
            font-size: 24px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            margin-bottom: 5px !important;
            color: #000 !important;
            letter-spacing: 0.5px !important;
        }

        .checkout-breadcrumbs {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            font-size: 13px !important;
            color: #888 !important;
            margin-bottom: 30px !important;
            padding: 0 !important;
            list-style: none !important;
        }

        .checkout-breadcrumbs a {
            color: #888 !important;
            text-decoration: none !important;
        }

        .checkout-breadcrumbs .active {
            color: #333 !important;
            font-weight: 500 !important;
        }

        /* login prompt box */
        .login-prompt-box {
            background: #fdfdfd !important;
            border: 1px solid #eee !important;
            padding: 20px !important;
            margin-bottom: 30px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            border-radius: 4px !important;
        }

        .login-prompt-text {
            font-size: 14px !important;
            line-height: 1.5 !important;
        }

        .btn-login-outline {
            border: 1px solid #000 !important;
            padding: 10px 25px !important;
            background: transparent !important;
            color: #000 !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 12px !important;
            text-decoration: none !important;
            border-radius: 2px !important;
        }

        .user-profile-section {
            background: #fdfdfd !important;
            border: 1px solid #eee !important;
            padding: 20px !important;
            margin-bottom: 30px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            border-radius: 4px !important;
        }

        /* grid layouts */
        .field-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 15px !important;
            margin-bottom: 20px !important;
        }

        .address-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 15px !important;
            margin-top: 15px !important;
        }

        .form-section-title {
            font-size: 13px !important;
            color: #666 !important;
            margin-bottom: 8px !important;
            font-weight: 500 !important;
            text-transform: none !important;
        }

        .modern-input {
            width: 100% !important;
            padding: 12px 15px !important;
            border: 1px solid #e1e1e1 !important;
            border-radius: 4px !important;
            font-size: 14px !important;
            color: #333 !important;
            margin-bottom: 20px !important;
            background: #fff !important;
        }

        .modern-input:focus {
            border-color: #000 !important;
            outline: none !important;
        }

        .modern-select {
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 15px center !important;
            background-color: #fff !important;
        }

        /* delivery method toggle */
        .delivery-method-box {
            border: 1px solid #e1e1e1 !important;
            border-radius: 4px !important;
            margin-bottom: 20px !important;
            overflow: hidden !important;
        }

        .delivery-option {
            padding: 15px !important;
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
            cursor: pointer !important;
            border-bottom: 1px solid #eee !important;
        }

        .delivery-option:last-child {
            border-bottom: none !important;
        }

        .delivery-option input[type="radio"] {
            width: 18px !important;
            height: 18px !important;
            margin: 0 !important;
            cursor: pointer !important;
            accent-color: #000 !important;
        }

        .delivery-option label {
            margin: 0 !important;
            font-weight: 500 !important;
            color: #000 !important;
            font-size: 14px !important;
            cursor: pointer !important;
        }

        .delivery-content {
            padding: 20px !important;
            background: #fff !important;
            border-top: 1px solid #eee !important;
        }

        /* order summary refinement */
        .summary-card {
            background: #fff !important;
            padding: 0 0 0 30px !important;
            position: sticky !important;
            top: 20px !important;
        }

        .summary-title {
            font-size: 15px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            margin-bottom: 25px !important;
            color: #000 !important;
            letter-spacing: 0.5px !important;
        }

        .summary-row {
            display: flex !important;
            justify-content: space-between !important;
            margin-bottom: 12px !important;
            font-size: 14px !important;
            color: #333 !important;
        }

        .summary-row.total {
            margin-top: 20px !important;
            padding-top: 15px !important;
            border-top: 1px dashed #ddd !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        /* buttons with aristino style icon */
        .btn-primary-black {
            background: #222 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 2px !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            text-transform: uppercase !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 15px !important;
            height: 48px !important;
            width: 100% !important;
            transition: background 0.3s !important;
        }

        .btn-primary-black .arrow-box {
            width: 24px !important;
            height: 24px !important;
            background: #fff !important;
            color: #222 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 2px !important;
            font-size: 12px !important;
        }

        .btn-apply-coupon {
            background: #222 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 0 2px 2px 0 !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            padding: 0 15px !important;
            height: 48px !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
        }

        .coupon-group {
            display: flex !important;
            margin-top: 20px !important;
            border: 1px solid #ddd !important;
            border-radius: 2px !important;
        }

        .coupon-group input {
            border: none !important;
            flex: 1 !important;
            padding: 0 15px !important;
            font-size: 13px !important;
            height: 46px !important;
        }

        @media (max-width: 991px) {
            .field-grid, .address-grid {
                grid-template-columns: 1fr !important;
            }
            .summary-card {
                padding: 30px 0 0 0 !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="checkout-container">
        <h1 class="checkout-title">THÔNG TIN</h1>
        <div class="checkout-breadcrumbs">
            <a href="{{ route('cart.index') }}">Giỏ hàng</a>
            <i class="fa fa-angle-right"></i>
            <span id="breadcrumb-step-1" class="active">Thông tin giao hàng</span>
            <i class="fa fa-angle-right"></i>
            <span id="breadcrumb-step-2">Phương thức thanh toán</span>
        </div>
         <!-- Checkout page section -->
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
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
            <!-- Left Column: Form Steps -->
            <div class="col-lg-7">
                @if(Auth::check())
                    <div class="user-profile-section">
                        <div class="user-profile-info">
                            <div class="user-profile-avatar">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="user-profile-details">
                                <span class="user-profile-name">{{ Auth::user()->name }}</span>
                                <span class="user-profile-email">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                        <a href="{{ route('logout') }}" class="btn-logout-small" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                @endif

                <div class="checkout_form">
                    <!-- STEP 1: SHIPPING DETAILS -->
                    <div id="checkout-step-1" class="checkout-step-content">
                        @if(!Auth::check())
                            <div class="login-prompt-box">
                                <div class="login-prompt-text">
                                    <strong>Bạn đã có tài khoản?</strong><br>
                                    <span class="text-muted small">Đăng nhập để có trải nghiệm thanh toán nhanh nhất</span>
                                </div>
                                <a href="{{ route('login') }}" class="btn-login-outline">Đăng nhập</a>
                            </div>
                        @endif

                        <div class="form-section-title">Họ và tên</div>
                        <input type="text" name="name" value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" required class="modern-input @error('name') is-invalid @enderror" placeholder="Họ và tên">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div class="field-grid">
                            <div>
                                <div class="form-section-title">Email</div>
                                <input type="email" name="email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required class="modern-input @error('email') is-invalid @enderror" placeholder="Email">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <div class="form-section-title">Số điện thoại</div>
                                <input type="tel" name="phone" value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}" required pattern="^(03|05|07|08|09)\d{8}$" class="modern-input @error('phone') is-invalid @enderror" placeholder="Số điện thoại">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="delivery-method-box">
                            <div class="delivery-option active" onclick="$('#delivery_home_content').slideDown(); $(this).addClass('active').next().next().removeClass('active'); $('#delivery_home').prop('checked', true);">
                                <input type="radio" name="delivery_type" id="delivery_home" value="home" checked>
                                <label for="delivery_home">Giao tận nơi</label>
                            </div>
                            <div id="delivery_home_content" class="delivery-content">
                                <div class="form-section-title">Địa chỉ</div>
                                <input type="text" name="address" value="{{ old('address', Auth::check() ? Auth::user()->address : '') }}" required minlength="5" class="modern-input @error('address') is-invalid @enderror" placeholder="Địa chỉ">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                <div class="address-grid">
                                    <div>
                                        <div class="form-section-title">Tỉnh / Thành phố</div>
                                        <select name="province" id="province" required
                                            class="modern-input modern-select @error('province') is-invalid @enderror">
                                            <option value="">-- Đang tải... --</option>
                                        </select>
                                        @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <div class="form-section-title">Xã / Phường</div>
                                        <select name="commune" id="commune" required disabled
                                            class="modern-input modern-select @error('commune') is-invalid @enderror">
                                            <option value="">-- Chọn tỉnh trước --</option>
                                        </select>
                                        @error('commune') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="delivery-option" onclick="$('#delivery_home_content').slideUp(); $(this).addClass('active').prev().prev().removeClass('active'); $('#delivery_store').prop('checked', true);">
                                <input type="radio" name="delivery_type" id="delivery_store" value="store">
                                <label for="delivery_store">Nhận tại cửa hàng</label>
                            </div>
                        </div>

                        <button type="button" class="btn-primary-black" id="btn-next-step-1">
                            TIẾP TỤC ĐẾN PHƯƠNG THỨC THANH TOÁN
                            <span class="arrow-box"><i class="fa fa-arrow-right"></i></span>
                        </button>
                        
                        <div class="legal-text">
                            Nhấn "Đặt hàng" đồng nghĩa với việc bạn đồng ý tuân theo <a href="#">Điều khoản web</a>
                        </div>
                    </div>

                    <!-- STEP 2: PAYMENT & SHIPPING METHOD (Hidden initially) -->
                    <div id="checkout-step-2" class="checkout-step-content" style="display: none;">
                        <h3 class="summary-title mb-4">Phương thức vận chuyển</h3>
                        <div id="shipping_method_container">
                            <p class="text-muted small mb-3">Phí ship sẽ cập nhật theo địa chỉ nhận hàng. Nếu chọn nhận tại cửa hàng, hệ thống sẽ chuyển về 0đ.</p>
                            <div id="shipping_options">
                                <p class="text-muted">Đang tải...</p>
                            </div>
                            <input type="hidden" name="shipping_fee" id="hidden_shipping_fee" value="0">
                            <input type="hidden" name="shipping_service_name" id="hidden_shipping_service_name" value="">
                        </div>

                        <h3 class="summary-title mt-5 mb-4">Phương thức thanh toán</h3>
                        <div class="payment_method">
                            <div class="panel-default mb-3 border rounded overflow-hidden">
                                <input id="payment_cod" name="payment_method" type="radio" value="COD" data-bs-target="#method_cod" checked required class="ms-3 mt-3" />
                                <label for="payment_cod" class="p-3 w-100 d-inline-block" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#method_cod">
                                    {{ __('messages.cash_on_delivery') }}
                                </label>
                                <div id="method_cod" class="collapse show" data-bs-parent="#accordion"></div>
                            </div>

                            <div class="panel-default mb-3 border rounded overflow-hidden">
                                <input id="payment_bank" name="payment_method" type="radio" value="BANK_TRANSFER" data-bs-target="#method_bank" required class="ms-3 mt-3" />
                                <label for="payment_bank" class="p-3 w-100 d-inline-block" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#method_bank">
                                    Chuyển khoản ngân hàng trực tiếp (VietQR)
                                </label>
                                <div id="method_bank" class="collapse" data-bs-parent="#accordion">
                                    <div class="p-3 bg-light border-top small">
                                        <i class="fa fa-info-circle"></i> Bạn sẽ nhận được thông tin số tài khoản và mã QR để chuyển khoản sau khi nhấn "Đặt hàng".
                                    </div>
                                </div>
                            </div>

                            <div class="panel-default mb-3 border rounded overflow-hidden">
                                <input id="payment_vnpay" name="payment_method" type="radio" value="VNPAY" data-bs-target="#method_vnpay" required class="ms-3 mt-3" />
                                <label for="payment_vnpay" class="p-3 w-100 d-inline-block" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#method_vnpay">
                                    VNPay (Thẻ ATM, Ví điện tử, QR Code)
                                </label>
                                <div id="method_vnpay" class="collapse" data-bs-parent="#accordion">
                                    <div class="p-3 bg-light border-top small">
                                        <i class="fa fa-info-circle"></i> Thanh toán an toàn qua VNPay.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 gap-3">
                            <button type="button" class="btn btn-outline-secondary px-4" id="btn-prev-step-2" style="height: 54px; border-radius: 4px; font-weight: 600;">
                                <i class="fa fa-arrow-left me-2"></i> QUAY LẠI
                            </button>
                            <button type="button" class="btn-primary-black mt-0" id="btn-next-step-2">
                                TIẾP TỤC <span class="arrow-box"><i class="fa fa-arrow-right"></i></span>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: FINAL CONFIRMATION -->
                    <div id="checkout-step-3" class="checkout-step-content" style="display: none;">
                        <h3 class="summary-title mb-4">Xác nhận đơn hàng</h3>
                        <div class="card border-0 bg-light p-3 mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold">Thông tin nhận hàng</h6>
                                    <p class="mb-1 small"><strong>Người nhận:</strong> <span id="confirm-name"></span></p>
                                    <p class="mb-1 small"><strong>SĐT:</strong> <span id="confirm-phone"></span></p>
                                    <p class="mb-1 small"><strong>Địa chỉ:</strong> <span id="confirm-address"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Thanh toán & Vận chuyển</h6>
                                    <p class="mb-1 small"><strong>Vận chuyển:</strong> <span id="confirm-shipping"></span></p>
                                    <p class="mb-1 small"><strong>Thanh toán:</strong> <span id="confirm-payment"></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Info -->
                        <div id="bank-transfer-info" class="mb-4 p-3 border rounded bg-white" style="display: none;">
                            <h6 class="fw-bold mb-3">Thông tin chuyển khoản</h6>
                            <p class="mb-1 small">Ngân hàng: <strong>{{ $defaultBank->bank_name ?? 'N/A' }}</strong></p>
                            <p class="mb-1 small">Số tài khoản: <strong class="text-primary">{{ $defaultBank->account_number ?? 'N/A' }}</strong></p>
                            <p class="mb-3 small">Chủ tài khoản: <strong>{{ $defaultBank->account_name ?? 'N/A' }}</strong></p>
                            <div class="text-center">
                                <img id="bank_qr_image" src="" alt="VietQR" class="img-fluid" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 gap-3">
                            <button type="button" class="btn btn-outline-secondary px-4" id="btn-prev-step-3" style="height: 54px; border-radius: 4px; font-weight: 600;">
                                <i class="fa fa-arrow-left me-2"></i> QUAY LẠI
                            </button>
                            <button type="submit" class="btn-primary-black mt-0" id="btn-place-order">
                                ĐẶT HÀNG <span class="arrow-box"><i class="fa fa-arrow-right"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-5">
                <div class="summary-card">
                    <h3 class="summary-title">THÔNG TIN ĐƠN HÀNG</h3>
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span class="text-dark fw-medium">{{ number_format($total) }}đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển</span>
                        <span id="shipping_fee_display" class="text-dark fw-medium">—</span>
                    </div>
                    @if($discount > 0)
                        <div class="summary-row text-success">
                            <span>Mã giảm giá</span>
                            <span class="fw-medium">-{{ number_format($discount) }}đ</span>
                        </div>
                    @endif
                    <div class="summary-row total">
                        <span>Tổng cộng</span>
                        <span id="final_total_display" class="text-dark">{{ number_format($finalTotal) }}đ</span>
                    </div>

                    <div id="checkout_coupon" class="mt-4">
                        @if($coupon)
                            <div class="p-3 bg-light border rounded d-flex justify-content-between align-items-center">
                                <div class="small">
                                    <i class="fa fa-tag text-success me-2"></i>
                                    Mã <strong class="text-dark">{{ $coupon->code }}</strong> đã áp dụng
                                </div>
                                <button type="button" class="btn btn-sm text-danger fw-bold" id="removeCouponBtn">XÓA</button>
                            </div>
                        @else
                            <div class="coupon-group">
                                <input type="text" id="couponCode" placeholder="Mã giảm giá">
                                <button type="button" id="applyCouponBtn" class="btn-apply-coupon">
                                    SỬ DỤNG
                                    <span class="arrow-box"><i class="fa fa-arrow-right"></i></span>
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@section('scripts')
    <div id="checkout-config" style="display: none;"
        data-csrf="{{ csrf_token() }}"
        data-route-inventory="{{ route('api.checkout.checkInventory') }}"
        data-route-coupon-apply="{{ route('checkout.applyCoupon') }}"
        data-route-coupon-remove="{{ route('checkout.removeCoupon') }}"
        data-route-cart="{{ route('cart.index') }}"
        data-route-shipping="{{ url('/api/checkout/shipping-fees') }}"
        data-route-provinces="{{ route('api.vn-address.provinces') }}"
        data-route-communes="{{ url('api/vn-address/communes') }}"
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
                    calculateShippingFees();
                }).catch(xhr => {
                    // if fails silently go back to step 1
                    $('#checkout-step-2').hide();
                    $('#checkout-step-1').show();
                    currentStep = 1;
                    updateProgressBar(1);
                });
            }

            function updateProgressBar(step) {
                // Update breadcrumbs
                if (step === 1) {
                    $('#breadcrumb-step-1').addClass('active');
                    $('#breadcrumb-step-2').removeClass('active');
                } else if (step === 2 || step === 3) {
                    $('#breadcrumb-step-1').removeClass('active');
                    $('#breadcrumb-step-2').addClass('active');
                }
            }

            function checkInventoryAsync() {
                return $.ajax({
                    url: config.routeInventory,
                    method: 'POST',
                    data: { _token: config.csrf }
                });
            }

            function getDeliveryType() {
                return $('input[name="delivery_type"]:checked').val() || 'home';
            }

            function requiresAddress() {
                return getDeliveryType() === 'home';
            }

            function setShippingSelection(provider, serviceName, fee) {
                $('#hidden_shipping_fee').val(fee);
                $('#hidden_shipping_service_name').val(serviceName);
                updateTotals(fee);
            }

            function renderShippingOptions(options) {
                let html = '';

                options.forEach((option, index) => {
                    const checked = index === 0 ? 'checked' : '';
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
                $('input[name="shipping_provider"]:checked').trigger('change');
            }

            function syncDeliveryModeUI() {
                const isHomeDelivery = requiresAddress();

                $('input[name="address"]').prop('required', isHomeDelivery);
                $('select[name="province"]').prop('required', isHomeDelivery);

                if (!isHomeDelivery) {
                    showValidation('input[name="address"]', '');
                    showValidation('select[name="province"]', '');
                }
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
                const provinceError = requiresAddress() ? validateProvince($('select[name="province"]').val()) : '';
                const addressError  = requiresAddress() ? validateAddress($('input[name="address"]').val()) : '';

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
                    const isHomeDelivery = requiresAddress();
                    const addressParts = [
                        $('input[name="address"]').val(),
                        $('select[name="commune"]').val(),
                        $('select[name="province"]').val()
                    ].filter(Boolean);

                    $('#confirm-name').text($('input[name="name"]').val());
                    $('#confirm-phone').text($('input[name="phone"]').val());
                    $('#confirm-email').text($('input[name="email"]').val());
                    $('#confirm-address').text(isHomeDelivery ? addressParts.join(', ') : 'Nhận tại cửa hàng');
                    $('#confirm-shipping').text($('input[name="shipping_provider"]:checked').data('service-name') || $('#hidden_shipping_service_name').val());

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

            // ============ DYNAMIC ADDRESS CASCADE ============
            const $province = $('#province');
            const $commune  = $('#commune');

            // Load provinces once on page load
            $.getJSON(config.routeProvinces, function(provinces) {
                let html = '<option value="">-- Chọn tỉnh/thành phố --</option>';
                provinces.forEach(function(p) {
                    const sel = (p.name === '{{ old('province') }}') ? 'selected' : '';
                    html += `<option value="${p.name}" data-code="${p.code}" ${sel}>${p.name}</option>`;
                });
                $province.html(html);
                if ($province.val()) $province.trigger('change');
            });

            $province.on('change', function() {
                const code = this.options[this.selectedIndex]?.dataset.code || '';
                $commune.html('<option value="">-- Đang tải... --</option>').prop('disabled', true);
                if (!code) {
                    $commune.html('<option value="">-- Chọn tỉnh trước --</option>');
                    return;
                }
                $.getJSON(config.routeCommunes + '/' + code, function(list) {
                    let html = '<option value="">-- Chọn xã/phường --</option>';
                    list.forEach(function(c) {
                        const sel = (c.name === '{{ old('commune') }}') ? 'selected' : '';
                        html += `<option value="${c.name}" ${sel}>${c.name}</option>`;
                    });
                    $commune.html(html).prop('disabled', false);
                    calculateShippingFees();
                });
                calculateShippingFees();
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

            function calculateShippingFees() {
                const deliveryType = getDeliveryType();
                const province = $('select[name="province"]').val();
                const commune  = $('select[name="commune"]').val();

                $('#shipping_method_container').show();

                if (deliveryType === 'store') {
                    renderShippingOptions([{
                        provider: 'store_pickup',
                        service_name: 'Nhận tại cửa hàng',
                        fee: 0,
                        expected_delivery_time: 'Trong giờ hành chính'
                    }]);
                    return;
                }

                if (!province) {
                    $('#shipping_options').html('<div class="alert alert-info">Vui lòng chọn tỉnh/thành để xem phí vận chuyển.</div>');
                    $('#hidden_shipping_fee').val(0);
                    $('#hidden_shipping_service_name').val('');
                    updateTotals(0);
                    return;
                }

                $('#shipping_options').html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-primary"></span> Đang tính phí vận chuyển...</div>');

                $.ajax({
                    url: config.routeShipping,
                    method: 'POST',
                    data: {
                        _token: config.csrf,
                        delivery_type: deliveryType,
                        province: province,
                        district: '',
                        ward: $('select[name="commune"]').val()
                    },
                    success: function (response) {
                        if (response.success && response.data && response.data.length > 0) {
                            renderShippingOptions(response.data);
                        } else {
                            $('#shipping_options').html('<div class="alert alert-warning">Kh?ng th? t?nh ph? v?n chuy?n l?c n?y.</div>');
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

                setShippingSelection($(this).val(), serviceName, fee);
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

            syncDeliveryModeUI();
            calculateShippingFees();

            $('select[name="province"]').on('change', function () {
                showValidation(this, validateProvince($(this).val()));
                calculateShippingFees();
            });

            $('select[name="commune"], input[name="delivery_type"]').on('change', function () {
                syncDeliveryModeUI();
                calculateShippingFees();
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
                const provinceError = requiresAddress() ? validateProvince($('select[name="province"]').val()) : '';
                const communeError  = requiresAddress() ? (($('select[name="commune"]').val() ? '' : 'Vui lòng chọn xã/phường')) : '';
                const addressError  = requiresAddress() ? validateAddress($('input[name="address"]').val()) : '';
                showValidation('input[name="name"]', nameError);
                showValidation('input[name="phone"]', phoneError);
                showValidation('input[name="email"]', emailError);
                showValidation('select[name="province"]', provinceError);
                showValidation('select[name="commune"]', communeError);
                showValidation('input[name="address"]', addressError);
                if (nameError || phoneError || emailError || provinceError || communeError || addressError) {
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

        });
    </script>
@endsection

