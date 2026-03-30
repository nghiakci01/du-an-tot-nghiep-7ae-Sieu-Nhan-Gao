@extends('layouts.public')

@section('title', 'Đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<style>
.order-show-wrapper {
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
.detail-header {
  padding: 18px 24px;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.detail-header h5 { margin: 0; font-weight: 700; font-size: 1rem; }
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
  padding:12px 0; border-bottom:1px solid #f5f5f5;
}
.item-row:last-child { border-bottom:none; }
.item-img { width:60px; height:60px; object-fit:cover; border-radius:10px; border:1px solid #eee; flex-shrink:0; }
.item-placeholder { width:60px; height:60px; border-radius:10px; background:#f2f2f2; display:flex; align-items:center; justify-content:center; color:#ccc; flex-shrink:0; }

/* Price rows */
.p-row { display:flex; justify-content:space-between; padding:7px 0; font-size:0.9rem; border-bottom:1px solid #f8f8f8; }
.p-row:last-child { border-bottom:none; }
.p-row.total { border-top:2px solid #f0f0f0; margin-top:6px; padding-top:12px; font-size:1rem; }

/* Info */
.info-row { display:flex; justify-content:space-between; padding:7px 0; font-size:0.88rem; border-bottom:1px solid #f8f8f8; }
.info-row:last-child { border-bottom:none; }

/* History timeline */
.timeline-item { display:flex; gap:14px; padding-bottom:18px; position:relative; }
.timeline-item:not(:last-child)::before {
  content:'';
  position:absolute;
  left:15px;
  top:32px;
  bottom:0;
  width:2px;
  background:#f0f0f0;
}
.timeline-dot {
  width:32px; height:32px;
  border-radius:50%;
  background:#1a1a2e;
  color:white;
  display:flex; align-items:center; justify-content:center;
  font-size:0.8rem;
  flex-shrink:0;
}

/* Review */
.review-card { border:1px solid #eee; border-radius:12px; overflow:hidden; margin-bottom:14px; }
.review-card-head { background:#f9fafb; padding:14px 18px; display:flex; align-items:center; gap:12px; }
.star-rating-order { display:inline-flex; flex-direction:row-reverse; gap:4px; margin-bottom:4px; }
.star-rating-order input { display:none; }
.star-rating-order label { font-size:28px; color:#ccc; cursor:pointer; transition:color 0.15s; margin:0; }
.star-rating-order label:hover i,
.star-rating-order label:hover ~ label i,
.star-rating-order input:checked ~ label i { color:#f39c12 !important; }
.star-rating-order label i { pointer-events:none; }

/* Buttons */
.btn-primary-dark { background:#1a1a2e; color:white; border:none; border-radius:50px; padding:10px 28px; font-weight:600; transition:background .2s; }
.btn-primary-dark:hover { background:#2d2d5e; color:white; }
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
    'BANK_TRANSFER' => '🏦 Chuyển khoản ngân hàng',
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

<div class="order-show-wrapper">
<div class="container">

  @if(session('success'))
    <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
  @endif

  {{-- ===== HERO ===== --}}
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

  <div class="row g-4">
    {{-- LEFT --}}
    <div class="col-lg-8">

      @if($order->returnRequest)
      <div class="alert {{ $order->returnRequest->isRejected() ? 'alert-danger' : ($order->returnRequest->isCompleted() ? 'alert-success' : 'alert-warning') }} rounded-3 mb-4 shadow-sm border-0">
        <h6 class="fw-bold mb-2">
          <i class="bi bi-arrow-return-left me-2"></i>
          Trạng thái Yêu cầu Hoàn hàng
        </h6>
        <div class="small">
          <div class="mb-1"><strong>Trạng thái:</strong> 
            @if($order->returnRequest->isPending())
              <span class="badge bg-warning text-dark">Chờ xử lý</span>
            @elseif($order->returnRequest->isApproved())
              <span class="badge bg-info">Đã duyệt - Đang chờ gửi hàng</span>
            @elseif($order->returnRequest->isCompleted())
              <span class="badge bg-success">Hoàn thành - Đã hoàn tiền</span>
            @elseif($order->returnRequest->isRejected())
              <span class="badge bg-danger">Từ chối</span>
            @endif
          </div>
          <div class="mb-1"><strong>Lý do:</strong> {{ $order->returnRequest->reason }}</div>
          @if($order->returnRequest->admin_note)
            <div class="mt-2 p-2 rounded" style="background:rgba(255,255,255,0.6);">
              <strong>Ghi chú từ cửa hàng:</strong><br>
              {!! nl2br(e($order->returnRequest->admin_note)) !!}
            </div>
          @endif
        </div>
      </div>
      @endif

      {{-- Items --}}
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
            <div class="fw-bold text-danger text-nowrap">{{ number_format($item->total) }}đ</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Order History --}}
      @if($order->histories->count() > 0)
      <div class="detail-card">
        <div class="detail-header">
          <h5><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</h5>
        </div>
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
      @endif

      {{-- ===== REVIEW SECTION ===== --}}
      @if($user && $order->status === \App\Models\Order::STATUS_COMPLETED)
      <div class="detail-card">
        <div class="detail-header">
          <h5><i class="bi bi-star me-2"></i>Đánh giá sản phẩm</h5>
        </div>
        <div class="detail-body">
          <p class="text-muted small mb-3">Chia sẻ cảm nhận của bạn về các sản phẩm đã mua.</p>
          @foreach($order->items as $item)
            @if($item->product)
              @php $existingReview = $userReviews->get($item->product_id); @endphp
              <div class="review-card">
                <div class="review-card-head">
                  @if($item->product->image)
                    <img src="{{ asset('storage/'.$item->product->image) }}" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                  @endif
                  <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $item->product->name }}</div>
                    @if($item->variant)<small class="text-muted">{{ $item->variant->name }}</small>@endif
                  </div>
                  @if($existingReview)
                    <span class="badge bg-success ms-auto"><i class="bi bi-check me-1"></i>Đã đánh giá</span>
                  @endif
                </div>
                <div class="p-3">
                  @if($existingReview)
                    <div class="d-flex gap-1 mb-1">
                      @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$existingReview->rating?'-fill':'' }}" style="color:#f39c12;font-size:1.1rem;"></i>@endfor
                      <small class="text-muted ms-2">{{ $existingReview->created_at->format('d/m/Y') }}</small>
                    </div>
                    <p class="text-muted small mb-0">{{ $existingReview->comment }}</p>
                  @else
                    <form action="{{ route('product.review.store', $item->product_id) }}" method="POST">
                      @csrf
                      <div class="mb-3">
                        <label class="fw-semibold small d-block mb-1">Đánh giá của bạn:</label>
                        <div class="star-rating-order">
                          @for($s=5;$s>=1;$s--)
                            <input type="radio" id="star{{$s}}_p{{$item->product_id}}" name="rating" value="{{$s}}" {{ $s===5?'required':'' }}>
                            <label for="star{{$s}}_p{{$item->product_id}}" title="{{$s}} sao"><i class="fa fa-star"></i></label>
                          @endfor
                        </div>
                        <div class="likert-label-order text-danger small fw-bold mt-1" style="min-height:18px;display:none;"></div>
                      </div>
                      <div class="mb-3">
                        <label class="fw-semibold small d-block mb-1">Nhận xét:</label>
                        <textarea name="comment" rows="3" required placeholder="Chia sẻ cảm nhận của bạn..." class="form-control" style="border-radius:10px;"></textarea>
                      </div>
                      <button type="submit" class="btn btn-sm btn-primary-dark rounded-pill px-4">
                        <i class="bi bi-send me-1"></i>Gửi đánh giá
                      </button>
                    </form>
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
            <span class="fw-bold text-danger">{{ number_format($displayTotal) }}đ</span>
          </div>
        </div>
      </div>

      {{-- Shipping Info --}}
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

      {{-- Return Shipping Action --}}
      @if($order->returnRequest && $order->returnRequest->status === 'approved')
      <div class="detail-card border-warning bg-light-warning mb-3">
        <div class="detail-header bg-warning text-white">
          <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Gửi hàng hoàn trả</h5>
        </div>
        <div class="detail-body">
          <p class="small text-muted mb-3">Yêu cầu trả hàng của bạn đã được duyệt. Vui lòng gửi hàng về kho và nộp thông tin vận đơn tại đây.</p>
          <form action="{{ route('account.orders.return.shipping', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label class="form-label small fw-bold">Thông tin vận chuyển</label>
              <textarea name="shipping_info" class="form-control form-control-sm" rows="2" placeholder="VD: Giao hàng nhanh - Mã: 12345678" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold">Ảnh minh chứng gửi hàng</label>
              <input type="file" name="shipping_proof" class="form-control form-control-sm" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-warning rounded-pill w-100 py-2">
              <i class="bi bi-send me-1"></i> Xác nhận đã gửi hàng
            </button>
          </form>
        </div>
      </div>
      @endif

      {{-- Actions --}}
      <div class="d-flex flex-column gap-2">
        <a href="{{ route('account.index') }}?tab=orders" class="btn btn-outline-dark rounded-pill py-2">
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

@endsection

@push('scripts')
<script>
var likertLabels = {1:'Rất không hài lòng',2:'Không hài lòng',3:'Bình thường',4:'Hài lòng',5:'Rất hài lòng'};

document.querySelectorAll('.star-rating-order label').forEach(function(label) {
  label.addEventListener('mouseenter', function() {
    var val = this.previousElementSibling?.value;
    if(val) {
      var lbl = this.closest('.mb-3').querySelector('.likert-label-order');
      lbl.textContent = likertLabels[val];
      lbl.style.display = 'block';
    }
  });
});
document.querySelectorAll('.star-rating-order input').forEach(function(input) {
  input.addEventListener('change', function() {
    var lbl = this.closest('.mb-3').querySelector('.likert-label-order');
    lbl.textContent = likertLabels[this.value];
    lbl.style.display = 'block';
  });
});
</script>
@endpush
