@extends('layouts.public')

@section('title', 'Shop | FashionStore')

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
                                <h2>Filter by price</h2>
                                <form action="{{ route('shop') }}" method="GET"> 
                                    @if(request('sort'))
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                    @endif
                                    <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
                                    <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', 5000000) }}">

                                    <div id="slider-range"></div>   
                                    <button type="submit">Filter</button>
                                    <input type="text" name="text" id="amount" readonly style="border:0; color:#f6931d; font-weight:bold; width: 100%; margin-top: 10px;" />   
                                </form> 
                            </div>
                            <div class="widget_list widget_categories">
                                <h2>Product categories</h2>
                                <ul>
                                    <li>
                                        <a href="{{ route('shop') }}" class="{{ !request('category') ? 'active' : '' }}">
                                            All Products <span>{{ $totalActiveProducts }}</span>
                                        </a>
                                    </li>
                                    @foreach($categories as $category)
                                        <li>
                                            <a href="{{ route('shop', ['category' => $category->slug]) }}">
                                                {{ $category->name }} <span>{{ $category->products_count }}</span>
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
                            <h1>shop</h1>
                        </div>
                        <div class="shop_toolbar_wrapper">
                            <div class="shop_toolbar_btn">
                                <button data-role="grid_3" type="button" class="active btn-grid-3" data-bs-toggle="tooltip" title="3"></button>
                                <button data-role="grid_4" type="button" class="btn-grid-4" data-bs-toggle="tooltip" title="4"></button>
                                <button data-role="grid_list" type="button" class="btn-list" data-bs-toggle="tooltip" title="List"></button>
                            </div>
                            <div class="niceselect_option">
                                <form class="select_option" action="{{ route('shop') }}" method="GET">
                                    <select name="sort" id="short" onchange="this.form.submit()">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort by latest</option>
                                        <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Sort by popularity</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by average rating</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Sort by price: low to high</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Sort by price: high to low</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Product Name: A-Z</option>
                                    </select>
                                </form>
                            </div>
                            <div class="page_amount">
                                <p>Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</p>
                            </div>
                        </div>
                        <!--shop toolbar end-->
                        
                        <div class="row shop_wrapper">
                            @forelse($products as $product)
                            <div class="col-lg-4 col-md-4 col-12 ">
                                <div class="single_product">
                                    <div class="product_thumb">
                                        <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product15.jpg') }}" alt="{{ $product->name }}">
                                        </a>
                                        <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                            <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : ($product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product16.jpg')) }}" alt="{{ $product->name }}">
                                        </a>
                                        <div class="product_action">
                                            <div class="hover_action">
                                               <a href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-plus"></i></a>
                                                <div class="action_button">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="add to cart" onclick="event.preventDefault(); document.getElementById('add-to-cart-{{ $product->id }}').submit();">
                                                                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                                            </a>
                                                            <form id="add-to-cart-{{ $product->id }}" action="{{ route('cart.add') }}" method="POST" style="display: none;">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <input type="hidden" name="quantity" value="1">
                                                            </form>
                                                        </li>
                                                        <li><a href="#" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                                        <li><a href="#" title="Add to Compare"><i class="fa fa-sliders" aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </div>
                                           </div>
                                        </div>
                                        <div class="quick_button">
                                            <a href="{{ route('product.detail', $product->slug) }}" title="quick_view">+ quick view</a>
                                        </div>

                                        <div class="double_base">
                                            @if($product->price < $product->original_price)
                                            <div class="product_sale">
                                                <span>Sale</span>
                                            </div>
                                            @endif
                                            @if($product->created_at->diffInDays(now()) < 7)
                                            <div class="label_product">
                                                <span>new</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="product_content grid_content">
                                        <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
                                        @include('frontend.partials.product-price', ['product' => $product])
                                    </div>
                                    
                                    <div class="product_content list_content">
                                        <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
                                        <div class="product_ratting">
                                            <ul>
                                                @php $rating = $product->reviews->avg('rating') ?? 0; @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    <li><a href="#"><i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i></a></li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <div class="product_price">
                                            @include('frontend.partials.product-price', ['product' => $product])
                                        </div>
                                        <div class="product_desc">
                                            <p>{{ $product->short_description }}</p>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center">
                                <p>No products found.</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="shop_toolbar t_bottom">
                            <div class="pagination">
                                {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
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
