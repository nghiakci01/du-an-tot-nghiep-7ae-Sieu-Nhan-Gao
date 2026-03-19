@extends('layouts.public')

@section('title', 'Chi tiết đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<style>
.order-detail-wrapper {
  background: #f5f5f7;
  padding: 40px 0 60px;
  min-height: 80vh;
}
.detail-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 20px rgba(0,0,0,0.07);
  overflow: hidden;
  margin-bottom: 20px;
}
.detail-card-header {
  padding: 20px 28px;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.detail-card-header h5 { margin: 0; font-weight: 700; font-size: 1rem; }
.detail-card-body { padding: 24px 28px; }

/* Hero header */
.order-hero {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  color: white;
  padding: 28px 32px;
  border-radius: 16px;
  margin-bottom: 20px;
}
.order-hero .order-badge {
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 50px;
  padding: 4px 14px;
  font-size: 0.8rem;
  font-weight: 600;
}

/* Status */
.status-badge {
  padding: 6px 16px;
  border-radius: 50px;
  font-size: 0.8rem;
  font-weight: 700;
  display: inline-block;
}
.status-pending    { background: #fff3cd; color: #856404; }
.status-confirmed  { background: #cff4fc; color: #055160; }
.status-shipped    { background: #cfe2ff; color: #084298; }
.status-completed  { background: #d1e7dd; color: #0a3622; }
.status-cancelled  { background: #f8d7da; color: #842029; }

/* Progress bar */
.progress-steps {
  display: flex;
  justify-content: space-between;
  position: relative;
  margin: 10px 0 24px;
}
.progress-steps::before {
  content:'';
  position: absolute;
  top: 18px;
  left: 10%;
  right: 10%;
  height: 2px;
  background: #e0e0e0;
  z-index: 0;
}
.progress-line {
  position: absolute;
  top: 18px;
  left: 10%;
  height: 2px;
  background: #28a745;
  z-index: 1;
  transition: width 0.4s;
}
.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  z-index: 2;
  flex: 1;
}
.step-dot {
  width: 36px; height: 36px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem;
  margin-bottom: 8px;
}
.step-dot.done { background: #28a745; color: white; }
.step-dot.active { background: #1a1a2e; color: white; }
.step-dot.pending { background: #e9ecef; color: #aaa; }
.step-label { font-size: 0.72rem; text-align: center; color: #888; }
.step-label.done, .step-label.active { color: #1a1a2e; font-weight: 600; }

/* Info rows */
.info-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #f5f5f5;
  font-size: 0.9rem;
}
.info-row:last-child { border-bottom: none; }
.info-label { color: #888; }
.info-value { font-weight: 500; text-align: right; max-width: 60%; }

/* Items */
.item-row {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 0;
  border-bottom: 1px solid #f5f5f5;
}
.item-row:last-child { border-bottom: none; }
.item-img {
  width: 64px; height: 64px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #eee;
  flex-shrink: 0;
}
.item-placeholder {
  width: 64px; height: 64px;
  border-radius: 10px;
  background: #f2f2f2;
  display: flex; align-items: center; justify-content: center;
  color: #ccc;
  flex-shrink: 0;
}

/* Price summary */
.price-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  font-size: 0.93rem;
}
.price-row.total {
  border-top: 2px solid #f0f0f0;
  margin-top: 6px;
  padding-top: 14px;
  font-size: 1.05rem;
}
</style>
@endpush

@section('content')
@php
  $shippingFee  = $order->shipping_fee ?? 0;
  $displayTotal = ($order->final_total > 0) ? $order->final_total : ($order->total_price + $shippingFee);
  $statusSteps  = ['pending','confirmed','shipped','completed'];
  $statusIdx    = array_search(strtolower($order->status), $statusSteps);
  $progressPct  = $statusIdx !== false && $order->status !== 'cancelled'
                    ? ($statusIdx / (count($statusSteps)-1)) * 80
                    : 0;

  $pmLabel = match($order->payment_method) {
    'COD'           => '💵 Thanh toán khi nhận hàng (COD)',
    'BANK_TRANSFER' => '🏦 Chuyển khoản ngân hàng',
    'VNPAY'         => '💳 VNPay',
    'ZALOPAY'       => '💳 ZaloPay',
    default         => $order->payment_method,
  };
@endphp

{{-- Breadcrumb --}}
<div class="breadcrumbs_area other_bread">
  <div class="container">
    <div class="breadcrumb_content">
      <ul>
        <li><a href="{{ route('welcome') }}">Trang chủ</a></li>
        <li>/</li>
        <li><a href="{{ route('account.index') }}?tab=orders">Tài khoản</a></li>
        <li>/</li>
        <li>Đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</li>
      </ul>
    </div>
  </div>
</div>

<div class="order-detail-wrapper">
<div class="container">

  {{-- HERO HEADER --}}
  <div class="order-hero">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
      <div>
        <span class="order-badge mb-2 d-inline-block">🛒 CHI TIẾT ĐƠN HÀNG</span>
        <h4 class="fw-bold mb-0">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h4>
        <small class="opacity-60">Đặt lúc {{ $order->created_at->format('H:i - d/m/Y') }}</small>
      </div>
      <div class="text-end">
        <span class="status-badge status-{{ strtolower($order->status) }}">{{ $order->status_text }}</span>
      </div>
    </div>

    {{-- Progress --}}
    @if($order->status !== 'cancelled' && $order->status !== 'failed' && $order->status !== 'returned')
    <div class="mt-4">
      <div class="progress-steps">
        <div style="position:absolute;top:18px;left:10%;right:10%;height:2px;background:rgba(255,255,255,0.15);z-index:0;"></div>
        <div style="position:absolute;top:18px;left:10%;height:2px;background:rgba(255,255,255,0.8);z-index:1;width:{{ $progressPct }}%;"></div>

        @foreach(['pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','shipped'=>'Đang giao','completed'=>'Hoàn thành'] as $s => $lbl)
        @php $idx = array_search($s, $statusSteps); $cur = array_search(strtolower($order->status), $statusSteps); @endphp
        <div class="step">
          <div class="step-dot {{ $idx < $cur ? 'done' : ($idx == $cur ? 'active' : 'pending') }}" style="{{ $idx < $cur ? '' : ($idx == $cur ? 'background:rgba(255,255,255,0.9);color:#1a1a2e;' : 'background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.4);') }}">
            @if($idx < $cur)<i class="bi bi-check-lg"></i>
            @elseif($s==='pending')<i class="bi bi-clipboard-check"></i>
            @elseif($s==='confirmed')<i class="bi bi-shield-check"></i>
            @elseif($s==='shipped')<i class="bi bi-truck"></i>
            @else<i class="bi bi-house-check"></i>@endif
          </div>
          <span style="font-size:0.7rem;color:rgba(255,255,255,{{ $idx <= $cur ? '0.9' : '0.4' }});font-weight:{{ $idx <= $cur ? '600' : '400' }};text-align:center;">{{ $lbl }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>

  <div class="row g-4">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">

      {{-- Order Items --}}
      <div class="detail-card">
        <div class="detail-card-header">
          <h5><i class="bi bi-bag me-2"></i>Sản phẩm đã đặt</h5>
          <span class="text-muted small">{{ $order->items->count() }} sản phẩm</span>
        </div>
        <div class="detail-card-body">
          @foreach($order->items as $item)
          <div class="item-row">
            @if($item->product && $item->product->images && $item->product->images->count() > 0)
              <img src="{{ Storage::url($item->product->images->first()->image_path) }}" class="item-img" alt="{{ $item->product->name }}">
            @elseif($item->product && $item->product->image)
              <img src="{{ asset('storage/' . $item->product->image) }}" class="item-img" alt="{{ $item->product->name }}">
            @else
              <div class="item-placeholder"><i class="bi bi-image"></i></div>
            @endif

            <div class="flex-grow-1">
              <div class="fw-semibold">{{ $item->product?->name ?? 'Sản phẩm #' . $item->product_id }}</div>
              @if($item->variant)
                <small class="text-muted">
                  Kích cỡ: {{ $item->variant->sizeRelationship?->name ?? $item->variant->size ?? 'N/A' }}
                  &nbsp;/&nbsp; Màu: {{ $item->variant->colorRelationship?->name ?? $item->variant->color ?? 'N/A' }}
                </small>
              @endif
              <div class="text-muted small">SL: {{ $item->quantity }}&nbsp;×&nbsp;{{ number_format($item->price) }}đ</div>
            </div>
            <div class="fw-bold text-danger text-nowrap">{{ number_format($item->price * $item->quantity) }}đ</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Return Request Details (if any) --}}
      @if($order->returnRequest)
      <div class="detail-card" style="border: 2px solid #ffca2c;">
        <div class="detail-card-header" style="background-color: #fff9e6;">
          <h5 class="text-dark"><i class="bi bi-arrow-return-left me-2"></i>Chi tiết Yêu cầu Hoàn trả</h5>
          @php
            $returnStatus = match($order->returnRequest->status) {
                'pending' => ['text' => 'Chờ duyệt', 'class' => 'bg-warning text-dark'],
                'approved' => ['text' => 'Đã duyệt - Chờ bạn gửi hàng', 'class' => 'bg-info text-white'],
                'shipping' => ['text' => 'Đang di chuyển về kho', 'class' => 'bg-primary text-white'],
                'received' => ['text' => 'Đã nhận tại kho - Chờ hoàn tiền', 'class' => 'bg-dark text-white'],
                'completed' => ['text' => 'Đã hoàn tiền thành công', 'class' => 'bg-success text-white'],
                'rejected' => ['text' => 'Bị từ chối', 'class' => 'bg-danger text-white'],
                default => ['text' => 'Đang xử lý', 'class' => 'bg-secondary text-white'],
            };
          @endphp
          <span class="badge {{ $returnStatus['class'] }}">
            {{ $returnStatus['text'] }}
          </span>
        </div>
        <div class="detail-card-body">
          <div class="mb-3">
            <strong>Lý do:</strong> {{ $order->returnRequest->reason }}<br>
            <strong>Ghi chú của bạn:</strong> {{ $order->returnRequest->note ?: 'Không có' }}<br>
            <strong>Số tiền hoàn đề nghị:</strong> <span class="text-danger fw-bold">{{ number_format($order->returnRequest->refund_amount) }}đ</span><br>
            <strong>Ngày yêu cầu:</strong> {{ $order->returnRequest->created_at->format('H:i - d/m/Y') }}
          </div>
          @if($order->returnRequest->admin_note)
            <div class="alert alert-info mb-0">
              <strong>Phản hồi từ Cửa hàng / Hướng dẫn gửi trả:</strong><br>
              {!! nl2br(e($order->returnRequest->admin_note)) !!}
            </div>
          @endif
        </div>
      </div>
      @endif

      {{-- Order History (if has) --}}
      @if(isset($order->histories) && $order->histories->count() > 0)
      <div class="detail-card">
        <div class="detail-card-header">
          <h5><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</h5>
        </div>
        <div class="detail-card-body">
          @foreach($order->histories as $history)
          <div class="d-flex gap-3 mb-3">
            <div class="flex-shrink-0" style="width:8px;height:8px;background:#1a1a2e;border-radius:50%;margin-top:6px;"></div>
            <div>
              <div class="fw-semibold small">{{ $history->status ?? '' }}</div>
              <div class="text-muted small">{{ $history->note ?? '' }}</div>
              <div class="text-muted" style="font-size:0.75rem;">{{ $history->created_at->format('H:i - d/m/Y') }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">

      {{-- Payment Summary --}}
      <div class="detail-card">
        <div class="detail-card-header">
          <h5><i class="bi bi-receipt me-2"></i>Tóm tắt thanh toán</h5>
        </div>
        <div class="detail-card-body">
          <div class="price-row">
            <span class="text-muted">Tạm tính</span>
            <span>{{ number_format($order->total_price) }}đ</span>
          </div>
          @if($shippingFee > 0)
          <div class="price-row">
            <span class="text-muted">Phí vận chuyển</span>
            <span>{{ number_format($shippingFee) }}đ</span>
          </div>
          @endif
          @if($order->discount_amount > 0)
          <div class="price-row text-success">
            <span>🏷️ Giảm giá @if($order->coupon_code)<small>({{ $order->coupon_code }})</small>@endif</span>
            <span>-{{ number_format($order->discount_amount) }}đ</span>
          </div>
          @endif
          <div class="price-row total">
            <span class="fw-bold">Tổng thanh toán</span>
            <span class="fw-bold text-danger">{{ number_format($displayTotal) }}đ</span>
          </div>
        </div>
      </div>

      {{-- Shipping Info --}}
      <div class="detail-card">
        <div class="detail-card-header">
          <h5><i class="bi bi-geo-alt me-2"></i>Thông tin giao hàng</h5>
        </div>
        <div class="detail-card-body">
          <div class="info-row">
            <span class="info-label">Người nhận</span>
            <span class="info-value">{{ $order->name }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Điện thoại</span>
            <span class="info-value">{{ $order->phone }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Địa chỉ</span>
            <span class="info-value">{{ $order->shipping_address ?: ($order->address . ($order->province ? ', ' . $order->province : '')) }}</span>
          </div>
          @if($order->note)
          <div class="info-row">
            <span class="info-label">Ghi chú</span>
            <span class="info-value text-muted">{{ $order->note }}</span>
          </div>
          @endif
          <div class="info-row">
            <span class="info-label">Thanh toán</span>
            <span class="info-value">{{ $pmLabel }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Ngày đặt</span>
            <span class="info-value">{{ $order->created_at->format('H:i - d/m/Y') }}</span>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="d-flex flex-column gap-2">
        <a href="{{ route('account.index') }}?tab=orders" class="btn btn-outline-dark rounded-pill py-2">
          <i class="bi bi-arrow-left me-1"></i> Quay lại đơn hàng
        </a>
        @if(($order->status == 'completed' || $order->status == 'shipped') && !$order->returnRequest)
          <a href="{{ route('account.orders.return_form', $order->id) }}" class="btn btn-outline-warning rounded-pill py-2 w-100">
            <i class="bi bi-arrow-return-left me-1"></i> Yêu cầu Hoàn hàng
          </a>
        @endif
        @if(strtolower($order->status) == 'pending')
          <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger rounded-pill py-2 w-100">
              <i class="bi bi-x-circle me-1"></i> Hủy đơn hàng
            </button>
          </form>
        @endif
        <a href="{{ route('order-tracking.index', ['order_id' => $order->id]) }}" class="btn btn-dark rounded-pill py-2">
          <i class="bi bi-truck me-1"></i> Theo dõi đơn hàng
        </a>
      </div>

    </div>

  </div>
</div>
</div>
@endsection