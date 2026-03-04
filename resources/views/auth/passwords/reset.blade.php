@extends('layouts.public')

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
                            <li>{{ __('messages.reset_password') }}</li>
                        </ul>   
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <div class="customer_login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6">
                    <div class="account_form">
                        <h2>Đặt lại mật khẩu</h2>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <p>
                                <label>Email Address <span>*</span></label>
                                <input type="email" name="email" value="{{ $email ?? old('email') }}" required
                                    autocomplete="email" autofocus>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </p>

                            <p>
                                <label>New Password <span>*</span></label>
                                <input type="password" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </p>

                            <p>
                                <label>Confirm Password <span>*</span></label>
                                <input type="password" name="password_confirmation" required autocomplete="new-password">
                            </p>

                            <div class="login_submit">
                                <button type="submit">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection