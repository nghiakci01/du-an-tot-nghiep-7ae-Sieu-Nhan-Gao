@extends('layouts.public')

@section('title', 'Tài khoản của tôi | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Bank Selector Styling */
.select2-container .select2-selection--single {
    height: 45px;
    display: flex;
    align-items: center;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 45px;
    padding-left: 12px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 43px;
}
.bank-logo {
    height: 20px;
    width: auto;
    margin-right: 10px;
    vertical-align: middle;
}
.bank-item {
    display: flex;
    align-items: center;
    padding: 2px 0;
}
/* ===== ACCOUNT PAGE ===== */
.account-wrapper {
  background: #f5f5f7;
  padding: 40px 0 60px;
  min-height: 80vh;
}

/* Sidebar */
.account-sidebar {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 2px 20px rgba(0,0,0,0.07);
  position: sticky;
  top: 80px;
}
.account-avatar-box {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  padding: 28px 20px;
  text-align: center;
}
.account-avatar-box img {
  width: 80px; height: 80px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(255,255,255,0.3);
}
.account-nav {
  list-style: none;
  padding: 12px 0;
  margin: 0;
}
.account-nav li a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  color: #555;
  font-size: 0.9rem;
  font-weight: 500;
  border-left: 3px solid transparent;
  transition: all 0.2s;
  text-decoration: none;
}
.account-nav li a:hover,
.account-nav li a.active {
  color: #1a1a2e;
  background: #f5f5f7;
  border-left-color: #1a1a2e;
}
.account-nav li a i { font-size: 1.1rem; width: 20px; }

/* Main Content */
.account-content {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 20px rgba(0,0,0,0.07);
  overflow: hidden;
}
.tab-head {
  padding: 24px 28px;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.tab-head h4 { margin: 0; font-weight: 700; }
.tab-body { padding: 28px; }

/* Dashboard Stats */
.stat-card {
  background: #f9fafb;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  border: 1px solid #eee;
}
.stat-card .stat-icon {
  width: 48px; height: 48px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 12px;
  font-size: 1.4rem;
}
.stat-card .stat-number { font-size: 1.8rem; font-weight: 800; }
.stat-card .stat-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-top: 2px; }

/* Order table */
.orders-table th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #888; border: none; padding-bottom: 12px; }
.orders-table td { padding: 14px 0; vertical-align: middle; border-color: #f0f0f0; }
.orders-table tr:last-child td { border-bottom: none; }
.order-id { font-weight: 700; color: #1a1a2e; }
.status-badge {
  padding: 4px 12px;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 600;
  display: inline-block;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #cff4fc; color: #055160; }
.status-shipped { background: #cfe2ff; color: #084298; }
.status-completed { background: #d1e7dd; color: #0a3622; }
.status-cancelled { background: #f8d7da; color: #842029; }

/* Wishlist Grid */
.wish-card {
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #eee;
  transition: box-shadow 0.2s;
}
.wish-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
.wish-card img { width: 100%; height: 180px; object-fit: cover; }

/* Coupon Cards */
.coupon-card {
  border-radius: 12px;
  border: 2px dashed #1a1a2e;
  padding: 16px 20px;
  position: relative;
  background: white;
}
.coupon-card.coupon-used {
  border-color: #ddd;
  background: #f9f9f9;
  opacity: 0.6;
  filter: grayscale(1);
  pointer-events: none; /* Ngăn chặn mọi tương tác chuột bao gồm click và copy */
}
.coupon-card.coupon-used .coupon-code {
  color: #888;
  text-decoration: line-through;
  user-select: none; /* Ngăn chặn chọn văn bản bằng chuột */
}
.coupon-card.coupon-used .badge {
  background: #bbb !important;
}

/* Account Form */
.account-form label { font-size: 0.83rem; font-weight: 600; color: #444; margin-bottom: 6px; }
.account-form .form-control {
  border-radius: 10px;
  border: 1px solid #e0e0e0;
  padding: 11px 14px;
  font-size: 0.93rem;
  transition: border-color 0.2s;
}
.account-form .form-control:focus { border-color: #1a1a2e; box-shadow: none; }
.btn-save {
  background: #1a1a2e;
  color: white;
  border-radius: 10px;
  padding: 12px 32px;
  font-weight: 600;
  border: none;
  transition: background 0.2s;
}
.btn-save:hover { background: #2d2d5e; color: white; }

@media (max-width: 768px) {
  .account-sidebar { position: relative; top: 0; margin-bottom: 20px; }
  .account-nav { display: flex; flex-wrap: nowrap; overflow-x: auto; padding: 8px 12px; }
  .account-nav li { flex-shrink: 0; }
  .account-nav li a { border-left: none; border-bottom: 3px solid transparent; padding: 10px 14px; border-radius: 8px; }
  .account-nav li a.active { border-bottom-color: #1a1a2e; }
  .account-avatar-box { display: flex; align-items: center; gap: 14px; padding: 20px; text-align: left; }
  .account-avatar-box img { width: 60px; height: 60px; }
}

</style>
@endpush

@section('content')

@php
  $totalOrders = $user ? $orders->total() : 0;
  $totalSpent   = $user ? $user->orders()->where('status', 'completed')->sum('final_total') : 0;
  $wishCount    = $user ? $wishlists->count() : 0;
@endphp

{{-- Breadcrumb --}}
<div class="breadcrumbs_area other_bread">
  <div class="container">
    <div class="breadcrumb_content">
      <ul>
        <li><a href="{{ route('welcome') }}">Trang chủ</a></li>
        <li>/</li>
        <li>Tài khoản của tôi</li>
      </ul>
    </div>
  </div>
</div>

<div class="account-wrapper">
<div class="container">
<div class="row g-4">

  {{-- ===== SIDEBAR ===== --}}
  <div class="col-md-3">
    <div class="account-sidebar">
      {{-- Avatar --}}
      <div class="account-avatar-box">
        @if($user && $user->avatar)
          <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
        @elseif($user)
          <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4f46e5&color=fff&size=128" alt="{{ $user->name }}">
        @endif
        <div class="text-white mt-1">
          <div class="fw-bold">{{ $user?->name ?? 'Khách' }}</div>
          <div class="opacity-60 small">{{ $user?->email }}</div>
        </div>
      </div>

      {{-- Nav --}}
      <ul class="account-nav">
        <li>
          <a href="#dashboard" data-tab="dashboard" class="nav-tab-link">
            <i class="bi bi-grid-1x2"></i> Tổng quan
          </a>
        </li>
        <li>
          <a href="#orders" data-tab="orders" class="nav-tab-link">
            <i class="bi bi-bag-check"></i> Đơn hàng của tôi
          </a>
        </li>
        <li>
          <a href="#wishlist" data-tab="wishlist" class="nav-tab-link">
            <i class="bi bi-heart"></i> Yêu thích
            @if($wishCount > 0)<span class="badge bg-danger ms-auto">{{ $wishCount }}</span>@endif
          </a>
        </li>
        <li>
          <a href="#notifications" data-tab="notifications" class="nav-tab-link">
            <i class="bi bi-bell"></i> Thông báo
            @if($user && $user->unreadNotifications->count() > 0)
              <span class="badge bg-danger ms-auto unread-badge-count">{{ $user->unreadNotifications->count() }}</span>
            @endif
          </a>
        </li>
        <li>
          <a href="#coupons" data-tab="coupons" class="nav-tab-link">
            <i class="bi bi-ticket-perforated"></i> Mã giảm giá
          </a>
        </li>
        <li>
          <a href="#account-details" data-tab="account-details" class="nav-tab-link">
            <i class="bi bi-person-gear"></i> Thông tin tài khoản
          </a>
        </li>
        {{-- Địa chỉ được gộp vào Thông tin tài khoản --}}
        @if(config('features.wallet'))
        <li>
          <a href="#wallet" data-tab="wallet" class="nav-tab-link">
            <i class="bi bi-wallet2"></i> Ví của tôi
            @if($user && ($user->wallet_balance ?? 0) > 0)
            <span class="badge ms-auto rounded-pill" style="background:#e8f5e9;color:#2e7d32;font-size:0.65rem;">{{ number_format($user->wallet_balance) }}đ</span>
            @endif
          </a>
        </li>
        @endif
        <li>
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-account').submit();" class="text-danger">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
          </a>
          <form id="logout-form-account" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
      </ul>
    </div>
  </div>

  {{-- ===== MAIN CONTENT ===== --}}
  <div class="col-md-9">

    @if(session('success'))
      <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
    @endif

    {{-- =============== TAB: DASHBOARD =============== --}}
    <div class="account-content tab-pane-block" id="tab-dashboard">
      <div class="tab-head">
        <h4><i class="bi bi-grid me-2"></i>Tổng quan tài khoản</h4>
      </div>
      <div class="tab-body">
        @if($user)
          {{-- Stat Cards --}}
          <div class="row g-3 mb-4">
            <div class="col-3">
              <div class="stat-card">
                <div class="stat-icon" style="background:#e8f5e9; color:#28a745;"><i class="bi bi-bag-check-fill"></i></div>
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-label">Đơn hàng</div>
              </div>
            </div>
            <div class="col-3">
              <div class="stat-card">
                <div class="stat-icon" style="background:#fff3e0; color:#f57c00;"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-number" style="font-size:1.3rem;">{{ number_format($totalSpent) }}đ</div>
                <div class="stat-label">Đã chi tiêu</div>
              </div>
            </div>
            <div class="col-3">
              <div class="stat-card">
                <div class="stat-icon" style="background:#fce4ec; color:#e91e63;"><i class="bi bi-heart-fill"></i></div>
                <div class="stat-number">{{ $wishCount }}</div>
                <div class="stat-label">Yêu thích</div>
              </div>
            </div>
          </div>

          {{-- Welcome --}}
          <div class="p-4 rounded-3" style="background:#f9fafb; border:1px solid #eee;">
            <h5 class="fw-bold mb-1">Xin chào, {{ $user->name }}! 👋</h5>
            <p class="text-muted mb-0">Từ bảng điều khiển tài khoản, bạn có thể xem <a href="#" data-tab="orders" class="nav-tab-link2">lịch sử đơn hàng</a>, quản lý <a href="#" data-tab="account-details" class="nav-tab-link2">thông tin tài khoản</a> và danh sách <a href="#" data-tab="wishlist" class="nav-tab-link2">yêu thích</a> của mình.</p>
          </div>

          {{-- Recent Orders --}}
          @if($orders->count() > 0)
          <div class="mt-4">
            <h6 class="fw-bold mb-3">Đơn hàng gần đây</h6>
            @foreach($orders->take(3) as $order)
            <a href="{{ route('account.orders.show', $order->id) }}" class="text-decoration-none text-dark d-block">
                <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3" style="background:#f9fafb; border:1px solid #eee; transition: background 0.2s;">
                  <div class="flex-grow-1">
                    <div class="fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-muted small">{{ $order->created_at->format('d/m/Y') }}</div>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="status-badge status-{{ strtolower($order->status) }}">{{ $order->status_text }}</span>
                    @if($order->returnRequest)
                      <span class="badge mt-1 {{ $order->returnRequest->status == 'completed' ? 'bg-success' : ($order->returnRequest->status == 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}" style="font-size: 0.65rem;">
                        Hoàn: {{ $order->returnRequest->status == 'pending' ? 'Chờ duyệt' : ($order->returnRequest->status == 'approved' ? 'Đã duyệt' : ($order->returnRequest->status == 'completed' ? 'Đã hoàn tiền' : 'Từ chối')) }}
                      </span>
                    @endif
                  </div>
                  <span class="fw-bold">{{ number_format($order->final_total ?: $order->total_price) }}đ</span>
                </div>
            </a>
            @endforeach
          </div>
          @endif
        @else
          <div class="text-center py-5">
            <i class="bi bi-person-circle" style="font-size:3rem; color:#ccc;"></i>
            <p class="text-muted mt-3">Vui lòng đăng nhập để xem thông tin tài khoản</p>
            <a href="{{ route('login') }}" class="btn btn-dark px-4 rounded-pill">Đăng nhập ngay</a>
          </div>
        @endif
      </div>
    </div>

    {{-- =============== TAB: ORDERS =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-orders">
      <div class="tab-head">
        <h4><i class="bi bi-bag-check me-2"></i>Đơn hàng của tôi</h4>
        <a href="{{ route('order-tracking.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
          <i class="bi bi-truck me-1"></i> Tra cứu đơn hàng
        </a>
      </div>
      <div class="tab-body p-0">
        <table class="table orders-table mb-0 px-2">
          <thead>
            <tr style="padding-left:28px;">
              <th style="padding-left:28px;">Mã đơn</th>
              <th>Ngày đặt</th>
              <th>Trạng thái</th>
              <th>Tổng tiền</th>
              <th style="padding-right:28px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @if(count($orders) > 0)
              @foreach($orders as $order)
            <tr>
              <td style="padding-left:28px;" class="order-id">
                <a href="{{ route('account.orders.show', $order->id) }}" class="text-decoration-none text-dark">
                  #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                </a>
              </td>
              <td class="text-muted">{{ $order->created_at->format('d/m/Y') }}</td>
              <td>
                <div class="d-flex flex-column align-items-start">
                  <span class="status-badge status-{{ strtolower($order->status) }}">{{ $order->status_text }}</span>
                  @if($order->returnRequest)
                    <span class="badge mt-1 {{ $order->returnRequest->status_badge }}" style="font-size: 0.7rem;">
                      Hoàn tiền: {{ $order->returnRequest->status_text }}
                    </span>
                  @endif
                </div>
              </td>
              <td class="fw-semibold">{{ number_format($order->final_total ?: $order->total_price) }}đ</td>
              <td style="padding-right:28px;">
                <div class="d-flex gap-2">
                  <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-dark px-3 rounded-pill">Xem</a>
                  @if(($order->status == 'completed' || $order->status == 'shipped') && !$order->returnRequest)
                    <a href="{{ route('account.orders.return_form', $order->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">Hoàn hàng</a>
                  @endif
                  @if($order->status == 'pending')
                    <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Hủy</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
            @else
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">
                <i class="bi bi-bag-x" style="font-size:2rem;"></i>
                <p class="mt-2 mb-0">Bạn chưa có đơn hàng nào.</p>
              </td>
            </tr>
            @endif
          </tbody>
        </table>
        @if(method_exists($orders, 'links'))
        <div class="px-4 py-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
        @endif
      </div>
    </div>

    {{-- =============== TAB: WISHLIST =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-wishlist">
      <div class="tab-head">
        <h4><i class="bi bi-heart me-2"></i>Danh sách yêu thích</h4>
        <span class="text-muted small">{{ $wishCount }} sản phẩm</span>
      </div>
      <div class="tab-body">
        @php $hasValidProduct = false; @endphp
        @if($wishlists->isNotEmpty())
        <div class="row g-3">
          @foreach($wishlists as $wish)
          @php $product = $wish->product; @endphp
          @if($product)
          @php $hasValidProduct = true; @endphp
          <div class="col-sm-6 col-lg-4">
            <div class="wish-card">
              <a href="{{ route('product.detail', $product->slug) }}">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}">
              </a>
              <div class="p-3">
                <a href="{{ route('product.detail', $product->slug) }}" class="fw-semibold text-dark d-block mb-1" style="font-size:.9rem; text-decoration:none;">{{ $product->name }}</a>
                <div class="text-danger fw-bold mb-2">{{ number_format($product->sale_price ?: $product->price) }}đ</div>
                <div class="d-flex gap-2">
                  <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-sm btn-dark flex-grow-1">Xem chi tiết</a>
                  <form action="{{ route('wishlist.destroy', $wish->id) }}" method="POST" id="delete-wish-{{ $wish->id }}">
                    @csrf @method('DELETE')
                    <button type="button" onclick="if(confirm('Xóa khỏi yêu thích?')) document.getElementById('delete-wish-{{ $wish->id }}').submit();" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endforeach
        </div>
        @endif
        
        @if(!$hasValidProduct)
          <div class="text-center py-5 text-muted">
            <i class="bi bi-heart" style="font-size:3rem; color:#eee;"></i>
            <p class="mt-2">Danh sách yêu thích của bạn còn trống.</p>
            <a href="{{ route('shop') }}" class="btn btn-dark rounded-pill px-4">Khám phá sản phẩm</a>
          </div>
        @endif
      </div>
    </div>

    {{-- =============== TAB: COUPONS =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-coupons">
      <div class="tab-head">
        <h4><i class="bi bi-ticket-perforated me-2"></i>Mã giảm giá của tôi</h4>
      </div>
      <div class="tab-body">
        @if($coupons->isNotEmpty())
        <div class="row g-3">
          @foreach($coupons as $coupon)
          @php $isUsed = $coupon->pivot && $coupon->pivot->used_at; @endphp
          <div class="col-md-6">
            <div class="coupon-card {{ $isUsed ? 'coupon-used' : '' }}">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <span class="coupon-code">{{ $coupon->code }}</span>
                <span class="badge bg-dark rounded-pill px-3">
                  @if($isUsed)
                    Đã sử dụng
                  @else
                    {{ $coupon->getFormattedValue() }}
                  @endif
                </span>
              </div>
              <p class="text-muted small mb-2">{{ $coupon->description }}</p>
              @if($coupon->end_date)
                <p class="text-muted small mb-2"><i class="bi bi-clock me-1"></i>HSD: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</p>
              @endif
              
              @if($isUsed)
                <button class="btn btn-sm btn-light rounded-pill w-100 disabled" style="cursor: not-allowed;">
                  <i class="bi bi-check-circle-fill me-1 text-success"></i>Đã áp dụng cho đơn #{{ str_pad($coupon->pivot->order_id, 6, '0', STR_PAD_LEFT) }}
                </button>
              @else
                <button class="btn btn-sm btn-outline-dark rounded-pill copy-coupon w-100" data-code="{{ $coupon->code }}">
                  <i class="bi bi-clipboard me-1"></i>Sao chép mã
                </button>
              @endif
            </div>
          </div>
          @endforeach
        </div>
        @else
          <div class="text-center py-5 text-muted">
            <i class="bi bi-ticket" style="font-size:3rem; color:#eee;"></i>
            <p class="mt-2">Bạn chưa có mã giảm giá nào.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- =============== TAB: NOTIFICATIONS =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-notifications">
      <div class="tab-head">
        <h4><i class="bi bi-bell me-2"></i>Thông báo của tôi</h4>
        @if($user && $user->unreadNotifications->count() > 0)
          <button class="btn btn-sm btn-outline-danger rounded-pill px-3 mark-all-read-tab">Đánh dấu tất cả đã đọc</button>
        @endif
      </div>
      <div class="tab-body p-0">
        @if(isset($notifications) && $notifications->count() > 0)
          <div class="notification-list-tab">
            @foreach($notifications as $notify)
              <div class="notify-row d-flex align-items-center p-3 border-bottom {{ is_null($notify->read_at) ? 'bg-light' : '' }}" style="position: relative; transition: background 0.2s;">
                <div class="flex-grow-1">
                  <a href="{{ $notify->data['url'] ?? 'javascript:void(0)' }}" class="text-decoration-none text-dark mark-read-manual" data-id="{{ $notify->id }}">
                    <div class="fw-semibold mb-1" style="font-size: 0.95rem;">
                      @if(is_null($notify->read_at))
                        <span class="badge bg-danger rounded-circle p-1 me-1" style="width:8px; height:8px; display:inline-block;"></span>
                      @endif
                      {{ $notify->data['message'] ?? 'Thông báo mới' }}
                    </div>
                    <div class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $notify->created_at->diffForHumans() }}</div>
                  </a>
                </div>
                <div>
                  @if(is_null($notify->read_at))
                    <button class="btn btn-sm btn-link text-decoration-none p-0 mark-read-manual" data-id="{{ $notify->id }}" title="Đánh dấu đã đọc">
                      <i class="bi bi-check2-circle fs-5"></i>
                    </button>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
          <div class="p-3 pagination-notifications">
            {{ $notifications->appends(['notifications_page' => $notifications->currentPage()])->links('pagination::bootstrap-5') }}
          </div>
        @else
          <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash" style="font-size:3rem; color:#eee;"></i>
            <p class="mt-2">Bạn không có thông báo nào.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- =============== TAB: ACCOUNT DETAILS =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-account-details">
      <div class="tab-head">
        <h4><i class="bi bi-person-gear me-2"></i>Thông tin tài khoản</h4>
      </div>
      <div class="tab-body">
        @if($user)
        <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data" class="account-form">
          @csrf

          {{-- Avatar Upload --}}
          <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
              @if($user->avatar)
                <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;border:3px solid #eee;">
              @else
                <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1a1a2e&color=fff&size=256" alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;border:3px solid #eee;">
              @endif
              <label for="avatar" class="position-absolute bottom-0 end-0" style="cursor:pointer;">
                <span class="badge bg-dark rounded-circle p-2"><i class="bi bi-camera-fill"></i></span>
                <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])">
              </label>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label>Họ và tên</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
            </div>
            <div class="col-md-6">
              <label>Số điện thoại</label>
              <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" pattern="^(03|05|07|08|09)\d{8}$">
            </div>
            <div class="col-12">
              <label>Email <span class="text-muted">(không thể đổi)</span></label>
              <input type="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>
          </div>

          <hr class="my-4">
          
          <div class="d-flex align-items-center justify-content-between mb-3">
              <div>
                  <h6 class="fw-bold mb-0">Mật khẩu</h6>
                  <p class="text-muted small mb-0">Đổi mật khẩu định kỳ để bảo mật tài khoản tốt hơn.</p>
              </div>
              <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" id="toggle-password-form">
                  <i class="bi bi-shield-lock me-1"></i>Đổi mật khẩu
              </button>
          </div>

          <div id="password-form-container" style="display: none;" class="bg-light p-4 rounded-4 mb-4 border border-info-subtle shadow-sm transition-all overflow-hidden">
              <div class="row g-3">
                <div class="col-md-12">
                  <label class="small fw-semibold">Mật khẩu hiện tại</label>
                  <div class="position-relative">
                    <input type="password" name="current_password" class="form-control pe-5" placeholder="Nhập mật khẩu hiện tại">
                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted toggle-password" style="z-index: 10; text-decoration: none;">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="small fw-semibold">Mật khẩu mới</label>
                  <div class="position-relative">
                    <input type="password" name="new_password" class="form-control pe-5" placeholder="Tối thiểu 6 ký tự">
                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted toggle-password" style="z-index: 10; text-decoration: none;">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="small fw-semibold">Xác nhận mật khẩu mới</label>
                  <div class="position-relative">
                    <input type="password" name="new_password_confirmation" class="form-control pe-5" placeholder="••••••••">
                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted toggle-password" style="z-index: 10; text-decoration: none;">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>
                <div class="col-12 mt-2">
                    <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted p-0" id="cancel-password">Huỷ bỏ</button>
                </div>
              </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-save">Lưu thay đổi</button>
          </div>

          <hr class="my-5">

          {{-- ===== REFUND INFO SECTION ===== --}}
          <div class="refund-info-section bg-light p-4 rounded-4 border border-dark-subtle shadow-sm">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
              <i class="bi bi-bank me-2 text-dark"></i>Thông tin hoàn tiền mặc định
            </h5>
            <p class="text-muted small mb-4">Thông tin này sẽ được tự động điền vào Form khi bạn yêu cầu hoàn tiền cho đơn hàng.</p>
            
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-bold">Ngân hàng</label>
                <select name="bank_bin" id="bank_bin_profile" class="form-select select2-bank" style="height: 45px;">
                  <option value="">-- Chọn Ngân Hàng --</option>
                  <option value="970436" data-logo="https://api.vietqr.io/img/VCB.png" data-name="Vietcombank" {{ $user->bank_bin == '970436' ? 'selected' : '' }}>Vietcombank</option>
                  <option value="970418" data-logo="https://api.vietqr.io/img/BIDV.png" data-name="BIDV" {{ $user->bank_bin == '970418' ? 'selected' : '' }}>BIDV</option>
                  <option value="970405" data-logo="https://api.vietqr.io/img/VBA.png" data-name="Agribank" {{ $user->bank_bin == '970405' ? 'selected' : '' }}>Agribank</option>
                  <option value="970415" data-logo="https://api.vietqr.io/img/CTG.png" data-name="VietinBank" {{ $user->bank_bin == '970415' ? 'selected' : '' }}>VietinBank</option>
                  <option value="970407" data-logo="https://api.vietqr.io/img/TCB.png" data-name="Techcombank" {{ $user->bank_bin == '970407' ? 'selected' : '' }}>Techcombank</option>
                  <option value="970422" data-logo="https://api.vietqr.io/img/MB.png" data-name="MBBank" {{ $user->bank_bin == '970422' ? 'selected' : '' }}>MBBank</option>
                  <option value="970416" data-logo="https://api.vietqr.io/img/ACB.png" data-name="ACB" {{ $user->bank_bin == '970416' ? 'selected' : '' }}>ACB</option>
                  <option value="970403" data-logo="https://api.vietqr.io/img/STB.png" data-name="Sacombank" {{ $user->bank_bin == '970403' ? 'selected' : '' }}>Sacombank</option>
                  <option value="970432" data-logo="https://api.vietqr.io/img/VPB.png" data-name="VPBank" {{ $user->bank_bin == '970432' ? 'selected' : '' }}>VPBank</option>
                  <option value="970423" data-logo="https://api.vietqr.io/img/TPB.png" data-name="TPBank" {{ $user->bank_bin == '970423' ? 'selected' : '' }}>TPBank</option>
                  <option value="970437" data-logo="https://api.vietqr.io/img/HDB.png" data-name="HDBank" {{ $user->bank_bin == '970437' ? 'selected' : '' }}>HDBank</option>
                  <option value="970441" data-logo="https://api.vietqr.io/img/VIB.png" data-name="VIB" {{ $user->bank_bin == '970441' ? 'selected' : '' }}>VIB</option>
                  <option value="970443" data-logo="https://api.vietqr.io/img/SHB.png" data-name="SHB" {{ $user->bank_bin == '970443' ? 'selected' : '' }}>SHB</option>
                  <option value="970426" data-logo="https://api.vietqr.io/img/MSB.png" data-name="MSB" {{ $user->bank_bin == '970426' ? 'selected' : '' }}>MSB</option>
                  <option value="970440" data-logo="https://api.vietqr.io/img/SEAB.png" data-name="SeABank" {{ $user->bank_bin == '970440' ? 'selected' : '' }}>SeABank</option>
                  <option value="970449" data-logo="https://api.vietqr.io/img/LPB.png" data-name="LPBank" {{ $user->bank_bin == '970449' ? 'selected' : '' }}>LPBank</option>
                  <option value="970428" data-logo="https://api.vietqr.io/img/NAB.png" data-name="NamABank" {{ $user->bank_bin == '970428' ? 'selected' : '' }}>NamABank</option>
                  <option value="970414" data-logo="https://api.vietqr.io/img/OCB.png" data-name="OCB" {{ $user->bank_bin == '970414' ? 'selected' : '' }}>OCB</option>
                  <option value="970431" data-logo="https://api.vietqr.io/img/EIB.png" data-name="Eximbank" {{ $user->bank_bin == '970431' ? 'selected' : '' }}>Eximbank</option>
                  <option value="970438" data-logo="https://api.vietqr.io/img/BVB.png" data-name="BVBank" {{ $user->bank_bin == '970438' ? 'selected' : '' }}>BVBank</option>
                </select>
                <input type="hidden" name="bank_name" id="bank_name_profile" value="{{ $user->bank_name }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Số tài khoản</label>
                <input type="text" name="account_number" id="account_number_profile" class="form-control" value="{{ $user->account_number }}" placeholder="Nhập số tài khoản">
              </div>
              <div class="col-12 mt-3">
                <label class="form-label small fw-bold">Tên chủ tài khoản</label>
                <div class="position-relative">
                  <input type="text" name="account_name" id="account_name_profile" class="form-control text-uppercase" value="{{ $user->account_name }}" placeholder="Hệ thống sẽ tra cứu tự động..." style="background-color: #f8f9fa;">
                  <div id="lookup-spinner-profile" class="spinner-border spinner-border-sm text-primary position-absolute" role="status" style="right: 15px; top: 15px; display: none;">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </div>
                <small id="lookup-msg-profile" class="text-muted"></small>
              </div>
            </div>
            <div class="mt-4">
              <button type="submit" class="btn btn-save w-100">Lưu thông tin hoàn tiền</button>
            </div>
          </div>
        </form>

        <hr class="my-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt me-2"></i>Sổ địa chỉ</h5>
          <button type="button" class="btn btn-sm btn-dark rounded-pill px-3" onclick="openAddAddressModal()">
            <i class="bi bi-plus me-1"></i> Thêm địa chỉ mới
          </button>
        </div>

        @if(isset($addresses) && $addresses->isNotEmpty())
          <div class="row g-3" id="address-list">
            @foreach($addresses as $addr)
            <div class="col-md-6 address-card-wrap" id="addr-wrap-{{ $addr->id }}">

              {{-- Card hiển thị --}}
              <div class="addr-view" id="addr-view-{{ $addr->id }}">
                <div class="p-3 rounded-3 h-100" style="border:1.5px solid #{{ $addr->is_default ? '1a1a2e' : 'e8e8e8' }};background:#fff;position:relative;">
                  @if($addr->is_default)
                    <span class="badge" style="background:#1a1a2e;color:#fff;font-size:0.65rem;position:absolute;top:12px;right:12px;">Mặc định</span>
                  @endif
                  <div class="fw-bold mb-1" style="font-size:0.95rem;">{{ $addr->receiver_name }}</div>
                  <div class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>{{ $addr->phone }}</div>
                  <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $addr->address }}{{ $addr->commune ? ', ' . $addr->commune : '' }}, {{ $addr->province }}</div>
                  <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-sm btn-outline-dark rounded-pill px-3" onclick="toggleAddrEdit({{ $addr->id }})">
                      <i class="bi bi-pencil me-1"></i>Sửa
                    </button>
                    @if(!$addr->is_default)
                    <form action="{{ route('account.addresses.default', $addr->id) }}" method="POST" class="d-inline">
                      @csrf @method('PATCH')
                      <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Đặt mặc định</button>
                    </form>
                    <form action="{{ route('account.addresses.destroy', $addr->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Xoá địa chỉ này?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Inline Edit Form --}}
              <div class="addr-edit d-none" id="addr-edit-{{ $addr->id }}">
                <div class="p-3 rounded-3" style="border:1.5px solid #1a1a2e;background:#fafafa;">
                  <div class="fw-semibold mb-3" style="font-size:0.9rem;">Chỉnh sửa địa chỉ</div>
                  <form action="{{ route('account.addresses.update', $addr->id) }}" method="POST" class="addr-form" data-id="{{ $addr->id }}">
                    @csrf @method('PUT')
                    {{-- Receiver name and phone are now managed via User Profile --}}
                    <div class="mb-2">
                      <select name="province" class="form-select form-select-sm rounded-3 addr-province" data-selected="{{ $addr->province }}" required>
                        <option value="">-- Đang tải tỉnh/thành... --</option>
                      </select>
                    </div>
                    <div class="mb-2">
                      <select name="commune" class="form-select form-select-sm rounded-3 addr-commune" data-selected="{{ $addr->commune }}" disabled required>
                        <option value="">-- Chọn tỉnh trước --</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <input type="text" name="address" value="{{ $addr->address }}"
                        class="form-control form-control-sm rounded-3" placeholder="Số nhà, tên đường..." required>
                    </div>
                    <div class="form-check mb-3">
                      <input class="form-check-input" type="checkbox" name="is_default" value="1" id="def-{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }}>
                      <label class="form-check-label small" for="def-{{ $addr->id }}">Đặt làm địa chỉ mặc định</label>
                    </div>
                    <div class="d-flex gap-2">
                      <button type="submit" class="btn btn-sm btn-dark rounded-pill px-3"><i class="bi bi-check me-1"></i>Lưu</button>
                      <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="toggleAddrEdit({{ $addr->id }})">Huỷ</button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-5 text-muted border rounded-3 bg-light">
            <i class="bi bi-geo-alt" style="font-size:3rem;color:#eee;"></i>
            <p class="mt-2">Bạn chưa có địa chỉ nào.</p>
            <button type="button" class="btn btn-dark rounded-pill px-4" onclick="openAddAddressModal()">Thêm địa chỉ mới</button>
          </div>
        @endif

        @else
          <div class="text-center py-5">
            <p class="text-muted">Vui lòng đăng nhập để xem thông tin tài khoản.</p>
            <a href="{{ route('login') }}" class="btn btn-dark px-4 rounded-pill">Đăng nhập ngay</a>
          </div>
        @endif
      </div>
    </div>



    @if(config('features.wallet'))
    {{-- =============== TAB: WALLET =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-wallet">
      <div class="tab-head">
        <h4><i class="bi bi-wallet2 me-2"></i>Ví của tôi</h4>
      </div>
      <div class="tab-body">
        {{-- Wallet Balance Card --}}
        <div class="p-4 rounded-4 mb-4 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
            <div class="small opacity-80 mb-1">Số dư hiện tại</div>
            <div class="h2 fw-bold mb-0">{{ number_format($user->wallet_balance ?? 0) }}đ</div>
        </div>

        {{-- Transaction History --}}
        <h6 class="fw-bold mb-3">Lịch sử giao dịch</h6>
        @if(isset($walletTransactions) && $walletTransactions->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Ngày</th>
                            <th class="border-0">Loại</th>
                            <th class="border-0">Số tiền</th>
                            <th class="border-0">Nội dung</th>
                            <th class="border-0">Số dự sau</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($walletTransactions as $transaction)
                            <tr>
                                <td class="text-muted small">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($transaction->type === 'credit')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">Cộng tiền</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Trừ tiền</span>
                                    @endif
                                </td>
                                <td class="fw-bold {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount) }}đ
                                </td>
                                <td class="small">{{ $transaction->description }}</td>
                                <td class="text-muted">{{ number_format($transaction->balance_after) }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 bg-light rounded-3">
                <i class="bi bi-journal-text opacity-20" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2 mb-0">Chưa có lịch sử giao dịch nào.</p>
            </div>
        @endif
      </div>{{-- tab-body --}}
    </div>{{-- tab-wallet --}}
    @endif

  </div>{{-- col-md-9 --}}

</div>{{-- row --}}
</div>{{-- container --}}
</div>{{-- account-wrapper --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const tabs = {
    'dashboard'       : 'tab-dashboard',
    'orders'          : 'tab-orders',
    'wishlist'        : 'tab-wishlist',
    'coupons'         : 'tab-coupons',
    'addresses'       : 'tab-account-details',
    'account-details' : 'tab-account-details',
    @if(config('features.wallet'))
    'wallet'          : 'tab-wallet',
    @endif
    'notifications'   : 'tab-notifications',
  };

  function showTab(tabId) {
    Object.values(tabs).forEach(id => {
      const el = document.getElementById(id);
      if(el) el.classList.add('d-none');
    });
    const target = document.getElementById(tabs[tabId] || 'tab-dashboard');
    if(target) target.classList.remove('d-none');

    document.querySelectorAll('.nav-tab-link').forEach(a => a.classList.remove('active'));
    document.querySelectorAll(`.nav-tab-link[data-tab="${tabId}"]`).forEach(a => a.classList.add('active'));
    window.location.hash = tabId;
  }

  // Bind sidebar links
  document.querySelectorAll('.nav-tab-link').forEach(link => {
    link.addEventListener('click', function(e) {
      const tab = this.getAttribute('data-tab');
      if(tab) { e.preventDefault(); showTab(tab); }
    });
  });

  // Bind inline links inside content
  document.querySelectorAll('.nav-tab-link2').forEach(link => {
    link.addEventListener('click', function(e) {
      const tab = this.getAttribute('data-tab');
      if(tab) { e.preventDefault(); showTab(tab); }
    });
  });

  // Load from URL hash
  const hash = window.location.hash.replace('#', '');
  showTab(hash in tabs ? hash : 'dashboard');

  // Mark all as read within tab
  document.querySelectorAll('.mark-all-read-tab').forEach(btn => {
    btn.addEventListener('click', function() {
        const url = '{{ route("notifications.mark_all_read") }}';
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                location.reload();
            }
        });
    });
  });

  // Copy coupon
  document.querySelectorAll('.copy-coupon').forEach(btn => {
    btn.addEventListener('click', function() {
      const code = this.getAttribute('data-code');
      const button = this;

      function onSuccess() {
        button.innerHTML = '<i class="bi bi-check2 me-1"></i>Đã sao chép!';
        button.classList.remove('btn-outline-dark');
        button.classList.add('btn-success');
        setTimeout(() => {
          button.innerHTML = '<i class="bi bi-clipboard me-1"></i>Sao chép mã';
          button.classList.remove('btn-success');
          button.classList.add('btn-outline-dark');
        }, 2000);
      }

      function fallbackCopy() {
        const ta = document.createElement('textarea');
        ta.value = code;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try {
          document.execCommand('copy');
          onSuccess();
        } catch(e) {
          alert('Không thể sao chép. Vui lòng copy thủ công: ' + code);
        }
        document.body.removeChild(ta);
      }

      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(onSuccess).catch(fallbackCopy);
      } else {
        fallbackCopy();
      }
    });
  });

  // Toggle password visibility
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
  });

  // Toggle Password Change Form
  const toggleBtn = document.getElementById('toggle-password-form');
  const cancelBtn = document.getElementById('cancel-password');
  const passwordContainer = document.getElementById('password-form-container');

  if(toggleBtn && passwordContainer) {
      toggleBtn.addEventListener('click', function() {
          const isHidden = passwordContainer.style.display === 'none';
          if(isHidden) {
              passwordContainer.style.display = 'block';
              passwordContainer.classList.add('animate__animated', 'animate__fadeIn');
              this.innerHTML = '<i class="bi bi-x-lg me-1"></i>Hủy đổi mật khẩu';
              this.classList.replace('btn-outline-dark', 'btn-outline-danger');
          } else {
              passwordContainer.style.display = 'none';
              this.innerHTML = '<i class="bi bi-shield-lock me-1"></i>Đổi mật khẩu';
              this.classList.replace('btn-outline-danger', 'btn-outline-dark');
              // Clear inputs
              passwordContainer.querySelectorAll('input').forEach(input => input.value = '');
          }
      });
  }

  if(cancelBtn && toggleBtn) {
      cancelBtn.addEventListener('click', () => toggleBtn.click());
  }

});

// ===== ADD ADDRESS MODAL JS =====
async function openAddAddressModal() {
    const modalEl = document.getElementById('modalAddAddress');
    const provinceSelect = document.getElementById('add-addr-province');
    const communeSelect  = document.getElementById('add-addr-commune');
    
    // Reset form
    document.getElementById('formAddAddress').reset();
    communeSelect.innerHTML = '<option value="">-- Chọn tỉnh/thành trước --</option>';
    communeSelect.disabled = true;

    // Show modal
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // Load provinces if not already loaded (or just reload to be safe)
    try {
        provinceSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
        const res = await fetch('{{ route("api.vn-address.provinces") }}');
        const data = await res.json();

        provinceSelect.innerHTML = '<option value="">-- Chọn tỉnh/thành phố --</option>';
        data.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.name;
            opt.dataset.code = p.code;
            opt.textContent = p.name;
            provinceSelect.appendChild(opt);
        });
    } catch (err) {
        provinceSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
    }
}

async function onAddAddrProvinceChange(select) {
    const provinceCode = select.options[select.selectedIndex].dataset.code;
    const communeSelect = document.getElementById('add-addr-commune');

    if (!provinceCode) {
        communeSelect.innerHTML = '<option value="">-- Chọn tỉnh/thành trước --</option>';
        communeSelect.disabled = true;
        return;
    }

    communeSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
    communeSelect.disabled = true;

    try {
        const res = await fetch('{{ url("api/vn-address/communes") }}/' + provinceCode);
        const data = await res.json();

        communeSelect.innerHTML = '<option value="">-- Chọn xã/phường --</option>';
        data.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.name;
            opt.textContent = c.name;
            communeSelect.appendChild(opt);
        });
        communeSelect.disabled = false;
    } catch (err) {
        communeSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
    }
}

function copyBankAccount(number, btn) {
  if (!navigator.clipboard) return;
  navigator.clipboard.writeText(number).then(function() {
    var orig = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check2"></i>';
    setTimeout(function() { btn.innerHTML = orig; }, 2000);
  });
}


function updateDestBankInfo(select) {
    const container = document.getElementById('dest-bank-info');
    const accName = document.getElementById('dest-acc-name');
    const accNumber = document.getElementById('dest-acc-number');
    
    if (select.value) {
        const option = select.options[select.selectedIndex];
        accName.textContent = option.dataset.accountName;
        accNumber.textContent = option.dataset.accountNumber;
        container.classList.remove('d-none');
    } else {
        container.classList.add('d-none');
    }
}


// Track current bank
var _currentBankId = '';
var _currentAccount = document.getElementById('display-account-number')
  ? document.getElementById('display-account-number').textContent.trim() : '';
var _currentName = document.getElementById('display-account-name')
  ? document.getElementById('display-account-name').textContent.trim() : '';

// Init bank id from logo src
(function(){
  var logo = document.getElementById('bank-logo-display');
  if (logo) {
    var m = logo.src.match(/img\/([^.]+)\.png/);
    if (m) _currentBankId = m[1];
  }
})();

function _refreshQR(bankId, account, name, amount) {
  var qrImg  = document.getElementById('qr-image');
  var dlBtn  = document.getElementById('qr-download-btn');
  if (!qrImg) return;
  var url = 'https://img.vietqr.io/image/' + bankId + '-' + account + '-qr_only.png?accountName=' + encodeURIComponent(name);
  if (amount && Number(amount) > 0) url += '&amount=' + amount;
  qrImg.style.opacity = '0.4';
  qrImg.onload = function() { qrImg.style.opacity = '1'; };
  qrImg.src = url;
  if (dlBtn) dlBtn.href = url;
}

document.querySelectorAll('.bank-select-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.bank-select-btn').forEach(function(b) {
      b.classList.remove('btn-dark');
      b.classList.add('btn-outline-secondary');
    });
    this.classList.remove('btn-outline-secondary');
    this.classList.add('btn-dark');

    var bankId   = this.dataset.bankId;
    var account  = this.dataset.account;
    var name     = this.dataset.name;
    var bankName = this.dataset.bankname;

    _currentBankId = bankId;
    _currentAccount = account;
    _currentName = name;

    var logoEl = document.getElementById('bank-logo-display');
    if (logoEl) logoEl.src = 'https://api.vietqr.io/img/' + bankId + '.png';
    var nameEl = document.getElementById('display-bank-name');
    if (nameEl) nameEl.textContent = bankName;
    var accEl = document.getElementById('display-account-number');
    if (accEl) accEl.textContent = account;
    var ownerEl = document.getElementById('display-account-name');
    if (ownerEl) ownerEl.textContent = name.toUpperCase();

    var amount = document.getElementById('qr-amount-input') ? document.getElementById('qr-amount-input').value : '';
    _refreshQR(bankId, account, name, amount);
  });
});

function updateQRWithAmount() {
  var amount = document.getElementById('qr-amount-input') ? document.getElementById('qr-amount-input').value : '';
  _refreshQR(_currentBankId, _currentAccount, _currentName, amount);
}

var amtInput = document.getElementById('qr-amount-input');
if (amtInput) amtInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') updateQRWithAmount();
});

// ===== ADDRESS INLINE EDIT =====
let _vnProvincesCache = null;

async function _loadVnProvinces() {
    if (_vnProvincesCache) return _vnProvincesCache;
    const res  = await fetch('{{ route("api.vn-address.provinces") }}');
    _vnProvincesCache = await res.json();
    return _vnProvincesCache;
}

async function initAddrForm(addrId) {
    const form = document.querySelector(`.addr-form[data-id="${addrId}"]`);
    if (!form) return;
    const provinceEl = form.querySelector('.addr-province');
    const communeEl  = form.querySelector('.addr-commune');
    const selProvince = provinceEl.dataset.selected || '';
    const selCommune  = communeEl.dataset.selected || '';

    // Load provinces
    const provinces = await _loadVnProvinces();
    provinceEl.innerHTML = '<option value="">-- Chọn tỉnh/thành phố --</option>';
    let selectedCode = '';
    provinces.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.name; opt.dataset.code = p.code; opt.textContent = p.name;
        if (p.name === selProvince) { opt.selected = true; selectedCode = p.code; }
        provinceEl.appendChild(opt);
    });

    // Auto-load communes if province known
    if (selectedCode) await loadAddrCommunes(communeEl, selectedCode, selCommune);

    provinceEl.addEventListener('change', async function() {
        const opt = this.options[this.selectedIndex];
        communeEl.innerHTML = '<option value="">-- Đang tải... --</option>';
        communeEl.disabled = true;
        if (opt && opt.dataset.code) {
            await loadAddrCommunes(communeEl, opt.dataset.code, '');
        } else {
            communeEl.innerHTML = '<option value="">-- Chọn tỉnh trước --</option>';
        }
    });
}

async function loadAddrCommunes(select, provinceCode, selectedCommune) {
    try {
        const res  = await fetch('{{ url("api/vn-address/communes") }}/' + provinceCode);
        const data = await res.json();
        select.innerHTML = '<option value="">-- Chọn xã/phường --</option>';
        data.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.name; opt.textContent = c.name;
            if (c.name === selectedCommune) opt.selected = true;
            select.appendChild(opt);
        });
        select.disabled = false;
    } catch(e) {
        select.innerHTML = '<option value="">Lỗi tải xã/phường</option>';
    }
}

function toggleAddrEdit(addrId) {
    const view = document.getElementById('addr-view-' + addrId);
    const edit = document.getElementById('addr-edit-' + addrId);
    if (!view || !edit) return;
    const isHidden = edit.classList.contains('d-none');
    view.classList.toggle('d-none', isHidden);
    edit.classList.toggle('d-none', !isHidden);
    if (isHidden) initAddrForm(addrId);
}

// Notifications mark as read manual logic for tab
document.querySelectorAll('.mark-read-manual').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const id = this.dataset.id;
        if (!id) return;
        
        fetch('{{ url("/notifications") }}/' + id + '/mark-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(r => r.json()).then(data => {
            if (data.status === 'success') {
                // Find all elements for this notification in the tab and mark them as read
                document.querySelectorAll(`.mark-read-manual[data-id="${id}"]`).forEach(el => {
                    const row = el.closest('.notify-row');
                    if (row) {
                        row.classList.remove('bg-light');
                        const badge = row.querySelector('.badge.bg-danger');
                        if (badge) badge.remove();
                    }
                    const btnMark = el.closest('.mark-read-manual');
                    if (btnMark && btnMark.tagName === 'BUTTON') btnMark.remove();
                });
                
                // Update badge counts if they exist
                const badgeCounts = document.querySelectorAll('.unread-badge-count, .notification-badge');
                badgeCounts.forEach(bc => {
                    let count = parseInt(bc.textContent);
                    if (count > 0) {
                        count--;
                        if (count === 0) bc.remove();
                        else bc.textContent = count;
                    }
                });
            }
        }).catch(console.error);
    });
});


// ===== REFUND BANK LOGIC =====
$(document).ready(function() {
    function formatBank(bank) {
        if (!bank.id) return bank.text;
        var logoUrl = $(bank.element).data('logo');
        if (logoUrl) {
            return $('<div class="bank-item"><img src="' + logoUrl + '" class="bank-logo" /> <span>' + bank.text + '</span></div>');
        }
        return bank.text;
    }

    $('.select2-bank').select2({
        placeholder: '-- Chọn Ngân Hàng --',
        allowClear: true,
        templateResult: formatBank,
        templateSelection: formatBank,
        width: '100%'
    });

    $('#bank_bin_profile').on('change', function() {
        const selected = $(this).find('option:selected');
        $('#bank_name_profile').val(selected.data('name') || '');
        checkProfileAccountName();
    });

    let profileLookupTimer;
    $('#account_number_profile').on('input', function() {
        clearTimeout(profileLookupTimer);
        profileLookupTimer = setTimeout(checkProfileAccountName, 800);
    });

    function checkProfileAccountName() {
        const bin = $('#bank_bin_profile').val();
        const accountNo = $('#account_number_profile').val().trim();
        const $accNameInput = $('#account_name_profile');
        const $spinner = $('#lookup-spinner-profile');
        const $msg = $('#lookup-msg-profile');

        if (bin && accountNo && accountNo.length >= 6) {
            $spinner.show();
            $msg.text('Đang xác thực tài khoản...');
            $accNameInput.css('background-color', '#fff');

            $.ajax({
                url: 'https://api.vietqr.io/v2/lookup',
                method: 'POST',
                headers: {
                    'x-client-id': 'b85a3c26-f831-4a5f-abaa-ae57d25e40e2',
                    'x-api-key': 'd102dc85-2eec-4752-9654-20a221f7e34a',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ bin: bin, accountNumber: accountNo }),
                success: function(res) {
                    $spinner.hide();
                    if (res.code == '00') {
                        $accNameInput.val(res.data.accountName);
                        $msg.html('<span class="text-success"><i class="bi bi-check-circle-fill"></i> Tài khoản hợp lệ</span>');
                    } else {
                        $msg.html('<span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Không tìm thấy tài khoản.</span>');
                    }
                },
                error: function() {
                    $spinner.hide();
                    $msg.text('Không thể tra cứu tự động. Vui lòng nhập tay.');
                }
            });
        }
    }

    // Trigger initial lookup if data exists
    if ($('#bank_bin_profile').val() && $('#account_number_profile').val()) {
        checkProfileAccountName();
    }
});

</script>
@endpush

{{-- Modal Thêm địa chỉ mới --}}
<div class="modal fade" id="modalAddAddress" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-geo-alt me-2 text-primary"></i>Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddAddress" action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- Receiver name and phone are now managed via User Profile --}}
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-uppercase">Tỉnh / Thành phố</label>
                            <select name="province" id="add-addr-province" class="form-select rounded-3" onchange="onAddAddrProvinceChange(this)" required>
                                <option value="">-- Chọn tỉnh/thành --</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-uppercase">Xã / Phường</label>
                            <select name="commune" id="add-addr-commune" class="form-select rounded-3" required disabled>
                                <option value="">-- Chọn tỉnh/thành trước --</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-uppercase">Địa chỉ cụ thể</label>
                            <textarea name="address" class="form-control rounded-3" rows="2" placeholder="Số nhà, tên đường, ngõ hẻm..." required></textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="add-addr-default">
                                <label class="form-check-label small" for="add-addr-default">
                                    Đặt làm địa chỉ mặc định
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>
