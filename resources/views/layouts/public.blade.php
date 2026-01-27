<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Reid - FashionStore')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend-assets/img/favicon.ico') }}">

    <!-- CSS 
    ========================= -->

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/plugins.css') }}">
    
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}">
    
    <!-- Custom CSS -->
    <style>
        .header_black .main_menu_inner .main_menu > nav > ul > li > a { color: #fff; }
        .header_black .main_menu_inner .main_menu > nav > ul > li:hover > a { color: #fe4536; }
    </style>

</head>

<body>

    <!-- Main Wrapper Start -->
    <!--Offcanvas menu area start-->
    <div class="off_canvars_overlay"></div>
    <div class="offcanvas_menu offcanvas_six">
        <div class="canvas_open">
            <a href="javascript:void(0)"><i class="ion-navicon"></i></a>
        </div>
        <div class="offcanvas_menu_wrapper">
            <div class="canvas_close">
                <a href="javascript:void(0)"><i class="ion-android-close"></i></a>  
            </div>
            <div class="welcome_text">
                <ul>
                    <li><span>Giao hàng miễn phí</span> cho đơn hàng trên 500k</li>
                    <li><span>Đổi trả miễn phí *</span> Đảm bảo hài lòng</li>
                </ul>
            </div>
            
            <div class="top_right">
                <ul>
                    @guest
                        <li class="top_links"><a href="{{ route('login') }}">Đăng nhập</a></li>
                        <li class="top_links"><a href="{{ route('register') }}">Đăng ký</a></li>
                    @else
                        <li class="top_links"><a href="#">{{ Auth::user()->name }} <i class="ion-chevron-down"></i></a>
                            <ul class="dropdown_links">
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div> 
            <div class="search_bar">
                <form action="{{ route('shop') }}" method="GET">
                    <input placeholder="Tìm kiếm sản phẩm..." type="text" name="search">
                    <button type="submit"><i class="ion-ios-search-strong"></i></button>
                </form>
            </div>
            <div class="cart_area">
                <div class="cart_link">
                    <a href="{{ route('cart.index') }}"><i class="fa fa-shopping-basket"></i>{{ count((array) session('cart')) }} item(s)</a>
                </div>
            </div>
            <div id="menu" class="text-left ">
                <ul class="offcanvas_main_menu">
                    <li class="menu-item-has-children active">
                        <a href="{{ route('welcome') }}">Trang chủ</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{ route('shop') }}">Cửa hàng</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">Liên hệ</a>
                    </li>
                </ul>
            </div>
            <div class="offcanvas_footer">
                <span><a href="#"><i class="fa fa-envelope-o"></i> info@yourdomain.com</a></span>
            </div>
        </div>
    </div>
    <!--Offcanvas menu area end-->
    
    <!--header area start-->
    <header class="header_area header_black header_six">
        <!--header top start-->
        <div class="header_top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-4">
                        <div class="welcome_text">
                            <p>Chào mừng đến với cửa hàng Reid</p>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="top_right text-right">
                            <ul>
                               @guest
                                   <li class="top_links"><a href="{{ route('login') }}">Đăng nhập</a></li>
                                   <li class="top_links"><a href="{{ route('register') }}">Đăng ký</a></li>
                               @else
                                   <li class="top_links"><a href="#">{{ Auth::user()->name }} <i class="ion-chevron-down"></i></a>
                                       <ul class="dropdown_links">
                                           <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a></li>
                                       </ul>
                                   </li>
                               @endguest
                            </ul>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
        <!--header top start-->

        <!--header middel start-->
        <div class="header_middel">
            <div class="container">
                <div class="row align-items-center hidden_menu">
                    <div class="col-lg-2 col-md-3 col-sm-3 col-3">
                         <!-- TODO: Update Logo URL if needed -->
                        <div class="logo">
                            <a href="{{ route('welcome') }}"><img src="{{ asset('frontend-assets/img/logo/logo-2.jpg') }}" alt=""></a>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-9 col-sm-9 col-9">
                        <div class="header_right">
                            <div class="main_menu_inner">
                                <div class="main_menu">
                                    <nav>
                                        <ul>
                                            <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">Trang chủ</a></li>
                                            <li class="{{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ route('shop') }}">Cửa hàng</a></li>
                                            <li><a href="#">Giới thiệu</a></li>
                                            <li><a href="#">Liên hệ</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="header_right_info">
                                <div class="search_bar">
                                    <form action="{{ route('shop') }}" method="GET">
                                        <input placeholder="Tìm kiếm..." type="text" name="search">
                                        <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                    </form>
                                </div>
                                <div class="cart_area">
                                    <div class="cart_link">
                                        <a href="{{ route('cart.index') }}"><i class="fa fa-shopping-basket"></i>{{ count((array) session('cart')) }} item(s)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--header middel end-->
    </header>
    <!--header area end-->

    @yield('content')

    <!--footer area start-->
    <footer class="footer_widgets">
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="widgets_container contact_us">
                            <h3>Giới thiệu</h3>
                            <div class="footer_contact">
                                <p>Cửa hàng thời trang cao cấp Reid.</p>
                                <p><span>Địa chỉ:</span> Hà Nội, Việt Nam</p>
                                <p><span>Điện thoại:</span> 0123 456 789</p>
                                <p><span>Email:</span> support@reid.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="widgets_container widget_menu">
                            <h3>Thông tin</h3>
                            <div class="footer_menu">
                                <ul>
                                    <li><a href="#">Về chúng tôi</a></li>
                                    <li><a href="#">Chính sách giao hàng</a></li>
                                    <li><a href="#">Chính sách bảo mật</a></li>
                                    <li><a href="#">Điều khoản sử dụng</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="widgets_container widget_menu">
                            <h3>Tài khoản</h3>
                            <div class="footer_menu">
                                <ul>
                                    <li><a href="#">Tài khoản của tôi</a></li>
                                    <li><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                                    <li><a href="#">Yêu thích</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="widgets_container newsletter">
                            <h3>Đăng ký nhận tin</h3>
                            <div class="newleter-content">
                                <p>Nhận thông tin cập nhật mới nhất về sản phẩm và khuyến mãi.</p>
                                <div class="subscribe_form">
                                    <form action="#">
                                        <input placeholder="Email của bạn..." type="text">
                                        <button type="submit">Đăng ký</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="copyright_area">
                            <p>Copyright &copy; 2026 <a href="#">Reid</a>. All Right Reserved.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="footer_payment text-right">
                            <img src="{{ asset('frontend-assets/img/icon/payment.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--footer area end-->

    <!-- JS
    ============================================ -->

    <!-- Plugins JS -->
    <script src="{{ asset('frontend-assets/js/plugins.js') }}"></script>
    
    <!-- Main JS -->
    <script src="{{ asset('frontend-assets/js/main.js') }}"></script>

    @yield('scripts')

</body>
</html>
