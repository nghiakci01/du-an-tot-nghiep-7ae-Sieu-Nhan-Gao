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
                  <i class="ti ti-alert-circle"></i> {{ $lowStockProducts }} Mẫu sắp hết hàng
                </p>
              </div>
            </div>
          </div>
        </div>
      </a>
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
            <!-- Xóa div cũ của ApexCharts, dùng canvas cho Chart.js -->
            <!-- <div id="revenue-chart"></div> -->
            <canvas id="revenueLineChart" style="max-height: 350px;"></canvas>
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

    <!-- Đoạn HTML của bảng Top Selling Products giữ nguyên -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5>Top 5 Sản Phẩm Bán Chạy</h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Đã Bán</th>
                    <th>Doanh Thu (Ước tính)</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($topProducts as $product)
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <img src="{{ asset('storage/' . $product->image) }}" alt="" class="img-fluid wid-40 rounded me-2"
                            style="height: 40px; object-fit: cover;">
                          <h6 class="mb-0">{{ $product->name }}</h6>
                        </div>
                      </td>
                      <td>{{ number_format($product->price) }} VND</td>
                      <td>{{ $product->total_sold }}</td>
                      <td>{{ number_format($product->price * $product->total_sold) }} VND</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center">Chưa có dữ liệu bán hàng</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Notifications Row -->
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 pb-0">
            <h5 class="mb-0 fw-bold"><i class="ti ti-bell me-2 text-primary"></i>Thông báo mới nhất</h5>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-link-primary">Xem tất cả</a>
          </div>
          <div class="card-body">
            <div class="list-group list-group-flush">
              @forelse($admin_notifications ?? [] as $notification)
                <a href="{{ route('admin.notifications.markAsRead', $notification->id) }}" 
                   class="list-group-item list-group-item-action border-0 mb-2 rounded p-3 {{ $notification->read_at ? 'bg-light' : 'bg-light-primary border-start border-primary border-4' }}">
                  <div class="d-flex w-100 justify-content-between align-items-start">
                    <div>
                      <h6 class="mb-1 fw-semibold text-dark">{{ $notification->data['message'] ?? 'Thông báo' }}</h6>
                      <small class="text-muted d-block mt-1">
                        <i class="ti ti-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                      </small>
                    </div>
                    @if(!$notification->read_at)
                      <span class="badge bg-primary rounded-pill px-2 py-1">Mới</span>
                    @endif
                  </div>
                </a>
              @empty
                <div class="text-center py-5">
                  <i class="ti ti-bell-off f-40 text-muted opacity-50"></i>
                  <p class="text-muted mb-0 mt-2">Tuyệt vời! Không có thông báo mới.</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
      
      <!-- You could add another card here for Balance/Quick Actions or leave it half-width -->
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
  
  <!-- Giữ lại ApexCharts cho Biểu đồ Trạng Thái (Donut) nếu muốn -->
  <script src="{{ asset('admin-assets/js/plugins/apexcharts.min.js') }}"></script>
  
  <!-- Thêm Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      
      // 1. Tải và Vẽ biểu đồ Line Chart bằng Chart.js cho Doanh Thu (Gọi API trực tiếp)
      const urlParams = new URLSearchParams(window.location.search);
      const startDate = urlParams.get('start_date');
      const endDate = urlParams.get('end_date');
      
      let apiUrl = '{{ route('admin.api.dashboard.revenue') }}?filter=month';
      if(startDate && endDate) {
          apiUrl = `{{ route('admin.api.dashboard.revenue') }}?start_date=${startDate}&end_date=${endDate}`;
      }

      fetch(apiUrl)
        .then(response => response.json())
        .then(res => {
            if(res.success && res.data && res.data.chart) {
                const ctx = document.getElementById('revenueLineChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: res.data.chart.labels,
                        datasets: [{
                            label: 'Doanh Thu (VND)',
                            data: res.data.chart.values,
                            borderColor: '#4680ff',
                            backgroundColor: 'rgba(70, 128, 255, 0.2)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4 // Làm mượt đường line
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return new Intl.NumberFormat('vi-VN', { notation: "compact", compactDisplay: "short" }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error loading chart data:', error));

      // 2. Order Status Chart (Dùng Data từ Backend truyền thẳng vào view qua $statusValues)
      var statusOptions = {
        series: @json($statusValues),
        chart: {
          type: 'donut',
          height: 350,
        },
        labels: @json($statusLabels),
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