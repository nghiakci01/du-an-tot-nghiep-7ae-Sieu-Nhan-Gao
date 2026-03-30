@if(!isset($categories))
    @php $categories = collect(); @endphp
@endif
@if(!isset($settings))
    @php $settings = []; @endphp
@endif
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
                <li><span>{{ __('messages.contact') }}:</span> {{ $settings['site_phone'] ?? '0354869999' }}</li>
                <li><span>{{ __('messages.offer') }}:</span> {{ __('messages.free_shipping') }}</li>
            </ul>
        </div>
        
        <div class="top_right">
            <ul>
                <li class="top_links">
                    @guest
                        <a href="{{ route('account.index') }}" class="user-account-link">{{ __('messages.my_account') }} <i class="ion-chevron-down"></i></a>
                        <ul class="dropdown_links">
                            <li><a href="{{ route('wishlist.index') }}">{{ __('messages.my_wishlist') }}</a></li>
                            <li><a href="{{ route('account.index') }}">{{ __('messages.my_account') }}</a></li>
                            <li><a href="{{ route('order-tracking.index') }}">{{ __('messages.track_order') }}</a></li>
                            <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                        </ul>
                    @else
                        <a href="#" class="user-account-link" style="display: flex; align-items: center; gap: 8px;">
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
            </ul>
        </div> 
        <div class="search_bar">
            <form action="{{ route('search.index') }}" method="GET">
                <select class="select_option" name="category" aria-label="Select Category">
                    <option selected value="">{{ __('messages.all_products') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <input id="search-input-offcanvas" placeholder="{{ __('messages.search_placeholder') }}" type="text" name="q" autocomplete="off" aria-label="Search">
                <button type="submit" aria-label="Submit Search"><i class="ion-ios-search-strong"></i></button>
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
            @include('frontend.partials.notification-bell')
            @include('frontend.partials.mini-cart')
        </div>
        <div class="offcanvas_footer">
            <span><a href="mailto:{{ $settings['site_email'] ?? 'Elite@gmail.com' }}"><i class="fa fa-envelope-o"></i> {{ $settings['site_email'] ?? 'Elite@gmail.com' }}</a></span>
            <ul>
                <li class="facebook"><a href="{{ $settings['social_facebook'] ?? 'https://www.facebook.com/profile.php?id=61577211110743' }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook"></i></a></li>
                <li class="instagram"><a href="{{ $settings['social_instagram'] ?? 'https://www.instagram.com/' }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-instagram"></i></a></li>
                <li class="twitter"><a href="https://twitter.com/" target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter"></i></a></li>
                <li class="youtube"><a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer"><i class="fa fa-youtube"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<header class="header_area header_six {{ request()->is('/') ? 'is-home' : '' }}">
    <div class="header_middel">
        <div class="container-fluid">
            <div class="middel_inner">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-3">
                        <div class="header_left d-flex align-items-center">
                            <div class="logo">
                                <a href="{{ route('welcome') }}"><img src="{{ asset('frontend-assets/img/logo/logo-elite-new.png') }}" alt="Elite Logo"></a>
                            </div>
                            <div class="main_menu d-none d-lg-block">
                                <nav>
                                    <ul>
                                        <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                                        <li class="mega_items">
                                            <a href="{{ route('shop') }}">{{ __('messages.shop') }} <i class="fa fa-angle-down"></i></a>
                                            <ul class="mega_menu custom-shop-mega">
                                                <li class="custom-shop-mega-left">
                                                    <ul>
                                                        @foreach($categories as $category)
                                                            <li>
                                                                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="parent-cat-link">{{ $category->name }}</a>
                                                                @if($category->children->count() > 0)
                                                                    <ul style="display: block; padding-left: 0; margin-bottom: 20px;">
                                                                        @foreach($category->children as $child)
                                                                            <li style="margin-bottom: 8px !important;">
                                                                                <a href="{{ route('shop', ['category' => $child->slug]) }}"><i class="fa fa-angle-right" style="margin-right: 5px; font-size: 12px; color: #999;"></i> {{ $child->name }}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    <div style="margin-bottom: 20px;"></div>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                <li class="custom-shop-mega-right">
                                                    @php $megaMenuProducts = \App\Models\Product::where('is_active', true)->latest()->take(3)->get(); @endphp
                                                    @foreach($megaMenuProducts as $product)
                                                        <div class="custom-shop-product">
                                                            <a href="{{ route('product.detail', $product->slug) }}">
                                                                <div class="custom-shop-product-img">
                                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                                                </div>
                                                                <h5>{{ Str::limit($product->name, 25) }}</h5>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </li>
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
                    <div class="col-lg-6 col-md-9">
                        <div class="middel_right_info">
                            <div class="search_bar" style="position: relative;">
                                <form action="{{ route('search.index') }}" method="GET">                          
                                    <input id="search-input-desktop" placeholder="{{ __('messages.search_placeholder') }}" type="text" name="q" autocomplete="off" aria-label="Search">
                                    <button type="submit" aria-label="Submit Search"><i class="ion-ios-search-strong"></i></button>
                                </form>
                                <div id="search-suggestions-desktop" class="search-suggestions-dropdown"></div>
                            </div>
                            @include('frontend.partials.user-menu')
                            @include('frontend.partials.notification-bell')
                            @include('frontend.partials.mini-cart')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Header Integrated -->
    <div class="header_bottom sticky-header header_6_sticky">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="main_menu_inner">
                        <div class="main_menu d-flex align-items-center justify-content-between"> 
                            <nav class="flex-grow-1">  
                                <ul>
                                    <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                                    <li class="mega_items">
                                        <a href="{{ route('shop') }}">{{ __('messages.shop') }} <i class="fa fa-angle-down"></i></a>
                                        <ul class="mega_menu custom-shop-mega">
                                            <li class="custom-shop-mega-left">
                                                <ul>
                                                    @foreach($categories as $category)
                                                        <li>
                                                            <a href="{{ route('shop', ['category' => $category->slug]) }}" class="parent-cat-link">{{ $category->name }}</a>
                                                            @if($category->children->count() > 0)
                                                                <ul style="display: block; padding-left: 0; margin-bottom: 20px;">
                                                                    @foreach($category->children as $child)
                                                                        <li style="margin-bottom: 8px !important;">
                                                                            <a href="{{ route('shop', ['category' => $child->slug]) }}">{{ $child->name }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                            <li class="custom-shop-mega-right">
                                                @foreach($megaMenuProducts as $product)
                                                    <div class="custom-shop-product">
                                                        <a href="{{ route('product.detail', $product->slug) }}">
                                                            <div class="custom-shop-product-img">
                                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                                            </div>
                                                            <h5>{{ Str::limit($product->name, 25) }}</h5>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('news') }}">{{ __('messages.news') }}</a></li>
                                    <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                                    <li><a href="{{ route('contact.index') }}">{{ __('messages.contact') }}</a></li>
                                </ul>   
                            </nav> 
                            <div class="sticky_middel_right_info d-flex align-items-center justify-content-end" style="gap: 15px; margin-left: 20px;">
                                @include('frontend.partials.user-menu')
                                @include('frontend.partials.notification-bell')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
