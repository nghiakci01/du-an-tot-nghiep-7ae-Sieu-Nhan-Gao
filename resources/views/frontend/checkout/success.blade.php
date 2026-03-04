@extends('layouts.public')

@section('title', 'Đặt hàng thành công | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<style>
  .success-page-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e9 100%);
    min-height: 80vh;
    padding: 60px 0;
  }
  .success-icon-animate {
    animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
  }
  @keyframes popIn {
    0% { transform: scale(0); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }
  .order-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.08);
    overflow: hidden;
  }
  .order-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    padding: 28px 32px;
  }
  .order-badge {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    padding: 6px 16px;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-block;
  }
  .section-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 12px;
  }
  .item-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
  }
  .item-row:last-child { border-bottom: none; }
  .item-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #eee;
    flex-shrink: 0;
  }
  .item-img-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    background: #f2f2f2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    flex-shrink: 0;
  }
  .price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 0.95rem;
  }
  .price-row.total {
    border-top: 2px solid #f0f0f0;
    margin-top: 8px;
    padding-top: 14px;
    font-size: 1.1rem;
  }
  .bank-info-box {
    background: #fff9f0;
    border: 1px solid #ffd280;
    border-radius: 12px;
    padding: 20px;
  }
  .bank-info-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    border-bottom: 1px dashed #ffe0b2;
    font-size: 0.9rem;
  }
  .bank-info-row:last-child { border-bottom: none; }
  .qr-holder {
    background: white;
    padding: 12px;
    border-radius: 12px;
    display: inline-block;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .step-bar {
    display: flex;
    justify-content: center;
    gap: 0;
    margin: 32px 0 0;
  }
  .step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    max-width: 150px;
    position: relative;
  }
  .step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 18px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #e0e0e0;
    z-index: 0;
  }
  .step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    z-index: 1;
    position: relative;
  }
  .step-circle.done { background: #28a745; color: white; }
  .step-circle.pending { background: #e9ecef; color: #aaa; }
  .step-label { font-size: 0.72rem; margin-top: 8px; text-align: center; color: #666; }
  .step-label.done { color: #28a745; font-weight: 600; }
</style>
@endpush

@section('content')
@php
  $shippingFee = $order->shipping_fee ?? 0;
  $displayTotal = ($order->final_total > 0) ? $order->final_total : ($order->total_price + $shippingFee);
  $isBankTransfer = $order->payment_method == 'BANK_TRANSFER';
@endphp

<div class="success-page-wrapper">
<div class="container">

  {{-- ===== SUCCESS HEADER ===== --}}
  <div class="text-center mb-5">
    <div class="success-icon-animate d-inline-block mb-3">
      <div style="width:90px;height:90px;background:linear-gradient(135deg,#28a745,#20c997);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;box-shadow:0 8px 24px rgba(40,167,69,0.3);">
        <i class="bi bi-check-lg text-white" style="font-size:2.8rem;"></i>
      </div>
    </div>
    <h2 class="fw-bold mb-1" style="font-size:2rem;">Đặt hàng thành công!</h2>
    <p class="text-muted mb-0">Cảm ơn bạn đã tin tưởng mua sắm tại <strong>{{ $settings['site_title'] ?? 'Elite' }}</strong></p>
    <p class="text-muted">Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>

    {{-- ORDER PROGRESS STEPS --}}
    <div class="step-bar">
      <div class="step-item">
        <div class="step-circle done"><i class="bi bi-clipboard-check" style="font-size:1rem;"></i></div>
        <span class="step-label done">Đã đặt hàng</span>
      </div>
      <div class="step-item">
        <div class="step-circle pending"><i class="bi bi-box-seam" style="font-size:1rem;"></i></div>
        <span class="step-label">Đang chuẩn bị</span>
      </div>
      <div class="step-item">
        <div class="step-circle pending"><i class="bi bi-truck" style="font-size:1rem;"></i></div>
        <span class="step-label">Đang vận chuyển</span>
      </div>
      <div class="step-item">
        <div class="step-circle pending"><i class="bi bi-house-check" style="font-size:1rem;"></i></div>
        <span class="step-label">Đã giao hàng</span>
      </div>
    </div>
  </div>

  {{-- ===== MAIN CARD ===== --}}
  <div class="row justify-content-center">
    <div class="col-lg-9">
      <div class="order-card">

        {{-- ORDER HEADER --}}
        <div class="order-header d-flex flex-wrap justify-content-between align-items-center gap-3">
          <div>
            <div class="order-badge mb-2">🛒 ĐƠN HÀNG</div>
            <h4 class="mb-0 fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h4>
            <small class="opacity-75">Đặt lúc: {{ $order->created_at->format('H:i - d/m/Y') }}</small>
          </div>
          <div class="text-end">
            <div class="order-badge">
              @if($order->payment_method == 'COD') 💵 Thanh toán khi nhận hàng
              @elseif($order->payment_method == 'BANK_TRANSFER') 🏦 Chuyển khoản ngân hàng
              @else {{ $order->payment_method }}
              @endif
            </div>
            <div class="mt-2">
              <span class="badge" style="background:rgba(40,167,69,0.3);color:#a3f7bf;font-size:0.85rem;padding:6px 14px;border-radius:50px;">
                ✅ {{ ucfirst($order->status ?? 'pending') }}
              </span>
            </div>
          </div>
        </div>

        <div class="p-4">
          <div class="row g-4">

            {{-- ORDER ITEMS --}}
            <div class="col-12">
              <p class="section-label">Sản phẩm đã đặt</p>
              @foreach($order->orderItems as $item)
              <div class="item-row">
                @if(isset($item->product) && $item->product?->image)
                  <img src="{{ asset('storage/' . $item->product->image) }}" class="item-img" alt="{{ $item->product_name }}">
                @else
                  <div class="item-img-placeholder"><i class="bi bi-image"></i></div>
                @endif
                <div class="flex-grow-1">
                  <div class="fw-semibold">{{ $item->product_name }}</div>
                  @if($item->variant_info)
                    <small class="text-muted">{{ $item->variant_info }}</small>
                  @elseif(isset($item->productVariant))
                    <small class="text-muted">{{ $item->productVariant?->size }} / {{ $item->productVariant?->color }}</small>
                  @endif
                  <div class="text-muted small">Số lượng: {{ $item->quantity }}</div>
                </div>
                <div class="text-end fw-bold text-danger">{{ number_format($item->price * $item->quantity) }}&thinsp;đ</div>
              </div>
              @endforeach
            </div>

            {{-- ORDER SUMMARY + SHIPPING --}}
            <div class="col-md-6">
              <p class="section-label">Địa chỉ nhận hàng</p>
              <div class="p-3 rounded-3" style="background:#f9fafb;border:1px solid #eee;">
                <div class="fw-semibold">{{ $order->customer_name }}</div>
                <div class="text-muted small">📞 {{ $order->phone }}</div>
                <div class="text-muted small">📧 {{ $order->email }}</div>
                <div class="text-muted small mt-1">📍 {{ $order->address }}{{ $order->city ? ', ' . $order->city : '' }}</div>
                @if($order->notes)
                  <div class="text-muted small mt-1">📝 Ghi chú: <em>{{ $order->notes }}</em></div>
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <p class="section-label">Tóm tắt thanh toán</p>
              <div class="p-3 rounded-3" style="background:#f9fafb;border:1px solid #eee;">
                <div class="price-row">
                  <span class="text-muted">Tạm tính</span>
                  <span>{{ number_format($order->total_price) }}&thinsp;đ</span>
                </div>
                @if($shippingFee > 0)
                <div class="price-row">
                  <span class="text-muted">Phí vận chuyển</span>
                  <span>{{ number_format($shippingFee) }}&thinsp;đ</span>
                </div>
                @endif
                @if($order->discount_amount > 0)
                <div class="price-row text-success">
                  <span>🏷️ Giảm giá <small>({{ $order->coupon_code }})</small></span>
                  <span>-{{ number_format($order->discount_amount) }}&thinsp;đ</span>
                </div>
                @endif
                <div class="price-row total">
                  <span class="fw-bold">Tổng thanh toán</span>
                  <span class="fw-bold text-danger" style="font-size:1.15rem;">{{ number_format($displayTotal) }}&thinsp;đ</span>
                </div>
              </div>
            </div>

            {{-- BANK TRANSFER INFO --}}
            @if($isBankTransfer)
            <div class="col-12">
              <p class="section-label">Thông tin chuyển khoản</p>
              <div class="bank-info-box">
                <div class="row align-items-center">
                  <div class="col-md-7">
                    <p class="fw-bold mb-3" style="color:#d4860a;">⚠️ Vui lòng chuyển khoản trong vòng 24 giờ để đơn hàng được xử lý.</p>
                    <div class="bank-info-row">
                      <span class="text-muted">Ngân hàng</span>
                      <span class="fw-semibold">{{ $bankName }}</span>
                    </div>
                    <div class="bank-info-row">
                      <span class="text-muted">Số tài khoản</span>
                      <span class="fw-bold text-dark">{{ $bankAccount }}</span>
                    </div>
                    <div class="bank-info-row">
                      <span class="text-muted">Chủ tài khoản</span>
                      <span class="fw-semibold">{{ $bankOwner }}</span>
                    </div>
                    <div class="bank-info-row">
                      <span class="text-muted">Số tiền</span>
                      <span class="fw-bold text-danger">{{ number_format($displayTotal) }}&thinsp;đ</span>
                    </div>
                    <div class="bank-info-row" style="border-bottom:none;">
                      <span class="text-muted">Nội dung CK</span>
                      <span class="fw-bold text-danger">THANHTOAN DH{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                  </div>
                  <div class="col-md-5 text-center mt-4 mt-md-0">
                    <div class="qr-holder">
                      <img src="https://img.vietqr.io/image/{{ $bankId }}-{{ $bankAccount }}-compact2.png?amount={{ $displayTotal }}&addInfo=THANHTOAN%20DH{{ $order->id }}&accountName={{ urlencode($bankOwner) }}"
                           alt="VietQR" class="img-fluid" style="max-width: 190px;">
                    </div>
                    <p class="small text-muted mt-2 mb-0">📷 Quét mã QR để thanh toán nhanh</p>
                  </div>
                </div>
              </div>
            </div>
            @endif

          </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="px-4 pb-4 pt-2 d-flex flex-wrap gap-2 justify-content-center border-top mt-2" style="background:#f9fafb;">
          <a href="{{ route('account.index') }}?tab=orders" class="btn btn-outline-dark px-4 py-2 rounded-pill">
            <i class="bi bi-list-ul me-1"></i> Xem đơn hàng của tôi
          </a>
          <a href="{{ route('shop') }}" class="btn btn-dark px-4 py-2 rounded-pill">
            <i class="bi bi-bag me-1"></i> Tiếp tục mua sắm
          </a>
        </div>

      </div>

      {{-- FOOTER NOTE --}}
      <p class="text-center text-muted small mt-3">
        <i class="bi bi-envelope-at me-1"></i> Thông tin đơn hàng đã được gửi đến email <strong>{{ $order->email }}</strong>
      </p>

    </div>
  </div>

</div>
</div>
@endsection
