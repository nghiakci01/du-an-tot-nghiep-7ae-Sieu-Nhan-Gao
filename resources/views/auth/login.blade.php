@extends('auth.layouts.auth')

@section('title', 'Đăng nhập')

@section('content')
    <div class="card-front">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="center-wrap">
                <div class="section text-center">
                    <h4 class="mb-4 pb-3">Đăng nhập</h4>

                    <div class="form-group">
                        <input type="email" name="email"
                               class="form-style @error('email') is-invalid @enderror"
                               placeholder="Nhập Email"
                               value="{{ old('email') }}" required autofocus>
                        <i class="input-icon uil uil-at"></i>
                    </div>
                    @error('email')
                        <span class="invalid-feedback d-block mb-3">{{ $message }}</span>
                    @enderror

                    <div class="form-group mt-2">
                        <input type="password" name="password"
                               class="form-style @error('password') is-invalid @enderror"
                               placeholder="Nhập mật khẩu" required>
                        <i class="input-icon uil uil-lock-alt"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block mb-3">{{ $message }}</span>
                    @enderror

                    <button type="submit" class="btn mt-4">Đăng nhập</button>

                    <p class="mb-0 mt-4 text-center">
                        <a href="{{ route('password.request') }}" class="link">Bạn quên mật khẩu?</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
@endsection