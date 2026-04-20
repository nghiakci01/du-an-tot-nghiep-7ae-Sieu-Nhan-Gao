@extends('layouts.admin')

@section('title', 'Chỉnh sửa Mã Giảm Giá')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Chỉnh sửa Mã Giảm Giá</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Mã Giảm Giá</a></li>
                    <li class="breadcrumb-item"><a href="#!">#{{ $coupon->code }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" id="voucherForm">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Main Configuration (Left Column) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">Cấu hình chính</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label fw-bold">Mã Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $coupon->code) }}" 
                                   placeholder="VD: SUMMER2026" style="text-transform: uppercase;" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label fw-bold">Loại Giảm Giá <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="value" class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                       id="value" name="value" value="{{ old('value', $coupon->value) }}" 
                                       step="0.01" min="0" placeholder="0" required>
                                <span class="input-group-text bg-light border-start-0" id="value-suffix">-</span>
                            </div>
                            @error('value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text mt-1 text-muted small" id="value-hint">Chọn loại giảm giá để xem hướng dẫn</div>
                        </div>

                        <div class="col-md-12 mt-4">
                            <label for="description" class="form-label fw-bold">Mô tả (Không bắt buộc)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Mô tả về mã giảm giá này...">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">Điều kiện & Hạn chế</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="min_order_amount" class="form-label fw-bold">Giá trị đơn tối thiểu (VNĐ)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror" 
                                       id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" 
                                       step="1000" min="0" placeholder="VD: 200000">
                                <span class="input-group-text bg-light border-start-0">đ</span>
                            </div>
                            @error('min_order_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6" id="max-discount-col" style="display: none;">
                            <label for="max_discount_amount" class="form-label fw-bold">Giảm tối đa (VNĐ)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('max_discount_amount') is-invalid @enderror" 
                                       id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" 
                                       step="1000" min="0" placeholder="VD: 100000">
                                <span class="input-group-text bg-light border-start-0">đ</span>
                            </div>
                            @error('max_discount_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-info small mt-1"><i class="ti ti-info-circle me-1"></i>Chỉ dùng cho %</div>
                        </div>

                        <div class="col-md-12 mt-4">
                            <label for="user_id" class="form-label fw-bold">Áp dụng cho người dùng cụ thể</label>
                            <select name="user_id" id="user_id" class="form-select select2 @error('user_id') is-invalid @enderror">
                                <option value="">-- Công khai (Tất cả người dùng) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $coupon->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text mt-1 text-muted small">Để trống nếu mã này là công khai cho mọi người.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right Column) -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 sticky-lg-top" style="top: 2rem;">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">Thời gian & Giới hạn</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-12 text-center py-3 bg-light border rounded mb-3">
                            <div class="form-check form-switch d-inline-block">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold ms-1" for="is_active">Đang hoạt động</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold d-block">Đã sử dụng</label>
                            <div class="p-2 border rounded bg-light">
                                <span class="h4 mb-0 fw-bold text-primary">{{ $coupon->used_count }}</span>
                                <span class="text-muted">lượt</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="usage_limit" class="form-label fw-bold">Tổng lượt sử dụng tối đa</label>
                            <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" 
                                   min="1" placeholder="Vô hạn nếu để trống">
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="start_date" class="form-label fw-bold">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d\TH:i') : '') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="end_date" class="form-label fw-bold">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d\TH:i') : '') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4 border-top pt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-2">Cập nhật mã giảm giá</button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary w-100">Quay lại danh sách</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 border-start border-danger border-4">
                <div class="card-body p-3">
                    <h6 class="text-danger fw-bold mb-2"><i class="ti ti-alert-triangle me-1"></i>Vùng nguy hiểm</h6>
                    <p class="small text-muted mb-3">Hành động này không thể hoàn tác. Vui lòng cẩn thận.</p>
                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="no-pjax" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">Xóa mã giảm giá</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "-- Công khai --",
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    const typeSelect = $('#type');
    const valueSuffix = $('#value-suffix');
    const valueHint = $('#value-hint');
    const maxDiscountCol = $('#max-discount-col');
    const valueInput = $('#value');

    function updateFormBasedOnType() {
        const type = typeSelect.val();
        
        if (type === 'percentage') {
            valueSuffix.text('%');
            valueHint.text('Nhập giá trị từ 1 đến 100').removeClass('text-muted').addClass('text-info');
            valueInput.attr('max', '100');
            valueInput.attr('placeholder', 'VD: 20');
            maxDiscountCol.fadeIn(200);
        } else if (type === 'fixed') {
            valueSuffix.text('đ');
            valueHint.text('Nhập số tiền giảm (VD: 50.000đ)').removeClass('text-info').addClass('text-muted');
            valueInput.removeAttr('max');
            valueInput.attr('placeholder', 'VD: 50000');
            maxDiscountCol.fadeOut(200);
        } else {
            valueSuffix.text('-');
            valueHint.text('Hãy chọn loại giảm giá').removeClass('text-info').addClass('text-muted');
            valueInput.removeAttr('max');
            valueInput.attr('placeholder', '0');
            maxDiscountCol.fadeOut(200);
        }
    }

    typeSelect.on('change', updateFormBasedOnType);
    updateFormBasedOnType();
});
</script>
@endsection
