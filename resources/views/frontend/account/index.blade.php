@extends('layouts.public')

@section('title', 'Tài khoản của tôi | ' . ($settings['site_title'] ?? 'Elite'))

@push('styles')
<style>
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
.coupon-code {
  font-size: 1.4rem;
  font-weight: 900;
  letter-spacing: 2px;
  color: #1a1a2e;
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
          <a href="#coupons" data-tab="coupons" class="nav-tab-link">
            <i class="bi bi-ticket-perforated"></i> Mã giảm giá
          </a>
        </li>
        <li>
          <a href="#loyalty" data-tab="loyalty" class="nav-tab-link">
            <i class="bi bi-star-fill"></i> Điểm thưởng
            @if($loyaltyPoints > 0)<span class="badge bg-success ms-auto">{{ $loyaltyPoints }}</span>@endif
          </a>
        </li>
        <li>
          <a href="#account-details" data-tab="account-details" class="nav-tab-link">
            <i class="bi bi-person-gear"></i> Thông tin tài khoản
          </a>
        </li>
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
            <div class="col-3">
              <div class="stat-card">
                <div class="stat-icon" style="background:#e8eaf6; color:#3f51b5;"><i class="bi bi-star-fill"></i></div>
                <div class="stat-number">{{ $loyaltyPoints }}</div>
                <div class="stat-label">Điểm thưởng</div>
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
                  <span class="status-badge status-{{ strtolower($order->status) }}">{{ $order->status_text }}</span>
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
            @forelse($orders as $order)
            <tr>
              <td style="padding-left:28px;" class="order-id">
                <a href="{{ route('account.orders.show', $order->id) }}" class="text-decoration-none text-dark">
                  #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                </a>
              </td>
              <td class="text-muted">{{ $order->created_at->format('d/m/Y') }}</td>
              <td>
                <span class="status-badge status-{{ strtolower($order->status) }}">{{ $order->status_text }}</span>
              </td>
              <td class="fw-semibold">{{ number_format($order->final_total ?: $order->total_price) }}đ</td>
              <td style="padding-right:28px;">
                <div class="d-flex gap-2">
                  <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-dark px-3 rounded-pill">Xem</a>
                  @if($order->status == 'pending')
                    <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Hủy</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">
                <i class="bi bi-bag-x" style="font-size:2rem;"></i>
                <p class="mt-2 mb-0">Bạn chưa có đơn hàng nào.</p>
              </td>
            </tr>
            @endforelse
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
        @if($wishlists->isNotEmpty())
        <div class="row g-3">
          @foreach($wishlists as $wish)
          @php $product = $wish->product; @endphp
          <div class="col-sm-6 col-lg-4">
            <div class="wish-card">
              <a href="{{ route('product.detail', $product->slug) }}">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}">
              </a>
              <div class="p-3">
                <a href="{{ route('product.detail', $product->slug) }}" class="fw-semibold text-dark d-block mb-1" style="font-size:.9rem; text-decoration:none;">{{ $product->name }}</a>
                <div class="text-danger fw-bold mb-2">{{ number_format($product->sale_price ?: $product->price) }}đ</div>
                <div class="d-flex gap-2">
                  @if(isset($product->stock) && $product->stock > 0)
                    <a href="javascript:void(0)" class="btn btn-sm btn-dark flex-grow-1 add-to-cart-btn" data-id="{{ $product->id }}"><i class="bi bi-bag-plus me-1"></i>Thêm giỏ</a>
                  @else
                    <button class="btn btn-sm btn-secondary flex-grow-1 disabled">Hết hàng</button>
                  @endif
                  <form action="{{ route('wishlist.destroy', $wish->id) }}" method="POST" id="delete-wish-{{ $wish->id }}">
                    @csrf @method('DELETE')
                    <button type="button" onclick="if(confirm('Xóa khỏi yêu thích?')) document.getElementById('delete-wish-{{ $wish->id }}').submit();" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
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
          <div class="col-md-6">
            <div class="coupon-card">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <span class="coupon-code">{{ $coupon->code }}</span>
                <span class="badge bg-dark rounded-pill px-3">{{ $coupon->getFormattedValue() }}</span>
              </div>
              <p class="text-muted small mb-2">{{ $coupon->description }}</p>
              @if($coupon->end_date)
                <p class="text-muted small mb-2"><i class="bi bi-clock me-1"></i>HSD: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</p>
              @endif
              <button class="btn btn-sm btn-outline-dark rounded-pill copy-coupon" data-code="{{ $coupon->code }}">
                <i class="bi bi-clipboard me-1"></i>Sao chép mã
              </button>
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

    {{-- =============== TAB: LOYALTY POINTS =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-loyalty">
      <div class="tab-head">
        <h4><i class="bi bi-star me-2"></i>Điểm thưởng</h4>
      </div>
      <div class="tab-body">
        {{-- Points Summary --}}
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="p-4 rounded-3 text-center" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: white;">
              <div style="font-size: 3rem; font-weight: 900;">{{ number_format($loyaltyPoints) }}</div>
              <div class="opacity-75">Điểm tích lũy</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-4 rounded-3 text-center" style="background: #f9fafb; border: 1px solid #eee;">
              <div style="font-size: 2rem; font-weight: 800; color: #28a745;">{{ number_format($loyaltyPointsValue) }}đ</div>
              <div class="text-muted">Giá trị quy đổi</div>
              <div class="text-muted small mt-1">1 điểm = 1.000đ giảm giá</div>
            </div>
          </div>
        </div>

        {{-- How it works --}}
        <div class="p-3 rounded-3 mb-4" style="background: #fff8e1; border: 1px solid #ffe082;">
          <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1"></i>Cách tích điểm</h6>
          <ul class="mb-0 small">
            <li>Mỗi <strong>10.000đ</strong> trong đơn hàng hoàn thành = <strong>1 điểm</strong></li>
            <li>Điểm được cộng khi đơn hàng chuyển sang trạng thái <strong>"Hoàn thành"</strong></li>
            <li>Điểm sẽ bị thu hồi nếu đơn hàng bị hủy hoặc trả hàng</li>
          </ul>
        </div>

        {{-- Points History --}}
        <h6 class="fw-bold mb-3">Lịch sử điểm thưởng</h6>
        @if($loyaltyHistory->isNotEmpty())
          @foreach($loyaltyHistory as $point)
          <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3" style="background:#f9fafb; border:1px solid #eee;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px; {{ $point->points > 0 ? 'background:#d1e7dd; color:#0a3622;' : 'background:#f8d7da; color:#842029;' }}">
              <i class="bi {{ $point->points > 0 ? 'bi-plus-lg' : 'bi-dash-lg' }}"></i>
            </div>
            <div class="flex-grow-1">
              <div class="fw-semibold">{{ $point->description }}</div>
              <div class="text-muted small">{{ $point->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="fw-bold {{ $point->points > 0 ? 'text-success' : 'text-danger' }}">{{ $point->points > 0 ? '+' : '' }}{{ $point->points }} điểm</div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4 text-muted">
            <i class="bi bi-star" style="font-size:2rem; color:#eee;"></i>
            <p class="mt-2 mb-0">Chưa có lịch sử điểm thưởng. Hãy mua sắm để tích điểm!</p>
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
          <h6 class="fw-bold mb-1">Đổi mật khẩu</h6>
          <p class="text-muted small mb-3">Để trống nếu bạn không muốn đổi mật khẩu.</p>

          <div class="row g-3">
            <div class="col-md-4">
              <label>Mật khẩu hiện tại</label>
              <div class="position-relative">
                <input type="password" name="current_password" class="form-control pe-5" placeholder="••••••••">
                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted toggle-password" style="z-index: 10; text-decoration: none;">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-4">
              <label>Mật khẩu mới</label>
              <div class="position-relative">
                <input type="password" name="new_password" class="form-control pe-5" placeholder="••••••••">
                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted toggle-password" style="z-index: 10; text-decoration: none;">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-4">
              <label>Xác nhận mật khẩu mới</label>
              <div class="position-relative">
                <input type="password" name="new_password_confirmation" class="form-control pe-5" placeholder="••••••••">
                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted toggle-password" style="z-index: 10; text-decoration: none;">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-save">Lưu thay đổi</button>
          </div>
        </form>
        @else
          <div class="text-center py-5">
            <p class="text-muted">Vui lòng đăng nhập để xem thông tin tài khoản.</p>
            <a href="{{ route('login') }}" class="btn btn-dark px-4 rounded-pill">Đăng nhập ngay</a>
          </div>
        @endif
      </div>
    </div>

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
    'loyalty'         : 'tab-loyalty',
    'account-details' : 'tab-account-details',
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

  // Copy coupon
  document.querySelectorAll('.copy-coupon').forEach(btn => {
    btn.addEventListener('click', function() {
      const code = this.getAttribute('data-code');
      navigator.clipboard?.writeText(code).then(() => {
        this.innerHTML = '<i class="bi bi-check2 me-1"></i>Đã sao chép!';
        setTimeout(() => { this.innerHTML = '<i class="bi bi-clipboard me-1"></i>Sao chép mã'; }, 2000);
      }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = code;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
      });
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
});
</script>
@endpush