@extends('layouts.app')

@section('title', 'Tra cứu đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Tra cứu đơn hàng</h2>
                <p class="text-muted">Nhập thông tin bên dưới để theo dõi trạng thái đơn hàng của bạn.</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger border-0">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('order-tracking.search') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Mã đơn hàng <span class="text-danger">*</span></label>
                            <input type="text" name="order_id" id="order_id" class="form-control" 
                                   placeholder="VD: 123456" value="{{ old('order_id') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="contact" class="form-label">Email hoặc Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="contact" id="contact" class="form-control" 
                                   placeholder="Email hoặc SĐT bạn đã dùng khi đặt hàng" value="{{ old('contact') }}" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-search me-2"></i> Tra cứu ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-muted small">Cần hỗ trợ? Liên hệ hotline: <a href="tel:0123456789" class="text-decoration-none fw-bold">0123.456.789</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
