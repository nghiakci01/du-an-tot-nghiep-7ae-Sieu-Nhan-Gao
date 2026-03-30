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

</style>
@endpush

@section('content')
@php
  $shippingFee = $order->shipping_fee ?? 0;
  $displayTotal = ($order->final_total > 0) ? $order->final_total : ($order->total_price + $shippingFee);
  $isBankTransfer = $order->payment_method == 'BANK_TRANSFER';
  $isVnpay = $order->payment_method == 'VNPAY';
  $isPaymentPending = in_array($order->payment_status, ['pending', 'failed']);
@endphp

<div class="success-page-wrapper">
<div class="container">

  {{-- ===== SUCCESS HEADER ===== --}}
  <div class="text-center mb-5">
    <div class="success-icon-animate d-inline-block mb-3">
      <div style="width:90px;height:90px;background:linear-gradient(135deg,@if(($isBankTransfer && $order->payment_status == 'pending') || ($isVnpay && $order->payment_status == 'pending')) #ffc107, #ff9800 @elseif($isVnpay && $order->payment_status == 'failed') #dc3545, #c82333 @else #28a745,#20c997 @endif);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;box-shadow:0 8px 24px rgba(@if(($isBankTransfer && $order->payment_status == 'pending') || ($isVnpay && $order->payment_status == 'pending')) 255,193,7,0.3 @elseif($isVnpay && $order->payment_status == 'failed') 220,53,69,0.3 @else 40,167,69,0.3 @endif);">
        <i class="bi @if(($isBankTransfer && $order->payment_status == 'pending') || ($isVnpay && $order->payment_status == 'pending')) bi-clock-history @elseif($isVnpay && $order->payment_status == 'failed') bi-x-lg @else bi-check-lg @endif text-white" style="font-size:2.8rem;"></i>
      </div>
    </div>
    <h2 class="fw-bold mb-1" style="font-size:2rem;">
      @if($isBankTransfer && $order->payment_status == 'pending')
        Chờ thanh toán
      @elseif($isVnpay && $order->payment_status == 'failed')
        Thanh toán thất bại
      @elseif($isVnpay && $order->payment_status == 'pending')
        Chờ thanh toán
      @else
        Đặt hàng thành công!
      @endif
    </h2>
    <p class="text-muted mb-0">Cảm ơn bạn đã tin tưởng mua sắm tại <strong>Elite Shop</strong></p>
    <p class="text-muted">Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>


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
              @elseif($order->payment_method == 'VNPAY') 💳 Thanh toán VNPay
              @else {{ $order->payment_method }}
              @endif
            </div>
            <div class="mt-2">
              @if(($order->status ?? 'pending') == 'pending')
                <span class="badge" style="background:rgba(255, 193, 7, 0.2);color:#d4860a;font-size:0.85rem;padding:6px 14px;border-radius:50px;">
                  ⏳ {{ ucfirst($order->status ?? 'pending') }}
                </span>
              @else
                <span class="badge" style="background:rgba(40, 167, 69, 0.2);color:#28a745;font-size:0.85rem;padding:6px 14px;border-radius:50px;">
                  ✅ {{ ucfirst($order->status ?? 'pending') }}
                </span>
              @endif
            </div>
          </div>
        </div>

        <div class="p-4">
          <div class="row g-4">

            {{-- ORDER ITEMS --}}
            <div class="col-12">
              <p class="section-label">Sản phẩm đã đặt</p>
              @foreach($order->items as $item)
              <div class="item-row">
                @if(isset($item->product) && $item->product?->image)
                  <img src="{{ asset('storage/' . $item->product->image) }}" class="item-img" alt="{{ $item->product_name }}">
                @else
                  <div class="item-img-placeholder"><i class="bi bi-image"></i></div>
                @endif
                <div class="flex-grow-1">
                  <div class="fw-semibold">{{ $item->product?->name ?? 'Sản phẩm #' . $item->product_id }}</div>
                  @if($item->variant)
                    <small class="text-muted">{{ $item->variant->size }} / {{ $item->variant->color }}</small>
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
                <div class="fw-semibold">{{ $order->name }}</div>
                <div class="text-muted small">📞 {{ $order->phone }}</div>
                <div class="text-muted small">📧 {{ $order->email }}</div>
                <div class="text-muted small mt-1">📍 {{ $order->address }}{{ $order->province ? ', ' . $order->province : '' }}</div>
                @if($order->note)
                  <div class="text-muted small mt-1">📝 Ghi chú: <em>{{ $order->note }}</em></div>
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
            @php
                $bank = $order->bankSetting;
                if (!$bank) {
                    $bank = \App\Models\BankSetting::where('is_active', true)->where('is_default', true)->first() ?: \App\Models\BankSetting::where('is_active', true)->first();
                }
                $bName = $bank?->bank_name ?? 'N/A';
                $bAccount = $bank?->account_number ?? 'N/A';
                $bOwner = $bank?->account_name ?? 'N/A';
                $bId = $bank?->bank_id ?? '';
            @endphp
            <div class="col-12">
              <div class="p-4 rounded-4 @if($order->payment_status == 'waiting_confirmation') bg-light-success @else bank-info-box @endif">
                <div class="row align-items-center">
                  <div class="col-md-7">
                    @if($order->payment_status == 'pending')
                      <p class="section-label mb-2">Thông tin chuyển khoản</p>
                      <p class="fw-bold mb-3" style="color:#d4860a;">⚠️ Vui lòng chuyển khoản và nhấn nút xác nhận bên dưới.</p>
                      <div class="bank-info-row">
                        <span class="text-muted">Ngân hàng</span>
                        <span class="fw-semibold">{{ $bName }}</span>
                      </div>
                      <div class="bank-info-row">
                        <span class="text-muted">Số tài khoản</span>
                        <span class="fw-bold text-dark">{{ $bAccount }}</span>
                      </div>
                      <div class="bank-info-row">
                        <span class="text-muted">Chủ tài khoản</span>
                        <span class="fw-semibold">{{ $bOwner }}</span>
                      </div>
                      <div class="bank-info-row">
                        <span class="text-muted">Số tiền</span>
                        <span class="fw-bold text-danger">{{ number_format($displayTotal) }}&thinsp;đ</span>
                      </div>
                      <div class="bank-info-row" style="border-bottom:none;">
                        <span class="text-muted">Nội dung CK</span>
                        <span class="fw-bold text-danger">THANHTOAN ELITE {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                      </div>

                      <div class="mt-4 d-flex gap-2">
                        <form action="{{ route('checkout.confirm_transfer', $order->id) }}" method="POST" class="flex-grow-1">
                          @csrf
                          <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-send-check-fill me-2"></i> Xác nhận đã chuyển khoản
                          </button>
                        </form>
                        <form action="{{ route('checkout.cancel_order', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                          @csrf
                          <button type="submit" class="btn btn-outline-danger py-3 px-4 rounded-pill fw-bold">
                            Hủy đơn
                          </button>
                        </form>
                      </div>
                    @elseif($order->payment_status == 'waiting_confirmation')
                      <div class="text-center py-4">
                        <div class="mb-3">
                          <i class="bi bi-clock-history text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold">Đang chờ xác nhận thanh toán</h5>
                        <p class="text-muted">Hệ thống đã ghi nhận thông báo chuyển khoản của bạn. Admin sẽ kiểm tra và xác nhận đơn hàng sớm nhất có thể.</p>
                      </div>
                    @elseif($order->payment_status == 'paid')
                      <div class="text-center py-4">
                        <div class="mb-3">
                          <i class="bi bi-patch-check-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold">Thanh toán đã được xác nhận</h5>
                        <p class="text-muted">Cảm ơn bạn, chúng tôi đã nhận được thanh toán và đang chuẩn bị hàng.</p>
                      </div>
                    @endif
                  </div>
                  <div class="col-md-5 text-center mt-4 mt-md-0">
                    <div class="qr-holder">
                      @php
                        $qrUrl = "https://img.vietqr.io/image/{$bId}-{$bAccount}-compact2.png?amount={$displayTotal}&addInfo=THANHTOAN%20ELITE%20{$order->id}&accountName=" . urlencode($bOwner);
                      @endphp
                      <img src="{{ $qrUrl }}" alt="VietQR" class="img-fluid" style="max-width: 190px;">
                    </div>
                    <p class="small text-muted mt-2 mb-0">📷 Quét mã QR để thanh toán nhanh</p>
                  </div>
                </div>
              </div>
            </div>
            @endif

            {{-- VNPAY PAYMENT STATUS --}}
            @if($isVnpay)
            <div class="col-12">
              @if($order->payment_status == 'paid')
              <div class="p-4 rounded-4" style="background: #e8f5e9; border: 1px solid #a5d6a7;">
                <div class="text-center py-3">
                  <div class="mb-3">
                    <i class="bi bi-patch-check-fill text-success" style="font-size: 3rem;"></i>
                  </div>
                  <h5 class="fw-bold">Thanh toán VNPay thành công</h5>
                  <p class="text-muted mb-1">Mã giao dịch: <strong class="text-dark">{{ $order->transaction_id }}</strong></p>
                  <p class="text-muted mb-0">Đơn hàng của bạn đang được chuẩn bị. Cảm ơn bạn đã mua hàng!</p>
                </div>
              </div>
              @elseif($order->payment_status == 'failed')
              <div class="p-4 rounded-4" style="background: #fef2f2; border: 1px solid #fca5a5;">
                <div class="row align-items-center">
                  <div class="col-md-7">
                    <div class="mb-3">
                      <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-danger">Thanh toán VNPay không thành công</h5>
                    <p class="text-muted">Giao dịch thanh toán qua VNPay đã bị hủy hoặc gặp lỗi. Bạn có thể thử thanh toán lại bằng cách nhấn nút bên dưới.</p>
                    <div class="d-flex gap-2 flex-wrap">
                      <a href="{{ route('payment.vnpay.retry', $order->id) }}" class="btn btn-danger px-4 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Thanh toán lại qua VNPay
                      </a>
                      <form action="{{ route('checkout.cancel_order', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary py-2 px-4 rounded-pill fw-bold">
                          Hủy đơn hàng
                        </button>
                      </form>
                    </div>
                  </div>
                  <div class="col-md-5 text-center mt-4 mt-md-0">
                    <img src="https://cdn-icons-png.flaticon.com/512/6195/6195678.png" alt="Payment Failed" style="max-width: 140px; opacity: 0.7;">
                  </div>
                </div>
              </div>
              @elseif($order->payment_status == 'pending')
              <div class="p-4 rounded-4" style="background: #fff9f0; border: 1px solid #ffd280;">
                <div class="text-center py-3">
                  <div class="mb-3">
                    <i class="bi bi-clock-history text-warning" style="font-size: 3rem;"></i>
                  </div>
                  <h5 class="fw-bold">Đang chờ xác nhận thanh toán VNPay</h5>
                  <p class="text-muted">Hệ thống đang xử lý giao dịch VNPay của bạn. Vui lòng chờ trong giây lát.</p>
                </div>
              </div>
              @endif
            </div>
            @endif


          </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="px-4 pb-4 pt-2 d-flex flex-wrap gap-2 justify-content-center border-top mt-2" style="background:#f9fafb;">
          @if(Auth::check())
            <a href="{{ route('account.index') }}?tab=orders" class="btn btn-outline-dark px-4 py-2 rounded-pill">
              <i class="bi bi-list-ul me-1"></i> Xem đơn hàng của tôi
            </a>
          @else
            <a href="{{ route('order-tracking.index') }}" class="btn btn-outline-dark px-4 py-2 rounded-pill">
              <i class="bi bi-search me-1"></i> Tra cứu đơn hàng
            </a>
          @endif
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
