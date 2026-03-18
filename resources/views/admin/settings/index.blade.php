@extends('layouts.admin')

@section('title', 'Cấu hình hệ thống')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Cấu hình chung</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h6 class="mb-3 text-primary"><i class="ti ti-info-circle"></i> Thông tin Website</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên Website (Site Title)</label>
                                <input type="text" class="form-control" name="site_title"
                                    value="{{ $settings['site_title'] ?? 'Elite' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phí Ship Mặc Định (VND)</label>
                                <input type="number" class="form-control" name="shipping_fee"
                                    value="{{ $settings['shipping_fee'] ?? '30000' }}">
                            </div>
                        </div>

                        <h6 class="mb-3 mt-3 text-primary"><i class="ti ti-phone"></i> Thông tin Liên hệ</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Liên Hệ</label>
                                <input type="text" class="form-control" name="site_email"
                                    value="{{ $settings['site_email'] ?? 'contact@elite.com' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hotline</label>
                                <input type="text" class="form-control" name="site_phone"
                                    value="{{ $settings['site_phone'] ?? '0912345678' }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Địa chỉ Shop</label>
                                <textarea class="form-control" name="site_address"
                                    rows="2">{{ $settings['site_address'] ?? 'Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam' }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Google Map Iframe URL</label>
                                <textarea class="form-control" name="store_map_iframe"
                                    rows="3">{{ $settings['store_map_iframe'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.6575765790473!2d105.71077797584149!3d21.04638368717544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134546536093551%3A0x673199834278e993!2sNg.%2091%20Lai%20X%C3%A3%2C%20Kim%20Chung%2C%20Ho%C3%A0i%20%C4%90%E1%BB%A9c%2C%20H%C3%A0%20N%E1%BB%99i!5e0!3m2!1svi!2s!4v1710000000000!5m2!1svi!2s' }}</textarea>
                                <small class="text-muted">Dán đoạn mã URL từ mã nhúng iframe của Google Maps (chỉ phần trong thuộc tính src).</small>
                            </div>
                        </div>

                        <h6 class="mb-3 mt-3 text-primary"><i class="ti ti-brand-facebook"></i> Mạng Xã Hội</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Facebook URL</label>
                                <input type="text" class="form-control" name="social_facebook"
                                    value="{{ $settings['social_facebook'] ?? '#' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instagram URL</label>
                                <input type="text" class="form-control" name="social_instagram"
                                    value="{{ $settings['social_instagram'] ?? '#' }}">
                            </div>
                        </div>

                        {{-- ====== VNPAY SETTINGS ====== --}}
                        <hr class="my-4">
                        <h6 class="mb-3 text-primary">
                            <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logoVNPAY.svg" alt="VNPAY" style="height:22px; margin-right:6px;">
                            Cấu hình cổng thanh toán VNPAY
                        </h6>
                        <div class="alert alert-info py-2 mb-3">
                            <i class="ti ti-info-circle"></i>
                            Lấy thông tin tại:
                            <a href="https://sandbox.vnpayment.vn/devreg/" target="_blank">VNPAY Sandbox</a> (thử nghiệm)
                            hoặc <a href="https://merchant.vnpay.vn" target="_blank">VNPAY Merchant Portal</a> (thực tế).
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Môi trường</label>
                                <select class="form-select" name="vnpay_env" id="vnpay_env_select">
                                    <option value="sandbox" {{ ($settings['vnpay_env'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>
                                        🧪 Sandbox (Test)
                                    </option>
                                    <option value="production" {{ ($settings['vnpay_env'] ?? '') === 'production' ? 'selected' : '' }}>
                                        🚀 Production (Thực tế)
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">TMN Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control font-monospace" name="vnpay_tmn_code"
                                    placeholder="Ví dụ: ABCD1234"
                                    value="{{ $settings['vnpay_tmn_code'] ?? '' }}">
                                <small class="text-muted">Mã Terminal/Merchant từ VNPAY cấp.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Hash Secret Key <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control font-monospace" name="vnpay_hash_secret"
                                        id="vnpay_hash_secret"
                                        placeholder="••••••••••••••••••••"
                                        value="{{ $settings['vnpay_hash_secret'] ?? '' }}">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleSecret"
                                        onclick="toggleVnpaySecret()">
                                        <i class="ti ti-eye" id="toggleSecretIcon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Khóa bí mật do VNPAY cấp để tạo chữ ký.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Payment URL</label>
                                <input type="text" class="form-control font-monospace" name="vnpay_url"
                                    id="vnpay_url_input"
                                    placeholder="https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"
                                    value="{{ $settings['vnpay_url'] ?? '' }}">
                                <small class="text-muted">URL cổng thanh toán (tự động điền theo môi trường).</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Return URL</label>
                                <input type="text" class="form-control" name="vnpay_return_url"
                                    value="{{ $settings['vnpay_return_url'] ?? url('/vnpay/return') }}">
                                <small class="text-muted">URL VNPAY sẽ redirect sau thanh toán.</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Lưu Cấu
                                Hình</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill URL based on selected environment
        const sandboxUrl = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        const productionUrl = 'https://pay.vnpay.vn/vpcpay.html';

        document.getElementById('vnpay_env_select').addEventListener('change', function() {
            const urlInput = document.getElementById('vnpay_url_input');
            if (!urlInput.value || urlInput.value === sandboxUrl || urlInput.value === productionUrl) {
                urlInput.value = this.value === 'production' ? productionUrl : sandboxUrl;
            }
        });

        function toggleVnpaySecret() {
            const input = document.getElementById('vnpay_hash_secret');
            const icon = document.getElementById('toggleSecretIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ti ti-eye-off';
            } else {
                input.type = 'password';
                icon.className = 'ti ti-eye';
            }
        }
    </script>
@endsection