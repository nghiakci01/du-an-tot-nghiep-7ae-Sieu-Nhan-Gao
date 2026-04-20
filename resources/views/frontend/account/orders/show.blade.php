@extends('layouts.public')

@section('title', 'Đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<style>
.order-show-wrapper {
  background: #fff;
  padding: 40px 0 60px;
  min-height: 80vh;
}
.detail-card {
  background: white;
  border-radius: 0;
  box-shadow: none;
  border: 1px solid #000;
  overflow: hidden;
  margin-bottom: 20px;
}
.detail-header {
  padding: 15px 24px;
  border-bottom: 1px solid #000;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8f9fa;
}
.detail-header h5 { margin: 0; font-weight: 700; font-size: 1rem; color: #000; }
.detail-body { padding: 22px 24px; }

/* Hero */
.order-hero {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  color: white;
  padding: 28px 32px;
  border-radius: 16px;
  margin-bottom: 20px;
}
.order-tag {
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 50px;
  padding: 4px 14px;
  font-size: 0.78rem;
  font-weight: 600;
  display: inline-block;
  margin-bottom: 8px;
}

/* Status badges */
.s-badge { padding: 5px 14px; border-radius: 50px; font-size: 0.78rem; font-weight: 700; }
.s-pending    { background:#fff3cd; color:#856404; }
.s-confirmed  { background:#cff4fc; color:#055160; }
.s-shipped    { background:#cfe2ff; color:#084298; }
.s-completed  { background:#d1e7dd; color:#0a3622; }
.s-cancelled  { background:#f8d7da; color:#842029; }
.s-returned   { background:#e2e3e5; color:#41464b; }
.s-failed     { background:#f8d7da; color:#842029; }

/* Progress bar */
.status-track {
  display: flex;
  justify-content: space-between;
  position: relative;
  margin-top: 20px;
}
.status-track::before {
  content:'';
  position:absolute;
  top:16px;
  left:8%;
  right:8%;
  height:2px;
  background:rgba(255,255,255,0.15);
}
.track-fill {
  position:absolute;
  top:16px;
  left:8%;
  height:2px;
  background:rgba(255,255,255,0.85);
  z-index:1;
  transition:width 0.4s;
}
.track-step { display:flex; flex-direction:column; align-items:center; z-index:2; flex:1; }
.track-dot {
  width:32px; height:32px;
  border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  font-size:0.9rem;
  margin-bottom:6px;
}
.track-dot.done   { background:rgba(255,255,255,0.9); color:#1a1a2e; }
.track-dot.active { background:white; color:#1a1a2e; }
.track-dot.idle   { background:rgba(255,255,255,0.15); color:rgba(255,255,255,0.5); }
.track-lbl { font-size:0.68rem; text-align:center; }
.track-lbl.done, .track-lbl.active { color:rgba(255,255,255,0.95); font-weight:600; }
.track-lbl.idle { color:rgba(255,255,255,0.4); }

/* Items */
.item-row {
  display:flex; align-items:center; gap:14px;
  padding:12px 0; border-bottom:1px solid #ddd;
}
.item-row:last-child { border-bottom:none; }
.item-img { width:60px; height:60px; object-fit:cover; border-radius:0; border:1px solid #000; flex-shrink:0; }
.item-placeholder { width:60px; height:60px; border-radius:0; background:#f2f2f2; display:flex; align-items:center; justify-content:center; color:#ccc; flex-shrink:0; border: 1px solid #000; }

/* Price rows */
.p-row { display:flex; justify-content:space-between; padding:7px 0; font-size:0.9rem; border-bottom:1px solid #eee; }
.p-row:last-child { border-bottom:none; }
.p-row.total { border-top:1px solid #000; margin-top:6px; padding-top:12px; font-size:1rem; color: #000; }

/* Info */
.info-row { display:flex; justify-content:space-between; padding:7px 0; font-size:0.88rem; border-bottom:1px solid #eee; }
.info-row:last-child { border-bottom:none; }

/* History timeline */
.timeline-item { display:flex; gap:14px; padding-bottom:18px; position:relative; }
.timeline-item:not(:last-child)::before {
  content:'';
  position:absolute;
  left:15px;
  top:32px;
  bottom:0;
  width:1px;
  background:#000;
}
.timeline-dot {
  width:32px; height:32px;
  border-radius:0;
  background:#000;
  color:white;
  display:flex; align-items:center; justify-content:center;
  font-size:0.8rem;
  flex-shrink:0;
}

/* Review */
.review-card { border:1px solid #000; border-radius:0; overflow:hidden; margin-bottom:14px; }
.review-card-head { background:#f9fafb; padding:14px 18px; display:flex; align-items:center; gap:12px; border-bottom:1px solid #eee; }

/* Buttons */
.btn-primary-dark { background:#000; color:white; border:none; border-radius:0; padding:10px 28px; font-weight:600; transition:background .2s; }
.btn-primary-dark:hover { background:#333; color:white; }
.btn-outline-dark, .btn-outline-danger, .btn-outline-warning { border-radius: 0 !important; }
.btn-warning { background: #000 !important; color: #fff !important; border-radius: 0 !important; border: none !important; }
.btn-warning:hover { background: #333 !important; }
.btn-success { background: #000 !important; color: #fff !important; border-radius: 0 !important; border: 1px solid #000 !important; }
.badge { border-radius: 0 !important; }
.text-danger, .text-success, .text-info, .text-warning { color: #000 !important; }
.bg-danger, .bg-success, .bg-info, .bg-warning { background: #000 !important; color: #fff !important; }

/* Modal & Collapse */
.history-toggle { cursor: pointer; border-radius: 12px; transition: all 0.2s; }
.history-toggle:hover { background: #eee !important; }
.review-item-minimal { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
.review-item-minimal:last-child { border-bottom: none; }
.modal-content { border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
.modal-header { border-bottom: 1px solid #f0f0f0; padding: 20px 25px; }
.modal-body { padding: 25px; }
.modal-footer { border-top: none; padding: 10px 25px 25px; }
</style>
@endpush

@section('content')
@php
  $shippingFee  = $order->shipping_fee ?? 0;
  $displayTotal = ($order->final_total > 0) ? $order->final_total : ($order->total_price + $shippingFee);
  $statusSteps  = ['pending','confirmed','shipped','completed','returned'];
  $curIdx       = array_search(strtolower($order->status), $statusSteps);
  $isCancelled  = in_array(strtolower($order->status), ['cancelled','failed']);
  $progressW    = ($curIdx !== false && !$isCancelled) ? ($curIdx / (count($statusSteps)-1)) * 84 : 0;
  $pmLabel = match($order->payment_method) {
    'COD'           => '💵 Thanh toán khi nhận hàng (COD)',
    'VNPAY'         => '💳 VNPay',
    default         => $order->payment_method,
  };
  $returnShippingInfo = [
    'name' => $settings['return_receiver_name'] ?? ($settings['site_title'] ?? 'Elite'),
    'phone' => $settings['return_receiver_phone'] ?? ($settings['site_phone'] ?? ''),
    'address' => $settings['return_receiver_address'] ?? ($settings['site_address'] ?? ''),
    'note' => $settings['return_receiver_note'] ?? 'Vui lòng ghi rõ mã đơn hàng và giữ lại biên nhận khi gửi trả hàng.',
  ];
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

<div class="order-show-wrapper">
<div class="container">

  @if(session('success'))
    <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
  @endif

  {{-- ===== HERO ===== --}}
  @if(!$order->returnRequest)
  <div class="order-hero">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
      <div>
        <span class="order-tag">🛒 ĐƠN HÀNG</span>
        <h4 class="fw-bold mb-0">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h4>
        <small class="opacity-60">Đặt lúc {{ $order->created_at->format('H:i - d/m/Y') }}</small>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="s-badge s-{{ strtolower($order->status) }}">{{ $order->status_text }}</span>
      </div>
    </div>

    {{-- Progress --}}
    @if(!$isCancelled)
    <div class="status-track mt-4 pb-1">
      <div class="track-fill" style="--track-width: {{ $progressW }}%; width: var(--track-width);"></div>
      @foreach(['pending'=>['Chờ xác nhận','bi-clipboard-check'],'confirmed'=>['Đã xác nhận','bi-shield-check'],'shipped'=>['Đang giao','bi-truck'],'completed'=>['Hoàn thành','bi-house-check'],'returned'=>['Hoàn hàng','bi-arrow-return-left']] as $s=>[$lbl,$icn])
        @php $idx = array_search($s, $statusSteps); $cls = $idx < $curIdx ? 'done' : ($idx == $curIdx ? 'active' : 'idle'); @endphp
        <div class="track-step">
          <div class="track-dot {{ $cls }}"><i class="{{ $cls !== 'idle' && ($s==='completed' || $s==='returned' || $idx<$curIdx) ? 'bi bi-check-lg' : 'bi '.$icn }}"></i></div>
          <span class="track-lbl {{ $cls }}">{{ $lbl }}</span>
        </div>
      @endforeach
    </div>
    @endif
  </div>
  @endif

  <div class="row g-4">
    {{-- LEFT --}}
    <div class="col-lg-8">

      @if($order->returnRequest)
      <div class="alert alert-light border border-dark rounded-0 mb-4 shadow-sm">
        <h6 class="fw-bold mb-2">
          <i class="bi bi-arrow-return-left me-2"></i>
          Yêu cầu {{ $order->returnRequest->type_text }}
        </h6>
        <div class="p-3" style="color: #000; font-size: 0.95rem;">
          <div class="mb-2"><strong>Trạng thái:</strong> 
            <span class="badge {{ $order->returnRequest->status_badge }}">{{ $order->returnRequest->status_text }}</span>
          </div>
          </div>
          <div class="mb-2"><strong>Lý do:</strong> {{ $order->returnRequest->reason }}</div>
          
          @if($order->returnRequest->return_method)
            <div class="mb-2 d-flex align-items-center">
                <strong>Phương thức gửi trả:</strong> 
                <span class="ms-1 fw-bold">{{ $order->returnRequest->return_method_text }}</span>
                @if($order->returnRequest->isApproved())
                    <button type="button" onclick="document.getElementById('change-return-method').classList.toggle('d-none')" class="ms-2 btn btn-dark btn-sm rounded-0 px-3" style="font-size: 0.75rem;">
                        Thay đổi
                    </button>
                @endif
            </div>
          @endif

          {{-- UI Chọn/Đổi phương thức --}}
          @if($order->returnRequest->isApproved())
            <div id="change-return-method" class="mt-3 p-3 rounded-3 bg-white border border-warning shadow-sm {{ $order->returnRequest->return_method ? 'd-none' : '' }}">
                <p class="mb-2 small fw-bold text-danger"><i class="bi bi-info-circle me-1"></i> {{ $order->returnRequest->return_method ? 'Thay đổi phương thức gửi hàng:' : 'Chọn phương thức gửi hàng:' }}</p>
                <form action="{{ route('account.orders.return.update_method', $order->id) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <button type="submit" name="return_method" value="at_home" class="btn {{ $order->returnRequest->return_method === 'at_home' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm rounded-0 flex-grow-1">
                            <i class="bi bi-house-door me-1"></i> Shipper lấy hàng
                        </button>
                        <button type="submit" name="return_method" value="at_post_office" class="btn {{ $order->returnRequest->return_method === 'at_post_office' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm rounded-pill flex-grow-1" style="{{ $order->returnRequest->return_method === 'at_post_office' ? 'background-color: #f26522; border-color: #f26522; color: white;' : 'color: #f26522; border-color: #f26522;' }}">
                            <i class="bi bi-building me-1"></i> Tự mang đến bưu cục
                        </button>
                    </div>
                </form>
            </div>
          @endif

          @if($order->returnRequest->return_method === 'at_post_office' && $order->returnRequest->isApproved())
            <div class="mt-2 text-center p-3 rounded-3" style="background: #fff; border: 1px solid #f26522; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <p class="mb-2 small fw-bold" style="color: #f26522;">📍 Bạn đã chọn tự mang ra bưu cục:</p>
                <a href="https://www.google.com/maps/search/Giao+hàng+nhanh/" target="_blank" class="btn btn-warning btn-sm w-100 rounded-pill py-2" style="background-color: #f26522; border-color: #f26522; color: white;">
                    <i class="bi bi-geo-alt-fill me-1"></i>🌏 Tìm bưu cục GHN gần nhất (Google Maps)
                </a>
            </div>
          @endif

          @if($order->returnRequest->admin_note)
            <div class="mt-2 p-2 rounded" style="background:rgba(255,255,255,0.6);">
              <strong>Phản hồi từ cửa hàng:</strong><br>
              {!! nl2br(e($order->returnRequest->admin_note)) !!}
            </div>
          @endif
        </div>
      </div>
      @endif

      @if($order->returnRequest)
      <div class="detail-card border-dark mb-4 shadow-sm">
        <div class="detail-header bg-dark text-white">
          <h5 class="mb-0 text-white py-1"><i class="bi bi-bank me-2"></i>Tài khoản hoàn tiền</h5>
        </div>
        <div class="detail-body p-4" style="color: #000; font-size: 0.95rem;">
          <div class="info-row border-bottom pb-2 mb-2">
            <span class="text-muted">Ngân hàng</span>
            <span class="fw-bold text-uppercase d-flex align-items-center">
                @if($order->returnRequest->bank_bin)
                    <img src="https://api.vietqr.io/img/{{ $order->returnRequest->bank_bin }}.png" style="height: 18px;" class="me-2" onerror="this.style.display='none'">
                @endif
                {{ $order->returnRequest->bank_name ?: '---' }}
            </span>
          </div>
          <div class="info-row border-bottom pb-2 mb-2">
            <span class="text-muted">Số tài khoản</span>
            <span class="fw-bold fs-5">{{ $order->returnRequest->account_number ?: '---' }}</span>
          </div>
          <div class="info-row">
            <span class="text-muted">Chủ tài khoản</span>
            <span class="fw-bold text-uppercase">{{ $order->returnRequest->account_name ?: '---' }}</span>
          </div>
        </div>
      </div>
      @endif

      {{-- Items --}}
      @if(!$order->returnRequest)
      <div class="detail-card">
        <div class="detail-header">
          <h5><i class="bi bi-bag me-2"></i>Sản phẩm đã đặt</h5>
          <span class="text-muted small">{{ $order->items->count() }} sản phẩm</span>
        </div>
        <div class="detail-body">
          @foreach($order->items as $item)
          <div class="item-row">
            @if($item->product?->image)
              <img src="{{ asset('storage/'.$item->product->image) }}" class="item-img" alt="{{ $item->product->name }}">
            @elseif($item->product?->images?->count())
              <img src="{{ Storage::url($item->product->images->first()->image_path) }}" class="item-img" alt="{{ $item->product->name }}">
            @else
              <div class="item-placeholder"><i class="bi bi-image"></i></div>
            @endif
            <div class="flex-grow-1">
              <div class="fw-semibold">{{ $item->product?->name ?? 'Sản phẩm #'.$item->product_id }}</div>
              @if($item->variant)
                <small class="text-muted">
                  Kích cỡ: {{ $item->variant->sizeRelationship?->name ?? $item->variant->size ?? 'N/A' }}
                  &nbsp;/&nbsp;
                  Màu: {{ $item->variant->colorRelationship?->name ?? $item->variant->color ?? 'N/A' }}
                </small>
              @endif
              <div class="text-muted small mt-1">{{ $item->quantity }} × {{ number_format($item->price) }}đ</div>
            </div>
            <div class="fw-bold text-dark text-nowrap">{{ number_format($item->total) }}đ</div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Order History --}}
      @if(!$order->returnRequest && $order->histories->count() > 0)
      <div class="detail-card">
        <div class="detail-header history-toggle" onclick="toggleOrderHistory()" role="button">
          <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</h5>
          <i class="bi bi-chevron-down opacity-50 transition-transform" id="historyChevron"></i>
        </div>
        <div class="collapse" id="orderHistoryCollapse">
          <div class="detail-body">
            @foreach($order->histories as $history)
            <div class="timeline-item">
              <div class="timeline-dot"><i class="bi bi-activity"></i></div>
              <div>
                <div class="fw-semibold small">
                  {{ $history->user?->name ?? 'Hệ thống' }}:
                  <span class="badge bg-secondary rounded-pill">{{ $history->previous_status }}</span>
                  → <span class="badge rounded-pill" style="background:#1a1a2e;">{{ $history->new_status }}</span>
                </div>
                @if($history->note)
                  <div class="text-muted small">{{ $history->note }}</div>
                @endif
                <div style="font-size:0.75rem;" class="text-muted">{{ $history->created_at->format('H:i - d/m/Y') }}</div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      {{-- ===== REVIEW SECTION ===== --}}
      @if($user && $order->status === \App\Models\Order::STATUS_COMPLETED)
      <div class="detail-card">
        <div class="detail-header">
          <h5><i class="bi bi-star me-2"></i>Đánh giá sản phẩm</h5>
        </div>
        <div class="detail-body">
          @foreach($order->items as $item)
            @if($item->product)
              @php $existingReview = $userReviews->get($item->product_id); @endphp
              <div class="review-item-minimal">
                <div class="d-flex align-items-center gap-3">
                  @if($item->product->image)
                    <img src="{{ asset('storage/'.$item->product->image) }}" style="width:45px;height:45px;object-fit:cover;border-radius:8px;border:1px solid #f0f0f0;">
                  @endif
                  <div>
                    <div class="fw-semibold small">{{ $item->product->name }}</div>
                    @if($item->variant)<small class="text-muted" style="font-size:0.7rem;">{{ $item->variant->name }}</small>@endif
                  </div>
                </div>
                <div>
                  @if($existingReview)
                     <div class="text-end">
                       <div class="d-flex gap-1 justify-content-end mb-1">
                         @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$existingReview->rating?'-fill':'' }}" style="color:#f39c12;font-size:0.8rem;"></i>@endfor
                       </div>
                       <button class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size:0.7rem;" 
                               onclick="openReviewModal({{ $item->product_id }}, '{{ addslashes($item->product->name) }}', '{{ asset('storage/'.$item->product->image) }}', {{ $existingReview->rating }}, '{{ addslashes($existingReview->comment) }}')">
                         Xem lại
                       </button>
                     </div>
                  @else
                    <button class="btn btn-primary-dark btn-sm rounded-pill px-3" style="font-size:0.75rem;"
                            onclick="openReviewModal({{ $item->product_id }}, '{{ addslashes($item->product->name) }}', '{{ asset('storage/'.$item->product->image) }}')">
                      Đánh giá
                    </button>
                  @endif
                </div>
              </div>
            @endif
          @endforeach
        </div>
      </div>
      @endif

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">

      {{-- Payment Summary --}}
      @if(!$order->returnRequest)
      <div class="detail-card">
        <div class="detail-header">
          <h5><i class="bi bi-receipt me-2"></i>Tóm tắt thanh toán</h5>
        </div>
        <div class="detail-body">
          <div class="p-row">
            <span class="text-muted">Tạm tính</span>
            <span>{{ number_format($order->total_price) }}đ</span>
          </div>
          @if($shippingFee > 0)
          <div class="p-row">
            <span class="text-muted">Phí vận chuyển</span>
            <span>{{ number_format($shippingFee) }}đ</span>
          </div>
          @endif
          @if($order->discount_amount > 0)
          <div class="p-row text-success">
            <span>🏷️ Giảm giá @if($order->coupon_code)<small>({{ $order->coupon_code }})</small>@endif</span>
            <span>-{{ number_format($order->discount_amount) }}đ</span>
          </div>
          @endif
          <div class="p-row total">
            <span class="fw-bold">Tổng thanh toán</span>
            <span class="fw-bold text-dark">{{ number_format($displayTotal) }}đ</span>
          </div>
        </div>
      </div>
      @endif

      {{-- Shipping Info --}}
      @if(!$order->returnRequest)
      <div class="detail-card">
        <div class="detail-header">
          <h5><i class="bi bi-geo-alt me-2"></i>Thông tin giao hàng</h5>
        </div>
        <div class="detail-body">
          <div class="info-row">
            <span class="text-muted">Người nhận</span>
            <span class="fw-semibold">{{ $order->name }}</span>
          </div>
          <div class="info-row">
            <span class="text-muted">Điện thoại</span>
            <span>{{ $order->phone }}</span>
          </div>
          <div class="info-row">
            <span class="text-muted">Địa chỉ</span>
            <span class="text-end" style="max-width:60%;font-size:0.85rem;">{{ $order->shipping_address ?? ($order->address . ($order->province ? ', '.$order->province : '')) }}</span>
          </div>
          @if($order->note)
          <div class="info-row">
            <span class="text-muted">Ghi chú</span>
            <span class="text-muted fst-italic text-end" style="max-width:60%;font-size:0.85rem;">{{ $order->note }}</span>
          </div>
          @endif
          <div class="info-row">
            <span class="text-muted">Thanh toán</span>
            <span class="text-end" style="max-width:60%;font-size:0.85rem;">{{ $pmLabel }}</span>
          </div>
        </div>
      </div>
      @endif

      {{-- Return Shipping Action --}}
      @if($order->returnRequest && $order->returnRequest->isApproved())
      <div class="detail-card border-dark bg-white mb-3">
        <div class="detail-header bg-dark text-white">
          <h5 class="mb-0 text-white"><i class="bi bi-truck me-2"></i>Gửi hàng hoàn trả</h5>
        </div>
        <div class="detail-body">
          <p class="small text-muted mb-3">Yêu cầu trả hàng của bạn đã được duyệt. Vui lòng gửi hàng về kho và nộp thông tin vận đơn tại đây.</p>
          @if ($errors->any())
              <div class="alert alert-danger p-2 small mb-3">
                  <ul class="mb-0 ps-3">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          <form action="{{ route('account.orders.return.shipping', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label class="form-label small fw-bold">Mã vận đơn / Đơn vị vận chuyển <span class="text-muted fw-normal">(không bắt buộc)</span></label>
              <textarea name="shipping_info" class="form-control form-control-sm" rows="1" placeholder="VD: GHN - 12345678" style="border-radius: 8px;"></textarea>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small fw-bold">Ảnh minh chứng <span class="text-danger">*</span></label>
                <input type="file" id="shipping_proof_input" name="shipping_proof[]" class="form-control form-control-sm @error('shipping_proof') is-invalid @enderror" accept="image/*" multiple required style="border-radius: 8px;">
                @error('shipping_proof')
                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                @enderror
                @error('shipping_proof.*')
                    <div class="text-danger mt-1" style="font-size: 0.7rem;">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-6">
                <label class="form-label small fw-bold">Video minh chứng <span class="text-muted fw-normal">(nếu có)</span></label>
                <input type="file" id="shipping_video_input" name="shipping_video[]" class="form-control form-control-sm @error('shipping_video') is-invalid @enderror" accept="video/*" multiple style="border-radius: 8px;">
                @error('shipping_video')
                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                @enderror
                @error('shipping_video.*')
                    <div class="text-danger mt-1" style="font-size: 0.7rem;">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Live Preview Container --}}
            <div id="shipment_preview_container" class="mb-3 d-none">
              <label class="form-label small fw-bold text-primary mb-2 mt-2"><i class="bi bi-eye me-1"></i> Xem trước minh chứng:</label>
              <div id="shipment_previews" class="d-flex flex-wrap gap-2 p-2 border rounded bg-white shadow-sm" style="min-height: 50px;">
                <!-- Previews will be injected here -->
              </div>
            </div>

            <button type="submit" class="btn btn-warning rounded-pill w-100 py-2 shadow-sm mt-2">
              <i class="bi bi-send me-1"></i> Xác nhận đã gửi hàng
            </button>
          </form>
        </div>
      </div>
      @endif

      {{-- Actions --}}
      <div class="d-flex flex-column gap-2">
        @if($order->returnRequest && $order->returnRequest->isCompleted())
          <a href="{{ route('account.index') }}?tab=orders" class="btn btn-dark rounded-0 py-2 fw-bold">
            Hoàn thành
          </a>
        @endif
        <a href="{{ route('account.index') }}?tab=orders" class="btn btn-outline-dark rounded-0 py-2">
          <i class="bi bi-arrow-left me-1"></i> Quay lại đơn hàng
        </a>
        @if($user && $order->status === \App\Models\Order::STATUS_PENDING)
          <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger rounded-pill py-2 w-100">
              <i class="bi bi-x-circle me-1"></i> Hủy đơn hàng
            </button>
          </form>
        @endif
        @if($user && in_array($order->status, [\App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_SHIPPED]))
          @if(!$order->returnRequest)
          <a href="{{ route('account.orders.return_form', $order->id) }}" class="btn btn-outline-warning rounded-pill py-2 w-100 mt-2">
            <i class="bi bi-arrow-return-left me-1"></i> Yêu cầu hoàn hàng
          </a>
          @endif
        @endif
      </div>

    </div>
  </div>
</div>
</div>

{{-- ===== REVIEW MODAL ===== --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="reviewForm" action="" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="modalProductName">Đánh giá sản phẩm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <img id="modalProductImg" src="" style="width:80px;height:80px;object-fit:cover;border-radius:12px;margin-bottom:10px;border:1px solid #eee;">
            <p class="text-muted small">Cảm ơn bạn đã tin dùng sản phẩm của chúng tôi!</p>
          </div>
          
          <div class="mb-4 text-center">
            <label class="fw-bold small d-block mb-2">Đánh giá của bạn:</label>
            <div class="star-rating-order justify-content-center">
              @for($s=5;$s>=1;$s--)
                <input type="radio" id="modalStar{{$s}}" name="rating" value="{{$s}}" required>
                <label for="modalStar{{$s}}" title="{{$s}} sao" style="font-size:35px;"><i class="fa fa-star"></i></label>
              @endfor
            </div>
            <div id="modalLikert" class="text-danger small fw-bold mt-2" style="min-height:18px;"></div>
          </div>

          <div class="mb-0">
            <label class="fw-bold small d-block mb-2">Nhận xét chi tiết:</label>
            <textarea name="comment" id="modalComment" rows="4" required placeholder="Chia sẻ cảm nhận thực tế của bạn về sản phẩm này..." class="form-control" style="border-radius:12px; background:#f9fafb; border:1px solid #eee; font-size:0.9rem;"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary-dark rounded-pill px-4" id="modalSubmitBtn">Gửi đánh giá</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
var likertLabels = {1:'Rất không hài lòng',2:'Không hài lòng',3:'Bình thường',4:'Hài lòng',5:'Rất hài lòng'};
var reviewModal;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Modal safely
    if (typeof bootstrap !== 'undefined') {
        reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
    } else {
        console.error('Bootstrap is not defined. Please ensure npm run dev/build is active.');
    }
});

function toggleOrderHistory() {
    const target = document.getElementById('orderHistoryCollapse');
    const chevron = document.getElementById('historyChevron');
    
    // Sử dụng Bootstrap Collapse API để có hiệu ứng trượt mượt mà
    let bsCollapse = bootstrap.Collapse.getInstance(target);
    if (!bsCollapse) {
        bsCollapse = new bootstrap.Collapse(target, { toggle: false });
    }
    
    bsCollapse.toggle();

    // Đồng bộ xoay icon theo trạng thái đóng/mở
    target.addEventListener('shown.bs.collapse', function () {
        chevron.style.transform = 'rotate(180deg)';
    });
    target.addEventListener('hidden.bs.collapse', function () {
        chevron.style.transform = 'rotate(0deg)';
    });
}

function openReviewModal(productId, productName, productImg, rating = 5, comment = '') {
    if (!reviewModal) {
        alert('Đang nạp thư viện, vui lòng thử lại sau giây lát!');
        return;
    }
    const form = document.getElementById('reviewForm');
    const title = document.getElementById('modalProductName');
    const img = document.getElementById('modalProductImg');
    const commentField = document.getElementById('modalComment');
    const likert = document.getElementById('modalLikert');
    const submitBtn = document.getElementById('modalSubmitBtn');

    // Reset Form
    form.action = `{{ url('product/review') }}/${productId}`;
    title.textContent = productName;
    img.src = productImg;
    commentField.value = comment;
    
    // Set Rating
    document.querySelectorAll('#reviewModal input[name="rating"]').forEach(inp => {
        inp.checked = (inp.value == rating);
    });
    likert.textContent = likertLabels[rating];

    // If already reviewed, visual adjustments
    if (comment) {
        submitBtn.innerHTML = '<i class="bi bi-pencil me-1"></i>Cập nhật đánh giá';
    } else {
        submitBtn.innerHTML = '<i class="bi bi-send me-1"></i>Gửi đánh giá';
    }

    reviewModal.show();
}

// Hover effects for stars in modal
document.querySelectorAll('#reviewModal .star-rating-order label').forEach(function(label) {
  label.addEventListener('mouseenter', function() {
    var val = this.previousElementSibling?.value;
    if(val) document.getElementById('modalLikert').textContent = likertLabels[val];
  });
});

// Selection change
document.querySelectorAll('#reviewModal .star-rating-order input').forEach(function(input) {
  input.addEventListener('change', function() {
    document.getElementById('modalLikert').textContent = likertLabels[this.value];
  });
});
// Handle Shipment Proof Live Previews
document.addEventListener('DOMContentLoaded', function() {
    const imgInput = document.getElementById('shipping_proof_input');
    const videoInput = document.getElementById('shipping_video_input');
    const container = document.getElementById('shipment_preview_container');
    const previewList = document.getElementById('shipment_previews');

    function updatePreviews() {
        previewList.innerHTML = '';
        let hasFiles = false;

        const handleFiles = (files, isVideo) => {
            if (files.length > 0) hasFiles = true;
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                const wrapper = document.createElement('div');
                wrapper.className = 'position-relative';
                wrapper.style.width = '80px';
                wrapper.style.height = '80px';
                
                if (!isVideo) {
                    const img = document.createElement('img');
                    img.className = 'img-thumbnail shadow-sm w-100 h-100';
                    img.style.objectFit = 'cover';
                    img.src = URL.createObjectURL(file);
                    wrapper.appendChild(img);
                } else {
                    const vidPlaceholder = document.createElement('div');
                    vidPlaceholder.className = 'img-thumbnail shadow-sm w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark text-white';
                    vidPlaceholder.innerHTML = '<i class="bi bi-play-btn-fill fs-4"></i><span style="font-size:0.6rem">Video</span>';
                    wrapper.appendChild(vidPlaceholder);
                }
                previewList.appendChild(wrapper);
            });
        };

        if (imgInput) handleFiles(imgInput.files, false);
        if (videoInput) handleFiles(videoInput.files, true);

        if (hasFiles) {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }

    if (imgInput) imgInput.addEventListener('change', updatePreviews);
    if (videoInput) videoInput.addEventListener('change', updatePreviews);
});
</script>
<style>
.transition-transform { transition: transform 0.3s ease; }
</style>
@endpush
