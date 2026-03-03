@extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng #' . $order->id)

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="page-header-title">
                    <h5 class="m-b-10">Chi tiết Đơn hàng #{{ $order->id }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item"><a href="#!">Chi tiết</a></li>
                </ul>
            </div>
            <div class="col-md-6 text-end">
                @php
                    $phone = $order->user ? ($order->user->phone ?? 'N/A') : ($order->phone ? $order->phone : 'N/A');
                    $name = $order->user ? $order->user->name : ($order->name ? $order->name : 'Khách vãng lai');
                    $total = number_format($order->final_total ?? $order->total_price, 0, ',', '.') . " VND";
                    $address = $order->shipping_address;
                    $status = $order->status_text;

                    $qrContent = "Mã Đơn: #" . $order->id . "\nKhách: " . $name . "\nSĐT: " . $phone . "\nĐịa Chỉ: " . $address . "\nTổng Tiền: " . $total . "\nTrạng Thái: " . $status;
                    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($qrContent);
                    $barcodeUrl = "https://barcode.tec-it.com/barcode.ashx?data=" . $order->id . "&code=Code128&translate-esc=true&dpi=96";
                @endphp
                <div class="d-flex justify-content-end align-items-center">
                    <div class="me-3 text-center border p-1 bg-white rounded shadow-sm d-inline-block">
                        <img src="{{ $barcodeUrl }}" alt="Mã Vạch Đơn Hàng" style="height: 50px; padding: 2px;">
                        <div style="font-size: 10px; color: #555; margin-top: 2px;">Barcode</div>
                    </div>
                    <div class="me-3 text-center border p-1 bg-white rounded shadow-sm d-inline-block">
                        <img src="{{ $qrCodeUrl }}" alt="Mã QR Đơn Hàng" style="width: 50px; height: 50px;">
                        <div style="font-size: 10px; color: #555; margin-top: 2px;">QR Code</div>
                    </div>
                    <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-primary">
                        <i class="feather icon-printer"></i> In Hóa Đơn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Products -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-dark"><i class="feather icon-shopping-cart text-primary me-2"></i>Sản phẩm trong đơn</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="{{ $item->product->image_url ?? asset('assets/images/default-product.png') }}" class="rounded border" width="50" height="50" style="object-fit:cover;" alt="Product">
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $item->product->name }}</strong>
                                            @if($item->variant)
                                                <small class="text-muted">Phân loại: {{ $item->variant->name ?? ($item->variant->size . '/' . $item->variant->color) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end align-middle">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="text-center align-middle">{{ $item->quantity }}</td>
                                <td class="text-end align-middle fw-bold">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="3" class="text-end py-2">Tổng tiền hàng:</th>
                                <th class="text-end py-2">{{ number_format($order->total_price, 0, ',', '.') }}đ</th>
                            </tr>
                            @if($order->shipping_fee > 0)
                            <tr>
                                <th colspan="3" class="text-end py-2">Phí vận chuyển 
                                    @if($order->shipping_provider)
                                    <small class="text-muted">({{ $order->shipping_provider }})</small>
                                    @endif
                                :</th>
                                <th class="text-end py-2">+ {{ number_format($order->shipping_fee, 0, ',', '.') }}đ</th>
                            </tr>
                            @endif
                            @if($order->discount_amount > 0)
                            <tr>
                                <th colspan="3" class="text-end py-2">Giảm giá:</th>
                                <th class="text-end text-success py-2">- {{ number_format($order->discount_amount, 0, ',', '.') }}đ</th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="3" class="text-end h5 mb-0 text-primary">TỔNG CẦN THANH TOÁN:</th>
                                <th class="text-end h5 mb-0 text-danger fw-bold">{{ number_format($order->final_total ?? $order->total_price, 0, ',', '.') }}đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-dark"><i class="feather icon-clock text-info me-2"></i>Lịch sử trạng thái đơn hàng</h5>
            </div>
            <div class="card-body">
                @if($order->histories->count() > 0)
                    <div class="timeline ml-2">
                        @foreach($order->histories as $history)
                            <div class="border-start border-2 border-primary ps-3 mb-4 position-relative">
                                <span class="position-absolute top-0 start-0 translate-middle p-2 bg-primary border border-light rounded-circle"></span>
                                <div class="mb-1">
                                    <strong class="text-dark">{{ $history->new_status }}</strong>
                                    <small class="text-muted ms-2"><i class="feather icon-calendar"></i> {{ $history->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="text-muted small mb-1">
                                    <i class="feather icon-user"></i> Người cập nhật: <strong>{{ $history->user ? $history->user->name : 'Hệ thống tự động' }}</strong>
                                </div>
                                @if($history->note)
                                    <div class="bg-light p-2 rounded small mt-2 border-start border-warning border-3">
                                        <em>"{!! nl2br(e($history->note)) !!}"</em>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Chưa có lịch sử trạng thái.</p>
                @endif
            </div>
        </div>
    </div>


    <!-- Right Column: Info -->
    <div class="col-md-4">
        <!-- Status Update Card -->
        <div class="card shadow-sm mb-4 border-top border-primary border-3">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-dark">Trạng thái hiện tại</h5>
            </div>
            <div class="card-body text-center">
                <h4 class="mb-3">
                    <span class="badge {{ $order->status_badge }} px-3 py-2 fs-6">{{ $order->status_text }}</span>
                </h4>
                
                @if($order->canTransitionTo($order->status)) 
                @php $allowed = $order->getAllowedTransitions(); @endphp
                @if(count($allowed) > 0)
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-4 text-start bg-light p-3 rounded">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chuyển sang trạng thái:</label>
                        <select name="status" class="form-select border-primary">
                            <option value="">-- Chọn thao tác --</option>
                            @foreach($allowed as $status)
                                <option value="{{ $status }}">
                                    @switch($status)
                                        @case(\App\Models\Order::STATUS_CONFIRMED) 🟢 Duyệt & Đã xác nhận @break
                                        @case(\App\Models\Order::STATUS_SHIPPED) 🚚 Giao cho Vận chuyển @break
                                        @case(\App\Models\Order::STATUS_COMPLETED) ✅ Giao thành công (Hoàn thành) @break
                                        @case(\App\Models\Order::STATUS_CANCELLED) ❌ Hủy đơn này @break
                                        @case(\App\Models\Order::STATUS_FAILED) ⚠️ Giao thất bại @break
                                        @case(\App\Models\Order::STATUS_RETURNED) 🔄 Khách trả hàng @break
                                        @default {{ $status }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú (Tùy chọn):</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Nhập lý do hoặc chú thích..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="feather icon-check-circle"></i> Cập nhật ngay</button>
                </form>
                @endif
                @endif
            </div>
        </div>

        <!-- Customer Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="feather icon-user text-success me-2"></i>Khách hàng</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><i class="feather icon-user me-2 text-muted"></i> <strong>{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</strong> 
                    @if($order->user) <span class="badge bg-success ms-1">Đã đăng ký</span> @endif
                </p>
                <p class="mb-2"><i class="feather icon-phone me-2 text-muted"></i> {{ $order->user ? ($order->user->phone ?? 'N/A') : ($order->phone ?? 'N/A') }}</p>
                <p class="mb-0"><i class="feather icon-mail me-2 text-muted"></i> {{ $order->user ? $order->user->email : ($order->email ?? 'N/A') }}</p>
            </div>
        </div>

        <!-- Shipping & Payment Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="feather icon-truck text-warning me-2"></i>Giao hàng & Thanh toán</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="mb-1 text-muted small">ĐỊA CHỈ NHẬN HÀNG</p>
                    <p class="fw-bold mb-0">{{ $order->name ?? ($order->user->name ?? '') }}</p>
                    <p class="mb-0">{{ $order->shipping_address }}</p>
                </div>
                <hr>
                <div class="mb-3">
                    <p class="mb-1 text-muted small">PHƯƠNG THỨC THANH TOÁN</p>
                    <p class="mb-0">
                        @if($order->payment_method == 'COD')
                            <span class="badge bg-secondary"><i class="feather icon-dollar-sign"></i> Thanh toán khi nhận hàng (COD)</span>
                        @elseif($order->payment_method == 'BANK_TRANSFER')
                            <span class="badge bg-info"><i class="feather icon-credit-card"></i> Chuyển khoản ngân hàng</span>
                        @else
                            <span class="badge bg-dark">{{ $order->payment_method }}</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <p class="mb-1 text-muted small">TRẠNG THÁI THANH TOÁN</p>
                    <p class="mb-0">
                        @if($order->payment_status == 'PAID')
                            <span class="badge bg-success"><i class="feather icon-check"></i> Đã thanh toán</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="feather icon-clock"></i> Chưa thanh toán</span>
                        @endif
                    </p>
                </div>
                @if($order->shipping_service_name)
                <div class="mb-0">
                    <p class="mb-1 text-muted small">ĐƠN VỊ VẬN CHUYỂN</p>
                    <p class="mb-0 fw-bold">{{ $order->shipping_service_name }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Note Card -->
        @if($order->note)
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="feather icon-file-text text-secondary me-2"></i>Ghi chú của khách</h5>
            </div>
            <div class="card-body bg-light text-danger">
                <em>"{{ $order->note }}"</em>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
