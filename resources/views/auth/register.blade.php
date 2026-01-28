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
                        <li>register</li>
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
                         <div class="text-center mt-3">
                            <p>Already have an account?</p>
                            <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
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
