@extends('layouts.public')

@section('title', 'Xác thực Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body text-center p-5">
                    <div style="font-size: 64px; margin-bottom: 16px;">📧</div>
                    <h4 class="fw-bold mb-3">Xác thực địa chỉ Email</h4>

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i> Link xác thực mới đã được gửi đến email của bạn!
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-warning" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-muted mb-3">
                        Chúng tôi đã gửi link xác thực đến email <strong>{{ Auth::user()->email }}</strong>.
                        Vui lòng kiểm tra hộp thư (hoặc thư rác) và nhấn vào link để xác thực.
                    </p>

                    <p class="text-muted mb-4" style="font-size: 14px;">
                        Bạn cần xác thực email trước khi có thể đặt hàng và sử dụng đầy đủ tính năng.
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-redo"></i> Gửi lại email xác thực
                        </button>
                    </form>

                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="text-decoration-none">← Quay về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
