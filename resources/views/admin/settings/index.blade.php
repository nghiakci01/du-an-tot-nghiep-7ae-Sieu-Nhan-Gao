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

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Lưu Cấu Hình</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection