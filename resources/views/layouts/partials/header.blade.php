   
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
                    <li><span>{{ __('messages.contact') }}:</span> {{ $settings['site_phone'] ?? '0354869999' }}</li>
                    <li><span>{{ __('messages.offer') }}:</span> {{ __('messages.free_shipping') }}</li>
                </ul>
            </div>
            
            <div class="top_right">
                <ul>
                    <li class="top_links">
                        @guest
                            <a href="#">{{ __('messages.my_account') }} <i class="ion-chevron-down"></i></a>
                            <ul class="dropdown_links">
                                <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                                <li><a href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                            </ul>
                        @else
                            <a href="#" style="display: flex; align-items: center; gap: 8px;">
                                @if(Auth::user()->avatar_url)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;">
                                @else
                                    <i class="ion-android-person"></i>
                                @endif
                                <span style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->name }}</span>
                                <i class="ion-chevron-down"></i>
                            </a>
                            <ul class="dropdown_links">
                                <li><a href="{{ route('account.index') }}">{{ __('messages.my_account') }}</a></li>
                                @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                    <li><a href="{{ route('admin.dashboard') }}">{{ Auth::user()->isAdmin() ? __('messages.admin') : __('messages.staff') }}</a></li>
                                @endif
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-offcanvas').submit();">{{ __('messages.logout') }}</a></li>
                                <form id="logout-form-offcanvas" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </ul>
                        @endguest
                    </li> 
                    <!-- <li class="language">
                         <a href="#">
                            @if(App::getLocale() == 'vi')
                                <img src="{{ asset('frontend-assets/img/logo/language.png') }}" alt=""> Tiếng Việt
                            @else
                                <img src="{{ asset('frontend-assets/img/logo/en-gb.png') }}" alt=""> English
                            @endif
                            <i class="ion-chevron-down"></i>
                        </a>
                        <ul class="dropdown_language">
                            <li><a href="{{ route('lang.switch', 'vi') }}">Tiếng Việt</a></li>
                            <li><a href="{{ route('lang.switch', 'en') }}">English</a></li>
                        </ul>
                    </li>
                    <li class="currency"><a href="#">VND <i class="ion-chevron-down"></i></a>
                        <ul class="dropdown_currency">
                            <li><a href="#">USD</a></li>
                        </ul>
                    </li> -->
                </ul>
            </div> 
            <div class="search_bar">
                <form action="{{ route('search.index') }}" method="GET">
                    <select class="select_option" name="category">
                        <option selected value="">{{ __('messages.all_products') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input id="search-input-offcanvas" placeholder="{{ __('messages.search_placeholder') }}" type="text" name="q" autocomplete="off">
                    <button type="submit"><i class="ion-ios-search-strong"></i></button>
                </form>
                <div id="search-suggestions-offcanvas" class="search-suggestions-dropdown"></div>
            </div>
            <div class="cart_area">
                <div class="middel_links">
                    <ul>
                        @guest
                            <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                            <li>/</li>
                            <li><a href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                        @endguest
                    </ul>
                </div>
                @include('frontend.partials.mini-cart')
            </div>
            <div class="offcanvas_footer">
                <span><a href="mailto:{{ $settings['site_email'] ?? 'Elite@gmail.com' }}"><i class="fa fa-envelope-o"></i> {{ $settings['site_email'] ?? 'Elite@gmail.com' }}</a></span>
                <ul>
                    <li class="facebook"><a href="{{ $settings['social_facebook'] ?? '#' }}"><i class="fa fa-facebook"></i></a></li>
                    <li class="instagram"><a href="{{ $settings['social_instagram'] ?? '#' }}"><i class="fa fa-instagram"></i></a></li>
                    <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li class="youtube"><a href="#"><i class="fa fa-youtube"></i></a></li>
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
                        <!-- Logo + Menu Column -->
                        <div class="col-lg-6 col-md-3">
                            <div class="header_left d-flex align-items-center">
                                <div class="logo">
                                    <a href="{{ route('welcome') }}"><img src="{{ asset('frontend-assets/img/logo/logo-elite-new.png') }}" alt=""></a>
                                </div>
                                
                                <!-- Main Menu (Desktop) -->
                                <div class="main_menu d-none d-lg-block">
                                    <nav>
                                        <ul>
                                            <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                                            <li class="mega_items {{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ route('shop') }}">{{ __('messages.shop') }} <i class="fa fa-angle-down"></i></a>
                                                <ul class="mega_menu">
                                                    <li><a href="#">{{ __('messages.product_categories') }}</a>
                                                        <ul>
                                                            @foreach($categories as $category)
                                                                <li><a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                    <li><a href="#">{{ __('messages.other_pages') }}</a>
                                                        <ul>
                                                            <li><a href="{{ route('cart.index') }}">{{ __('messages.cart') }}</a></li>
                                                            <li><a href="{{ route('checkout.index') }}">{{ __('messages.checkout') }}</a></li>
                                                            <li><a href="{{ route('account.index') }}">{{ __('messages.account') }}</a></li>
                                                        </ul>
                                                    </li>
                                                    <li class="banner_menu"><a href="#"><img src="{{ asset('frontend-assets/img/bg/banner1.jpg') }}" alt=""></a></li>
                                                </ul>
                                            </li>
                                            <li><a href="{{ route('news') }}">{{ __('messages.news') }}</a></li>
                                            <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                                            <li><a href="{{ route('contact.index') }}">{{ __('messages.contact') }}</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search/Account/Cart Column -->
                        <div class="col-lg-6 col-md-9">
                            <div class="middel_right_info">
                                <div class="search_bar" style="position: relative;">
                                    <form action="{{ route('search.index') }}" method="GET">                          
                                        <input id="search-input-desktop" placeholder="{{ __('messages.search_placeholder') }}" type="text" name="q" autocomplete="off">
                                        <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                    </form>
                                    <div id="search-suggestions-desktop" class="search-suggestions-dropdown"></div>
                                </div>
                                <div class="top_right text-right">
                                    <ul>
                                        <li class="top_links">
                                            @guest
                                                <a href="#"><i class="ion-android-person"></i> {{ __('messages.account') }} <i class="ion-chevron-down"></i></a>
                                                <ul class="dropdown_links">
                                                    <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                                                    <li><a href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                                                </ul>
                                            @else
                                                <a href="#" style="display: flex; align-items: center; gap: 8px;">
                                                    @if(Auth::user()->avatar_url)
                                                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;">
                                                    @else
                                                        <i class="ion-android-person"></i>
                                                    @endif
                                                    <span style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->name }}</span>
                                                    <i class="ion-chevron-down"></i>
                                                </a>
                                                <ul class="dropdown_links">
                                                    <li><a href="{{ route('account.index') }}">{{ __('messages.my_account') }}</a></li>
                                                    @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                                        <li><a href="{{ route('admin.dashboard') }}">{{ Auth::user()->isAdmin() ? __('messages.admin') : __('messages.staff') }}</a></li>
                                                    @endif
                                                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('messages.logout') }}</a></li>
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
                
                <!-- Horizontal Menu -->
                <!-- <div class="horizontal_menu horizontal_menu_six">
                    <div class="main_menu"> 
                        <nav>  
                            <ul>
                                <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                                <li class="mega_items {{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ route('shop') }}">{{ __('messages.shop') }}<i class="fa fa-angle-down"></i></a>
                                    <ul class="mega_menu">
                                        <li><a href="#">{{ __('messages.product_categories') }}</a>
                                            <ul>
                                                @foreach($categories as $category)
                                                    <li><a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        <li><a href="#">{{ __('messages.other_pages') }}</a>
                                            <ul>
                                                <li><a href="{{ route('cart.index') }}">{{ __('messages.cart') }}</a></li>
                                                <li><a href="{{ route('checkout.index') }}">{{ __('messages.checkout') }}</a></li>
                                                <li><a href="{{ route('account.index') }}">{{ __('messages.account') }}</a></li>
                                            </ul>
                                        </li>
                                        <li class="banner_menu"><a href="#"><img src="{{ asset('frontend-assets/img/bg/banner1.jpg') }}" alt=""></a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('news') }}">{{ __('messages.news') }}</a></li>
                                <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                                <li><a href="{{ route('contact.index') }}">{{ __('messages.contact') }}</a></li>
                            </ul>   
                        </nav> 
                    </div>
                </div>
            </div>
        </div> -->
        <!--header middel end-->

    </header>
    <!--header area end-->

    <!--sticky header start-->
    <div class="sticky-header header_six">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="main_menu_inner">
                        <div class="main_menu"> 
                            <nav>  
                                <ul>
                                    <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                                    <li class="mega_items {{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ route('shop') }}">{{ __('messages.shop') }}<i class="fa fa-angle-down"></i></a>
                                        <ul class="mega_menu">
                                            <li><a href="#">{{ __('messages.product_categories') }}</a>
                                                <ul>
                                                    @foreach($categories as $category)
                                                        <li><a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                            <li><a href="#">{{ __('messages.other_pages') }}</a>
                                                <ul>
                                                    <li><a href="{{ route('cart.index') }}">{{ __('messages.cart') }}</a></li>
                                                    <li><a href="{{ route('checkout.index') }}">{{ __('messages.checkout') }}</a></li>
                                                    <li><a href="{{ route('account.index') }}">{{ __('messages.account') }}</a></li>
                                                </ul>
                                            </li>
                                            <li class="banner_menu"><a href="#"><img src="{{ asset('frontend-assets/img/bg/banner1.jpg') }}" alt=""></a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('news') }}">{{ __('messages.news') }}</a></li>
                                    <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                                    <li><a href="{{ route('contact.index') }}">{{ __('messages.contact') }}</a></li>
                                </ul>   
                            </nav> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--sticky header end-->
    </header>
    <!--header area end-->
