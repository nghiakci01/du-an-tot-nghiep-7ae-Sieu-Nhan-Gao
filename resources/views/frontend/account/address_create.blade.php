@extends('layouts.public')

@section('title', 'Thêm địa chỉ mới | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<style>
.address-page {
    background: #f5f5f7;
    padding: 48px 0 80px;
    min-height: 80vh;
}
.address-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.07);
    padding: 40px;
    max-width: 680px;
    margin: 0 auto;
}
.address-card h2 {
    font-size: 22px;
    font-weight: 700;
    letter-spacing: -0.3px;
    margin-bottom: 8px;
}
.address-card .subtitle {
    color: #888;
    font-size: 14px;
    margin-bottom: 32px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #444;
    margin-bottom: 8px;
}
.form-group label span.required {
    color: #e02020;
    margin-left: 2px;
}
.form-control-custom {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    color: #222;
    background: #fafafa;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
}
.form-control-custom:focus {
    border-color: #222;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.06);
}
.form-control-custom.is-loading {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='12' r='10' fill='none' stroke='%23ccc' stroke-width='3'/%3E%3Cpath d='M12 2a10 10 0 0 1 10 10' fill='none' stroke='%23333' stroke-width='3' stroke-linecap='round'%3E%3CanimateTransform attributeName='transform' type='rotate' from='0 12 12' to='360 12 12' dur='0.8s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 18px;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.default-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    transition: border-color 0.2s;
}
.default-toggle:hover { border-color: #222; }
.default-toggle input[type="checkbox"] {
    width: 18px; height: 18px;
    accent-color: #222;
    cursor: pointer;
}
.default-toggle span {
    font-size: 14px;
    color: #444;
}
.btn-row {
    display: flex;
    gap: 12px;
    margin-top: 32px;
}
.btn-save {
    flex: 1;
    padding: 14px;
    background: #222;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.3px;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-save:hover { background: #000; }
.btn-cancel {
    padding: 14px 24px;
    background: transparent;
    color: #444;
    border: 1.5px solid #ddd;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: border-color 0.2s;
}
.btn-cancel:hover { border-color: #222; color: #222; }
.breadcrumb-bar {
    background: #fff;
    border-bottom: 1px solid #f0f0f0;
    padding: 12px 0;
    margin-bottom: 0;
}
.breadcrumb-bar .breadcrumb {
    margin: 0;
    padding: 0;
    background: none;
    font-size: 13px;
    color: #888;
}
.breadcrumb-bar .breadcrumb a { color: #555; text-decoration: none; }
.breadcrumb-bar .breadcrumb a:hover { color: #222; }
</style>
@endpush

@section('content')
{{-- Breadcrumb --}}
<div class="breadcrumb-bar">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('welcome') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('account.index') }}">Tài khoản</a>
            <span class="mx-2">/</span>
            <span>Thêm địa chỉ mới</span>
        </nav>
    </div>
</div>

<div class="address-page">
    <div class="container">
        <div class="address-card">
            <h2>Thêm địa chỉ mới</h2>
            <p class="subtitle">Địa chỉ sẽ được sử dụng khi giao hàng</p>

            <form action="{{ route('account.addresses.store') }}" method="POST" id="addressForm">
                @csrf

                {{-- Tên & SĐT --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Tên người nhận <span class="required">*</span></label>
                        <input type="text" name="receiver_name" class="form-control-custom"
                            placeholder="Nguyễn Văn A"
                            value="{{ old('receiver_name') }}" required>
                        @error('receiver_name')
                            <div class="text-danger mt-1" style="font-size:13px">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại <span class="required">*</span></label>
                        <input type="tel" name="phone" class="form-control-custom"
                            placeholder="0901234567"
                            pattern="^(03|05|07|08|09)\d{8}$"
                            maxlength="10" minlength="10"
                            title="10 chữ số, bắt đầu bằng 03, 05, 07, 08 hoặc 09"
                            value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="text-danger mt-1" style="font-size:13px">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tỉnh / Thành phố --}}
                <div class="form-group">
                    <label>Tỉnh / Thành phố <span class="required">*</span></label>
                    <select name="province" id="provinceSelect" class="form-control-custom" required>
                        <option value="">-- Đang tải danh sách tỉnh/thành... --</option>
                    </select>
                    @error('province')
                        <div class="text-danger mt-1" style="font-size:13px">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Xã / Phường --}}
                <div class="form-group">
                    <label>Xã / Phường <span class="required">*</span></label>
                    <select name="commune" id="communeSelect" class="form-control-custom" required disabled>
                        <option value="">-- Chọn tỉnh/thành trước --</option>
                    </select>
                    @error('commune')
                        <div class="text-danger mt-1" style="font-size:13px">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Địa chỉ cụ thể --}}
                <div class="form-group">
                    <label>Địa chỉ cụ thể <span class="required">*</span></label>
                    <input type="text" name="address" class="form-control-custom"
                        placeholder="Số nhà, tên đường, ngõ/hẻm..."
                        value="{{ old('address') }}" required>
                    @error('address')
                        <div class="text-danger mt-1" style="font-size:13px">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Đặt làm mặc định --}}
                <div class="form-group">
                    <label class="default-toggle">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                        <span>Đặt làm địa chỉ mặc định</span>
                    </label>
                </div>

                <div class="btn-row">
                    <a href="{{ route('account.index') }}" class="btn-cancel">
                        <i class="fa fa-arrow-left me-2"></i> Quay lại
                    </a>
                    <button type="submit" class="btn-save">
                        <i class="fa fa-check me-2"></i> Lưu địa chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const provinceSelect = document.getElementById('provinceSelect');
    const communeSelect  = document.getElementById('communeSelect');

    // Load danh sách tỉnh/thành từ proxy Laravel
    async function loadProvinces() {
        try {
            const res  = await fetch('{{ route("api.vn-address.provinces") }}');
            const data = await res.json();

            provinceSelect.innerHTML = '<option value="">-- Chọn tỉnh/thành phố --</option>';
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value        = p.name;
                opt.dataset.code = p.code;
                opt.textContent  = p.name;
                @if(old('province'))
                if (p.name === '{{ old('province') }}') opt.selected = true;
                @endif
                provinceSelect.appendChild(opt);
            });

            // Nếu có old value, load communes ngay
            @if(old('province'))
            const selectedOpt = provinceSelect.querySelector('option[selected]');
            if (selectedOpt) loadCommunes(selectedOpt.dataset.code);
            @endif

        } catch (err) {
            provinceSelect.innerHTML = '<option value="">Lỗi tải dữ liệu — thử lại sau</option>';
            console.error('Load provinces error:', err);
        }
    }

    // Load xã/phường theo provinceCode
    async function loadCommunes(provinceCode) {
        communeSelect.innerHTML  = '<option value="">-- Đang tải... --</option>';
        communeSelect.disabled   = true;
        try {
            const res  = await fetch('{{ url("api/vn-address/communes") }}/' + provinceCode);
            const data = await res.json();

            communeSelect.innerHTML = '<option value="">-- Chọn xã/phường --</option>';
            data.forEach(c => {
                const opt = document.createElement('option');
                opt.value       = c.name;
                opt.textContent = c.name;
                @if(old('commune'))
                if (c.name === '{{ old('commune') }}') opt.selected = true;
                @endif
                communeSelect.appendChild(opt);
            });
            communeSelect.disabled = false;
        } catch (err) {
            communeSelect.innerHTML = '<option value="">Lỗi tải xã/phường</option>';
            console.error('Load communes error:', err);
        }
    }

    provinceSelect.addEventListener('change', function () {
        const selectedOpt = this.options[this.selectedIndex];
        if (this.value && selectedOpt.dataset.code) {
            loadCommunes(selectedOpt.dataset.code);
        } else {
            communeSelect.innerHTML = '<option value="">-- Chọn tỉnh/thành trước --</option>';
            communeSelect.disabled  = true;
        }
    });

    loadProvinces();
})();
</script>
@endpush
