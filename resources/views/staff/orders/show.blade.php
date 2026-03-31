@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="page-header py-3 bg-primary text-white mb-4 rounded shadow-sm">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-8">
                <h5 class="text-white mb-0"><i class="feather icon-package me-2"></i>Chi tiết #{{ $order->id }}</h5>
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('staff.orders.index') }}" class="btn btn-sm btn-light-primary rounded-pill"> Quay lại</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3">{{ session('error') }}</div>
    @endif

    <!-- Status Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            <span class="badge {{ $order->status_badge }} fs-6 px-3 py-2 rounded-pill mb-3 shadow-sm">
                {{ $order->status_text }}
            </span>
            
            <div class="row g-2">
                @if($order->status === \App\Models\Order::STATUS_CONFIRMED)
                    <div class="col-12">
                        <form action="{{ route('staff.orders.accept', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="feather icon-check me-2"></i> NHẬN ĐI GIAO NGAY
                            </button>
                        </form>
                    </div>
                @elseif($order->status === \App\Models\Order::STATUS_SHIPPED)
                    <div class="col-6">
                        <form action="{{ route('staff.orders.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm" onclick="return confirm('Xác nhận đã giao hàng thành công?')">
                                <i class="feather icon-check-circle me-1"></i> HOÀN THÀNH
                            </button>
                        </form>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-danger w-100 py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#failModal">
                            <i class="feather icon-x-circle me-1"></i> THẤT BẠI
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Map Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-bold text-primary"><i class="feather icon-map me-2"></i>Bản đồ đường đi</h6>
        </div>
        <div class="card-body p-0">
            <div id="map" style="height: 300px; width: 100%;"></div>
            <div class="p-3 bg-light fs-6 fw-bold text-center border-top">
                <i class="feather icon-map-pin text-danger me-2"></i>
                {{ $order->shipping_address }}
            </div>
        </div>
    </div>

    <!-- Customer Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-bold text-success"><i class="feather icon-user me-2"></i>Thông tin người nhận</h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="text-muted small d-block">Tên khách hàng:</label>
                <span class="fs-6 fw-bold text-dark">{{ $order->user ? $order->user->name : ($order->name ?? 'N/A') }}</span>
            </div>
            <div class="mb-3">
                <label class="text-muted small d-block">Số điện thoại:</label>
                <a href="tel:{{ $order->phone }}" class="fs-6 fw-bold text-primary text-decoration-none">
                    <i class="feather icon-phone-call me-1"></i> {{ $order->phone ?? 'N/A' }}
                </a>
            </div>
            @if($order->note)
            <div class="p-3 bg-light rounded text-danger small">
                <i class="feather icon-file-text me-2"></i> <strong>Ghi chú đơn:</strong> <em>"{{ $order->note }}"</em>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Items Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-bold text-info"><i class="feather icon-shopping-cart me-2"></i>Chi tiết hàng hóa</h6>
        </div>
        <div class="card-body p-2">
            @foreach($order->items as $item)
            <div class="d-flex align-items-center p-2 mb-2 border-bottom last-child-no-border">
                <img src="{{ $item->product->image_url ?? asset('assets/images/default-product.png') }}" class="rounded me-3 border" width="45" height="45" style="object-fit:cover;">
                <div class="flex-grow-1">
                    <h6 class="small mb-0 fw-bold">{{ $item->product->name }}</h6>
                    <small class="text-muted">x {{ $item->quantity }} • {{ number_format($item->price) }}đ</small>
                </div>
                <div class="text-end text-dark fw-bold small">
                    {{ number_format($item->total) }}đ
                </div>
            </div>
            @endforeach
            <div class="p-3 bg-light rounded mt-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small">Tiền hàng:</span>
                    <span class="small">{{ number_format($order->total_price) }}đ</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small">Phí ship:</span>
                    <span class="small">+ {{ number_format($order->shipping_fee) }}đ</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small">Giảm giá:</span>
                    <span class="small text-success">- {{ number_format($order->discount_amount) }}đ</span>
                </div>
                @endif
                <hr class="my-2 opacity-25">
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="fw-bold text-dark">TỔNG THU HỘ (COD):</span>
                    <span class="fw-bold fs-5 text-danger">{{ number_format($order->final_total) }}đ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fail Modal (same as index) -->
<div class="modal fade" id="failModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-3">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('staff.orders.fail', $order->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white py-3">
                    <h5 class="modal-title text-white">Lý do giao thất bại</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <textarea name="delivery_note" class="form-control rounded-3" rows="4" placeholder="VD: Khách hàng hẹn lại, không liên lạc được..." required></textarea>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const address = "{{ $order->address }}, {{ $order->province }}";
    
    // Initialize Map with a default view (Vietnam center)
    var map = L.map('map').setView([14.0583, 108.2772], 6);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Geocoding using Nominatim (free)
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = data[0].lat;
                const lon = data[0].lon;
                map.setView([lat, lon], 15);
                L.marker([lat, lon]).addTo(map)
                    .bindPopup("{{ $order->user ? $order->user->name : ($order->name ?? 'Khách') }}<br>{{ $order->address }}, {{ $order->province }}")
                    .openPopup();
                
                // Add "Open in Google Maps" link for convenience
                L.popup()
                    .setLatLng([lat, lon])
                    .setContent(`<b>{{ $order->address }}, {{ $order->province }}</b><br><a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lon}" target="_blank" class="btn btn-sm btn-primary text-white mt-1">Dẫn đường (G-Maps)</a>`)
                    .openOn(map);
            } else {
                console.warn('Address not found on map:', address);
            }
        })
        .catch(error => {
            console.error('Error fetching map data:', error);
        });
});
</script>
<style>
    .last-child-no-border:last-child { border-bottom: none !important; }
</style>
@endsection
