@extends('layouts.public')

@push('styles')
    <style>
        .auth-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px 0;
        }

        .auth-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 30px;
        }

        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 15px 0;
            font-size: 16px;
            font-weight: 600;
            color: #666;
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .auth-tab.active {
            color: #333;
        }

        .auth-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: #f68b1e;
        }

        .auth-tab:hover {
            color: #333;
        }

        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
        }

        .auth-input {
            width: 100%;
            padding: 15px;
            border: none;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            margin-bottom: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        .auth-input:focus {
            border-bottom-color: #f68b1e;
        }

        .auth-input::placeholder {
            color: #999;
        }

        .auth-btn {
            display: inline-block;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .auth-btn-login {
            background: #333;
            color: #fff;
        }

        .auth-btn-login:hover {
            background: #000;
        }

        .auth-btn-register {
            background: #f68b1e;
            color: #fff;
            border: 2px solid #f68b1e;
        }

        .auth-btn-register:hover {
            background: #e07b0e;
            border-color: #e07b0e;
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3b5998;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #3b5998;
        }

        .social-divider {
            text-align: center;
            margin: 25px 0;
            color: #666;
            font-size: 14px;
        }

        .social-buttons {
            display: flex;
            gap: 15px;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 15px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            border: 2px solid #333;
            transition: all 0.3s;
        }

        .social-btn i {
            margin-right: 8px;
            font-size: 16px;
        }

        .social-btn-facebook {
            background: #333;
            color: #fff;
            border-color: #333;
        }

        .social-btn-facebook:hover {
            background: #000;
            color: #fff;
        }

        .social-btn-google {
            background: #fff;
            color: #333;
            border-color: #333;
        }

        .social-btn-google:hover {
            background: #f5f5f5;
            color: #333;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .input-group {
            margin-bottom: 15px;
        }

        @media (max-width: 576px) {
            .social-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li>Account</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!-- customer login start -->
    <div class="customer_login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="auth-container">
                        <!-- Tabs -->
                        <div class="auth-tabs">
                            <button type="button"
                                class="auth-tab {{ !request()->has('tab') || request('tab') == 'login' ? 'active' : '' }}"
                                data-tab="login">
                                Login
                            </button>
                            <button type="button" class="auth-tab {{ request('tab') == 'register' ? 'active' : '' }}"
                                data-tab="register">
                                Register
                            </button>
                        </div>

                        <!-- Login Form -->
                        <form action="{{ route('login') }}" method="POST"
                            class="auth-form {{ !request()->has('tab') || request('tab') == 'login' ? 'active' : '' }}"
                            id="login-form">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="auth-input"
                                    placeholder="Enter email or Username" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-group">
                                <input type="password" name="password" class="auth-input" placeholder="Password" required>
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="auth-btn auth-btn-login">Login</button>

                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>

                            <div class="social-divider">Or login with</div>

                            <div class="social-buttons">
                                <a href="{{ route('social.login', 'facebook') }}" class="social-btn social-btn-facebook">
                                    <i class="fa fa-facebook"></i> Login with Facebook
                                </a>
                                <a href="{{ route('social.login', 'google') }}" class="social-btn social-btn-google">
                                    <i class="fa fa-google"></i> Login with Google
                                </a>
                            </div>
                        </form>

                        <!-- Register Form -->
                        <form action="{{ route('register') }}" method="POST"
                            class="auth-form {{ request('tab') == 'register' ? 'active' : '' }}" id="register-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="name" class="auth-input" placeholder="Full Name"
                                    value="{{ old('name') }}" required minlength="2" maxlength="50"
                                    pattern="^[a-zA-ZÀ-ỹ\s]+$" title="Name can only contain letters and spaces">
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-group">
                                <input type="tel" name="phone" class="auth-input" placeholder="Phone"
                                    value="{{ old('phone') }}" required pattern="^(03|05|07|08|09)\d{8}$" maxlength="10"
                                    title="Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số">
                                @error('phone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-group">
                                <input type="email" name="email" class="auth-input" placeholder="Email"
                                    value="{{ old('email') }}" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                    title="Địa chỉ email không hợp lệ">
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-group">
                                <input type="password" name="password" class="auth-input" placeholder="Your password"
                                    required minlength="8" title="Password must be at least 8 characters">
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="auth-input"
                                    placeholder="Confirm password" required minlength="8">
                            </div>

                            <button type="submit" class="auth-btn auth-btn-register">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- customer login end -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.auth-tab');
            const forms = document.querySelectorAll('.auth-form');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Update tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Update forms
                    forms.forEach(form => {
                        form.classList.remove('active');
                        if (form.id === targetTab + '-form') {
                            form.classList.add('active');
                        }
                    });

                    // Update URL without reload
                    const url = new URL(window.location);
                    url.searchParams.set('tab', targetTab);
                    window.history.replaceState({}, '', url);
                });
            });
        });
    </script>
@endsection
