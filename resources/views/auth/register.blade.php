@extends('auth.layouts.auth')

@section('title', 'Đăng ký')

@section('content')
    <div class="card-back">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="center-wrap">
                <div class="section text-center">
                    <h4 class="mb-4 pb-3">Đăng ký</h4>

                    <div class="form-group">
                        <input type="text" name="name" class="form-style @error('name') is-invalid @enderror"
                            autocomplete="name" placeholder="Họ và tên" value="{{ old('name') }}" required>
                        <i class="input-icon uil uil-user"></i>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mt-2">
                        <input type="email" name="email" class="form-style @error('email') is-invalid @enderror"
                            placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
                        <i class="input-icon uil uil-at"></i>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mt-2">
                        <input type="password" name="password" class="form-style @error('password') is-invalid @enderror"
                            placeholder="Mật khẩu" required autocomplete="new-password">
                        <i class="input-icon uil uil-lock-alt"></i>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mt-2">
                        <input type="password"  id="password-confirm" name="password_confirmation"  class="form-style"
                            placeholder="Xác nhận mật khẩu"required autocomplete="new-password">
                        <i class="input-icon uil uil-lock-alt"></i>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn mt-4">Đăng ký</button>
                </div>
            </div>
        </form>
    </div>
@endsection
