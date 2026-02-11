@extends('layouts.admin')

@section('title', 'Tạo Mã Giảm Giá')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Tạo Mã Giảm Giá</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Mã Giảm Giá</a></li>
                    <li class="breadcrumb-item"><a href="#!">Tạo mới</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Thông tin Mã Giảm Giá</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Mã Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" 
                                       placeholder="VD: SUMMER2026" style="text-transform: uppercase;" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Mã sẽ tự động chuyển thành chữ in hoa</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Loại Giảm Giá <span class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">-- Chọn loại --</option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="value">Giá trị <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                           id="value" name="value" value="{{ old('value') }}" 
                                           step="0.01" min="0" required>
                                    <span class="input-group-text" id="value-suffix">-</span>
                                </div>
                                @error('value')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted" id="value-hint">Chọn loại giảm giá trước</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_order_amount">Đơn hàng tối thiểu (VNĐ)</label>
                                <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror" 
                                       id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}" 
                                       step="1000" min="0" placeholder="VD: 200000">
                                @error('min_order_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Để trống nếu không giới hạn</small>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="max-discount-row" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_discount_amount">Giảm tối đa (VNĐ)</label>
                                <input type="number" class="form-control @error('max_discount_amount') is-invalid @enderror" 
                                       id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}" 
                                       step="1000" min="0" placeholder="VD: 100000">
                                @error('max_discount_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Chỉ áp dụng cho loại phần trăm</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usage_limit">Giới hạn lượt dùng</label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" 
                                       min="1" placeholder="VD: 100">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Để trống nếu không giới hạn</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Ngày bắt đầu</label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">Ngày kết thúc</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Mô tả về mã giảm giá này...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Tạo mã giảm giá</button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueSuffix = document.getElementById('value-suffix');
    const valueHint = document.getElementById('value-hint');
    const maxDiscountRow = document.getElementById('max-discount-row');
    const valueInput = document.getElementById('value');

    function updateFormBasedOnType() {
        const type = typeSelect.value;
        
        if (type === 'percentage') {
            valueSuffix.textContent = '%';
            valueHint.textContent = 'Nhập giá trị từ 0 đến 100';
            valueInput.setAttribute('max', '100');
            valueInput.setAttribute('placeholder', 'VD: 20');
            maxDiscountRow.style.display = 'flex';
        } else if (type === 'fixed') {
            valueSuffix.textContent = 'VNĐ';
            valueHint.textContent = 'Nhập số tiền giảm (tối thiểu 1,000 VNĐ)';
            valueInput.removeAttribute('max');
            valueInput.setAttribute('placeholder', 'VD: 50000');
            maxDiscountRow.style.display = 'none';
        } else {
            valueSuffix.textContent = '-';
            valueHint.textContent = 'Chọn loại giảm giá trước';
            valueInput.removeAttribute('max');
            valueInput.setAttribute('placeholder', '');
            maxDiscountRow.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', updateFormBasedOnType);
    
    // Initialize on page load
    updateFormBasedOnType();
});
</script>
@endpush
