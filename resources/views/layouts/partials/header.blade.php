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
                    <li><span>Liên hệ:</span> 01234567890</li>
                    <li><span>Miễn phí vận chuyển</span> cho đơn hàng trên 500k</li>
                </ul>
            </div>
            
            <div class="top_right">
                <ul>
                    @guest
                    <li class="top_links"><a href="{{ route('login') }}">Đăng nhập / Đăng ký <i class="ion-android-person"></i></a></li>
                    @else
                    <li class="top_links"><a href="#"><i class="ion-android-person"></i> {{ Auth::user()->name }} <i class="ion-chevron-down"></i></a>
                        <ul class="dropdown_links">
                            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            @endif
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </ul>
                    </li> 
                    @endguest
                </ul>
            </div> 
            <div class="search_bar">
                <form action="{{ route('shop') }}" method="GET">
                    <select class="select_option" name="category_id">
                        <option selected value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
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
                    <li class="menu-item-has-children {{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ route('welcome') }}">Trang chủ</a>
                    </li>
                    <li class="menu-item-has-children {{ request()->is('shop*') ? 'active' : '' }}">
                        <a href="{{ route('shop') }}">Cửa hàng</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">Giới thiệu</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">Liên hệ</a>
                    </li>
                </ul>
            </div>
            <div class="offcanvas_footer">
                <span><a href="#"><i class="fa fa-envelope-o"></i> info@yourdomain.com</a></span>
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
                                        <input placeholder="Search entire store here..." type="text" name="search">
                                        <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                    </form>
                                </div>
                                <div class="top_right text-right">
                                    <ul>
                                      <li class="language"><a href="#"><img src="{{ asset('frontend-assets/img/logo/en-gb.png') }}" alt=""> en-gb <i class="ion-chevron-down"></i></a>
                                            <ul class="dropdown_language">
                                                <li><a href="#"><img src="{{ asset('frontend-assets/img/logo/cigar.jpg') }}" alt=""> French</a></li>
                                                <li><a href="#"><img src="{{ asset('frontend-assets/img/logo/language2.png') }}" alt="">German</a></li>
                                            </ul>
                                        </li>
                                        <li class="currency"><a href="#">USD <i class="ion-chevron-down"></i></a>
                                            <ul class="dropdown_currency">
                                                <li><a href="#">EUR</a></li>
                                                <li><a href="#">BRL</a></li>
                                            </ul>
                                        </li>
                                       <li class="top_links"><a href="#"><i class="ion-android-person"></i> My Account <i class="ion-chevron-down"></i></a>
                                            <ul class="dropdown_links">
                                                <li><a href="#">My Wish List </a></li>
                                                <li><a href="{{ route('account.index') }}">My Account </a></li>
                                                @auth
                                                    @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                                    @endif
                                                    <li><a href="#">Compare Products</a></li>
                                                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out</a></li>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                                @else
                                                    <li><a href="{{ route('login') }}">Sign In</a></li>
                                                    <li><a href="#">Compare Products</a></li>
                                                @endauth
                                            </ul>
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
            
                                                        <span>{{ $details['quantity'] }}x ${{ $details['price'] }}</span>
                
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
                                                            <td class="text-left">Sub-Total :</td>
                                                            <td class="text-right">${{ $total }}</td>
                                                        </tr>
                                                     
                                                        <tr>
                                                            <td class="text-left">Total :</td>
                                                            <td class="text-right">${{ $total }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="cart_item">
                                                <p>Your cart is empty.</p>
                                            </div>
                                        @endif
                                    
                                    <div class="cart_button view_cart">
                                        <a href="{{ route('cart.index') }}">View Cart</a>
                                    </div>
                                    <div class="cart_button checkout">
                                        <a href="{{ route('checkout.index') }}">Checkout</a>
                                    </div>
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
                                <li><a href="{{ route('welcome') }}">Home <i class="fa fa-angle-down"></i></a>
                                    <ul class="sub_menu pages">
                                        <li><a href="index.html">Home 1</a></li>
                                        <li><a href="index-2.html">Home 2</a></li>
                                        <li><a href="index-3.html">Home 3</a></li>
                                        <li><a href="index-4.html">Home 4</a></li>
                                        <li><a href="index-5.html">Home 5</a></li>
                                        <li><a href="index-6.html">Home 6</a></li>
                                        <li><a href="index-7.html">Home 7</a></li>
                                        <li><a href="index-8.html">Home 8</a></li>
                                        <li><a href="index-9.html">Home 9</a></li>
                                    </ul>
                                </li>
                                <li class="mega_items"><a href="{{ route('shop') }}">shop <i class="fa fa-angle-down"></i></a>
                                    <ul class="mega_menu">
                                        <li><a href="#">Shop Layouts</a>
                                            <ul>
                                                <li><a href="shop-fullwidth.html">Full Width</a></li>
                                                <li><a href="shop-fullwidth-list.html">Full Width list</a></li>
                                                <li><a href="shop-right-sidebar.html">Right Sidebar </a></li>
                                                <li><a href="shop-right-sidebar-list.html"> Right Sidebar list</a></li>
                                                <li><a href="shop-list.html">List View</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">other Pages</a>
                                            <ul>
                                                <li><a href="portfolio.html">portfolio</a></li>
                                                <li><a href="portfolio-details.html">portfolio details</a></li>
                                                <li><a href="{{ route('cart.index') }}">cart</a></li>
                                                <li><a href="checkout.html">Checkout</a></li>
                                                <li><a href="{{ route('account.index') }}">my account</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Product Types</a>
                                            <ul>
                                                <li><a href="product-details.html">product details</a></li>
                                                <li><a href="product-sidebar.html">product sidebar</a></li>
                                                <li><a href="product-gallery.html">product gallery</a></li>
                                                <li><a href="product-grouped.html">product grouped</a></li>
                                                <li><a href="variable-product.html">product variable</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">collection</a>
                                            <ul>
                                                <li><a href="{{ route('shop') }}">Handbag</a></li>
                                                <li><a href="{{ route('shop') }}">Accessories</a></li>
                                                <li><a href="{{ route('shop') }}">Clothing</a></li>
                                                <li><a href="{{ route('shop') }}">Shoes</a></li>
                                                <li><a href="{{ route('shop') }}">Check Trousers</a></li>
                                            </ul>
                                        </li>
                                        <li class="banner_menu"><a href="#"><img src="{{ asset('frontend-assets/img/bg/banner1.jpg') }}" alt=""></a></li>
                                    </ul>
                                </li>
                                <li><a href="blog.html">blog <i class="fa fa-angle-down"></i></a>
                                    <ul class="sub_menu pages">
                                        <li><a href="blog-details.html">blog details</a></li>
                                        <li><a href="blog-sidebar.html">blog  Sidebar</a></li>
                                        <li><a href="blog-fullwidth.html">blog fullwidth</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">pages <i class="fa fa-angle-down"></i></a>
                                    <ul class="sub_menu pages">
                                        <li><a href="about.html">About Us</a></li>
                                        <li><a href="services.html">services</a></li>
                                        <li><a href="faq.html">Frequently Questions</a></li>
                                        <li><a href="{{ route('login') }}">login</a></li>
                                        <li><a href="{{ route('register') }}">register</a></li>
                                        <li><a href="{{ route('account.index') }}">my account</a></li>
                                        <li><a href="wishlist.html">Wishlist</a></li>
                                        <li><a href="404.html">Error 404</a></li>
                                        <li><a href="compare.html">compare</a></li>
                                        <li><a href="privacy-policy.html">privacy policy</a></li>
                                        <li><a href="coming-soon.html">coming soon</a></li>
                                    </ul>
                                </li>
                                <li><a href="about.html">About us</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                                <li><a href="#">Sneaker</a></li>
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
                                        <li><a href="{{ route('welcome') }}">Home <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu pages">
                                                <li><a href="index.html">Home 1</a></li>
                                                <li><a href="index-2.html">Home 2</a></li>
                                                <li><a href="index-3.html">Home 3</a></li>
                                                <li><a href="index-4.html">Home 4</a></li>
                                                <li><a href="index-5.html">Home 5</a></li>
                                                <li><a href="index-6.html">Home 6</a></li>
                                                <li><a href="index-7.html">Home 7</a></li>
                                                <li><a href="index-8.html">Home 8</a></li>
                                                <li><a href="index-9.html">Home 9</a></li>
                                            </ul>
                                        </li>
                                        <li class="mega_items"><a href="{{ route('shop') }}">shop <i class="fa fa-angle-down"></i></a>
                                            <ul class="mega_menu">
                                                <li><a href="#">Shop Layouts</a>
                                                    <ul>
                                                        <li><a href="shop-fullwidth.html">Full Width</a></li>
                                                        <li><a href="shop-fullwidth-list.html">Full Width list</a></li>
                                                        <li><a href="shop-right-sidebar.html">Right Sidebar </a></li>
                                                        <li><a href="shop-right-sidebar-list.html"> Right Sidebar list</a></li>
                                                        <li><a href="shop-list.html">List View</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">other Pages</a>
                                                    <ul>
                                                        <li><a href="portfolio.html">portfolio</a></li>
                                                        <li><a href="portfolio-details.html">portfolio details</a></li>
                                                        <li><a href="{{ route('cart.index') }}">cart</a></li>
                                                        <li><a href="checkout.html">Checkout</a></li>
                                                        <li><a href="my-account.html">my account</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Product Types</a>
                                                    <ul>
                                                        <li><a href="product-details.html">product details</a></li>
                                                        <li><a href="product-sidebar.html">product sidebar</a></li>
                                                        <li><a href="product-gallery.html">product gallery</a></li>
                                                        <li><a href="product-grouped.html">product grouped</a></li>
                                                        <li><a href="variable-product.html">product variable</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">collection</a>
                                                    <ul>
                                                        <li><a href="{{ route('shop') }}">Handbag</a></li>
                                                        <li><a href="{{ route('shop') }}">Accessories</a></li>
                                                        <li><a href="{{ route('shop') }}">Clothing</a></li>
                                                        <li><a href="{{ route('shop') }}">Shoes</a></li>
                                                        <li><a href="{{ route('shop') }}">Check Trousers</a></li>
                                                    </ul>
                                                </li>
                                                <li class="banner_menu"><a href="#"><img src="{{ asset('frontend-assets/img/bg/banner1.jpg') }}" alt=""></a></li>
                                            </ul>
                                        </li>
                                        <li><a href="blog.html">blog <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu pages">
                                                <li><a href="blog-details.html">blog details</a></li>
                                                <li><a href="blog-sidebar.html">blog  Sidebar</a></li>
                                                <li><a href="blog-fullwidth.html">blog fullwidth</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">pages <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu pages">
                                                <li><a href="about.html">About Us</a></li>
                                                <li><a href="services.html">services</a></li>
                                                <li><a href="faq.html">Frequently Questions</a></li>
                                                <li><a href="{{ route('login') }}">login</a></li>
                                                <li><a href="my-account.html">my account</a></li>
                                                <li><a href="wishlist.html">Wishlist</a></li>
                                                <li><a href="404.html">Error 404</a></li>
                                                <li><a href="compare.html">compare</a></li>
                                                <li><a href="privacy-policy.html">privacy policy</a></li>
                                                <li><a href="coming-soon.html">coming soon</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="about.html">About us</a></li>
                                        <li><a href="contact.html">Contact Us</a></li>
                                        <li><a href="#">Sneaker</a></li>
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
