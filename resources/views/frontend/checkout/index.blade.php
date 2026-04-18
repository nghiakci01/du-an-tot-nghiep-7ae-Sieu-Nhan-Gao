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
/* vouchers */
.ck-v-list { display:flex; flex-direction:column; gap:10px; margin-top:15px; }
.v-card {
  display:flex; align-items:center; gap:12px; padding:12px;
  border:1.5px solid var(--ck-border); border-radius:10px;
  position:relative; transition:all .2s; cursor:pointer;
  background:#fff;
}
.v-card.selectable:hover { border-color:var(--ck-black); transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.05); }
.v-card.is-sel { border-color:var(--ck-black); background:#fafafa; }
.v-card.disabled { opacity:0.6; cursor:not-allowed; filter:grayscale(0.8); background:#f9fafb; }
.v-icon-box {
  width:40px; height:40px; border-radius:8px; background:#fff2eb;
  color:#f26522; display:flex; align-items:center; justify-content:center;
  font-size:18px; flex-shrink:0;
}
.v-card.disabled .v-icon-box { background:#f1f1f1; color:#999; }
.v-info { flex:1; min-width:0; }
.v-code { font-size:13px; font-weight:700; color:var(--ck-black); margin-bottom:1px; display:flex; align-items:center; gap:6px; }
.v-desc { font-size:11.5px; color:var(--ck-gray); line-height:1.3; margin-bottom:2px; }
.v-fail { font-size:10px; font-weight:600; color:var(--ck-red); text-transform:uppercase; letter-spacing:0.3px; }
.v-apply-btn {
  font-size:12px; font-weight:700; color:#f26522;
  padding:4px 10px; border-radius:6px; background:#fff2eb;
  transition:all .15s;
}
.v-card.selectable:hover .v-apply-btn { background:#f26522; color:#fff; }
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
    data-ship-base-fee="{{ (float) \App\Models\Setting::get('shipping_fee', 30000) }}"
    data-ship-free-threshold="799000"
    data-ship-provider="{{ $shippingProviderName ?? '' }}"
    data-ship-eta="{{ $shippingExpectedDeliveryTime ?? '' }}"
  ></div>

  @if(session('error'))
  <div class="alert alert-danger mb-3" role="alert" aria-live="polite">
    <strong>Thông báo:</strong> {{ session('error') }}
  </div>
  @endif

  @if($errors->any())
  <div class="alert alert-danger mb-3" role="alert" aria-live="polite">
    <strong>Vui lòng kiểm tra lại:</strong>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  <form id="ck-form" action="{{ route('checkout.store') }}" method="POST" autocomplete="on" novalidate>
  @csrf
  <input type="hidden" name="shipping_provider" value="default">

  <div class="row g-4">

    {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
    <div class="col-lg-7">

      {{-- ── 1. SHIPPING INFO ── --}}
      <div class="ck-card">
        <h2 class="ck-card-title">
          <span class="ck-step" aria-hidden="true">1</span>Thông tin giao hàng
        </h2>

        @auth
          @php
            $defAddr = $userAddresses->firstWhere('is_default', true) ?? $userAddresses->first();
          @endphp
          {{-- Hidden selected address ID (updated by JS) --}}
          <input type="hidden" name="user_address_id" id="sel-addr-id" value="{{ $defAddr?->id ?? '' }}">
          
          {{-- Hidden fields required by CheckoutController validation --}}
          <input type="hidden" name="name"     id="sel-addr-name-val"  value="{{ $defAddr?->receiver_name ?? '' }}">
          <input type="hidden" name="phone"    id="sel-addr-phone-val" value="{{ $defAddr?->phone ?? '' }}">
          <input type="hidden" name="province" id="sel-addr-prov-val"  value="{{ preg_replace('/^(Tỉnh|Thành phố)\s+/u', '', $defAddr?->province ?? '') }}">
          <input type="hidden" name="ward"     id="sel-addr-ward-val"  value="{{ $defAddr?->commune ?? '' }}">
          <input type="hidden" name="address"  id="sel-addr-addr-val"  value="{{ $defAddr?->address ?? '' }}">

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
        <div class="ship-row">
          <div>
            <div class="ship-name" id="ship-provider-name">{{ $shippingProviderName ?? 'Giao hàng tiêu chuẩn' }}</div>
            <div class="ship-sub" id="ship-provider-sub">
              @if(!empty($shippingExpectedDeliveryTime))
                Dự kiến giao {{ $shippingExpectedDeliveryTime }}
              @else
                Phí sẽ được cập nhật theo địa chỉ giao hàng
              @endif
            </div>
          </div>
          <span id="ship-fee-badge" class="ship-fee {{ $shippingFee == 0 ? 'free' : '' }}" aria-live="polite">
            {{ $shippingFee > 0 ? number_format($shippingFee,0,',','.') . 'đ' : 'Miễn phí' }}
          </span>
        </div>
        <p id="ship-note" class="ship-note">
          @if($shippingFee > 0) 💡 Phí vận chuyển sẽ được cộng vào đơn hàng.
          @else 🎉 Bạn được miễn phí vận chuyển! @endif
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
              <div class="pm-desc">Trả tiền mặt khi người giao hàng giao tới</div>
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

            {{-- Available Vouchers List --}}
            @if(isset($availableCoupons) && $availableCoupons->isNotEmpty())
              <div class="mt-4">
                <h4 style="font-size:13px; font-weight:700; color:var(--ck-black); margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                  <i class="bi bi-ticket-perforated"></i> Mã giảm giá của bạn
                </h4>
                <div class="ck-v-list">
                  @foreach($availableCoupons as $v)
                    <div class="v-card {{ $v->is_applicable ? 'selectable js-v-apply' : 'disabled' }}" 
                         data-code="{{ $v->code }}"
                         title="{{ !$v->is_applicable ? $v->failure_reason : '' }}">
                      <div class="v-icon-box"><i class="bi bi-tag-fill"></i></div>
                      <div class="v-info">
                        <div class="v-code">
                          {{ $v->code }}
                          @if($v->is_applicable)
                            <span class="badge rounded-pill bg-success" style="font-size:9px; font-weight:500;">Có thể áp dụng</span>
                          @endif
                        </div>
                        <div class="v-desc">{{ $v->description ?: ('Giảm ' . $v->getFormattedValue() . ($v->min_order_amount > 0 ? ' cho đơn từ ₫' . number_format($v->min_order_amount, 0, ',', '.') : '')) }}</div>
                        @if(!$v->is_applicable)
                          <div class="v-fail"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $v->failure_reason }}</div>
                        @endif
                      </div>
                      @if($v->is_applicable)
                        <div class="v-apply-btn">Dùng ngay</div>
                      @endif
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>

          <div class="sum-row"><span>Tạm tính</span><span>{{ number_format($total,0,',','.') }}đ</span></div>
          <div class="sum-row"><span>Phí vận chuyển</span><span id="sum-ship">{{ $shippingFee > 0 ? number_format($shippingFee,0,',','.') . 'đ' : 'Miễn phí' }}</span></div>
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
              <div class="mpick-edit addr-edit-btn" title="Sửa địa chỉ" data-id="{{ $addr->id }}">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </div>
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
          <h5 id="modal-addr-form-title" class="mb-3" style="font-size:16px;font-weight:600;">Thêm địa chỉ giao hàng mới</h5>
          <form id="modal-new-addr-form-inner" novalidate>
            @csrf
            <input type="hidden" id="modal_address_id" name="address_id">
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
      <div class="modal-footer" id="modal-footer-add" style="border-top:1px solid #f0f0f0;padding:14px 24px;display:none;justify-content:space-between;">
        <button type="button" id="modal-delete-addr-btn" class="btn btn-link text-danger d-none" style="text-decoration:none;font-weight:500;padding:0;">
          <i class="fa fa-trash"></i> Xóa địa chỉ
        </button>
        <button type="button" id="modal-save-addr-btn" class="btn-order" style="max-width:200px;padding:12px;">
          Thêm địa chỉ
        </button>
      </div>
    </div>
  </div>
</div>
@endauth

</div>{{-- /ck-page --}}

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
      shipBaseFee: parseFloat(el.dataset.shipBaseFee)||0,
      shipFreeThreshold: parseFloat(el.dataset.shipFreeThreshold)||0,
      shipProvider: el.dataset.shipProvider || '',
      shipEta: el.dataset.shipEta || '',
    };
  })();

  let currentShipFee = C.shipInit || 0;
  const fmt = v => new Intl.NumberFormat('vi-VN').format(v)+'đ';
  const qs  = s => document.querySelector(s);
  const qsa = s => document.querySelectorAll(s);
  const syncNS = el => { if(window.jQuery && typeof jQuery.fn.niceSelect === 'function') jQuery(el).niceSelect('update'); };

  function getFallbackShippingFee(){
    return C.base >= C.shipFreeThreshold ? 0 : C.shipBaseFee;
  }

  function getShippingPayload(){
    const provider = qs('input[name="shipping_provider"]')?.value || '';
    const deliveryType = provider === 'store_pickup' ? 'store' : 'home';

    if (deliveryType === 'store') {
      return { delivery_type: 'store' };
    }

    const province = (qs('#sel-addr-prov-val')?.value || qs('#g_province')?.value || '').trim();
    const ward = (qs('#sel-addr-ward-val')?.value || qs('#g_commune')?.value || '').trim();

    return {
      delivery_type: 'home',
      province,
      ward,
      commune: ward,
    };
  }

  function renderShip(fee, option = null, isFallback = false){
    const safeFee = Number.isFinite(Number(fee)) ? Math.max(0, Number(fee)) : 0;
    currentShipFee = safeFee;

    const badge = qs('#ship-fee-badge');
    const note = qs('#ship-note');
    const sumTotalEl = qs('#sum-total');
    const providerEl = qs('#ship-provider-name');
    const subEl = qs('#ship-provider-sub');
    const sumShipEl = qs('#sum-ship');
    const providerName = option?.service_name || C.shipProvider || 'Giao hàng tiêu chuẩn';
    const eta = option?.expected_delivery_time || C.shipEta || '';

    if (providerEl) providerEl.textContent = providerName;
    if (subEl) {
      if (eta) {
        subEl.textContent = 'Dự kiến giao ' + eta;
      } else if (isFallback) {
        subEl.textContent = 'Phụ thuộc vào địa chỉ giao hàng';
      } else {
        subEl.textContent = 'Phí sẽ được cập nhật theo địa chỉ giao hàng';
      }
    }
    if (badge) {
      badge.textContent = currentShipFee > 0 ? fmt(currentShipFee) : 'Miễn phí';
      badge.className = 'ship-fee' + (currentShipFee === 0 ? ' free' : '');
    }
    if (note) {
      note.textContent = currentShipFee > 0
        ? '💡 Phí vận chuyển sẽ được cộng vào đơn hàng.'
        : '🎉 Bạn được miễn phí vận chuyển!';
    }
    if (sumShipEl) {
      sumShipEl.textContent = currentShipFee > 0 ? fmt(currentShipFee) : 'Miễn phí';
    }
    if (sumTotalEl) sumTotalEl.textContent = fmt(C.base + currentShipFee);
  }

  async function refreshShippingQuote(){
    const payload = getShippingPayload();
    if (payload.delivery_type !== 'store' && (!payload.province || !payload.ward)) {
      renderShip(getFallbackShippingFee(), null, true);
      return;
    }

    try {
      const res = await fetch(C.shipping, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': C.csrf,
        },
        body: JSON.stringify(payload),
      });

      const data = await res.json();
      if (data.success && Array.isArray(data.data) && data.data.length > 0) {
        const option = data.data[0] || {};
        renderShip(option.fee ?? 0, option, false);
        return;
      }
    } catch (e) {}

    renderShip(getFallbackShippingFee(), null, true);
  }

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
      syncNS(pEl);
      if(selCode) await loadCommunes(cEl, selCode, oldComm);
    } catch(e){ pEl.innerHTML='<option value="">Lỗi tải dữ liệu</option>'; syncNS(pEl); }
  }
  async function loadCommunes(cEl, code, oldComm){
    cEl.innerHTML='<option value="">-- Đang tải… --</option>'; cEl.disabled=true;
    try {
      const r = await fetch(C.communes+'/'+code);
      const d = await r.json();
      let html='<option value="">-- Chọn xã/phường --</option>';
      d.forEach(c=>{ html+=`<option value="${c.name}" ${c.name===oldComm?'selected':''}>${c.name}</option>`; });
      cEl.innerHTML=html; cEl.disabled=false;
      syncNS(cEl);
    } catch(e){ cEl.innerHTML='<option value="">Lỗi tải dữ liệu</option>'; syncNS(cEl); }
  }
  function bindCascade(pEl, cEl, oldP, oldC){
    if(!pEl||!cEl) return;
    loadProvinces(pEl, cEl, oldP, oldC).then(refreshShippingQuote);
    pEl.addEventListener('change', function(){
      const opt = this.options[this.selectedIndex];
      const code = opt?.dataset?.code||'';
      if(code) loadCommunes(cEl,code,null);
      else { cEl.innerHTML='<option value="">-- Chọn tỉnh trước --</option>'; cEl.disabled=true; }
      syncNS(cEl);
      refreshShippingQuote();
    });
    cEl.addEventListener('change', refreshShippingQuote);
  }

  renderShip(C.shipInit, {
    service_name: C.shipProvider,
    expected_delivery_time: C.shipEta,
  }, false);
  refreshShippingQuote();

  function closeAddrModal(){ document.getElementById('modal-dismiss-hidden')?.click(); }

  function applyAddrSelection(card){
    qsa('.mpick-card').forEach(c=>c.classList.remove('is-sel'));
    card.classList.add('is-sel');
    const sid = qs('#sel-addr-id'); if(sid) sid.value = card.dataset.id;
    
    const hN = qs('#sel-addr-name-val'), hP = qs('#sel-addr-phone-val'), hV = qs('#sel-addr-prov-val'), hW = qs('#sel-addr-ward-val'), hA = qs('#sel-addr-addr-val');
    if(hN) hN.value = card.dataset.name||''; if(hP) hP.value = card.dataset.phone||'';
    if(hV) hV.value = (card.dataset.province||'').replace(/^(Tỉnh|Thành phố)\s+/gu, '');
    if(hW) hW.value = card.dataset.commune||'';
    if(hA) hA.value = card.dataset.address||'';

    if(qs('#sel-display-name')) qs('#sel-display-name').textContent = card.dataset.name||'';
    if(qs('#sel-display-phone')) qs('#sel-display-phone').textContent = card.dataset.phone||'';
    if(qs('#sel-display-detail')) qs('#sel-display-detail').textContent = [card.dataset.address, card.dataset.commune, card.dataset.province].filter(Boolean).join(', ');
    refreshShippingQuote();
  }

  const modalFormTitle = qs('#modal-addr-form-title'), modalAddrIdInp = qs('#modal_address_id');
  const modalSaveBtn = qs('#modal-save-addr-btn'), modalDeleteBtn = qs('#modal-delete-addr-btn'), modalErrBox = qs('#modal-addr-err'), cpnMsg = qs('#cpn-msg');
  const btnAddToggle = qs('#modal-btn-add-addr'), modalAddForm = qs('#modal-addr-new-form');
  const modalFooterSel = qs('#modal-footer-sel'), modalFooterAdd = qs('#modal-footer-add'), modalAddrGrid = qs('#modal-addr-grid');

  function toggleAddrList(show){
    if(modalAddrGrid) modalAddrGrid.style.display = show ? '' : 'none';
    if(qs('#modal-addr-grid + hr')) qs('#modal-addr-grid + hr').style.display = show ? '' : 'none';
  }

  function resetModalForm(){
    const f = qs('#modal-new-addr-form-inner'); if(f) f.reset();
    if(modalAddrIdInp) modalAddrIdInp.value = '';
    if(modalFormTitle) modalFormTitle.textContent = 'Thêm địa chỉ giao hàng mới';
    if(modalSaveBtn)   modalSaveBtn.textContent = 'Thêm địa chỉ';
    if(modalDeleteBtn) modalDeleteBtn.classList.add('d-none');
    if(modalErrBox)    modalErrBox.textContent = '';
    const mProv = qs('#modal_province'), mComm = qs('#modal_commune');
    if(mProv) mProv.innerHTML='<option value="">-- Định tỉnh trước --</option>';
    if(mComm){ mComm.innerHTML='<option value="">-- Chọn tỉnh trước --</option>'; mComm.disabled=true; }
    bindCascade(mProv, mComm, '', '');
  }

  const pageContainer = qs('.ck-page');
  pageContainer?.addEventListener('click', function(e){
    const editBtn = e.target.closest('.addr-edit-btn');
    if(editBtn){
      e.stopPropagation(); const card = editBtn.closest('.mpick-card'); if(!card) return;
      toggleAddrList(false);
      if(modalAddForm) modalAddForm.classList.remove('d-none');
      if(modalFooterSel) modalFooterSel.style.display = 'none';
      if(modalFooterAdd) modalFooterAdd.style.display = 'flex';
      if(btnAddToggle) btnAddToggle.textContent = '✕ Hủy bỏ';
      if(modalAddrIdInp) modalAddrIdInp.value = card.dataset.id;
      if(modalFormTitle) modalFormTitle.textContent = 'Chỉnh sửa địa chỉ';
      if(modalSaveBtn)   modalSaveBtn.textContent = 'Lưu thay đổi';
      if(modalDeleteBtn) modalDeleteBtn.classList.remove('d-none');
      qs('#modal_receiver_name').value = card.dataset.name || '';
      qs('#modal_phone').value         = card.dataset.phone || '';
      qs('#modal_address').value       = card.dataset.address || '';
      bindCascade(qs('#modal_province'), qs('#modal_commune'), card.dataset.province, card.dataset.commune);
      return;
    }

    const card = e.target.closest('.mpick-card'); if(card){ applyAddrSelection(card); closeAddrModal(); return; }

    const pmOpt = e.target.closest('.pm-opt');
    if(pmOpt){
      qsa('.pm-opt').forEach(o=>o.classList.remove('is-sel'));
      pmOpt.classList.add('is-sel');
      const radio = pmOpt.querySelector('input[type="radio"]'); if(radio) radio.checked = true;
      return;
    }

    if(e.target.closest('#btn-cpn')){ handleApplyCoupon(); return; }
    if(e.target.closest('#btn-rm-cpn')){ handleRemoveCoupon(); return; }
    const vCard = e.target.closest('.js-v-apply');
    if(vCard){
      const code = vCard.dataset.code;
      if(qs('#cpn-input')) qs('#cpn-input').value = code;
      handleApplyCoupon();
      return;
    }
    const rmItemBtn = e.target.closest('.sum-item-rm'); if(rmItemBtn){ handleRemoveItem(rmItemBtn); return; }

    const saveBtn = e.target.closest('#modal-save-addr-btn'); if(saveBtn) handleSaveAddress(saveBtn);
    const delBtn = e.target.closest('#modal-delete-addr-btn'); if(delBtn) handleDeleteAddress(delBtn);
    const addTog = e.target.closest('#modal-btn-add-addr');
    if(addTog){
      const opening = modalAddForm.classList.toggle('d-none'), isNowVisible = !opening;
      if(isNowVisible) resetModalForm();
      toggleAddrList(!isNowVisible);
      addTog.textContent = isNowVisible ? '✕ Hủy bỏ' : '+ Thêm địa chỉ mới';
      if(modalFooterSel) modalFooterSel.style.display = isNowVisible ? 'none' : '';
      if(modalFooterAdd) modalFooterAdd.style.display = isNowVisible ? 'flex' : 'none';
    }
  });

  async function handleApplyCoupon(){
    const cpnInput = qs('#cpn-input'), code = cpnInput?.value.trim(), btnCpn = qs('#btn-cpn');
    if(!code){ if(cpnMsg) cpnMsg.textContent='Vui lòng nhập mã giảm giá.'; return; }
    btnCpn.disabled=true; btnCpn.textContent='Đang kiểm tra…'; if(cpnMsg) cpnMsg.textContent='';
    try {
      const res = await fetch(C.cpnApply, {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':C.csrf},
        body: JSON.stringify({coupon_code: code, total: C.subtotal}),
      });
      const d = await res.json();
      if(d.success && d.data){ 
        C.discount = d.data.discount; C.base = C.subtotal - C.discount; refreshShippingQuote();
        if(qs('#sum-discount-row')) qs('#sum-discount-row').style.display = '';
        if(qs('#sum-discount')) qs('#sum-discount').textContent = '−' + fmt(C.discount);
        if(qs('#cpn-code-label')) qs('#cpn-code-label').textContent = d.data.coupon_code;
        qs('#cpn-applied-state')?.classList.remove('d-none'); qs('#cpn-input-state')?.classList.add('d-none');
      } else if(cpnMsg){ cpnMsg.textContent=d.message||'Mã không hợp lệ.'; cpnMsg.style.color='var(--ck-red)'; }
    } catch(e){ if(cpnMsg) cpnMsg.textContent='Có lỗi xảy ra.'; }
    finally { btnCpn.disabled=false; btnCpn.textContent='Áp dụng'; }
  }

  async function handleRemoveCoupon(){
    try { await fetch(C.cpnRemove, { method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':C.csrf} }); } catch(e){}
    C.discount = 0; C.base = C.subtotal; refreshShippingQuote();
    if(qs('#sum-discount-row')) qs('#sum-discount-row').style.display = 'none';
    qs('#cpn-applied-state')?.classList.add('d-none'); qs('#cpn-input-state')?.classList.remove('d-none');
    if(qs('#cpn-input')) qs('#cpn-input').value = ''; if(cpnMsg) cpnMsg.textContent = '';
  }

  async function handleRemoveItem(btn){
    const itemEl = btn.closest('.sum-item'), amount = parseFloat(itemEl?.dataset.amount)||0, url = btn.dataset.url;
    if(!url) return; btn.disabled = true; itemEl.style.opacity='0.4';
    try {
      const res = await fetch(url, { method:'POST', headers:{'Accept':'application/json','X-CSRF-TOKEN':C.csrf} });
      if(res.ok || res.redirected){
        itemEl.remove(); C.subtotal = Math.max(0, C.subtotal - amount); C.base = Math.max(0, C.subtotal - (C.discount||0));
        qsa('.sum-row').forEach(row=>{ if(row.querySelector('span:first-child')?.textContent.trim()==='Tạm tính') row.querySelector('span:last-child').textContent = fmt(C.subtotal); });
        await refreshShippingQuote(); if(!qs('.sum-item')) window.location.href = '{{ route("cart.index") }}';
      }
    } catch(e){ itemEl.style.opacity='1'; btn.disabled=false; }
  }

  async function handleSaveAddress(btn){
    const f = qs('#modal-new-addr-form-inner'); if(!f) return;
    const fields = f.querySelectorAll('input[required],select[required]'); let ok = true;
    fields.forEach(el=>{ if(!el.value.trim()){ el.classList.add('is-invalid'); ok=false; } else el.classList.remove('is-invalid'); });
    if(!ok){ if(modalErrBox) modalErrBox.textContent='Vui lòng điền đầy đủ.'; return; }
    const addrId = modalAddrIdInp?.value; btn.disabled=true; btn.textContent='Đang lưu…';
    try {
      const fd = new FormData(f); let url = '{{ route("account.addresses.store") }}';
      if(addrId){ url = '{{ route("account.addresses.update", ":id") }}'.replace(':id', addrId); fd.append('_method', 'PUT'); }
      const res = await fetch(url, { method:'POST', headers:{'Accept':'application/json','X-CSRF-TOKEN':C.csrf}, body: fd });
      const data = await res.json();
      if(data.success && data.address){
        const a = data.address, grid = qs('#modal-addr-grid');
        const cardHtml = `<div class="mpick-dot" aria-hidden="true"></div><div class="mpick-edit addr-edit-btn" title="Sửa" data-id="${a.id}"><i class="fa fa-pencil"></i></div><div class="mpick-name">${a.receiver_name}</div><div class="mpick-phone">${a.phone}</div><div class="mpick-addr">${a.address}, ${a.commune||''}, ${a.province}</div>`;
        if(addrId){
          const ex = grid?.querySelector(`.mpick-card[data-id="${addrId}"]`);
          if(ex){ ex.innerHTML=cardHtml; Object.assign(ex.dataset, {name:a.receiver_name, phone:a.phone, province:a.province, commune:a.commune||'', address:a.address}); }
        } else {
          const col = document.createElement('div'); col.className='col-6';
          col.innerHTML=`<div class="mpick-card" data-id="${a.id}" data-name="${a.receiver_name}" data-phone="${a.phone}" data-province="${a.province}" data-commune="${a.commune||''}" data-address="${a.address}">${cardHtml}</div>`;
          grid.prepend(col);
        }
        const fn = grid?.querySelector(`.mpick-card[data-id="${a.id}"]`); if(fn) applyAddrSelection(fn);
        resetModalForm(); toggleAddrList(true); closeAddrModal();
      }
    } catch(e){} finally { btn.disabled=false; btn.textContent = modalAddrIdInp?.value ? 'Lưu thay đổi' : 'Thêm địa chỉ'; }
  }

  async function handleDeleteAddress(btn){
    const addrId = modalAddrIdInp?.value; if(!addrId || !confirm('Xác nhận xóa?')) return;
    btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    try {
      const url = '{{ route("account.addresses.destroy", ":id") }}'.replace(':id', addrId);
      const res = await fetch(url, { method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':C.csrf}, body: JSON.stringify({_method:'DELETE'}) });
      const data = await res.json();
      if(data.success){
        qs(`.mpick-card[data-id="${addrId}"]`)?.closest('.col-6')?.remove();
        if(qs('#sel-addr-id')?.value == addrId){
          const first = qs('.mpick-card'); if(first) applyAddrSelection(first);
          else {
            qs('#sel-addr-display')?.remove(); qs('#sel-addr-id').value = '';
            qs('.ck-card')?.querySelector('h2')?.insertAdjacentHTML('afterend', '<button type="button" class="addr-no-addr-btn" data-bs-toggle="modal" data-bs-target="#addr-pick-modal">+ Thêm địa chỉ</button>');
          }
        }
        resetModalForm();
        if(qs('.mpick-card')){ if(modalAddForm) modalAddForm.classList.add('d-none'); if(modalFooterSel) modalFooterSel.style.display=''; if(modalFooterAdd) modalFooterAdd.style.display='none'; }
      }
    } catch(e){} finally { btn.disabled=false; btn.innerHTML='<i class="fa fa-trash"></i> Xóa địa chỉ'; }
  }

  // ───── Order Submit ─────
  pageContainer?.addEventListener('submit', function(e){
    if(e.target.id === 'ck-form'){
      const btn1 = qs('.btn-order');
      const btn2 = qsa('.btn-order')[1];
      [btn1, btn2].forEach(b=>{ if(b){ b.disabled=true; b.textContent='Đang xử lý…'; } });
    }
  });

  function init(){
    // Bind Guest Address (if exists)
    const gP = qs('#g_province'), gC = qs('#g_commune');
    if(gP && gC) bindCascade(gP, gC, '{{ old("province") }}', '{{ old("commune") }}');

    // Initial Address Selection (if auth)
    @auth
      const selCard = qs('.mpick-card.is-sel');
      if(selCard) applyAddrSelection(selCard);
    @endauth
  }

  init();
})();
</script>
@endsection
