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

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        <h6 class="mb-3 text-primary"><i class="ti ti-info-circle"></i> Thông tin Website</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên Website (Site Title)</label>
                                <input type="text" class="form-control" name="site_title"
                                    value="{{ $settings['site_title'] ?? 'Sieu Nhan Gao Shop' }}">
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
                                    value="{{ $settings['site_email'] ?? 'contact@sieunhangao.com' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hotline</label>
                                <input type="text" class="form-control" name="site_phone"
                                    value="{{ $settings['site_phone'] ?? '0912345678' }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Địa chỉ Shop</label>
                                <textarea class="form-control" name="site_address"
                                    rows="2">{{ $settings['site_address'] ?? 'Số 1, Đại Cồ Việt, Hà Nội' }}</textarea>
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
                            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Lưu Cấu
                                Hình</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection