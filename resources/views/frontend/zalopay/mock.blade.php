@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Mô phỏng Cổng Thanh toán ZaloPay</h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/ZaloPay_Logo.png" alt="ZaloPay" style="height: 60px;">
                    </div>
                    
                    <div class="alert alert-info border-0 shadow-sm">
                        <p class="mb-1"><strong>Mã đơn hàng:</strong> #{{ $orderId }}</p>
                        <p class="mb-1"><strong>Mã giao dịch:</strong> {{ $appTransId }}</p>
                        <p class="mb-0"><strong>Số tiền:</strong> <span class="text-danger fw-bold">{{ number_format($amount) }} VNĐ</span></p>
                    </div>

                    <p class="text-muted small mb-4">Đây là trang mô phỏng để kiểm thử luồng thanh toán tích hợp. Bạn có thể chọn kết quả thanh toán dưới đây.</p>

                    <form action="{{ route('zalopay.process_mock') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $orderId }}">
                        <input type="hidden" name="app_trans_id" value="{{ $appTransId }}">

                        <div class="d-grid gap-2">
                            <button type="submit" name="status" value="1" class="btn btn-success py-2">
                                <i class="fas fa-check-circle me-2"></i> Xác nhận Thanh toán Thành công
                            </button>
                            <button type="submit" name="status" value="2" class="btn btn-danger py-2">
                                <i class="fas fa-times-circle me-2"></i> Hủy / Thanh toán Thất bại
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">Hệ thống đang chạy chế độ Mock/Sandbox</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
