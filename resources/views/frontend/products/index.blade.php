@extends('layouts.public')

@section('title', (isset($currentCategory) && $currentCategory ? $currentCategory->name . ' - ' : '') . __('messages.shop') . ' | Elite')

@section('content')
<style>
    /* Fix image ratio and layout breaking on hover for Shop page */
    .single_product .product_thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 5;
        overflow: hidden;
        background: #f5f5f5;
    }

    .single_product .product_thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }

    /* Ensure secondary image also fills correctly */
    .single_product .product_thumb a.secondary_img {
        width: 100%;
        height: 100%;
    }

    /* Fix alignment for titles and prices in grid view */
    .product_content.grid_content {
        padding-top: 10px;
        text-align: left;
    }

    .product_content.grid_content h3 {
        margin-bottom: 5px;
        font-size: 14px;
        line-height: 1.2;
        height: 2.4em; /* Max 2 lines height for alignment */
        overflow: hidden;
    }
</style>
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li>/</li>
                            <li>shop</li>
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
                                    <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
                                    <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', 5000000) }}">

                                    <div id="slider-range"></div>   
                                    <button type="submit">{{ __('messages.filter') }}</button>
                                    <input type="text" name="text" id="amount" readonly style="border:0; color:#f6931d; font-weight:bold; width: 100%; margin-top: 10px;" />   
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
                                <h2>{{ __('messages.manufacturer') }}</h2>
                                <ul>
                                    @foreach($brands as $brand)
                                        <li>
                                            @php
                                                $params = request()->all();
                                                $params['brand'] = $brand->slug;
                                            @endphp
                                            <a href="{{ route('shop', $params) }}" class="{{ request('brand') == $brand->slug ? 'active' : '' }}">
                                                {{ $brand->name }} <span>{{ $brand->products_count }}</span>
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
                            <div class="widget_list tag-cloud">
                                <h2>{{ __('messages.popular_tags') }}</h2>
                                <div class="tag_widget">
                                    <ul>
                                        @foreach($tags as $tag)
                                            @php
                                                $params = request()->all();
                                                $params['tag'] = $tag->slug;
                                            @endphp
                                            <li><a href="{{ route('shop', $params) }}" class="{{ request('tag') == $tag->slug ? 'active' : '' }}">{{ $tag->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
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
                            @forelse($products as $product)
                                @include('frontend.partials.product-grid-item', [
                                    'product' => $product,
                                    'columnClass' => 'col-lg-4 col-md-4 col-12',
                                    'contentClass' => 'grid_content',
                                    'showListContent' => true
                                ])
                            @empty
                                <div class="col-12 text-center">
                                    <p>No products found.</p>
                                </div>
                            @endforelse
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
