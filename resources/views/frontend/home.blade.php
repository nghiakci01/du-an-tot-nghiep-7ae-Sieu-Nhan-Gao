@extends('layouts.public')

@section('content')
    <style>
        /* Nuclear fix: Direct embedded CSS for the 10-product grid */
        .custom_product_grid_10 {
            display: -webkit-box !important;
            display: -ms-flexbox !important;
            display: flex !important;
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important;
            margin-left: -15px !important;
            margin-right: -15px !important;
        }

        .custom_product_grid_10 .product_item_5 {
            position: relative !important;
            width: 100% !important;
            padding-right: 15px !important;
            padding-left: 15px !important;
            -webkit-box-flex: 0 !important;
            -ms-flex: 0 0 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            margin-bottom: 30px;
        }

        @media (min-width: 576px) {
            .custom_product_grid_10 .product_item_5 {
                -ms-flex: 0 0 50% !important;
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }

        @media (min-width: 768px) {
            .custom_product_grid_10 .product_item_5 {
                -ms-flex: 0 0 33.333333% !important;
                flex: 0 0 33.333333% !important;
                max-width: 33.333333% !important;
            }
        }

        @media (min-width: 992px) {
            .custom_product_grid_10 .product_item_5 {
                -ms-flex: 0 0 25% !important;
                flex: 0 0 25% !important;
                max-width: 25% !important;
            }
        }

        @media (min-width: 1200px) {
            .custom_product_grid_10 .product_item_5 {
                -ms-flex: 0 0 20% !important;
                flex: 0 0 20% !important;
                max-width: 20% !important;
            }
        }

        /* Fix image ratio and layout breaking on hover */
        .single_product .product_thumb {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            background: #f5f5f5;
            /* Placeholder color for loading */
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

        /* Fix alignment for titles and prices */
        .product_content {
            padding-top: 10px;
            text-align: left;
        }

    .product_content h3 {
        margin-bottom: 5px;
        font-size: 14px;
        line-height: 1.2;
        height: 2.4em; /* Max 2 lines height for alignment */
        overflow: hidden;
    }

    /* Banner & Slider Custom Refining */
    @media (min-width: 992px) {
        /* Desktop: Ensure slider and side banners have matching heights */
        .home_six_slider .single_slider {
            height: 600px !important; /* Fixed base height for slider */
            background-size: cover !important;
            background-position: center !important;
        }

        .banner_slider_section .banner_area.banner_top .banner_thumb img {
            height: 340px !important; /* Top big banner */
            object-fit: cover !important;
            width: 100%;
        }

        .banner_slider_section .banner_area:not(.banner_top) .banner_thumb img {
            height: 240px !important; /* Two small banners below */
            object-fit: cover !important;
            width: 100%;
        }
    }

    /* General aspect ratios for consistency */
    .banner_thumb img {
        width: 100%;
        object-fit: cover !important;
    }

    /* Smooth slider content overlay */
    .slider_content {
        background: rgba(255, 255, 255, 0.7);
        padding: 30px;
        display: inline-block;
        border-radius: 4px;
    }

    /* Bottom banner refinement */
    .section_fullwidth .banner_thumb img {
        aspect-ratio: 21 / 6;
        width: 100%;
        object-fit: cover !important;
    }
</style>
    <!--slider area start-->
    <div class="slider_section slider_section_six">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="slider_area home_six_slider owl-carousel">
                        @foreach($sliders as $slider)
                        <div class="single_slider" data-bgimg="{{ asset('storage/' . $slider->image) }}">
                            <div class="slider_content_inner text-center">
                                <div class="slider_content">
                                    <h2>{{ $slider->title }}</h2>
                                    <h1>{{ $slider->subtitle }}</h1>
                                    <p>{!! nl2br(e($slider->content)) !!}</p>
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}">Khám phá ngay</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <!--banner area start-->
                    <div class="banner_slider_section">
                        <div class="row">
                            @if($bannerTop->count() > 0)
                                @php $firstBanner = $bannerTop->first(); @endphp
                                <div class="col-12">
                                    <div class="banner_area banner_top">
                                        <div class="banner_thumb">
                                            <a href="{{ $firstBanner->link ?? '#' }}">
                                                <img src="{{ asset('storage/' . $firstBanner->image) }}" alt="{{ $firstBanner->title }}">
                                            </a>
                                            <div class="banner_content text-center">
                                                <h1>{!! nl2br(e($firstBanner->title)) !!}</h1>
                                                <h3>{{ $firstBanner->subtitle }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @foreach($bannerTop->skip(1) as $banner)
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="banner_area">
                                            <div class="banner_thumb">
                                                <a href="{{ $banner->link ?? '#' }}">
                                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                                                </a>
                                                <div class="banner_content text-center">
                                                    <h1>{!! nl2br(e($banner->title)) !!}</h1>
                                                    <h3>{{ $banner->subtitle }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <!--banner area end-->
                </div>
            </div>
        </div>
    </div>
    <!--slider area end-->

    <!--product section area start-->
    <section class="product_section womens_product product_section_six">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>{{ __('messages.featured_products') }}</h2>
                        <p>{{ __('messages.featured_desc') }}</p>
                    </div>
                </div>
            </div>
            <div class="product_area">
                <div class="product_container">
                    <div class="row product_column5">
                        @foreach($featuredProducts as $product)
                        <div class="col-lg-3">
                            <div class="single_product">
                                <div class="product_thumb">
                                    <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                        @php
                                            $secondaryImage = $product->images->count() > 0 
                                                ? $product->images->first()?->image_url 
                                                : $product->image_url;
                                        @endphp
                                        <img src="{{ $secondaryImage }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="product_action">
                                        <div class="hover_action">
                                           <a href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-plus"></i></a>
                                            <div class="action_button">
                                                <ul>
                                                    <li><a title="add to cart" href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                    <li><a href="#" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                                </ul>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="quick_button">
                                        <a href="{{ route('product.detail', $product->slug) }}" title="quick_view">+ quick view</a>
                                    </div>
                                    @if($product->price < $product->original_price)
                                    <div class="double_base">
                                        <div class="product_sale">
                                            <span>Sale</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="product_content">
                                    <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
                                    @include('frontend.partials.product-price', ['product' => $product])
                                </div>
                            </div>
                        </div>
                        @endforeach                    </div>
                </div> 
            </div>
        </div>
    </div>

    </div>
    </section>
    <!--product section area end-->

    <!--banner area start-->
    @if($bannerBottom)
    <div class="banner_slider_section section_fullwidth">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="banner_area">
                        <div class="banner_thumb">
                            <a href="{{ $bannerBottom->link ?? '#' }}">
                                <img src="{{ asset('storage/' . $bannerBottom->image) }}" alt="{{ $bannerBottom->title }}">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!--banner area end-->

    <!--product section area start-->
    <section class="product_section womens_product product_section_six bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>New Arrivals</h2>
                        <p>New products with modern, minimalist design and full of charm.</p>
                    </div>
                </div>
            </div>
            <div class="product_area">
                <div class="product_container">
                    <div class="row product_slick_column5">
                        @foreach($newProducts as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="single_product">
                                <div class="product_thumb">
                                    <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                        @php
                                            $secondaryImage = $product->images->count() > 0 
                                                ? $product->images->first()?->image_url 
                                                : $product->image_url;
                                        @endphp
                                        <img src="{{ $secondaryImage }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="product_action">
                                        <div class="hover_action">
                                           <a href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-plus"></i></a>
                                            <div class="action_button">
                                                <ul>
                                                    <li><a title="add to cart" href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                    <li><a href="#" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>                                                </ul>
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
                                        <div class="label_product">
                                            <span>new</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="product_content">
                                    <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
                                    @include('frontend.partials.product-price', ['product' => $product])
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--product section area end-->

    <!--product section area end-->

    <!--product section area start (Top Wishlisted)-->
    <section class="product_section womens_product product_section_six bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>Customer Favorites</h2>
                        <p>Products that customers like and love the most.</p>
                    </div>
                </div>
            </div>
            <div class="product_area">
                <div class="product_container">
                    <div class="row product_slick_column5">
                        @foreach($topWishlisted as $product)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="single_product">
                                    <div class="product_thumb">
                                        <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        </a>
                                        <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                            @php
                                                $secondaryImage = $product->images->count() > 0 
                                                    ? $product->images->first()?->image_url 
                                                    : $product->image_url;
                                            @endphp
                                            <img src="{{ $secondaryImage }}" alt="{{ $product->name }}">
                                        </a>
                                        <div class="product_action">
                                            <div class="hover_action">
                                                <a href="{{ route('product.detail', $product->slug) }}"><i
                                                        class="fa fa-plus"></i></a>
                                                <div class="action_button">
                                                    <ul>
                                                        <li><a title="add to cart"
                                                                href="{{ route('product.detail', $product->slug) }}"><i
                                                                    class="fa fa-shopping-basket" aria-hidden="true"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="add-to-wishlist" data-id="{{ $product->id }}"
                                                                title="Add to Wishlist">
                                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="quick_button">
                                            <a href="{{ route('product.detail', $product->slug) }}" title="quick_view">+ quick
                                                view</a>
                                        </div>
                                        <div class="double_base">
                                            @if($product->price < $product->original_price)
                                                <div class="product_sale">
                                                    <span>Sale</span>
                                                </div>
                                            @endif
                                            <div class="label_product">
                                                <span>Top</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product_content">
                                        <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                        </h3>
                                        @include('frontend.partials.product-price', ['product' => $product])
                                        <div style="font-size: 12px; color: #ff6a28; margin-top: 5px;">
                                            <i class="fa fa-heart"></i> {{ $product->wishlisted_by_count }} lượt yêu thích
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--product section area end (Top Wishlisted)-->

    <!--Instagram area start-->
    <section class="instagram_area instagram_six">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>Follow us On Instagram</h2>
                        <p>Contemporary, minimal and modern designs embody the Lavish Alice handwriting</p>
                    </div>
                </div>
            </div>
            <div class="instagram_home_block">
                <div class="row">
                    <div class="instagram_wrapper instagram_column5 owl-carousel">
                        <div class="col-lg-3">
                            <div class="single_instagram">
                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram.png" alt=""></a>
                                <div class="instagram_icone">
                                    <a class="instagram_pupop"
                                        href="{{ asset('frontend-assets') }}/img/about/intagram.png"><i
                                            class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="single_instagram">
                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram1.png" alt=""></a>
                                <div class="instagram_icone">
                                    <a class="instagram_pupop"
                                        href="{{ asset('frontend-assets') }}/img/about/intagram1.png"><i
                                            class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="single_instagram">
                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram2.png" alt=""></a>
                                <div class="instagram_icone">
                                    <a class="instagram_pupop"
                                        href="{{ asset('frontend-assets') }}/img/about/intagram2.png"><i
                                            class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="single_instagram">
                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram3(1).png" alt=""></a>
                                <div class="instagram_icone">
                                    <a class="instagram_pupop"
                                        href="{{ asset('frontend-assets') }}/img/about/intagram3(1).png"><i
                                            class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="single_instagram">
                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram4(1).png" alt=""></a>
                                <div class="instagram_icone">
                                    <a class="instagram_pupop"
                                        href="{{ asset('frontend-assets') }}/img/about/intagram4(1).png"><i
                                            class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="single_instagram">
                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram1.png" alt=""></a>
                                <div class="instagram_icone">
                                    <a class="instagram_pupop"
                                        href="{{ asset('frontend-assets') }}/img/about/intagram1.png"><i
                                            class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text_follow">
                            <a href="#">#Follow us on Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Instagram area end-->

    <!-- modal area start-->
    <div class="modal fade" id="modal_box" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal_body">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <div class="modal_tab">
                                    <div class="tab-content product-details-large">
                                        <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img
                                                        src="{{ asset('frontend-assets') }}/img/product/product4.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab2" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img
                                                        src="{{ asset('frontend-assets') }}/img/product/product6.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab3" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img
                                                        src="{{ asset('frontend-assets') }}/img/product/product8.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab4" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img
                                                        src="{{ asset('frontend-assets') }}/img/product/product2.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab5" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img
                                                        src="{{ asset('frontend-assets') }}/img/product/product12.jpg"
                                                        alt=""></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal_tab_button">
                                        <ul class="nav product_navactive owl-carousel" role="tablist">
                                            <li>
                                                <a class="nav-link active" data-bs-toggle="tab" href="#tab1" role="tab"
                                                    aria-controls="tab1" aria-selected="false"><img
                                                        src="{{ asset('frontend-assets') }}/img/s-product/product3.jpg"
                                                        alt=""></a>
                                            </li>
                                            <li>
                                                <a class="nav-link" data-bs-toggle="tab" href="#tab2" role="tab"
                                                    aria-controls="tab2" aria-selected="false"><img
                                                        src="{{ asset('frontend-assets') }}/img/s-product/product.jpg"
                                                        alt=""></a>
                                            </li>
                                            <li>
                                                <a class="nav-link button_three" data-bs-toggle="tab" href="#tab3"
                                                    role="tab" aria-controls="tab3" aria-selected="false"><img
                                                        src="{{ asset('frontend-assets') }}/img/s-product/product2.jpg"
                                                        alt=""></a>
                                            </li>
                                            <li>
                                                <a class="nav-link" data-bs-toggle="tab" href="#tab4" role="tab"
                                                    aria-controls="tab4" aria-selected="false"><img
                                                        src="{{ asset('frontend-assets') }}/img/s-product/product4.jpg"
                                                        alt=""></a>
                                            </li>
                                            <li>
                                                <a class="nav-link" data-bs-toggle="tab" href="#tab5" role="tab"
                                                    aria-controls="tab5" aria-selected="false"><img
                                                        src="{{ asset('frontend-assets') }}/img/s-product/product5.jpg"
                                                        alt=""></a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <div class="modal_right">
                                    <div class="modal_title mb-10">
                                        <h2>Handbag feugiat</h2>
                                    </div>
                                    <div class="modal_price mb-10">
                                        <span class="new_price">$64.99</span>
                                        <span class="old_price">$78.99</span>
                                    </div>
                                    <div class="modal_description mb-15">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia iste laborum
                                            ad impedit pariatur esse optio tempora sint ullam autem deleniti nam in quos qui
                                            nemo ipsum numquam, reiciendis maiores quidem aperiam, rerum vel recusandae </p>
                                    </div>
                                    <div class="variants_selects">
                                        <div class="variants_size">
                                            <h2>size</h2>
                                            <select class="select_option">
                                                <option selected value="1">s</option>
                                                <option value="1">m</option>
                                                <option value="1">l</option>
                                                <option value="1">xl</option>
                                                <option value="1">xxl</option>
                                            </select>
                                        </div>
                                        <div class="variants_color">
                                            <h2>color</h2>
                                            <select class="select_option">
                                                <option selected value="1">purple</option>
                                                <option value="1">violet</option>
                                                <option value="1">black</option>
                                                <option value="1">pink</option>
                                                <option value="1">orange</option>
                                            </select>
                                        </div>
                                        <div class="modal_add_to_cart">
                                            <form action="#">
                                                <input min="0" max="100" step="2" value="1" type="number">
                                                <button type="submit">add to cart</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal_social">
                                        <h2>Share this product</h2>
                                        <ul>
                                            <li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                            <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                            <li class="pinterest"><a href="#"><i class="fa fa-pinterest"></i></a></li>
                                            <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                            <li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal area end-->
    <!-- modal area end-->

    @section('scripts')
        <script>
            $(document).ready(function () {
                $('.add-to-wishlist').click(function (e) {
                    e.preventDefault();
                    var productId = $(this).data('id');
                    var icon = $(this).find('i');

                    $.ajax({
                        url: '{{ route("wishlist.add") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId
                        },
                        success: function (response) {
                            if (response.status === 'success' || response.status === 'info') {
                                // Change icon to filled heart
                                icon.removeClass('fa-heart-o').addClass('fa-heart').css('color', 'red');
                                alert(response.message);
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 401) {
                                window.location.href = "{{ route('login') }}";
                            } else {
                                alert('Có lỗi xảy ra, vui lòng thử lại!');
                            }
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection
