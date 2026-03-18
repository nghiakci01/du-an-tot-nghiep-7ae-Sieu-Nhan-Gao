@extends('layouts.public')

@section('title', (isset($currentCategory) && $currentCategory ? $currentCategory->name . ' - ' : '') . __('messages.shop') . ' | Elite')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.shop') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
    <!--shop  area start-->
    <div class="shop_area shop_reverse">
        <div class="container">
            <div class="shop_inner_area">
                <div class="row">
                    <div class="col-lg-3 col-md-12">
                       <!--sidebar widget start-->
                        <div class="sidebar_widget">
                            <div class="widget_list widget_filter">
                                <h2>{{ __('messages.filter_by_price') }}</h2>
                                <form action="{{ route('shop') }}" method="GET"> 
                                    @if(request('sort'))
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                    @endif

                                    <div class="price_slider_amount">
                                        <div id="slider-range"></div>
                                        <div class="price_summary text-center mb-3">
                                            <span id="price_min_text">0đ</span> - <span id="price_max_text">5,000,000đ</span>
                                        </div>
                                        <div class="price_inputs d-flex align-items-center justify-content-between">
                                            <div class="price_input_box">
                                                <input type="text" id="min_price_input" placeholder="Min">
                                                <span class="currency_symbol">đ</span>
                                                <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
                                            </div>
                                            <span class="separator">—</span>
                                            <div class="price_input_box">
                                                <input type="text" id="max_price_input" placeholder="Max">
                                                <span class="currency_symbol">đ</span>
                                                <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', 5000000) }}">
                                            </div>
                                        </div>
                                        <button type="submit" class="mt-3 w-100 product_filter_button">{{ __('messages.filter') }}</button>
                                    </div>
                                </form> 
                            </div>

                            <div class="widget_list widget_categories">
                                <h2>{{ __('messages.product_categories') }}</h2>
                                <ul>
                                    <li>
                                        <a href="{{ route('shop') }}" class="{{ !request('category') ? 'active' : '' }}">
                                            {{ __('messages.all_products') }} <span>{{ $totalActiveProducts }}</span>
                                        </a>
                                    </li>
                                    @foreach($categories as $category)
                                        <li>
                                            @php
                                                $params = request()->all();
                                                $params['category'] = $category->slug;
                                            @endphp
                                            <a href="{{ route('shop', $params) }}" class="{{ request('category') == $category->slug ? 'active' : '' }}">
                                                {{ $category->name }} <span>{{ $category->products_count }}</span>
                                            </a> 
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="widget_list widget_categories">
                                <h2>{{ __('messages.select_by_color') }}</h2>
                                <ul>
                                    @foreach($colors as $color)
                                        <li>
                                            @php
                                                $params = request()->all();
                                                $params['color'] = $color->slug;
                                            @endphp
                                            <a href="{{ route('shop', $params) }}" class="{{ request('color') == $color->slug ? 'active' : '' }}">
                                                {{ $color->name }} <span>{{ $color->products_count }}</span>
                                            </a> 
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="widget_list widget_categories mt-4">
                                <h2>{{ __('messages.select_by_size') }}</h2>
                                <ul>
                                    @foreach($sizes as $size)
                                        <li>
                                            @php
                                                $params = request()->all();
                                                $params['size'] = $size->name;
                                            @endphp
                                            <a href="{{ route('shop', $params) }}" class="{{ request('size') == $size->name ? 'active' : '' }}">
                                                {{ $size->name }} <span>{{ $size->products_count }}</span>
                                            </a> 
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!--sidebar widget end-->
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <!--shop wrapper start-->
                        <!--shop toolbar start-->
                        <div class="shop_title">
                            <h1>{{ __('messages.shop') }}</h1>
                        </div>
                        <div class="shop_toolbar_wrapper">
                            <div class="shop_toolbar_btn">
                                <button data-role="grid_3" type="button" class="active btn-grid-3" data-bs-toggle="tooltip" title="3"></button>
                                <button data-role="grid_4" type="button" class="btn-grid-4" data-bs-toggle="tooltip" title="4"></button>
                                <button data-role="grid_5" type="button" class="btn-grid-5" data-bs-toggle="tooltip" title="5"></button>
                                <button data-role="grid_list" type="button" class="btn-list" data-bs-toggle="tooltip" title="List"></button>
                            </div>
                            <div class="niceselect_option">
                                <form class="select_option" action="{{ route('shop') }}" method="GET">
                                    <select name="sort" id="short" onchange="this.form.submit()">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('messages.sort_by_latest') }}</option>
                                        <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>{{ __('messages.sort_by_popularity') }}</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('messages.sort_by_rating') }}</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('messages.sort_by_price_asc') }}</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('messages.sort_by_price_desc') }}</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('messages.product_name_az') }}</option>
                                    </select>
                                </form>
                            </div>
                            <div class="page_amount">
                                <p>{{ __('messages.showing_results', ['first' => $products->firstItem() ?? 0, 'last' => $products->lastItem() ?? 0, 'total' => $products->total()]) }}</p>
                            </div>
                        </div>
                        <!--shop toolbar end-->
                        
                        <div class="row shop_wrapper">
                            @if(count($products) > 0)
                                @foreach($products as $product)
                                @include('frontend.partials.product-grid-item', [
                                    'product' => $product,
                                    'columnClass' => 'col-lg-4 col-md-4 col-12',
                                    'contentClass' => 'grid_content',
                                    'showListContent' => true
                                ])
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="text-center py-5" style="margin-top: 40px; margin-bottom: 40px;">
                                        <div class="mb-4">
                                            <i class="fa fa-box-open" style="font-size: 80px; color: #e0e0e0;"></i>
                                        </div>
                                        <h3 style="font-size: 22px; color: #333; margin-bottom: 15px; font-weight: 500;">
                                            {{ __('Rất tiếc, không tìm thấy sản phẩm nào') }}
                                        </h3>
                                        <p class="text-muted mb-4" style="font-size: 15px; max-width: 500px; margin: 0 auto 25px auto;">
                                            Vui lòng thử điều chỉnh lại bộ lọc, tìm kiếm với từ khóa khác hoặc xóa các tùy chọn hiện tại để xem thêm nhiều sản phẩm.
                                        </p>
                                        <a href="{{ route('shop') }}" class="btn" style="background: #000; color: #fff; padding: 12px 30px; border-radius: 0; text-transform: uppercase; font-size: 13px; font-weight: 600; letter-spacing: 1px; transition: all 0.3s ease;">
                                            HỦY TẤT CẢ BỘ LỌC
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="shop_toolbar t_bottom">
                            <div class="pagination">
                                {{ $products->withQueryString()->links('vendor.pagination.reid') }}
                            </div>
                        </div>
                        <!--shop toolbar end-->
                        <!--shop wrapper end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--shop  area end-->
@endsection
