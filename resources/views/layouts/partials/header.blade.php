    <!--Offcanvas menu area start-->
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
                                    <li class="top_links"><a href="#">{{ Auth::user()->name }} <i class="ion-chevron-down"></i></a>
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
                                <select class="select_option" name="category_id" >
                                    <option selected value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                            <div class="cart_link">
                                <a href="{{ route('cart.index') }}"><i class="fa fa-shopping-basket"></i>{{ count((array) session('cart')) }} item(s)</a>
                            </div>
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
                                    <a href="#">Blog</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#">Contact</a>
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
    <header class="header_area header_six">
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
                                                <a href="#"><i class="ion-android-person"></i> {{ Auth::user()->name }} <i class="ion-chevron-down"></i></a>
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

                                <div class="cart_link">
                                    <a href="{{ route('cart.index') }}"><i class="fa fa-shopping-basket"></i>{{ count((array) session('cart')) }} item(s)</a>
                                    <!--mini cart-->
                                     <div class="mini_cart">
                                        @if(session('cart'))
                                            @php $total = 0; @endphp
                                            @foreach(session('cart') as $id => $details)
                                                @php $total += $details['price'] * $details['quantity']; @endphp
                                                <div class="cart_item top">
                                                   <div class="cart_img">
                                                       <a href="#"><img src="{{ $details['image'] ?? asset('frontend-assets/img/s-product/product.jpg') }}" alt=""></a>
                                                   </div>
                                                    <div class="cart_info">
                                                        <a href="#">{{ $details['name'] }}</a>
                                                        <span>{{ $details['quantity'] }}x ${{ number_format($details['price']) }}</span>
                                                    </div>
                                                    <div class="cart_remove">
                                                        <a href="#"><i class="ion-android-close"></i></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="cart__table">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left">Subtotal :</td>
                                                            <td class="text-right">${{ number_format($total) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">Total :</td>
                                                            <td class="text-right">${{ number_format($total) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="cart_button view_cart">
                                                <a href="{{ route('cart.index') }}">View Cart</a>
                                            </div>
                                            <div class="cart_button checkout">
                                                <a href="{{ route('checkout.index') }}">Checkout</a>
                                            </div>
                                        @else
                                            <div class="cart_item">
                                                <p>Cart is empty.</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!--mini cart end-->
                                </div>
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
                                                @foreach($categories->take(5) as $category)
                                                    <li><a href="{{ route('shop', ['category_id' => $category->id]) }}">{{ $category->name }}</a></li>
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
                                <li><a href="#">News</a></li>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Contact Us</a></li>
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
                                         <li class="mega_items {{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ route('shop') }}">Shop <i class="fa fa-angle-down"></i></a>
                                            <ul class="mega_menu">
                                                <li><a href="#">Product Categories</a>
                                                    <ul>
                                                        @foreach($categories->take(5) as $category)
                                                            <li><a href="{{ route('shop', ['category_id' => $category->id]) }}">{{ $category->name }}</a></li>
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
                                            </ul>
                                        </li>
                                        <li><a href="#">News</a></li>
                                        <li><a href="#">About Us</a></li>
                                        <li><a href="#">Contact Us</a></li>
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
