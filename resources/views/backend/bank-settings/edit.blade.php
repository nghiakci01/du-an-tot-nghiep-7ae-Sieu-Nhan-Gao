@extends('backend.layouts.master')

@section('title', 'Sửa Tài Khoản Ngân Hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa Tài Khoản</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bank-settings.update', $bank->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label">Tên Ngân Hàng <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name', $bank->bank_name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bank_id" class="form-label">Mã NH (VietQR Shortcode) <span class="text-danger">*</span></label>
                                <input type="text" name="bank_id" id="bank_id" class="form-control" value="{{ old('bank_id', $bank->bank_id) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account_number" class="form-label">Số Tài Khoản <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number', $bank->account_number) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account_name" class="form-label">Tên Chủ Tài Khoản <span class="text-danger">*</span></label>
                                <input type="text" name="account_name" id="account_name" class="form-control" value="{{ old('account_name', $bank->account_name) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ $bank->is_default ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">Đặt làm Mặc định (Sẽ ưu tiên load ở thanh toán)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $bank->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Hoạt động (Trạng thái hiển thị)</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.bank-settings.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
