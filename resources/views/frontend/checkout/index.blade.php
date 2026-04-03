{{-- ================================================================
     CHECKOUT — Single Page · 2-Column · Premium Design
     Guest: full address form | Auth: address cards + inline add
================================================================ --}}
@extends('layouts.public')

@section('title', 'Thanh toán | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<style>
:root {
  --ck-black:  #0f0f0f; --ck-gray: #6b7280;
  --ck-border: #e5e7eb; --ck-bg:   #f7f8fa;
  --ck-green:  #16a34a; --ck-red:  #dc2626;
  --ck-radius: 12px;
  --ck-shadow: 0 1px 3px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.04);
}
.ck-page { background: var(--ck-bg); min-height: 100vh; padding: 36px 0 80px; }
.ck-breadcrumb { font-size:13px; color:var(--ck-gray); margin-bottom:24px; }
.ck-breadcrumb a { color:var(--ck-gray); text-decoration:none; }
.ck-breadcrumb a:hover { color:var(--ck-black); }
.ck-card { background:#fff; border-radius:var(--ck-radius); box-shadow:var(--ck-shadow); padding:24px; margin-bottom:14px; }
.ck-card-title { font-size:15px; font-weight:700; letter-spacing:-.2px; color:var(--ck-black); margin:0 0 20px; display:flex; align-items:center; gap:10px; text-wrap:balance; }
.ck-step { width:26px; height:26px; border-radius:50%; background:var(--ck-black); color:#fff; font-size:12px; font-weight:700; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; }

/* fields */
.ck-row2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
@media(max-width:576px){ .ck-row2 { grid-template-columns:1fr; } }
.ck-field { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
.ck-field:last-child { margin-bottom:0; }
.ck-label { font-size:11.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#374151; }
.ck-label .req { color:var(--ck-red); margin-left:2px; }
.ck-input {
  padding:11px 14px; border:1.5px solid var(--ck-border); border-radius:9px;
  font-size:14.5px; color:var(--ck-black); background:#fafafa; width:100%;
  font-family:inherit; transition:border-color .18s, box-shadow .18s;
  touch-action:manipulation; -webkit-tap-highlight-color:transparent;
}
.ck-input:focus-visible { outline:none; border-color:var(--ck-black); background:#fff; box-shadow:0 0 0 3px rgba(0,0,0,.08); }
.ck-input:disabled { background:#f0f0f0; color:#aaa; cursor:not-allowed; }
.ck-input.is-invalid { border-color:var(--ck-red); }
.ck-error { font-size:12px; color:var(--ck-red); }

/* address cards */
.addr-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(210px,1fr)); gap:12px; margin-bottom:14px; }
.addr-card {
  position:relative; border:1.5px solid var(--ck-border); border-radius:var(--ck-radius);
  padding:15px 15px 13px; cursor:pointer; user-select:none;
  transition:border-color .18s, background .18s;
  display:flex; flex-direction:column; gap:3px;
  -webkit-tap-highlight-color:transparent;
}
.addr-card:hover { border-color:#9ca3af; }
.addr-card.is-sel { border-color:var(--ck-black); background:#fafafa; }
.addr-radio { position:absolute; opacity:0; width:0; height:0; }
.addr-dot { position:absolute; top:13px; right:13px; width:18px; height:18px; border-radius:50%; border:2px solid var(--ck-border); display:flex; align-items:center; justify-content:center; transition:border-color .18s,background .18s; }
.addr-card.is-sel .addr-dot { border-color:var(--ck-black); background:var(--ck-black); }
.addr-dot::after { content:''; width:6px; height:6px; border-radius:50%; background:#fff; opacity:0; transition:opacity .15s; }
.addr-card.is-sel .addr-dot::after { opacity:1; }
.addr-name { font-weight:700; font-size:13.5px; color:var(--ck-black); padding-right:24px; }
.addr-sub { font-size:12.5px; color:var(--ck-gray); line-height:1.4; }
.addr-badge { display:inline-block; font-size:10px; font-weight:700; letter-spacing:.4px; text-transform:uppercase; background:#f0fdf4; color:var(--ck-green); border:1px solid #bbf7d0; border-radius:4px; padding:2px 6px; margin-top:3px; width:fit-content; }
.addr-add { border-style:dashed; align-items:center; justify-content:center; flex-direction:row; gap:8px; color:var(--ck-gray); font-size:13.5px; font-weight:500; min-height:90px; }
.addr-add:hover { border-color:var(--ck-black); color:var(--ck-black); }
.addr-add.is-sel { border-color:var(--ck-black); color:var(--ck-black); }
.addr-empty { text-align:center; padding:16px 0 20px; color:var(--ck-gray); font-size:13.5px; }
.addr-empty i { font-size:28px; opacity:.3; display:block; margin-bottom:8px; }
.inline-form { border-top:1px solid var(--ck-border); padding-top:18px; margin-top:2px; }

/* shipping */
.ship-row { display:flex; align-items:center; justify-content:space-between; padding:13px 15px; border-radius:9px; background:#f9fafb; border:1.5px solid var(--ck-border); }
.ship-name { font-size:14px; font-weight:600; color:var(--ck-black); }
.ship-sub { font-size:12px; color:var(--ck-gray); }
.ship-fee { font-size:14px; font-weight:700; font-variant-numeric:tabular-nums; color:var(--ck-black); }
.ship-fee.free { color:var(--ck-green); }
.ship-note { font-size:12px; color:var(--ck-gray); margin:8px 0 0; }

/* payment */
.pm-list { display:flex; flex-direction:column; gap:10px; }
.pm-opt {
  display:flex; align-items:center; gap:14px; padding:13px 15px;
  border:1.5px solid var(--ck-border); border-radius:10px; cursor:pointer;
  transition:border-color .18s, background .18s; touch-action:manipulation;
}
.pm-opt:hover { border-color:#9ca3af; }
.pm-opt.is-sel { border-color:var(--ck-black); background:#fafafa; }
.pm-opt input { display:none; }
.pm-dot { width:20px; height:20px; border-radius:50%; border:2px solid var(--ck-border); flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:border-color .18s,background .18s; }
.pm-opt.is-sel .pm-dot { border-color:var(--ck-black); background:var(--ck-black); }
.pm-dot::after { content:''; width:7px; height:7px; border-radius:50%; background:#fff; opacity:0; transition:opacity .15s; }
.pm-opt.is-sel .pm-dot::after { opacity:1; }
.pm-title { font-size:13.5px; font-weight:600; color:var(--ck-black); }
.pm-desc { font-size:12px; color:var(--ck-gray); margin-top:2px; }
.pm-icon { font-size:20px; margin-left:auto; }
.bank-box { margin-top:14px; padding:14px; background:#f8fafc; border-radius:9px; border:1px solid #e0e7ef; font-size:13px; line-height:1.8; display:none; }
.bank-box.show { display:block; }

/* summary */
.sum-sticky { position:sticky; top:24px; }
.sum-title { font-size:15px; font-weight:700; color:var(--ck-black); margin-bottom:16px; }
.sum-items { display:flex; flex-direction:column; gap:10px; max-height:260px; overflow-y:auto; margin-bottom:14px; }
.sum-item { display:flex; align-items:center; gap:11px; }
.sum-img { width:50px; height:50px; border-radius:8px; object-fit:cover; border:1px solid var(--ck-border); flex-shrink:0; }
.sum-info { flex:1; min-width:0; }
.sum-iname { font-size:13px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sum-imeta { font-size:12px; color:var(--ck-gray); }
.sum-iprice { font-size:13px; font-weight:600; font-variant-numeric:tabular-nums; white-space:nowrap; }
hr.sum-div { border:none; border-top:1px solid var(--ck-border); margin:12px 0; }
.sum-row { display:flex; justify-content:space-between; font-size:13.5px; color:#374151; margin-bottom:7px; font-variant-numeric:tabular-nums; }
.sum-row.total { font-size:16px; font-weight:700; color:var(--ck-black); margin-top:4px; margin-bottom:0; }
.sum-discount { color:var(--ck-green); }

/* coupon */
.cpn-applied { display:flex; align-items:center; justify-content:space-between; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px 13px; font-size:13px; margin-bottom:10px; }
.cpn-row { display:flex; gap:8px; margin-bottom:10px; }
.cpn-input { flex:1; padding:10px 12px; border:1.5px solid var(--ck-border); border-radius:8px; font-size:13px; text-transform:uppercase; letter-spacing:1px; font-family:inherit; }
.cpn-input:focus-visible { outline:none; border-color:var(--ck-black); box-shadow:0 0 0 3px rgba(0,0,0,.07); }
.btn-cpn { padding:10px 15px; background:var(--ck-black); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; touch-action:manipulation; transition:background .15s; }
.btn-cpn:hover { background:#333; }
.btn-rm-cpn { font-size:12px; color:var(--ck-gray); background:none; border:none; cursor:pointer; text-decoration:underline; padding:0; }

/* submit */
.btn-order { width:100%; padding:15px; background:var(--ck-black); color:#fff; border:none; border-radius:10px; font-size:15px; font-weight:700; letter-spacing:.4px; cursor:pointer; touch-action:manipulation; transition:background .18s, transform .12s; }
.btn-order:hover { background:#1c1c1e; }
.btn-order:active { transform:scale(.985); }
.btn-order:disabled { opacity:.6; cursor:not-allowed; transform:none; }
/* sum-item delete button */
.sum-item-right { display:flex; align-items:center; gap:8px; flex-shrink:0; }
.sum-item-rm {
  border:none; background:none; color:#bbb; cursor:pointer;
  font-size:14px; padding:2px 4px; line-height:1;
  transition:color .15s;
}
.sum-item-rm:hover { color:#e53e3e; }

/* selected address display */
.sel-addr-box {
  display:flex; align-items:center; justify-content:space-between;
  border:1.5px solid var(--ck-border); border-radius:var(--ck-radius);
  padding:16px 18px; cursor:pointer; transition:border-color .18s,background .18s;
  -webkit-tap-highlight-color:transparent; gap:12px;
}
.sel-addr-box:hover { border-color:#9ca3af; background:#fafafa; }
.sel-addr-info { flex:1; min-width:0; }
.sel-addr-name { font-size:14px; font-weight:700; color:var(--ck-black); }
.sel-addr-phone { font-size:13px; color:var(--ck-gray); margin:2px 0; }
.sel-addr-detail { font-size:12.5px; color:var(--ck-gray); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sel-addr-change { font-size:13px; font-weight:600; color:var(--ck-black); white-space:nowrap; flex-shrink:0; }

/* no-address button */
.addr-no-addr-btn {
  width:100%; border:2px dashed var(--ck-border); border-radius:var(--ck-radius);
  padding:28px 20px; display:flex; flex-direction:column; align-items:center;
  justify-content:center; gap:6px; cursor:pointer; background:none;
  color:var(--ck-gray); transition:border-color .18s,color .18s;
  font-family:inherit;
}
.addr-no-addr-btn:hover { border-color:var(--ck-black); color:var(--ck-black); }
.addr-no-addr-btn i { font-size:26px; opacity:.4; }
.addr-no-addr-btn span { font-size:14px; font-weight:600; }
.addr-no-addr-btn small { font-size:12px; }

/* modal: address pick cards */
.mpick-card {
  border:1.5px solid var(--ck-border); border-radius:var(--ck-radius);
  padding:14px 15px 12px; cursor:pointer; user-select:none; position:relative;
  transition:border-color .18s,background .18s; display:flex; flex-direction:column; gap:3px;
  height:100%;
}
/* Force all address cards to same height with CSS Grid */
#modal-addr-grid {
  display:grid !important;
  grid-template-columns:1fr 1fr;
  grid-auto-rows:1fr;
  gap:12px;
}
#modal-addr-grid > .col-6 {
  padding:0 !important;
  width:auto !important;
  max-width:none !important;
}
.mpick-card:hover   { border-color:#9ca3af; }
.mpick-card.is-sel  { border-color:var(--ck-black); background:#fafafa; }
.mpick-dot {
  position:absolute; top:12px; right:12px; width:18px; height:18px; border-radius:50%;
  border:2px solid var(--ck-border); display:flex; align-items:center; justify-content:center;
  transition:border-color .18s,background .18s;
}
.mpick-card.is-sel .mpick-dot { border-color:var(--ck-black); background:var(--ck-black); }
.mpick-dot::after { content:''; width:6px; height:6px; border-radius:50%; background:#fff; opacity:0; transition:opacity .15s; }
.mpick-card.is-sel .mpick-dot::after { opacity:1; }
.mpick-name { font-weight:700; font-size:13px; color:var(--ck-black); padding-right:26px; }
.mpick-phone { font-size:12px; color:var(--ck-gray); }
.mpick-addr  { font-size:11.5px; color:var(--ck-gray); line-height:1.4; }
.mpick-add-btn {
  width:100%; border:1.5px dashed var(--ck-border); border-radius:var(--ck-radius);
  padding:12px; display:flex; align-items:center; justify-content:center;
  gap:8px; cursor:pointer; background:none; font-size:13px; font-weight:600;
  color:var(--ck-gray); transition:border-color .18s,color .18s; font-family:inherit;
}
.mpick-add-btn:hover { border-color:var(--ck-black); color:var(--ck-black); }
.modal-addr-form { border-top:1px solid var(--ck-border); padding-top:20px; margin-top:6px; }
.modal-addr-form .ck-label { font-size:11px; }
</style>
@endpush

@section('content')
<div class="ck-page">
<div class="container">

  {{-- Breadcrumb --}}
  <nav class="ck-breadcrumb" aria-label="Điều hướng">
    <a href="{{ route('welcome') }}">Trang chủ</a> <span aria-hidden="true"> › </span>
    <a href="{{ route('cart.index') }}">Giỏ hàng</a> <span aria-hidden="true"> › </span>
    <span>Thanh toán</span>
  </nav>

  {{-- Config --}}
  <div id="ck-cfg" hidden
    data-csrf="{{ csrf_token() }}"
    data-shipping="{{ url('/api/checkout/shipping-fees') }}"
    data-cpn-apply="{{ route('checkout.applyCoupon') }}"
    data-cpn-remove="{{ route('checkout.removeCoupon') }}"
    data-provinces="{{ route('api.vn-address.provinces') }}"
    data-communes="{{ url('api/vn-address/communes') }}"
    data-subtotal="{{ $total }}"
    data-base="{{ $total - $discount }}"
    data-discount="{{ $discount }}"
    data-ship-init="{{ $shippingFee }}"
  ></div>

  @if($errors->any())
  <div class="alert alert-danger mb-3" role="alert" aria-live="polite">
    <strong>Vui lòng kiểm tra lại:</strong>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  <form id="ck-form" action="{{ route('checkout.store') }}" method="POST" autocomplete="on" novalidate>
  @csrf

  <div class="row g-4">

    {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
    <div class="col-lg-7">

      {{-- ── 1. SHIPPING INFO ── --}}
      <div class="ck-card">
        <h2 class="ck-card-title">
          <span class="ck-step" aria-hidden="true">1</span>Thông tin giao hàng
        </h2>

        @auth
          @php $defAddr = $userAddresses->firstWhere('is_default', true) ?? $userAddresses->first(); @endphp
          {{-- Hidden selected address ID (updated by JS) --}}
          <input type="hidden" name="user_address_id" id="sel-addr-id"
                 value="{{ $defAddr?->id ?? '' }}">

          @if($userAddresses->isNotEmpty())
            {{-- Compact selected-address display --}}
            <div class="sel-addr-box" id="sel-addr-display"
                 data-bs-toggle="modal" data-bs-target="#addr-pick-modal"
                 role="button" tabindex="0"
                 aria-label="Thay đổi địa chỉ giao hàng">
              <div class="sel-addr-info">
                <div class="sel-addr-name" id="sel-display-name">{{ $defAddr->receiver_name }}</div>
                <div class="sel-addr-phone" id="sel-display-phone">{{ $defAddr->phone }}</div>
                <div class="sel-addr-detail" id="sel-display-detail">{{ $defAddr->address }}, {{ $defAddr->commune }}, {{ $defAddr->province }}</div>
                @if($defAddr->is_default)<span class="addr-badge" id="sel-display-badge">Mặc định</span>@endif
              </div>
              <span class="sel-addr-change">Đổi <i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            </div>

          @else
            {{-- No addresses: full-width add button --}}
            <button type="button" class="addr-no-addr-btn"
                    data-bs-toggle="modal" data-bs-target="#addr-pick-modal">
              <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
              <span>Địa chỉ nhận hàng</span>
              <small>Chưa có — nhấn để thêm</small>
            </button>
          @endif

          <input type="hidden" name="email" value="{{ Auth::user()->email }}">
        @endauth



        @guest
          <div class="ck-row2">
            <div class="ck-field">
              <label class="ck-label" for="g_name">Họ và tên <span class="req">*</span></label>
              <input class="ck-input {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                     id="g_name" name="name" autocomplete="name" placeholder="Nguyễn Văn A…"
                     value="{{ old('name') }}" required>
              @error('name')<span class="ck-error">{{ $message }}</span>@enderror
            </div>
            <div class="ck-field">
              <label class="ck-label" for="g_phone">Số điện thoại <span class="req">*</span></label>
              <input class="ck-input {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="tel"
                     id="g_phone" name="phone" autocomplete="tel" inputmode="numeric"
                     maxlength="10" pattern="^(03|05|07|08|09)\d{8}$"
                     placeholder="0901234567…" value="{{ old('phone') }}" required>
              @error('phone')<span class="ck-error">{{ $message }}</span>@enderror
            </div>
          </div>
          <div class="ck-field">
            <label class="ck-label" for="g_email">Email <span class="req">*</span></label>
            <input class="ck-input {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                   id="g_email" name="email" autocomplete="email" spellcheck="false"
                   placeholder="example@gmail.com…" value="{{ old('email') }}" required>
            @error('email')<span class="ck-error">{{ $message }}</span>@enderror
          </div>
          <div class="ck-row2">
            <div class="ck-field">
              <label class="ck-label" for="g_province">Tỉnh / Thành phố <span class="req">*</span></label>
              <select class="ck-input {{ $errors->has('province') ? 'is-invalid' : '' }}"
                      id="g_province" name="province" required>
                <option value="">-- Đang tải… --</option>
              </select>
              @error('province')<span class="ck-error">{{ $message }}</span>@enderror
            </div>
            <div class="ck-field">
              <label class="ck-label" for="g_commune">Xã / Phường <span class="req">*</span></label>
              <select class="ck-input {{ $errors->has('commune') ? 'is-invalid' : '' }}"
                      id="g_commune" name="commune" required disabled>
                <option value="">-- Chọn tỉnh trước --</option>
              </select>
              @error('commune')<span class="ck-error">{{ $message }}</span>@enderror
            </div>
          </div>
          <div class="ck-field">
            <label class="ck-label" for="g_address">Địa chỉ chi tiết <span class="req">*</span></label>
            <input class="ck-input {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text"
                   id="g_address" name="address" autocomplete="street-address"
                   placeholder="Nhập địa chỉ chi tiết…" value="{{ old('address') }}" required>
            @error('address')<span class="ck-error">{{ $message }}</span>@enderror
          </div>
        @endguest
      </div>

      {{-- ── 2. SHIPPING DISPLAY ── --}}
      <div class="ck-card">
        <h2 class="ck-card-title">
          <span class="ck-step" aria-hidden="true">2</span>Vận chuyển
        </h2>
        {{-- TODO: Team phát triển sau -- Ẩn Viettel Post và phí vận chuyển
        <div class="ship-row">
          <div>
            <div class="ship-name">Viettel Post</div>
            <div class="ship-sub">Giao hàng tiêu chuẩn 3–5 ngày</div>
          </div>
          <span id="ship-fee-badge" class="ship-fee {{ $shippingFee == 0 ? 'free' : '' }}" aria-live="polite">
            {{ $shippingFee > 0 ? number_format($shippingFee,0,',','.') . 'đ' : 'Miễn phí' }}
          </span>
        </div>
        <p id="ship-note" class="ship-note">
          @if($shippingFee > 0) 💡 Phí vận chuyển thanh toán khi nhận hàng.
          @else 🎉 Bạn được miễn phí vận chuyển! @endif
        </p>
        --}}
        <p class="text-muted small mb-0" style="padding:4px 0">
          📦 Phương thức vận chuyển sẽ được cập nhật sớm.
        </p>
      </div>

      {{-- ── 3. PAYMENT ── --}}
      <div class="ck-card">
        <h2 class="ck-card-title">
          <span class="ck-step" aria-hidden="true">3</span>Phương thức thanh toán
        </h2>
        <div class="pm-list" role="radiogroup" aria-label="Chọn phương thức thanh toán">
          <label class="pm-opt {{ old('payment_method','COD')==='COD' ? 'is-sel' : '' }}" for="pm_cod">
            <input type="radio" id="pm_cod" name="payment_method" value="COD"
                   {{ old('payment_method','COD')==='COD' ? 'checked' : '' }}>
            <div class="pm-dot" aria-hidden="true"></div>
            <div class="flex-grow-1">
              <div class="pm-title">Thanh toán khi nhận hàng (COD)</div>
              <div class="pm-desc">Trả tiền mặt khi shipper giao tới</div>
            </div>
            <span class="pm-icon" aria-hidden="true">💵</span>
          </label>
          {{-- TODO: Team phát triển sau -- Ẩn chức năng Chuyển khoản ngân hàng
          <label class="pm-opt {{ old('payment_method')==='BANK_TRANSFER' ? 'is-sel' : '' }}" for="pm_bank">
            <input type="radio" id="pm_bank" name="payment_method" value="BANK_TRANSFER"
                   {{ old('payment_method')==='BANK_TRANSFER' ? 'checked' : '' }}>
            <div class="pm-dot" aria-hidden="true"></div>
            <div class="flex-grow-1">
              <div class="pm-title">Chuyển khoản ngân hàng</div>
              <div class="pm-desc">Xác nhận trong 24h sau khi chuyển</div>
            </div>
            <span class="pm-icon" aria-hidden="true">🏦</span>
          </label>
          --}}
          <label class="pm-opt {{ old('payment_method')==='VNPAY' ? 'is-sel' : '' }}" for="pm_vnpay">
            <input type="radio" id="pm_vnpay" name="payment_method" value="VNPAY"
                   {{ old('payment_method')==='VNPAY' ? 'checked' : '' }}>
            <div class="pm-dot" aria-hidden="true"></div>
            <div class="flex-grow-1">
              <div class="pm-title">Thanh toán VNPAY</div>
              <div class="pm-desc">Thẻ ATM, Visa, QR Code qua cổng VNPAY</div>
            </div>
            <span class="pm-icon" aria-hidden="true">📱</span>
          </label>
        </div>
        {{-- TODO: Team phát triển sau -- Ẩn bank-box
        @if(isset($defaultBank) && $defaultBank)
        <div id="bank-box" class="bank-box {{ old('payment_method')==='BANK_TRANSFER' ? 'show' : '' }}">
          <div><strong>Ngân hàng:</strong> {{ $defaultBank->bank_id ?? '—' }}</div>
          <div><strong>Số tài khoản:</strong> <strong>{{ $defaultBank->account_number ?? '—' }}</strong></div>
          <div><strong>Chủ tài khoản:</strong> {{ $defaultBank->account_name ?? '—' }}</div>
          <div style="font-size:12px;color:#888;margin-top:4px;">Ghi nội dung chuyển khoản: Mã đơn hàng (nhận sau khi đặt)</div>
        </div>
        @endif
        --}}
      </div>

      {{-- ── 4. NOTE ── --}}
      <div class="ck-card">
        <div class="ck-field" style="margin-bottom:0">
          <label class="ck-label" for="ck_note">Ghi chú <span style="font-weight:400;text-transform:none;letter-spacing:0">(tuỳ chọn)</span></label>
          <textarea class="ck-input" id="ck_note" name="note" rows="2"
                    style="resize:vertical;min-height:68px"
                    placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao…">{{ old('note') }}</textarea>
        </div>
      </div>

    </div>

    {{-- ═══════════════ RIGHT COLUMN ═══════════════ --}}
    <div class="col-lg-5">
      <div class="sum-sticky">
        <div class="ck-card">
          <h3 class="sum-title">Đơn hàng của bạn</h3>

          <div class="sum-items" aria-label="Sản phẩm trong đơn">
            @foreach($cart as $vId => $item)
            @php $lineTotal = $item['price'] * $item['quantity']; @endphp
            <div class="sum-item" id="sum-item-{{ $vId }}"
                 data-vid="{{ $vId }}" data-amount="{{ $lineTotal }}">
              <img class="sum-img" loading="lazy" width="50" height="50"
                   src="{{ $item['image'] ? asset('storage/'.$item['image']) : asset('frontend-assets/img/s-product/product.jpg') }}"
                   alt="{{ $item['name'] }}">
              <div class="sum-info">
                <p class="sum-iname mb-0">{{ $item['name'] }}</p>
                <p class="sum-imeta mb-0">
                  x{{ $item['quantity'] }}@if(!empty($item['size'])) · {{ $item['size'] }}@endif
                  @if(!empty($item['color'])) · {{ $item['color'] }}@endif
                </p>
              </div>
              <div class="sum-item-right">
                <span class="sum-iprice">{{ number_format($lineTotal,0,',','.') }}đ</span>
                <button type="button" class="sum-item-rm"
                        aria-label="Xóa sản phẩm"
                        data-url="{{ route('checkout.remove-item', $vId) }}">&#x2715;</button>
              </div>
            </div>
            @endforeach
          </div>

          <hr class="sum-div">

          {{-- Coupon: applied state (shown when coupon active) --}}
          <div id="cpn-applied-state" class="{{ session('coupon_code') ? '' : 'd-none' }}">
            <div class="cpn-applied">
              <span>Mã <strong id="cpn-code-label">{{ session('coupon_code') }}</strong> đã áp dụng</span>
              <button type="button" id="btn-rm-cpn" class="btn-rm-cpn" aria-label="Xóa mã giảm giá">Xóa</button>
            </div>
          </div>

          {{-- Coupon: input state (shown when no coupon) --}}
          <div id="cpn-input-state" class="{{ session('coupon_code') ? 'd-none' : '' }}">
            <div class="cpn-row">
              <input class="cpn-input" type="text" id="cpn-input" autocomplete="off" spellcheck="false"
                     placeholder="Nhập mã giảm giá…" aria-label="Mã giảm giá">
              <button class="btn-cpn" id="btn-cpn" type="button" aria-label="Áp dụng mã">Áp dụng</button>
            </div>
            <div id="cpn-msg" aria-live="polite" style="font-size:12px;margin-bottom:8px;min-height:16px;"></div>
          </div>

          <div class="sum-row"><span>Tạm tính</span><span>{{ number_format($total,0,',','.') }}đ</span></div>
          {{-- TODO: Team phát triển sau -- Ẩn phí vận chuyển
          <div class="sum-row">
            <span>Phí vận chuyển</span>
            <span id="sum-ship">{{ $shippingFee > 0 ? number_format($shippingFee,0,',','.') . 'đ' : 'Miễn phí' }}</span>
          </div>
          --}}
          <div class="sum-row" id="sum-discount-row" @if(!$discount) style="display:none" @endif>
            <span>Giảm giá</span>
            <span class="sum-discount" id="sum-discount">
              @if($discount > 0)−{{ number_format($discount,0,',','.') }}đ@endif
            </span>
          </div>
          <hr class="sum-div">
          <div class="sum-row total">
            <span>Tổng cộng</span>
            <span id="sum-total">{{ number_format($finalTotal,0,',','.') }}đ</span>
          </div>

          <button type="submit" form="ck-form" class="btn-order mt-4" aria-label="Đặt hàng">
            Đặt Hàng
          </button>
        </div>
      </div>
    </div>

  </div>{{-- /row --}}
  </form>
</div>
</div>

@auth
{{-- ══════════ ADDRESS PICKER MODAL ══════════ --}}
<div class="modal fade" id="addr-pick-modal" tabindex="-1" aria-labelledby="addr-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 40px rgba(0,0,0,.14);">
      <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:18px 24px;">
        <h5 class="modal-title" id="addr-modal-label" style="font-size:16px;font-weight:700;">
          <i class="fa fa-map-marker-alt me-2" aria-hidden="true"></i>Chọn địa chỉ giao hàng
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body" style="padding:20px 24px;">
        {{-- Hidden dismiss trigger (JS uses this to close modal safely) --}}
        <button id="modal-dismiss-hidden" type="button" data-bs-dismiss="modal"
                style="display:none;" aria-hidden="true"></button>

        @if($userAddresses->isNotEmpty())
        {{-- Address List Grid 2 Cols --}}
        <div class="row g-3 mb-3" id="modal-addr-grid">
          @php $defAddr2 = $userAddresses->firstWhere('is_default', true) ?? $userAddresses->first(); @endphp
          @foreach($userAddresses as $addr)
          <div class="col-6">
            <div class="mpick-card {{ $addr->id === ($defAddr2?->id) ? 'is-sel' : '' }}"
                 data-id="{{ $addr->id }}"
                 data-name="{{ $addr->receiver_name }}"
                 data-phone="{{ $addr->phone }}"
                 data-province="{{ $addr->province }}"
                 data-commune="{{ $addr->commune }}"
                 data-address="{{ $addr->address }}">
              <div class="mpick-dot" aria-hidden="true"></div>
              <div class="mpick-name">{{ $addr->receiver_name }}</div>
              <div class="mpick-phone">{{ $addr->phone }}</div>
              <div class="mpick-addr">{{ $addr->address }}, {{ $addr->commune }}, {{ $addr->province }}</div>
              @if($addr->is_default)<span class="addr-badge" style="margin-top:4px">Mặc định</span>@endif
            </div>
          </div>
          @endforeach
        </div>
        <hr style="margin:16px 0;">
        @else
        <div id="modal-addr-grid" class="row g-3 mb-3"></div>
        @endif

        {{-- Add new address toggle --}}
        <button type="button" id="modal-btn-add-addr" class="mpick-add-btn mb-3">
          + Thêm địa chỉ mới
        </button>

        {{-- Add address form (hidden by default if addresses exist) --}}
        <div id="modal-addr-new-form" class="{{ $userAddresses->isNotEmpty() ? 'd-none' : '' }}">
          <form id="modal-new-addr-form-inner" novalidate>
            @csrf
            <div class="modal-addr-form">
              <div id="modal-addr-err" class="ck-error mb-2" aria-live="polite"></div>
              <div class="ck-row2">
                <div class="ck-field">
                  <label class="ck-label" for="modal_receiver_name">Tên người nhận <span class="req">*</span></label>
                  <input class="ck-input" type="text" id="modal_receiver_name" name="receiver_name"
                         autocomplete="name" placeholder="Nguyễn Văn A…" required>
                </div>
                <div class="ck-field">
                  <label class="ck-label" for="modal_phone">Số điện thoại <span class="req">*</span></label>
                  <input class="ck-input" type="tel" id="modal_phone" name="phone"
                         autocomplete="tel" inputmode="numeric" maxlength="10"
                         pattern="^(03|05|07|08|09)\d{8}$" placeholder="0901234567…" required>
                </div>
              </div>
              <div class="ck-row2">
                <div class="ck-field">
                  <label class="ck-label" for="modal_province">Tỉnh / Thành phố <span class="req">*</span></label>
                  <select class="ck-input" id="modal_province" name="province" required>
                    <option value="">-- Đang tải… --</option>
                  </select>
                </div>
                <div class="ck-field">
                  <label class="ck-label" for="modal_commune">Xã / Phường <span class="req">*</span></label>
                  <select class="ck-input" id="modal_commune" name="commune" required disabled>
                    <option value="">-- Chọn tỉnh trước --</option>
                  </select>
                </div>
              </div>
              <div class="ck-field">
                <label class="ck-label" for="modal_address">Địa chỉ chi tiết <span class="req">*</span></label>
                <input class="ck-input" type="text" id="modal_address" name="address"
                       autocomplete="street-address" placeholder="Số nhà, tên đường, ngõ/hẻm…" required>
              </div>
            </div>
          </form>
        </div>

      </div>{{-- /modal-body --}}

      {{-- Footer for selection mode --}}
      <div class="modal-footer" id="modal-footer-sel"
           style="border-top:1px solid #f0f0f0;padding:14px 24px;">
        <button type="button" class="btn-order" style="max-width:200px;padding:12px;"
                data-bs-dismiss="modal">Xác nhận địa chỉ</button>
      </div>
      {{-- Footer for add-address mode --}}
      <div class="modal-footer" id="modal-footer-add" style="border-top:1px solid #f0f0f0;padding:14px 24px;display:none;">
        <button type="button" id="modal-save-addr-btn" class="btn-order" style="max-width:200px;padding:12px;">
          Thêm địa chỉ
        </button>
      </div>
    </div>
  </div>
</div>
@endauth

<script>
(function(){
  'use strict';
  const C = (function(){
    const el = document.getElementById('ck-cfg');
    if(!el) return {};
    return {
      csrf:      el.dataset.csrf,
      shipping:  el.dataset.shipping,
      cpnApply:  el.dataset.cpnApply,
      cpnRemove: el.dataset.cpnRemove,
      provinces: el.dataset.provinces,
      communes:  el.dataset.communes,
      subtotal:  parseFloat(el.dataset.subtotal)||0,
      base:      parseFloat(el.dataset.base)||0,
      discount:  parseFloat(el.dataset.discount)||0,
      shipInit:  parseFloat(el.dataset.shipInit)||0,
    };
  })();

  let currentShipFee = 0; // C.shipInit; - Team phát triển sau: Force ship fee to 0

  const fmt = v => new Intl.NumberFormat('vi-VN').format(v)+'đ';
  const qs  = s => document.querySelector(s);
  const qsa = s => document.querySelectorAll(s);

  // ───── Province/Commune cascade ─────
  async function loadProvinces(pEl, cEl, oldProv, oldComm){
    try {
      const r = await fetch(C.provinces);
      const d = await r.json();
      let html = '<option value="">-- Chọn tỉnh/thành phố --</option>';
      let selCode = '';
      d.forEach(p=>{
        const sel = p.name===oldProv ? 'selected' : '';
        if(sel) selCode = p.code;
        html += `<option value="${p.name}" data-code="${p.code}" ${sel}>${p.name}</option>`;
      });
      pEl.innerHTML = html;
      if(selCode) loadCommunes(cEl, selCode, oldComm);
    } catch(e){ pEl.innerHTML='<option value="">Lỗi tải dữ liệu</option>'; }
  }
  async function loadCommunes(cEl, code, oldComm){
    cEl.innerHTML='<option value="">-- Đang tải… --</option>'; cEl.disabled=true;
    try {
      const r = await fetch(C.communes+'/'+code);
      const d = await r.json();
      let html='<option value="">-- Chọn xã/phường --</option>';
      d.forEach(c=>{ html+=`<option value="${c.name}" ${c.name===oldComm?'selected':''}>${c.name}</option>`; });
      cEl.innerHTML=html; cEl.disabled=false;
    } catch(e){ cEl.innerHTML='<option value="">Lỗi tải dữ liệu</option>'; }
  }
  function bindCascade(pEl, cEl, oldP, oldC){
    if(!pEl||!cEl) return;
    loadProvinces(pEl, cEl, oldP, oldC);
    pEl.addEventListener('change', function(){
      const opt = this.options[this.selectedIndex];
      const code = opt?.dataset?.code||'';
      if(code){
        loadCommunes(cEl,code,null);
        // triggerShipping(this.value,''); // Team phát triển sau
      }
      else { cEl.innerHTML='<option value="">-- Chọn tỉnh trước --</option>'; cEl.disabled=true; }
    });
    cEl.addEventListener('change', function(){
      // triggerShipping(pEl.value, this.value); // Team phát triển sau
    });
  }

  // ───── Shipping display ─────
  /* ───── TODO: Team phát triển sau ─────
  let shipTimer = null;
  function triggerShipping(province, commune){
    clearTimeout(shipTimer);
    if(!province) return;
    shipTimer = setTimeout(()=>{
      fetch(C.shipping, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':C.csrf},
        body: JSON.stringify({delivery_type:'home', province, district:'', ward:commune}),
      }).then(r=>r.json()).then(d=>{
        if(d.success && d.data?.length) renderShip(d.data[0].fee);
      }).catch(()=>{});
    }, 350);
  }
  */
  function renderShip(fee){
    currentShipFee = 0; // fee; - Force 0
    const badge   = qs('#ship-fee-badge');
    const note    = qs('#ship-note');
    const sumShip = qs('#sum-ship');
    const sumTotalEl = qs('#sum-total');
    if(badge){ badge.textContent = 'Miễn phí'; badge.className='ship-fee free'; }
    if(note){ note.textContent = '🎉 Bạn được miễn phí vận chuyển!'; }
    if(sumShip){ sumShip.textContent = 'Miễn phí'; }
    if(sumTotalEl){ sumTotalEl.textContent = fmt(C.base + 0); }
  }

  // ── Modal: address selection ──
  // Init shipping from default address on load
  (function(){
    /* TODO: Team phát triển sau
    const defId = qs('#sel-addr-id')?.value;
    const defCard = qs(`.mpick-card[data-id="${defId}"]`);
    if(defCard && defCard.dataset.province) triggerShipping(defCard.dataset.province, defCard.dataset.commune||'');
    const box = qs('#sel-addr-display');
    if(!defCard && box && box.dataset.province) triggerShipping(box.dataset.province, box.dataset.commune||'');
    */
    renderShip(0);
  })();

  // ——— Utility: close addr modal via Bootstrap's own dismiss mechanism ———
  function closeAddrModal(){
    // Clicking a data-bs-dismiss button is the safest way to close a Bootstrap modal
    // — avoids all race conditions with hide()/getInstance() during click events
    document.getElementById('modal-dismiss-hidden')?.click();
  }

  // ——— Helper: update selected-address display in checkout card ———
  function applyAddrSelection(card){
    document.querySelectorAll('.mpick-card').forEach(c=>c.classList.remove('is-sel'));
    card.classList.add('is-sel');
    const sid = qs('#sel-addr-id');
    if(sid) sid.value = card.dataset.id;
    const n = qs('#sel-display-name');
    const p = qs('#sel-display-phone');
    const d = qs('#sel-display-detail');
    if(n) n.textContent = card.dataset.name||'';
    if(p) p.textContent = card.dataset.phone||'';
    if(d) d.textContent = [card.dataset.address, card.dataset.commune, card.dataset.province].filter(Boolean).join(', ');
    // Show selected-addr-box if it was hidden (no-address state)
    const box = qs('#sel-addr-display');
    const noBtn = qs('.addr-no-addr-btn');
    if(!box){
      // Create display box if didn't exist (first-time add for no-addr user)
      const emailInp = qs('input[name="email"]');
      const container = emailInp?.closest('.ck-card') || qs('.ck-card');
      if(container){
        const div = document.createElement('div');
        div.id = 'sel-addr-display';
        div.className = 'sel-addr-box';
        div.setAttribute('data-bs-toggle','modal');
        div.setAttribute('data-bs-target','#addr-pick-modal');
        div.setAttribute('role','button');
        div.setAttribute('tabindex','0');
        div.innerHTML = `<div class="sel-addr-info">
          <div class="sel-addr-name" id="sel-display-name">${card.dataset.name||''}</div>
          <div class="sel-addr-phone" id="sel-display-phone">${card.dataset.phone||''}</div>
          <div class="sel-addr-detail" id="sel-display-detail">${[card.dataset.address,card.dataset.commune,card.dataset.province].filter(Boolean).join(', ')}</div>
        </div><span class="sel-addr-change">Đổi <i class="fa fa-chevron-right" aria-hidden="true"></i></span>`;
        if(noBtn) noBtn.replaceWith(div);
        else container.querySelector('h2')?.after(div);
      }
    }
    if(noBtn) noBtn.style.display='none';
    // if(card.dataset.province) triggerShipping(card.dataset.province, card.dataset.commune||''); // Team phát triển sau
  }

  // Modal address card click
  document.addEventListener('click', function(e){
    const card = e.target.closest('.mpick-card');
    if(!card) return;
    applyAddrSelection(card);
    closeAddrModal();
  });

  // Toggle add-address form in modal
  const btnAddToggle = qs('#modal-btn-add-addr');
  const modalAddForm = qs('#modal-addr-new-form');
  const modalFooterSel = qs('#modal-footer-sel');
  if(btnAddToggle && modalAddForm){
    btnAddToggle.addEventListener('click', ()=>{
      const opening = modalAddForm.classList.toggle('d-none');
      // opening = true means was open, now hidden (d-none added)
      const isNowVisible = !opening;
      btnAddToggle.textContent = isNowVisible ? '✕ Đóng' : '+ Thêm địa chỉ mới';
      if(modalFooterSel) modalFooterSel.style.display = isNowVisible ? 'none' : '';
      const modalFooterAdd = qs('#modal-footer-add');
      if(modalFooterAdd) modalFooterAdd.style.display = isNowVisible ? '' : 'none';
    });
  }

  // Load provinces for modal form
  bindCascade(qs('#modal_province'), qs('#modal_commune'), '', '');

  // AJAX save new address from modal
  const modalSaveBtn = qs('#modal-save-addr-btn');
  const modalErrBox = qs('#modal-addr-err');
  if(modalSaveBtn){
    modalSaveBtn.addEventListener('click', async ()=>{
      const f = qs('#modal-new-addr-form-inner');
      if(!f) return;
      // Simple client-side check
      const fields = f.querySelectorAll('input[required],select[required]');
      let ok = true;
      fields.forEach(el=>{ if(!el.value.trim()){ el.classList.add('is-invalid'); ok=false; } else el.classList.remove('is-invalid'); });
      if(!ok){ if(modalErrBox) modalErrBox.textContent='Vui lòng điền đầy đủ thông tin.'; return; }
      if(modalErrBox) modalErrBox.textContent='';

      modalSaveBtn.disabled=true; modalSaveBtn.textContent='Đang lưu…';
      try {
        const fd = new FormData(f);
        const res = await fetch('{{ route("account.addresses.store") }}', {
          method:'POST',
          headers:{'Accept':'application/json','X-CSRF-TOKEN':C.csrf},
          body: fd,
        });
        const data = await res.json();
        if(data.success && data.address){
          const a = data.address;
          // Build new card HTML and prepend to grid
          const grid = qs('#modal-addr-grid');
          if(grid){
            const col = document.createElement('div');
            col.className = 'col-6 col-sm-6';
            col.innerHTML = `<div class="mpick-card" data-id="${a.id}" data-name="${a.receiver_name}" data-phone="${a.phone}" data-province="${a.province}" data-commune="${a.commune||''}" data-address="${a.address}">
              <div class="mpick-dot"></div>
              <div class="mpick-name">${a.receiver_name}</div>
              <div class="mpick-phone">${a.phone}</div>
              <div class="mpick-addr">${a.address}, ${a.commune||''}, ${a.province}</div>
            </div>`;
            grid.prepend(col);
          }
          // Apply selection WITHOUT triggering click (avoids race with closeAddrModal)
          const newCard = grid?.querySelector(`.mpick-card[data-id="${a.id}"]`);
          if(newCard) applyAddrSelection(newCard);
          // Reset form fields
          f.reset();
          const mProv = qs('#modal_province'); if(mProv) mProv.innerHTML='<option value="">-- Định tỉnh trước --</option>';
          const mComm = qs('#modal_commune'); if(mComm){ mComm.innerHTML='<option value="">-- Chọn tỉnh trước --</option>'; mComm.disabled=true; }
          // Close modal cleanly
          closeAddrModal();
        } else {
          if(modalErrBox) modalErrBox.textContent = data.message || 'Có lỗi xảy ra.';
        }
      } catch(err){
        if(modalErrBox) modalErrBox.textContent='Lỗi kết nối, vui lòng thử lại.';
      } finally {
        modalSaveBtn.disabled=false; modalSaveBtn.textContent='Thêm địa chỉ';
      }
    });
  }

  // Handle show.bs.modal: set correct initial state
  document.getElementById('addr-pick-modal')?.addEventListener('show.bs.modal', ()=>{
    const hasCards = qs('#modal-addr-grid')?.querySelector('.mpick-card');
    if(!hasCards){
      // No addresses: always show add-form + footer-add
      if(modalAddForm) modalAddForm.classList.remove('d-none');
      if(modalFooterSel) modalFooterSel.style.display = 'none';
      const mfAdd = qs('#modal-footer-add');
      if(mfAdd) mfAdd.style.display = '';
      if(btnAddToggle) btnAddToggle.style.display = 'none'; // hide toggle btn when forced
    } else {
      // Has addresses: hide add-form, show selection footer
      if(modalAddForm) modalAddForm.classList.add('d-none');
      if(modalFooterSel) modalFooterSel.style.display = '';
      const mfAdd = qs('#modal-footer-add');
      if(mfAdd) mfAdd.style.display = 'none';
      if(btnAddToggle){ btnAddToggle.style.display = ''; btnAddToggle.textContent = '+ Thêm địa chỉ mới'; }
    }
    // Ensure provinces loaded in modal form
    const mProv = qs('#modal_province');
    if(mProv && mProv.options.length <= 1) bindCascade(mProv, qs('#modal_commune'), '', '');
  });

  // Province/commune cascade — fires for whichever form exists in DOM
  bindCascade(qs('#new_province'), qs('#new_commune'), '', '');
  bindCascade(qs('#g_province'), qs('#g_commune'), '', '');

  // ───── Payment method ─────
  document.querySelectorAll('.pm-opt').forEach(opt=>{
    opt.addEventListener('click', ()=>{
      document.querySelectorAll('.pm-opt').forEach(o=>o.classList.remove('is-sel'));
      opt.classList.add('is-sel');
      const radio = opt.querySelector('input[type="radio"]');
      if(radio) radio.checked = true;
      const bankBox = qs('#bank-box');
      // if(bankBox) bankBox.classList.toggle('show', radio?.value==='BANK_TRANSFER'); // Team phát triển sau
    });
  });

  // ───── Coupon ─────
  const btnCpn    = qs('#btn-cpn');
  const cpnInput  = qs('#cpn-input');
  const cpnMsg    = qs('#cpn-msg');

  function applyCouponUI(code, discount){
    // Update runtime totals
    C.discount = discount;
    C.base = C.subtotal - discount;
    // Refresh total display
    renderShip(currentShipFee);
    // Show discount row
    const dRow = qs('#sum-discount-row');
    const dAmt  = qs('#sum-discount');
    if(dRow) dRow.style.display = '';
    if(dAmt) dAmt.textContent = '−' + fmt(discount);
    // Toggle coupon UI
    const label = qs('#cpn-code-label');
    if(label) label.textContent = code;
    qs('#cpn-applied-state')?.classList.remove('d-none');
    qs('#cpn-input-state')?.classList.add('d-none');
  }

  function removeCouponUI(){
    C.discount = 0;
    C.base = C.subtotal;
    renderShip(currentShipFee);
    // Hide discount row
    const dRow = qs('#sum-discount-row');
    if(dRow) dRow.style.display = 'none';
    // Toggle coupon UI
    qs('#cpn-applied-state')?.classList.add('d-none');
    qs('#cpn-input-state')?.classList.remove('d-none');
    if(cpnInput) cpnInput.value = '';
    if(cpnMsg)  cpnMsg.textContent = '';
  }

  // Apply coupon
  if(btnCpn && cpnInput){
    btnCpn.addEventListener('click', async ()=>{
      const code = cpnInput.value.trim();
      if(!code){ if(cpnMsg) cpnMsg.textContent='Vui lòng nhập mã giảm giá.'; return; }
      btnCpn.disabled=true; btnCpn.textContent='Đang kiểm tra…';
      if(cpnMsg) cpnMsg.textContent='';
      try {
        const res = await fetch(C.cpnApply, {
          method:'POST',
          headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN':C.csrf
          },
          body: JSON.stringify({coupon_code: code, total: C.subtotal}),
        });
        const d = await res.json();
        if(d.success && d.data){
          applyCouponUI(d.data.coupon_code, d.data.discount);
        } else {
          if(cpnMsg){ cpnMsg.textContent=d.message||'Mã không hợp lệ.'; cpnMsg.style.color='var(--ck-red)'; }
        }
      } catch(e){
        if(cpnMsg){ cpnMsg.textContent='Có lỗi xảy ra, vui lòng thử lại.'; cpnMsg.style.color='var(--ck-red)'; }
      } finally {
        btnCpn.disabled=false; btnCpn.textContent='Áp dụng';
      }
    });
    cpnInput.addEventListener('keydown', e=>{ if(e.key==='Enter'){ e.preventDefault(); btnCpn.click(); } });
  }

  // Remove coupon
  qs('#btn-rm-cpn')?.addEventListener('click', async ()=>{
    try {
      await fetch(C.cpnRemove, {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':C.csrf},
      });
    } catch(e){}
    removeCouponUI();
  });

  // ───── Remove item from order summary ─────
  document.querySelectorAll('.sum-item-rm').forEach(btn=>{
    btn.addEventListener('click', async function(){
      const itemEl = this.closest('.sum-item');
      const amount = parseFloat(itemEl?.dataset.amount)||0;
      const url    = this.dataset.url;
      if(!url) return;
      // Optimistic UI: fade + remove
      this.disabled = true;
      itemEl.style.opacity='0.4';
      try {
        const res = await fetch(url, {
          method:'POST',
          headers:{'Accept':'application/json','X-CSRF-TOKEN':C.csrf},
        });
        if(res.ok || res.redirected){
          // Remove from DOM
          itemEl.remove();
          // Update subtotal
          C.subtotal = Math.max(0, C.subtotal - amount);
          C.base     = Math.max(0, C.subtotal - (C.discount||0));
          // Update subtotal row display
          const subEl = qs('.sum-row span:last-child');
          // Find the subtotal row by looking for the Tạm tính row
          document.querySelectorAll('.sum-row').forEach(row=>{
            if(row.querySelector('span:first-child')?.textContent.trim()==='Tạm tính'){
              row.querySelector('span:last-child').textContent = fmt(C.subtotal);
            }
          });
          // Refresh total (shipping stays same)
          renderShip(currentShipFee);
          // If no items left, redirect to cart
          if(!document.querySelector('.sum-item')){
            window.location.href = '{{ route("cart.index") }}';
          }
        } else {
          itemEl.style.opacity='1';
          this.disabled=false;
        }
      } catch(e){
        itemEl.style.opacity='1';
        this.disabled=false;
      }
    });
  });

  // ───── Submit guard ─────
  qs('#ck-form')?.addEventListener('submit', function(e){
    const btn1 = qs('.btn-order');
    const btn2 = document.querySelectorAll('.btn-order')[1];
    [btn1, btn2].forEach(b=>{ if(b){ b.disabled=true; b.textContent='Đang xử lý…'; } });
  });
})();
</script>
@endsection
