    <!-- Offcanvas menu area start
    <style>
        /* Show categories menu on hover for all pages */
        .categories_menu:hover .categories_menu_toggle {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Fix Shop mega menu hover */
        .main_menu nav ul li:hover .mega_menu {
            opacity: 1 !important;
            visibility: visible !important;
            top: 100% !important;
        }
        
        /* Ensure horizontal menu is visible if used */
        .horizontal_menu_six {
            display: flex !important;
            justify-content: center !important;
            padding: 5px 0 !important;
            z-index: 1000 !important;
            position: relative !important;
            top: auto !important;
            left: auto !important;
            width: 100% !important;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            margin-top: 5px;
        }

        .horizontal_menu_six .main_menu nav > ul > li {
            margin: 0 20px;
        }

        .horizontal_menu_six .main_menu nav > ul > li > a {
            font-size: 13px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 1.2px !important;
            color: #1a1a1a !important;
            padding: 12px 0 !important;
            position: relative;
            transition: color 0.3s ease;
        }

        .horizontal_menu_six .main_menu nav > ul > li > a::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #ff6a28;
            transition: width 0.3s ease;
        }

        .horizontal_menu_six .main_menu nav > ul > li:hover > a {
            color: #ff6a28 !important;
        }

        .horizontal_menu_six .main_menu nav > ul > li:hover > a::after {
            width: 100%;
        }

        .horizontal_menu_six .main_menu nav > ul > li.active > a {
            color: #ff6a28 !important;
        }

        .horizontal_menu_six .main_menu nav > ul > li.active > a::after {
            width: 100%;
        }

        /* Mega menu improvements */
        .horizontal_menu_six .mega_menu {
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
            border: 1px solid #f0f0f0 !important;
            padding: 25px !important;
            border-radius: 4px;
        }

        .horizontal_menu_six .mega_menu li a {
            font-size: 14px !important;
            transition: padding-left 0.3s ease;
        }

        .horizontal_menu_six .mega_menu li a:hover {
            padding-left: 5px;
            color: #ff6a28 !important;
        }

        /* Make product actions persistent on home page */
        .is-home .product_action {
            bottom: 30% !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .is-home .quick_button {
            bottom: 10% !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Compact Header */
        .header_middel {
            padding: 15px 0 !important;
        }
        .middel_inner {
            border-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
    </style> -->
    <div class="off_canvars_overlay">
                
    </div>
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
                               <li><span>Contact:</span> 0123 456 789 (Test Update)</li>
                               <li><span>Offer:</span> Free shipping for orders from 500k</li>
                           </ul>
                        </div>
                        
                        <div class="top_right">
                            <ul>
                               @guest
                                    <li class="top_links"><a href="{{ route('login') }}">Login</a></li>
                                    <li class="top_links"><a href="{{ route('register') }}">Register</a></li>
                               @else
                                    <li class="top_links">
                                        <a href="#" style="display: flex; align-items: center; gap: 5px;">
                                            @if(Auth::user()->avatar)
                                                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                            @else
                                                <i class="ion-android-person"></i>
                                            @endif
                                            {{ Auth::user()->name }} 
                                            <i class="ion-chevron-down"></i>
                                        </a>
                                        <ul class="dropdown_links">
                                            <li><a href="{{ route('account.index') }}">My Account</a></li>
                                            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                                <li><a href="{{ route('admin.dashboard') }}">{{ Auth::user()->isAdmin() ? 'Admin' : 'Staff' }}</a></li>
                                            @endif
                                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-offcanvas').submit();">Logout</a></li>
                                            <form id="logout-form-offcanvas" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                        </ul>
                                    </li> 
                               @endguest
                                <li class="language"><a href="#"><img src="{{ asset('frontend-assets/img/logo/language.png') }}" alt=""> Tiếng Việt <i class="ion-chevron-down"></i></a>
                                    <ul class="dropdown_language">
                                        <li><a href="#">Tiếng Anh</a></li>
                                    </ul>
                                </li>
                                <li class="currency"><a href="#">VND <i class="ion-chevron-down"></i></a>
                                    <ul class="dropdown_currency">
                                        <li><a href="#">USD</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div> 
                        <div class="search_bar">
                            <form action="{{ route('shop') }}" method="GET">
                                <select class="select_option" name="category" >
                                    <option selected value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <input placeholder="Search products..." type="text" name="search">
                                <button type="submit"><i class="ion-ios-search-strong"></i></button>
                            </form>
                        </div>
                        <div class="cart_area">
                            <div class="middel_links">
                                <ul>
                                    @guest
                                        <li><a href="{{ route('login') }}">Login</a></li>
                                        <li>/</li>
                                        <li><a href="{{ route('register') }}">Register</a></li>
                                    @endguest
                                </ul>

                            </div>
                            @include('frontend.partials.mini-cart')
                        </div>
                        <div id="menu" class="text-left ">
                            <ul class="offcanvas_main_menu">
                                <li class="menu-item-has-children {{ request()->is('/') ? 'active' : '' }}">
                                    <a href="{{ route('welcome') }}">Home</a>
                                </li>
                                <li class="menu-item-has-children {{ request()->is('shop*') ? 'active' : '' }}">
                                    <a href="{{ route('shop') }}">Shop</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="{{ route('about') }}">About Us</a>
                                </li>
                                <li class="menu-item-has-children {{ request()->is('contact') ? 'active' : '' }}">
                                    <a href="{{ route('contact.index') }}">Contact</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#">Liên hệ</a> 
                                </li>
                            </ul>
                        </div>
                        <div class="offcanvas_footer">
                            <span><a href="#"><i class="fa fa-envelope-o"></i> contact@yourdomain.com</a></span>
                            <ul>
                                <li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li class="pinterest"><a href="#"><i class="fa fa-pinterest-p"></i></a></li>
                                <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
    </div>
    <!--Offcanvas menu area end-->
    
    <!--header area start-->
    <header class="header_area header_six {{ request()->is('/') ? 'is-home' : '' }}">
        <!--header middel start-->
        <div class="header_middel">
            <div class="container-fluid">
                <div class="middel_inner">
                    <div class="row align-items-center">
                       <div class="col-lg-2 col-md-2">
                            <div class="logo">
                                <a href="{{ route('welcome') }}"><img src="{{ asset('frontend-assets/img/logo/logo.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-10">
                            <div class="middel_right_info">
                                <div class="search_bar">
                                    <form action="{{ route('shop') }}" method="GET">                          
                                        <input placeholder="Search products..." type="text" name="search">
                                        <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                    </form>
                                </div>
                                <div class="top_right text-right">
                                    <ul>
                                      <li class="language"><a href="#"><img src="{{ asset('frontend-assets/img/logo/en-gb.png') }}" alt=""> Vietnamese <i class="ion-chevron-down"></i></a>
                                            <ul class="dropdown_language">
                                                <li><a href="#">English</a></li>
                                            </ul>
                                        </li>
                                        <li class="currency"><a href="#">VND <i class="ion-chevron-down"></i></a>
                                            <ul class="dropdown_currency">
                                                <li><a href="#">USD</a></li>
                                            </ul>
                                        </li>
                                       <li class="top_links">
                                            @guest
                                                <a href="#"><i class="ion-android-person"></i> Account <i class="ion-chevron-down"></i></a>
                                                <ul class="dropdown_links">
                                                    <li><a href="{{ route('login') }}">Login</a></li>
                                                    <li><a href="{{ route('register') }}">Register</a></li>
                                                </ul>
                                            @else
                                                <a href="#" style="display: flex; align-items: center; gap: 5px;">
                                                    @if(Auth::user()->avatar)
                                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                                    @else
                                                        <i class="ion-android-person"></i>
                                                    @endif
                                                    {{ Auth::user()->name }} 
                                                    <i class="ion-chevron-down"></i>
                                                </a>
                                                <ul class="dropdown_links">
                                                    <li><a href="{{ route('account.index') }}">My Account</a></li>
                                                    @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                                        <li><a href="{{ route('admin.dashboard') }}">{{ Auth::user()->isAdmin() ? 'Admin' : 'Staff' }}</a></li>
                                                    @endif
                                                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                                </ul>
                                            @endguest
                                        </li> 
                                        
                                    </ul>
                                </div>   

                                @include('frontend.partials.mini-cart')
                            </div>
                        </div>  
                    </div>
                </div>
            <div class="horizontal_menu horizontal_menu_six">
                <div class="main_menu"> 
                    <nav>  
                        <ul>
                            <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">Home</a></li>
                            <li class="mega_items {{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ route('shop') }}">Shop<i class="fa fa-angle-down"></i></a>
                                <ul class="mega_menu">
                                    <li><a href="#">Product Categories</a>
                                        <ul>
                                            @foreach($categories as $category)
                                                <li><a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li><a href="#">Other pages</a>
                                        <ul>
                                            <li><a href="{{ route('cart.index') }}">Cart</a></li>
                                            <li><a href="{{ route('checkout.index') }}">Checkout</a></li>
                                            <li><a href="{{ route('account.index') }}">Account</a></li>
                                        </ul>
                                    </li>
                                    <li class="banner_menu"><a href="#"><img src="{{ asset('frontend-assets/img/bg/banner1.jpg') }}" alt=""></a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('news') }}">News</a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                        </ul>   
                    </nav> 
                </div>
            </div>
        </div>
    </div>
        <!--header middel end-->

        <!--header bottom satrt-->
        <div class="header_bottom sticky-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="main_menu_inner">
                            <div class="main_menu"> 
                                <nav>  
                                    <ul>
                                        <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">Home</a></li>
                                        <li class="mega_items {{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ route('shop') }}">Shop<i class="fa fa-angle-down"></i></a>
                                            <ul class="mega_menu">
                                                <li><a href="#">Product Categories</a>
                                                    <ul>
                                                        @foreach($categories as $category)
                                                            <li><a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                <li><a href="#">Other pages</a>
                                                    <ul>
                                                        <li><a href="{{ route('cart.index') }}">Cart</a></li>
                                                        <li><a href="{{ route('checkout.index') }}">Checkout</a></li>
                                                        <li><a href="{{ route('account.index') }}">Account</a></li>
                                                    </ul>
                                                </li>
                                                <li class="banner_menu"><a href="#"><img src="{{ asset('frontend-assets/img/bg/banner1.jpg') }}" alt=""></a></li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('news') }}">News</a></li>
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                                    </ul>   
                                </nav> 
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <!--header bottom end-->
    </header>
    <!--header area end-->
