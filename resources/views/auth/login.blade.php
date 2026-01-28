@extends('layouts.public')

@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area other_bread">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{ route('welcome') }}">home</a></li>
                        <li>/</li>
                        <li>login</li>
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
        <div class="row">
            <!--login area start-->
            <div class="col-lg-6 col-md-6">
                <div class="account_form">
                    <h2>login</h2>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <p>
                            <label>Email address <span>*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </p>
                        <p>
                            <label>Passwords <span>*</span></label>
                            <input type="password" name="password" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </p>
                        <div class="login_submit">
                            <a href="{{ route('password.request') }}">Lost your password?</a>
                            <label for="remember">
                                <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                Remember me
                            </label>
                            <button type="submit">login</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--login area end-->

            <!--register area start-->
            <div class="col-lg-6 col-md-6">
                <div class="account_form register">
                    <h2>Register</h2>
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <p>
                            <label>Name <span>*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </p>
                        <p>
                            <label>Email address <span>*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                             @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </p>
                        <p>
                            <label>Passwords <span>*</span></label>
                            <input type="password" name="password" required>
                             @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </p>
                        <p>
                            <label>Confirm Passwords <span>*</span></label>
                            <input type="password" name="password_confirmation" required>
                        </p>
                        <div class="login_submit">
                            <button type="submit">Register</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--register area end-->
        </div>
    </div>
</div>
<!-- customer login end -->
@endsection
