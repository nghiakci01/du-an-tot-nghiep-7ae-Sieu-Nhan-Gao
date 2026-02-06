@extends('layouts.admin')

@section('content')
  <div class="row">
    <!-- Welcome Banner (Optional) -->
    <div class="col-12">
      <div class="card welcome-banner bg-blue-800">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="p-4">
                <h2 class="text-white">Xin chào, Quản trị viên!</h2>
                <p class="text-white">
                  Đây là bảng điều khiển tổng quan tình hình kinh doanh của cửa hàng.
                </p>
              </div>
            </div>
            <div class="col-sm-6 text-center">
              <div class="img-welcome-banner">
                <img src="{{ asset('admin-assets/images/widget/welcome-banner.png') }}" alt="img" class="img-fluid" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-md-6 col-xxl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s bg-light-primary">
                <i class="ti ti-currency-dollar f-24"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">Tổng Doanh Thu</h6>
            </div>
          </div>
          <div class="bg-body p-3 mt-3 rounded">
            <div class="mt-3 row align-items-center">
              <div class="col-12">
                <h3 class="mb-1">{{ number_format($totalRevenue) }} VND</h3>
                <p class="text-primary mb-0">
                  <i class="ti ti-arrow-up-right"></i> Đơn hàng hoàn thành
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xxl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s bg-light-warning">
                <i class="ti ti-shopping-cart f-24"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">Tổng Đơn Hàng</h6>
            </div>
          </div>
          <div class="bg-body p-3 mt-3 rounded">
            <div class="mt-3 row align-items-center">
              <div class="col-12">
                <h3 class="mb-1">{{ number_format($totalOrders) }}</h3>
                <p class="text-warning mb-0">
                  <i class="ti ti-clipboard-list"></i> {{ $newOrders }} Đơn chờ xử lý
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xxl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s bg-light-success">
                <i class="ti ti-users f-24"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">Khách Hàng</h6>
            </div>
          </div>
          <div class="bg-body p-3 mt-3 rounded">
            <div class="mt-3 row align-items-center">
              <div class="col-12">
                <h3 class="mb-1">{{ number_format($totalCustomers) }}</h3>
                <p class="text-success mb-0">
                  <i class="ti ti-user-check"></i> Tổng tài khoản
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xxl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s bg-light-danger">
                <i class="ti ti-shirt f-24"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">Sản Phẩm</h6>
            </div>
          </div>
          <div class="bg-body p-3 mt-3 rounded">
            <div class="mt-3 row align-items-center">
              <div class="col-12">
                <h3 class="mb-1">{{ number_format($totalProducts) }}</h3>
                <p class="text-danger mb-0">
                  <i class="ti ti-alert-circle"></i> {{ $lowStockProducts }} Mẫu sắp hết hàng
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Đơn Hàng Gần Đây</h5>
        </div>
        <div class="card-body border-bottom pb-0">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Mã Đơn</th>
                  <th>Khách Hàng</th>
                  <th>Tổng Tiền</th>
                  <th>Trạng Thái</th>
                  <th>Ngày Đặt</th>
                  <th>Hành Động</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentOrders as $order)
                  <tr>
                    <td>#{{ $order->id }}</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1 ms-3">
                          <h6 class="mb-0">{{ $order->user ? $order->user->name : 'Khách lẻ' }}</h6>
                          <small class="text-muted">{{ $order->shipping_address }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ number_format($order->total_price) }} VND</td>
                    <td>
                      <span
                        class="badge {{ $order->status == 'COMPLETED' ? 'bg-success' : ($order->status == 'CANCELLED' ? 'bg-danger' : 'bg-warning') }}">
                        {{ $order->status }}
                      </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                      <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                        Xem Chi Tiết
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">Chưa có đơn hàng nào</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection