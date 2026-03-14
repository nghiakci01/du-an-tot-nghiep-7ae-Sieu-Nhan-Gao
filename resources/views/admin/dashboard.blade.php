@extends('layouts.admin')

@section('content')
  <div class="row">
    <!-- Filter & Export Toolbar -->
    <div class="col-12 mb-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <form action="{{ route('admin.dashboard') }}" method="GET" class="row align-items-end g-3">
            <div class="col-md-3">
              <label class="form-label fw-bold">Từ ngày</label>
              <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold">Đến ngày</label>
              <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-filter me-1"></i> Lọc dữ liệu
              </button>
            </div>
            <div class="col-md-4 text-md-end">
              <div class="btn-group">
                <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ti ti-download me-1"></i> Xuất Báo Cáo
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="{{ route('admin.reports.orders.excel', request()->all()) }}">
                    <i class="ti ti-file-spreadsheet me-2"></i> Xuất Excel Đơn hàng
                  </a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.reports.revenue.pdf', request()->all()) }}">
                    <i class="ti ti-file-description me-2"></i> Xuất PDF Doanh thu
                  </a></li>
                </ul>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Welcome Banner (Optional) -->

    <!-- Stats Cards -->
    <div class="col-md-6 col-xxl-3">
      <a href="{{ route('admin.orders.index') }}" class="card dashboard-card">
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
                  <i class="ti ti-arrow-up-right"></i> Đơn hoàn thành
                </p>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-6 col-xxl-3">
      <div class="card dashboard-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s bg-light-success">
                <i class="ti ti-chart-arrows-vertical f-24"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">Lợi Nhuận Ước Tính</h6>
            </div>
          </div>
          <div class="bg-body p-3 mt-3 rounded">
            <div class="mt-3 row align-items-center">
              <div class="col-12">
                <h3 class="mb-1">{{ number_format($totalProfit) }} VND</h3>
                <p class="text-success mb-0">
                  <i class="ti ti-trending-up"></i> Tỉ suất: {{ $totalRevenue > 0 ? number_format(($totalProfit / $totalRevenue) * 100, 1) : 0 }}%
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xxl-3">
      <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="card dashboard-card">
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
      </a>
    </div>

    <div class="col-md-6 col-xxl-3">
      <a href="{{ route('admin.users.index') }}" class="card dashboard-card">
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
      </a>
    </div>

    <div class="col-md-6 col-xxl-3">
      <a href="{{ route('admin.products.index') }}" class="card dashboard-card">
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
                   Sản phẩm đang kinh doanh
                </p>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- VTON Summary Row -->
    <div class="col-md-6 col-xxl-3">
      <div class="card dashboard-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s bg-light-info">
                <i class="ti ti-magic f-24"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">Lượt Thử Đồ AI</h6>
            </div>
          </div>
          <div class="bg-body p-3 mt-3 rounded">
            <div class="mt-3 row align-items-center">
              <div class="col-12">
                <h3 class="mb-1">{{ number_format($totalVtonHistories) }}</h3>
                <p class="text-info mb-0">
                  <i class="ti ti-history"></i> Tổng cộng mọi thời đại
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xxl-3">
      <div class="card dashboard-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s bg-light-primary">
                <i class="ti ti-user-check f-24"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-0">Sản phẩm có AI</h6>
            </div>
          </div>
          <div class="bg-body p-3 mt-3 rounded">
            <div class="mt-3 row align-items-center">
              <div class="col-12">
                <h3 class="mb-1">{{ number_format($vtonEnabledProducts) }}</h3>
                <p class="text-primary mb-0">
                  <i class="ti ti-check"></i> Hỗ trợ Thử Đồ AI
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
      <!-- Revenue Chart -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5>Doanh Thu 30 Ngày Gần Nhất</h5>
          </div>
          <div class="card-body">
            <div id="revenue-chart"></div>
          </div>
        </div>
      </div>

      <!-- Order Status Chart -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h5>Trạng Thái Đơn Hàng</h5>
          </div>
          <div class="card-body">
            <div id="order-status-chart"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Notifications & VTON Trends Row -->
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
           <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 pb-0">
            <h5 class="mb-0 fw-bold"><i class="ti ti-award me-2 text-info"></i>Top 5 Sản phẩm Thử đồ AI nhiều nhất</h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Sản Phẩm</th>
                    <th class="text-center">Số lượt thử</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($vtonStats['top_vton_products'] as $product)
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid wid-40 rounded me-3 shadow-sm"
                            style="height: 48px; width: 48px; object-fit: cover;">
                          <div>
                            <h6 class="mb-0 text-truncate" style="max-width: 250px;">{{ $product->name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-light-info text-info fw-bold px-3 py-2">{{ number_format($product->try_on_count) }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="2" class="text-center py-4 text-muted">Chưa có dữ liệu thử đồ</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-header bg-transparent border-bottom-0 pb-0">
            <h5 class="mb-0 fw-bold"><i class="ti ti-chart-funnel me-2 text-warning"></i>Phễu Chuyển Đổi (30 ngày)</h5>
          </div>
          <div class="card-body">
            @if(isset($funnelStats))
            <div class="row g-3 mb-3">
              <div class="col-6">
                <div class="p-3 rounded bg-light-danger text-center">
                  <h3 class="mb-1 text-danger">{{ $funnelStats['abandoned_carts'] }}</h3>
                  <small class="text-muted">Giỏ bị bỏ rơi</small>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded bg-light-success text-center">
                  <h3 class="mb-1 text-success">{{ $funnelStats['recovered_carts'] }}</h3>
                  <small class="text-muted">Giỏ phục hồi</small>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded bg-light-primary text-center">
                  <h3 class="mb-1 text-primary">{{ $funnelStats['cart_to_order_rate'] }}%</h3>
                  <small class="text-muted">Tỷ lệ chuyển đổi</small>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded bg-light-warning text-center">
                  <h3 class="mb-1 text-warning">{{ number_format($funnelStats['avg_order_value']) }}đ</h3>
                  <small class="text-muted">Giá trị TB / đơn</small>
                </div>
              </div>
            </div>
            <div class="p-3 rounded" style="background: #fff3e0;">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <small class="text-muted">Doanh thu tiềm năng bị mất</small>
                  <h5 class="mb-0 text-danger fw-bold">{{ number_format($funnelStats['abandoned_value']) }}đ</h5>
                </div>
                <i class="ti ti-alert-triangle f-28 text-warning"></i>
              </div>
            </div>
            @else
            <div class="text-center py-4 text-muted">
              <i class="ti ti-chart-funnel f-40 opacity-50"></i>
              <p class="mt-2 mb-0">Chưa có dữ liệu chuyển đổi.</p>
            </div>
            @endif
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
                      <span class="badge {{ $order->status_badge }}">
                        {{ $order->status_text }}
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

@section('scripts')
  <style>
    .dashboard-card {
      text-decoration: none;
      transition: all 0.3s ease;
      display: block;
    }
    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .dashboard-card .card-body {
      color: inherit;
    }
  </style>
  <!-- ApexChart -->
  <script src="{{ asset('admin-assets/js/plugins/apexcharts.min.js') }}"></script>
  
  <script id="revenue-values-data" type="application/json">@json($revenueValues)</script>
  <script id="revenue-labels-data" type="application/json">@json($revenueLabels)</script>
  <script id="status-values-data" type="application/json">@json($statusValues)</script>
  <script id="status-labels-data" type="application/json">@json($statusLabels)</script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Revenue Chart
      var revenueOptions = {
        series: [{
          name: 'Doanh Thu',
          data: JSON.parse(document.getElementById('revenue-values-data').textContent)
        }],
        chart: {
          type: 'area', // or line, bar
          height: 350,
          toolbar: {
            show: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          categories: JSON.parse(document.getElementById('revenue-labels-data').textContent),
        },
        yaxis: {
          labels: {
            formatter: function (value) {
              return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (value) {
              return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
            }
          }
        },
        colors: ['#4680ff']
      };

      var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
      revenueChart.render();

      // 2. Order Status Chart (Dùng Data từ Backend truyền thẳng vào view qua $statusValues)
      var statusOptions = {
        series: JSON.parse(document.getElementById('status-values-data').textContent),
        chart: {
          type: 'donut',
          height: 350,
        },
        labels: JSON.parse(document.getElementById('status-labels-data').textContent),
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        colors: ['#ffc107', '#4680ff', '#2ca87f', '#dc3545', '#6c757d']
      };

      var statusChart = new ApexCharts(document.querySelector("#order-status-chart"), statusOptions);
      statusChart.render();
    });
  </script>
@endsection