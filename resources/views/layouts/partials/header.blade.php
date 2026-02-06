   
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
                    <li><span>Contact:</span> 0123 456 789</li>
                    <li><span>Offer:</span> Free shipping for orders from 500k</li>
                </ul>
            </div>
            
            <div class="top_right">
                <ul>
                    <li class="top_links">
                        @guest
                            <a href="#">My Account <i class="ion-chevron-down"></i></a>
                            <ul class="dropdown_links">
                                <li><a href="{{ route('login') }}">Sign In</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
                            </ul>
                        @else
                            <a href="#" style="display: flex; align-items: center; gap: 5px;">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <i class="ion-android-person"></i>
                                @endif
                                {{ Auth::user()->name }} <i class="ion-chevron-down"></i>
                            </a>
                            <ul class="dropdown_links">
                                <li><a href="{{ route('account.index') }}">My Account</a></li>
                                @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                    <li><a href="{{ route('admin.dashboard') }}">{{ Auth::user()->isAdmin() ? 'Admin' : 'Staff' }}</a></li>
                                @endif
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-offcanvas').submit();">Logout</a></li>
                                <form id="logout-form-offcanvas" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </ul>
                        @endguest
                    </li> 
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
                    <select class="select_option" name="category">
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
                                                    <li><a href="{{ route('login') }}">Sign In</a></li>
                                                    <li><a href="{{ route('register') }}">Register</a></li>
                                                </ul>
                                            @else
                                                <a href="#" style="display: flex; align-items: center; gap: 5px;">
                                                    @if(Auth::user()->avatar)
                                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                                    @else
                                                        <i class="ion-android-person"></i>
                                                    @endif
                                                    {{ Auth::user()->name }} <i class="ion-chevron-down"></i>
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
        </div>
    </div>
        <!--header middel end-->

    </header>
    <!--header area end-->
    </header>
    <!--header area end-->
