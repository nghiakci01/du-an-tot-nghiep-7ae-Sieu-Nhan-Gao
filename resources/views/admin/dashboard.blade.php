@extends('layouts.admin')

@section('content')
  <div class="row">
    <!-- Filter & Export Toolbar -->
    <div class="col-12 mb-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <form action="{{ route('admin.dashboard') }}" method="GET" id="filter-form" class="row align-items-end g-3">
            <div class="col-md-2">
              <label class="form-label fw-bold">Khoảng thời gian</label>
              <select name="preset" id="preset-select" class="form-select">
                <option value="today" {{ (isset($preset) && $preset == 'today') ? 'selected' : '' }}>Hôm nay</option>
                <option value="last_7_days" {{ (isset($preset) && $preset == 'last_7_days') ? 'selected' : '' }}>7 ngày qua</option>
                <option value="this_week" {{ (isset($preset) && $preset == 'this_week') ? 'selected' : '' }}>Tuần này</option>
                <option value="this_month" {{ (isset($preset) && $preset == 'this_month') ? 'selected' : '' }}>Tháng này</option>
                <option value="last_30_days" {{ (!isset($preset) || $preset == 'last_30_days') ? 'selected' : '' }}>30 ngày qua</option>
                <option value="this_quarter" {{ (isset($preset) && $preset == 'this_quarter') ? 'selected' : '' }}>Quý này</option>
                <option value="this_year" {{ (isset($preset) && $preset == 'this_year') ? 'selected' : '' }}>Năm nay</option>
                <option value="custom" {{ (isset($preset) && $preset == 'custom') ? 'selected' : '' }}>Tùy chỉnh</option>
              </select>
            </div>
            <div class="col-md-2 custom-date-group" style="{{ (isset($preset) && $preset != 'custom') ? 'display: none;' : '' }}">
              <label class="form-label fw-bold">Từ ngày</label>
              <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-2 custom-date-group" style="{{ (isset($preset) && $preset != 'custom') ? 'display: none;' : '' }}">
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
                  <li><a class="dropdown-item" href="{{ route('admin.reports.orders.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}">
                    <i class="ti ti-file-spreadsheet me-2"></i> Xuất Excel Đơn hàng
                  </a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.reports.revenue.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}">
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



    <!-- Charts Row -->
    <div class="row">
      <!-- Revenue Chart -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5>Kết quả kinh doanh 6 tháng đầu năm</h5>
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

    <!-- Recent Notifications & Funnel Trends Row -->
    <div class="row">
      <div class="col-lg-12 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-header bg-transparent border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="ti ti-chart-funnel me-2 text-warning"></i>Phễu Chuyển Đổi</h5>
            <span class="badge bg-light-secondary text-muted">{{ $funnelStats['start_date'] }} - {{ $funnelStats['end_date'] }}</span>
          </div>
          <div class="card-body">
            @if(isset($funnelStats) && $funnelStats['funnel_steps']['step1_add_to_cart'] > 0)
            <div class="row align-items-center">
              <div class="col-md-8">
                <div id="funnel-chart" style="min-height: 300px;"></div>
              </div>
              <div class="col-md-4">
                <div class="row g-3">
                  <div class="col-12">
                    <div class="p-3 rounded border-start border-primary border-4 bg-light-primary shadow-none mb-3">
                      <small class="text-muted d-block mb-1">Tỷ lệ chuyển đổi</small>
                      <h4 class="mb-0 text-primary fw-bold">{{ $funnelStats['cart_to_order_rate'] }}%</h4>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="p-3 rounded border-start border-danger border-4 bg-light-danger shadow-none mb-3">
                      <small class="text-muted d-block mb-1">Doanh thu tiềm năng bị mất</small>
                      <h4 class="mb-0 text-danger fw-bold">{{ number_format($funnelStats['abandoned_value']) }}đ</h4>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="p-3 rounded border-start border-warning border-4 bg-light-warning shadow-none">
                      <small class="text-muted d-block mb-1">Giá trị trung bình / đơn</small>
                      <h4 class="mb-0 text-warning fw-bold">{{ number_format($funnelStats['avg_order_value']) }}đ</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @else
            <div class="text-center py-5 text-muted">
              <i class="ti ti-chart-funnel f-40 opacity-50"></i>
              <p class="mt-2 mb-0">Chưa có đủ dữ liệu để tạo phễu chuyển đổi.</p>
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
                @if(count($recentOrders) > 0)
                  @foreach($recentOrders as $order)
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
                  @endforeach
                @else
                  <tr>
                    <td colspan="6" class="text-center">Chưa có đơn hàng nào</td>
                  </tr>
                @endif
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
  <script id="half-year-data" type="application/json">@json($halfYearChart)</script>
  <script id="funnel-data" type="application/json">@json($funnelStats['funnel_steps'])</script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const presetSelect = document.getElementById('preset-select');
      const customGroups = document.querySelectorAll('.custom-date-group');
      
      presetSelect.addEventListener('change', function() {
          if (this.value === 'custom') {
              customGroups.forEach(el => el.style.display = 'block');
          } else {
              document.getElementById('filter-form').submit();
          }
      });

      // Business Results (Mixed Chart: Bar & Line)
      const halfYearData = JSON.parse(document.getElementById('half-year-data').textContent);
      
      var revenueOptions = {
        series: [{
          name: 'Lượng hàng bán ra',
          type: 'column',
          data: halfYearData.quantities
        }, {
          name: 'Doanh thu',
          type: 'line',
          data: halfYearData.revenues
        }],
        chart: {
          height: 350,
          type: 'line',
          stacked: false,
          toolbar: {
            show: false
          }
        },
        stroke: {
          width: [0, 4],
          curve: 'smooth'
        },
        plotOptions: {
          bar: {
            columnWidth: '50%'
          }
        },
        fill: {
          opacity: [0.85, 1],
        },
        labels: halfYearData.labels,
        markers: {
          size: 0
        },
        xaxis: {
          type: 'category'
        },
        yaxis: [
          {
            title: {
              text: 'Lượng hàng bán ra',
            },
            labels: {
                formatter: function (val) {
                    return Math.floor(val);
                }
            }
          },
          {
            opposite: true,
            title: {
              text: 'Doanh thu (VND)',
            },
            labels: {
              formatter: function (val) {
                return new Intl.NumberFormat('vi-VN', { 
                  notation: "compact", 
                  compactDisplay: "short" 
                }).format(val) + "đ";
              }
            }
          }
        ],
        tooltip: {
          shared: true,
          intersect: false,
          y: {
            formatter: function (y) {
              if (typeof y !== "undefined") {
                return new Intl.NumberFormat('vi-VN').format(y);
              }
              return y;
            }
          }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
        },
        colors: ['#4680ff', '#f44336']
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

      // 3. Funnel Chart
      const funnelData = JSON.parse(document.getElementById('funnel-data').textContent);
      var funnelOptions = {
        series: [
          {
            name: "Lượt người",
            data: [
              funnelData.step1_add_to_cart,
              funnelData.step2_checkout,
              funnelData.step3_purchase
            ],
          },
        ],
        chart: {
          type: 'bar',
          height: 300,
        },
        plotOptions: {
          bar: {
            borderRadius: 0,
            horizontal: true,
            distribute: true,
            barHeight: '80%',
            isFunnel: true,
          },
        },
        colors: [
          '#4680ff',
          '#ffc107',
          '#2ca87f',
        ],
        dataLabels: {
          enabled: true,
          formatter: function (val, opt) {
            return opt.w.globals.labels[opt.dataPointIndex] + ': ' + val;
          },
          dropShadow: {
            enabled: true,
          },
        },
        title: {
          text: '',
          align: 'middle',
        },
        xaxis: {
          categories: ['Thêm vào giỏ', 'Bắt đầu Checkout', 'Mua hàng xong'],
        },
        legend: {
          show: false,
        },
      };

      var funnelChart = new ApexCharts(document.querySelector("#funnel-chart"), funnelOptions);
      funnelChart.render();
    });
  </script>
@endsection