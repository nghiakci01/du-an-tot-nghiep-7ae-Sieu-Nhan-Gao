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




        <div class="table-responsive">
          <table class="table align-middle" style="font-size:0.9rem;">
            <thead style="background:#f5f5f7;">
              <tr>
                <th style="padding:12px 16px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">#</th>
                <th style="padding:12px 16px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Ngân hàng</th>
                <th style="padding:12px 16px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Số tài khoản</th>
                <th style="padding:12px 16px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Chủ tài khoản</th>
                <th style="padding:12px 16px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;"></th>
                <th style="padding:12px 16px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @forelse($userBankAccounts as $i => $ub)
              <tr style="border-color:#f0f0f0;">
                <td style="padding:14px 16px;">{{ $i + 1 }}</td>
                <td style="padding:14px 16px;">
                  <div class="d-flex align-items-center gap-2">
                    <img src="https://api.vietqr.io/img/{{ $ub->bank_id }}.png"
                         alt="{{ $ub->bank_name }}"
                         style="height:24px;width:48px;object-fit:contain;"
                         onerror="this.style.display='none'">
                    <span class="fw-semibold">{{ $ub->bank_name }}</span>
                  </div>
                </td>
                <td style="padding:14px 16px;"><code class="fw-bold text-dark">{{ $ub->account_number }}</code></td>
                <td style="padding:14px 16px;">{{ Str::upper($ub->account_name) }}</td>
                <td style="padding:14px 16px;">
                  @if($ub->is_default)
                  <span class="badge rounded-pill" style="background:#fff3cd;color:#856404;"><i class="bi bi-star-fill me-1" style="font-size:0.6rem;"></i>Mặc định</span>
                  @endif
                </td>
                <td style="padding:14px 16px;">
                  <form action="{{ route('account.bank-accounts.destroy', $ub->id) }}" method="POST"
                        onsubmit="return confirm('Xóa tài khoản {{ $ub->bank_name }} - {{ $ub->account_number }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                      <i class="bi bi-trash me-1"></i>Xóa
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-credit-card-2-back" style="font-size:2.5rem;color:#ddd;display:block;margin-bottom:10px;"></i>
                  Bạn chưa có tài khoản ngân hàng nào.
                  <br>
                  <button type="button" class="btn btn-dark btn-sm mt-3 rounded-pill px-4" onclick="openAddBankModal()">
                    <i class="bi bi-plus-lg me-1"></i>Thêm ngay
                  </button>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>

    {{-- ========== MODAL: ADD BANK ACCOUNT ========== --}}
    <div class="modal fade" id="modalAddBank" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold"><i class="bi bi-bank me-2"></i>Thêm tài khoản ngân hàng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('account.bank-accounts.store') }}" method="POST" id="formAddBank">
            @csrf
            <div class="modal-body pt-3">
              <div class="mb-3">
                <label class="form-label fw-semibold small">Ngân hàng <span class="text-danger">*</span></label>
                <select name="bank_id" id="add-bank-id" class="form-select" required onchange="onBankSelectChange(this, 'add')">
                  <option value="">-- Chọn ngân hàng --</option>
                  <option value="970436" data-name="Vietcombank">970436 - Vietcombank</option>
                  <option value="970418" data-name="BIDV">970418 - BIDV</option>
                  <option value="970415" data-name="Vietinbank">970415 - Vietinbank</option>
                  <option value="970422" data-name="MB Bank">970422 - MB Bank</option>
                  <option value="970407" data-name="Techcombank">970407 - Techcombank</option>
                  <option value="970405" data-name="Agribank">970405 - Agribank</option>
                  <option value="970416" data-name="ACB">970416 - ACB</option>
                  <option value="970432" data-name="VPBank">970432 - VPBank</option>
                  <option value="796500" data-name="MSB">796500 - MSB</option>
                  <option value="970426" data-name="TPBank">970426 - TPBank</option>
                  <option value="970423" data-name="TPBank">970423 - TPBank</option>
                  <option value="970441" data-name="VIB">970441 - VIB</option>
                  <option value="970425" data-name="HDBank">970425 - HDBank</option>
                  <option value="970443" data-name="SHB">970443 - SHB</option>
                  <option value="970454" data-name="Viet Capital Bank">970454 - Viet Capital Bank</option>
                  <option value="970448" data-name="OCB">970448 - OCB</option>
                  <option value="970403" data-name="Sacombank">970403 - Sacombank</option>
                  <option value="970431" data-name="Eximbank">970431 - Eximbank</option>
                  <option value="970400" data-name="Saigonbank">970400 - Saigonbank</option>
                  <option value="970449" data-name="LPBank">970449 - LPBank</option>
                  <option value="MoMo" data-name="Ví MoMo">MoMo - Ví MoMo</option>
                  <option value="ZaloPay" data-name="Ví ZaloPay">ZaloPay - Ví ZaloPay</option>
                </select>
                <input type="hidden" name="bank_name" id="add-bank-name">
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold small">Số tài khoản / Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="account_number" class="form-control" placeholder="Nhập số tài khoản" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold small">Tên chủ tài khoản <span class="text-danger">*</span></label>
                <input type="text" name="account_name" class="form-control" placeholder="NGUYEN VAN A" style="text-transform:uppercase;" required>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="add-is-default">
                <label class="form-check-label small" for="add-is-default">Đặt làm tài khoản mặc định</label>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-dark rounded-pill px-5">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- =============== TAB: WALLET =============== --}}
    <div class="account-content tab-pane-block d-none" id="tab-wallet">
      <div class="tab-head">
        <h4><i class="bi bi-wallet2 me-2"></i>Ví của tôi</h4>
      </div>
      <div class="tab-body">
        @if(session('wallet_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('wallet_success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($user)
        {{-- Balance Card --}}
        <div class="row g-4 mb-4">
          <div class="col-md-5">
            <div style="background:linear-gradient(135deg,#0f2027 0%,#203a43 50%,#2c5364 100%);border-radius:20px;padding:28px;color:white;position:relative;overflow:hidden;">
              <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>
              <div style="position:absolute;bottom:-30px;left:-10px;width:130px;height:130px;border-radius:50%;background:rgba(255,255,255,0.03);"></div>
              <div style="font-size:0.72rem;opacity:0.6;text-transform:uppercase;letter-spacing:2px;margin-bottom:8px;">Số dư hiện tại</div>
              <div style="font-size:2.2rem;font-weight:800;letter-spacing:1px;">
                {{ number_format($user->wallet_balance) }}<span style="font-size:1rem;opacity:0.7;"> đ</span>
              </div>
              <div class="mt-3" style="font-size:0.8rem;opacity:0.6;">{{ $user->name }}</div>
            </div>
          </div>

          <div class="col-md-7">
            {{-- Tabs for Topup and Withdraw --}}
            <ul class="nav nav-pills mb-3" id="wallet-pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4" id="pills-topup-tab" data-bs-toggle="pill" data-bs-target="#pills-topup" type="button" role="tab" aria-controls="pills-topup" aria-selected="true"><i class="bi bi-box-arrow-in-down me-1"></i>Nạp tiền</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4" id="pills-withdraw-tab" data-bs-toggle="pill" data-bs-target="#pills-withdraw" type="button" role="tab" aria-controls="pills-withdraw" aria-selected="false"><i class="bi bi-box-arrow-up me-1"></i>Rút tiền</button>
              </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="pills-topup" role="tabpanel" aria-labelledby="pills-topup-tab">
                {{-- Top-up Request Form --}}
                <div class="p-4 rounded-3 border mb-4" style="background:#fafafa;">
                  <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-success"></i>Tạo yêu cầu nạp tiền</h6>
                  
                  @if(session('show_qr_id'))
                    @php
                      $justCreated = \App\Models\WalletTopupRequest::find(session('show_qr_id'));
                    @endphp
                    @if($justCreated)
                    <div class="alert alert-success border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h5 class="fw-bold text-success mb-3"><i class="bi bi-check-circle-fill me-2"></i>{{ session('wallet_success') }}</h5>
                                <div class="bg-white p-3 rounded-3 border mb-3">
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="text-muted">Ngân hàng:</span>
                                        <span class="fw-bold">{{ $justCreated->dest_bank_name }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="text-muted">Số tài khoản:</span>
                                        <span class="fw-bold text-primary">{{ $justCreated->dest_account_number }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="text-muted">Chủ tài khoản:</span>
                                        <span class="fw-bold">{{ mb_strtoupper($justCreated->dest_account_name) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="text-muted">Số tiền:</span>
                                        <span class="fw-bold text-success">{{ number_format($justCreated->amount) }}đ</span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Nội dung:</span>
                                        <span class="fw-bold text-danger">{{ $justCreated->transfer_note }}</span>
                                    </div>
                                </div>
                                <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Vui lòng chuyển khoản đúng <strong>Số tiền</strong> và <strong>Nội dung</strong> để hệ thống tự động xử lý nhanh nhất.</p>
                            </div>
                            <div class="col-md-5 text-center mt-3 mt-md-0">
                                <div class="bg-white p-2 rounded-3 border d-inline-block shadow-sm">
                                    @php
                                        // Bank IDs mapping for VietQR if needed, but BankSetting->bank_id should be the code
                                        $qrUrl = "https://img.vietqr.io/image/{$justCreated->bankSetting->bank_id}-{$justCreated->dest_account_number}-compact2.png?amount={$justCreated->amount}&addInfo=" . urlencode($justCreated->transfer_note) . "&accountName=" . urlencode($justCreated->dest_account_name);
                                    @endphp
                                    <img src="{{ $qrUrl }}" alt="VietQR" class="img-fluid" style="max-width: 220px;">
                                </div>
                                <div class="mt-2">
                                    <a href="{{ $qrUrl }}" download="VietQR_Payment.png" class="btn btn-sm btn-outline-dark rounded-pill">
                                        <i class="bi bi-download me-1"></i>Tải mã QR
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                  @endif

                  <form action="{{ route('wallet.topup.request') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Số tiền muốn nạp (VND) <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <input type="number" name="amount" class="form-control" placeholder="100,000" min="10000" max="100000000" step="1000" required>
                          <span class="input-group-text">VND</span>
                        </div>
                        <div class="form-text small">Tối thiểu 10,000đ — Tối đa 100,000,000đ</div>
                      </div>
                      
                      <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Chọn ngân hàng thụ hưởng <span class="text-danger">*</span></label>
                        <select name="bank_setting_id" id="topup-bank-select" class="form-select" required onchange="updateDestBankInfo(this)">
                          <option value="">-- Chọn ngân hàng --</option>
                          @foreach($bankSettings as $bs)
                          <option value="{{ $bs->id }}" 
                                  data-bank-id="{{ $bs->bank_id }}"
                                  data-bank-name="{{ $bs->bank_name }}"
                                  data-account-number="{{ $bs->account_number }}"
                                  data-account-name="{{ $bs->account_name }}">
                            {{ $bs->bank_name }} - {{ $bs->account_number }}
                          </option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div id="dest-bank-info" class="p-3 rounded-3 border bg-white mb-3 d-none">
                      <div class="row small">
                        <div class="col-sm-6 mb-2 mb-sm-0">
                          <div class="text-muted">Chủ tài khoản:</div>
                          <div id="dest-acc-name" class="fw-bold text-uppercase"></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted">Số tài khoản:</div>
                            <div id="dest-acc-number" class="fw-bold text-primary"></div>
                        </div>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="form-label fw-semibold small">Ghi chú / Lời nhắn (Tùy chọn)</label>
                      <input type="text" name="transfer_note" class="form-control" placeholder="Để lại lời nhắn nếu cần">
                      <div class="form-text small">Ví dụ: Bill chuyển khoản của tôi, nạp cho tk...</div>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label fw-semibold small">Ảnh minh chứng chuyển khoản (Nếu đã chuyển)</label>
                      <input type="file" name="proof_image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-dark rounded-pill px-5">
                      <i class="bi bi-qr-code-scan me-1"></i>Khởi tạo & Hiện QR
                    </button>
                  </form>
                </div>
              </div>

              <div class="tab-pane fade" id="pills-withdraw" role="tabpanel" aria-labelledby="pills-withdraw-tab">
                {{-- Withdraw Request Form --}}
                <div class="p-4 rounded-3 border" style="background:#fafafa;">
                  <h6 class="fw-bold mb-3"><i class="bi bi-dash-circle me-2 text-warning"></i>Yêu cầu rút tiền</h6>
                  <form action="{{ route('wallet.withdraw.request') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label fw-semibold small">Số dư ví khả dụng <span class="text-danger">*</span></label>
                      <input type="text" class="form-control fw-bold text-success" value="{{ number_format($user->wallet_balance) }} VND" disabled>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-semibold small">Số tiền muốn rút (VND) <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="number" name="amount" class="form-control" placeholder="50,000" min="50000" max="{{ $user->wallet_balance }}" step="1000" required>
                        <span class="input-group-text">VND</span>
                      </div>
                      <div class="form-text text-muted">Tối thiểu 50,000đ — Tối đa {{ number_format($user->wallet_balance) }}đ</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-semibold small">Rút về tài khoản ngân hàng <span class="text-danger">*</span></label>
                      @if($userBankAccounts->count() > 0)
                      <select name="user_bank_account_id" class="form-select" required>
                        <option value="">-- Chọn tài khoản nhận tiền --</option>
                        @foreach($userBankAccounts as $bank)
                        <option value="{{ $bank->id }}" {{ $bank->is_default ? 'selected' : '' }}>
                          {{ $bank->bank_name }} - {{ $bank->account_number }} ({{ $bank->account_name }})
                        </option>
                        @endforeach
                      </select>
                      @else
                      <div class="alert alert-warning py-2 small mb-0">Bạn chưa thêm tài khoản ngân hàng nào. <a href="#" onclick="document.querySelector('[data-tab=bank-accounts]').click(); return false;" class="fw-bold text-dark text-decoration-underline">Thêm ngay</a></div>
                      @endif
                    </div>
                    <button type="submit" class="btn btn-dark rounded-pill px-5" {{ $userBankAccounts->count() == 0 || $user->wallet_balance < 50000 ? 'disabled' : '' }}>
                      <i class="bi bi-send me-1"></i>Gửi yêu cầu rút
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Top-up/Withdraw Requests Status --}}
        <h6 class="fw-bold mb-3 mt-2"><i class="bi bi-clock-history me-2"></i>Lịch sử yêu cầu</h6>
        
        <ul class="nav nav-tabs mb-3" id="wallet-history-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-dark" id="history-topup-tab" data-bs-toggle="tab" data-bs-target="#history-topup" type="button" role="tab" aria-controls="history-topup" aria-selected="true">Yêu cầu nạp</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-dark" id="history-withdraw-tab" data-bs-toggle="tab" data-bs-target="#history-withdraw" type="button" role="tab" aria-controls="history-withdraw" aria-selected="false">Yêu cầu rút</button>
          </li>
        </ul>

        <div class="tab-content mb-4" id="walletHistoryContent">
          <div class="tab-pane fade show active" id="history-topup" role="tabpanel" aria-labelledby="history-topup-tab">
            @if($walletTopupRequests->isNotEmpty())
            <div class="table-responsive">
              <table class="table align-middle" style="font-size:0.88rem;">
                <thead style="background:#f5f5f7;">
                  <tr>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Ngày gửi</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Số tiền</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Ngân hàng</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Trạng thái</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Ghi chú</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($walletTopupRequests as $tr)
                  <tr style="border-color:#f0f0f0;">
                    <td style="padding:12px 14px;">{{ $tr->created_at->format('d/m/Y H:i') }}</td>
                    <td style="padding:12px 14px;" class="fw-bold text-success">+{{ number_format($tr->amount) }}đ</td>
                    <td style="padding:12px 14px;">{{ $tr->dest_bank_name ?: ($tr->bank_name ?: '—') }}</td>
                    <td style="padding:12px 14px;">
                      @if($tr->isPending())
                        <span class="badge rounded-pill" style="background:#fff3cd;color:#856404;">Chờ duyệt</span>
                      @elseif($tr->isApproved())
                        <span class="badge rounded-pill" style="background:#d1e7dd;color:#0a3622;">Đã duyệt</span>
                      @else
                        <span class="badge rounded-pill bg-danger text-white">Từ chối</span>
                      @endif
                    </td>
                    <td style="padding:12px 14px;" class="text-muted">{{ Str::limit($tr->admin_note, 30) ?: '—' }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
            <div class="text-center py-4 text-muted small">Không có yêu cầu nạp tiền nào.</div>
            @endif
          </div>

          <div class="tab-pane fade" id="history-withdraw" role="tabpanel" aria-labelledby="history-withdraw-tab">
            @if($walletWithdrawRequests->isNotEmpty())
            <div class="table-responsive">
              <table class="table align-middle" style="font-size:0.88rem;">
                <thead style="background:#f5f5f7;">
                  <tr>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Ngày gửi</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Số tiền rút</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Tài khoản nhận</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Trạng thái</th>
                    <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Minh chứng/Lý do</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($walletWithdrawRequests as $wr)
                  <tr style="border-color:#f0f0f0;">
                    <td style="padding:12px 14px;">{{ $wr->created_at->format('d/m/Y H:i') }}</td>
                    <td style="padding:12px 14px;" class="fw-bold text-danger">-{{ number_format($wr->amount) }}đ</td>
                    <td style="padding:12px 14px;">
                      @if($wr->bankAccount)
                        {{ $wr->bankAccount->bank_name }}<br>
                        <span class="small text-muted">{{ $wr->bankAccount->account_number }}</span>
                      @else
                        Tài khoản đã xoá
                      @endif
                    </td>
                    <td style="padding:12px 14px;">
                      @if($wr->isPending())
                        <span class="badge rounded-pill" style="background:#fff3cd;color:#856404;">Chờ duyệt</span>
                      @elseif($wr->isApproved())
                        <span class="badge rounded-pill" style="background:#d1e7dd;color:#0a3622;">Đã chuyển khoản</span>
                      @else
                        <span class="badge rounded-pill bg-danger text-white">Từ chối (Hoàn tiền)</span>
                      @endif
                    </td>
                    <td style="padding:12px 14px;">
                      @if($wr->isApproved() && $wr->proof_image)
                        <a href="{{ Storage::url($wr->proof_image) }}" target="_blank" class="text-success small fw-bold"><i class="bi bi-image"></i> Xem UNC</a>
                      @elseif($wr->isRejected())
                        <span class="text-muted small">{{ Str::limit($wr->admin_note, 30) ?: 'Không có' }}</span>
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
            <div class="text-center py-4 text-muted small">Không có yêu cầu rút tiền nào.</div>
            @endif
          </div>
        </div>

        {{-- Transaction History --}}
        <div>
          <h6 class="fw-bold mb-3"><i class="bi bi-list-ul me-2"></i>Lịch sử giao dịch</h6>
          @if($walletTransactions->isEmpty())
          <div class="text-center py-5 text-muted">
            <i class="bi bi-wallet2" style="font-size:2.5rem;color:#ddd;display:block;margin-bottom:10px;"></i>
            Chưa có giao dịch nào.
          </div>
          @else
          <div class="table-responsive">
            <table class="table align-middle" style="font-size:0.88rem;">
              <thead style="background:#f5f5f7;">
                <tr>
                  <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Thời gian</th>
                  <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Mô tả</th>
                  <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Số tiền</th>
                  <th style="padding:10px 14px;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:1px;color:#888;border:none;">Số dư sau</th>
                </tr>
              </thead>
              <tbody>
                @foreach($walletTransactions as $tx)
                <tr style="border-color:#f0f0f0;">
                  <td style="padding:12px 14px;" class="text-muted">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                  <td style="padding:12px 14px;">{{ $tx->description }}</td>
                  <td style="padding:12px 14px;" class="fw-bold {{ $tx->isCredit() ? 'text-success' : 'text-danger' }}">
                    {{ $tx->isCredit() ? '+' : '-' }}{{ number_format($tx->amount) }}đ
                  </td>
                  <td style="padding:12px 14px;" class="fw-semibold">{{ number_format($tx->balance_after) }}đ</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
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
});


function copyBankAccount(number, btn) {
  if (!navigator.clipboard) return;
  navigator.clipboard.writeText(number).then(function() {
    var orig = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check2"></i>';
    setTimeout(function() { btn.innerHTML = orig; }, 2000);
  });
}

function onBankSelectChange(sel, prefix) {
  var opt = sel.options[sel.selectedIndex];
  document.getElementById(prefix + '-bank-name').value = opt.getAttribute('data-name') || '';
}

function openAddBankModal() {
  document.getElementById('add-bank-id').value = '';
  document.getElementById('add-bank-name').value = '';
  document.getElementById('add-is-default').checked = false;
  document.getElementById('formAddBank').reset();
  new bootstrap.Modal(document.getElementById('modalAddBank')).show();
}

function openEditBankModal(id, bankName, bankId, accountNumber, accountName, isDefault) {
  var baseUrl = '{{ url("/my-account/bank-accounts") }}';
  document.getElementById('formEditBank').action = baseUrl + '/' + id;

  var sel = document.getElementById('edit-bank-id');
  sel.value = bankId;
  document.getElementById('edit-bank-name').value = bankName;
  document.getElementById('edit-account-number').value = accountNumber;
  document.getElementById('edit-account-name').value = accountName;
  document.getElementById('edit-is-default').checked = isDefault;

  new bootstrap.Modal(document.getElementById('modalEditBank')).show();
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

</script>
@endpush