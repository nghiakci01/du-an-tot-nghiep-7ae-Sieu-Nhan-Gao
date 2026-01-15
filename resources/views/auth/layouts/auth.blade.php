<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Đăng nhập / Đăng ký')</title>
    
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://unicons.iconscout.com/release/v2.1.9/css/unicons.css'>
    <link rel="stylesheet" href="{{ asset('auth/style.css') }}">
</head>
<body>

    <a href="https://front.codes/" class="logo" target="_blank">
        <img src="https://assets.codepen.io/1462889/fcy.png" alt="">
    </a>

    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">

                        <!-- Text chuyển trang -->
                        <h6 class="mb-0 pb-3">
                            <span><a href="{{ route('login') }}" class="link">Đăng nhập</a></span>
                            <span><a href="{{ route('register') }}" class="link">Đăng ký</a></span>
                        </h6>

                        <!-- Toggle switch: trạng thái đúng theo route -->
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"
                               {{ request()->routeIs('register') ? 'checked' : '' }} />

                        <!-- Click vào switch → chuyển trang -->
                        @if(request()->routeIs('login'))
                            <label for="reg-log" onclick="window.location.href='{{ route('register') }}'"></label>
                        @else
                            <label for="reg-log" onclick="window.location.href='{{ route('login') }}'"></label>
                        @endif

                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                @yield('content')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>