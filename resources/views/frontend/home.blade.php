@extends('layouts.public')

@section('content')
    <!--slider area start-->
    <div class="slider_section slider_section_six mb-30" style="background: #fff;">
        <h1 class="visually-hidden">Elite - E-commerce Fashion Store</h1>
        <div style="padding: 0; width: 1521px; max-width: 100%; margin: 0 auto;">
            <div class="row no-gutters">
                <div class="col-12">
                    <div class="slider_area home_six_slider owl-carousel banner-wide-slider">
                        @if($sliders->count() > 0)
                            @foreach($sliders as $slider)
                                <div class="single_slider" data-bgimg="{{ asset('storage/' . $slider->image) }}">
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}" style="display: block; width: 100%; height: 100%;"></a>
                                    @endif
                                    <div class="slider_content_inner">
                                        <div class="slider_content">
                                            <h2>{{ $slider->title }}</h2>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .visually-hidden {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
            }
        /* Responsive Banner Styles */
        .banner-wide-slider .single_slider {
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            overflow: hidden;
            position: relative;
            width: 100%;
            /* Default height for desktop */
            height: 856px !important; 
        }
        .banner-wide-slider .slider_content_inner {
            display: flex;
            align-items: center;
            height: 100%;
        }
        .banner-wide-slider .owl-nav {
            display: none !important;
        }
        .slider_section_six.mb-30 { margin-bottom: 30px; }

        /* Tablet (lg, md) */
        @media only screen and (max-width: 1199px) {
            .banner-wide-slider .single_slider {
                height: 600px !important;
            }
        }
        @media only screen and (max-width: 991px) {
            .banner-wide-slider .single_slider {
                height: 500px !important;
            }
        }
        /* Mobile (sm, xs) */
        @media only screen and (max-width: 767px) {
            .banner-wide-slider .single_slider {
                /* For aspect ratio close to 16:9 or similar to original width/height */
                height: 350px !important; 
                background-position: center top !important; /* Keep the top part in focus on mobile */
            }
        }
        @media only screen and (max-width: 575px) {
            .banner-wide-slider .single_slider {
                height: 250px !important; 
            }
        }
    </style>
    <!--slider area end-->


    <!--flash sale section start-->
    @if(isset($flashSaleProducts) && $flashSaleProducts->count() > 0)
    <section class="flash-sale-section" style="padding: 30px 0; background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-auto">
                    <h2 style="color: white; font-weight: 900; margin: 0; font-size: 1.8rem;">
                        <i class="fa fa-bolt"></i> FLASH SALE
                    </h2>
                </div>
                <div class="col-auto">
                    <div id="flash-sale-countdown" style="display: flex; gap: 8px;">
                        <div style="background: rgba(0,0,0,0.3); color: white; padding: 6px 12px; border-radius: 6px; text-align: center; min-width: 50px;">
                            <span id="fs-hours" style="font-size: 1.4rem; font-weight: 800;">00</span>
                            <div style="font-size: 10px; opacity: 0.8;">GIỜ</div>
                        </div>
                        <div style="color: white; font-size: 1.4rem; font-weight: 800; line-height: 2.4;">:</div>
                        <div style="background: rgba(0,0,0,0.3); color: white; padding: 6px 12px; border-radius: 6px; text-align: center; min-width: 50px;">
                            <span id="fs-minutes" style="font-size: 1.4rem; font-weight: 800;">00</span>
                            <div style="font-size: 10px; opacity: 0.8;">PHÚT</div>
                        </div>
                        <div style="color: white; font-size: 1.4rem; font-weight: 800; line-height: 2.4;">:</div>
                        <div style="background: rgba(0,0,0,0.3); color: white; padding: 6px 12px; border-radius: 6px; text-align: center; min-width: 50px;">
                            <span id="fs-seconds" style="font-size: 1.4rem; font-weight: 800;">00</span>
                            <div style="font-size: 10px; opacity: 0.8;">GIÂY</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($flashSaleProducts as $fsProduct)
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <div class="single_product" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                        <div class="product_thumb" style="position: relative;">
                            <a href="{{ route('product.detail', $fsProduct->slug) }}">
                                <img src="{{ $fsProduct->image_url }}" alt="{{ $fsProduct->name }}" style="height: 220px; object-fit: cover; width: 100%;">
                            </a>
                            @php
                                $discountPercent = $fsProduct->price > 0 ? round((1 - $fsProduct->sale_price / $fsProduct->price) * 100) : 0;
                            @endphp
                            <span style="position: absolute; top: 10px; left: 10px; background: #ff4b2b; color: white; padding: 4px 10px; border-radius: 4px; font-weight: 800; font-size: 13px;">
                                -{{ $discountPercent }}%
                            </span>
                        </div>
                        <div class="product_content" style="padding: 12px;">
                            <h3 style="margin: 0 0 8px;"><a href="{{ route('product.detail', $fsProduct->slug) }}" style="font-size: 14px;">{{ Str::limit($fsProduct->name, 35) }}</a></h3>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-weight: 800; color: #ff4b2b; font-size: 16px;">{{ number_format($fsProduct->sale_price) }}đ</span>
                                <span style="text-decoration: line-through; color: #999; font-size: 13px;">{{ number_format($fsProduct->price) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var endTimes = @json($flashSaleProducts->pluck('flash_sale_ends_at')->filter()->values());
        if (endTimes.length === 0) return;
        var earliest = new Date(Math.min(...endTimes.map(t => new Date(t).getTime())));
        function updateCountdown() {
            var now = new Date().getTime();
            var diff = earliest.getTime() - now;
            if (diff <= 0) { location.reload(); return; }
            var h = Math.floor(diff / (1000 * 60 * 60));
            var m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            var s = Math.floor((diff % (1000 * 60)) / 1000);
            document.getElementById('fs-hours').textContent = String(h).padStart(2, '0');
            document.getElementById('fs-minutes').textContent = String(m).padStart(2, '0');
            document.getElementById('fs-seconds').textContent = String(s).padStart(2, '0');
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
    </script>
    @endif
    <!--flash sale section end-->

    <!--featured products section area start-->
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
                                        <img loading="lazy" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    @php
                                        $hoverImage = $product->images->firstWhere('image_path', '!=', $product->image);
                                    @endphp
                                    @if($hoverImage)
                                    <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                        <img loading="lazy" src="{{ $hoverImage->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    @endif
                                    <div class="product_action">
                                        <div class="hover_action">
                                           <a href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-plus"></i></a>
                                            <div class="action_button">
                                                <ul>
                                                    <li><a title="{{ __('messages.add_to_cart') }}" href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                    <li><a href="javascript:void(0)" class="add-to-wishlist" data-id="{{ $product->id }}" title="{{ __('messages.add_to_wishlist') }}"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                                </ul>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="quick_button">
                                        <a href="{{ route('product.detail', $product->slug) }}" title="{{ __('messages.quick_view') }}">+ {{ __('messages.quick_view') }}</a>
                                    </div>
                                    <div class="double_base">
                                        @if($product->price < $product->original_price)
                                        <div class="product_sale">
                                            <span>{{ __('messages.sale') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="product_content">
                                    <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
                                    @include('frontend.partials.product-price', ['product' => $product])
                                    @php $ratingAvg = $product->reviews->avg('rating') ?? 0; $ratingCount = $product->reviews->count(); @endphp
                                    <div class="product_ratting" style="margin-top:4px;">
                                        <ul style="display:flex; align-items:center; gap:2px; list-style:none; padding:0; margin:0;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <li><i class="fa {{ $i <= round($ratingAvg) ? 'fa-star' : 'fa-star-o' }}" style="color:#f39c12; font-size:11px;"></i></li>
                                            @endfor
                                            @if($ratingCount > 0)
                                                <li style="font-size:10px; color:#999; margin-left:3px;">({{ $ratingCount }})</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div> 
            </div>
        </div>
    </div>

    </div>
    </section>
    <!--product section area end-->



    <!--product section area start-->
    <section class="product_section womens_product product_section_six bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>{{ __('messages.new_products') }}</h2>
                        <p>{{ __('messages.new_desc') }}</p>
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
                                        <img loading="lazy" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    @php
                                        $hoverImage = $product->images->firstWhere('image_path', '!=', $product->image);
                                    @endphp
                                    @if($hoverImage)
                                    <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                        <img loading="lazy" src="{{ $hoverImage->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    @endif
                                    <div class="product_action">
                                        <div class="hover_action">
                                           <a href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-plus"></i></a>
                                            <div class="action_button">
                                                <ul>
                                                    <li><a title="add to cart" href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                    <li><a href="javascript:void(0)" class="add-to-wishlist" data-id="{{ $product->id }}" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>                                                </ul>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="quick_button">
                                        <a href="{{ route('product.detail', $product->slug) }}" title="{{ __('messages.quick_view') }}">+ {{ __('messages.quick_view') }}</a>
                                    </div>
                                    <div class="double_base">
                                        @if($product->price < $product->original_price)
                                        <div class="product_sale">
                                            <span>{{ __('messages.sale') }}</span>
                                        </div>
                                        @endif
                                        <div class="label_product">
                                            <span>{{ __('messages.new') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="product_content">
                                    <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
                                    @include('frontend.partials.product-price', ['product' => $product])
                                    @php $ratingAvg = $product->reviews->avg('rating') ?? 0; $ratingCount = $product->reviews->count(); @endphp
                                    <div class="product_ratting" style="margin-top:4px;">
                                        <ul style="display:flex; align-items:center; gap:2px; list-style:none; padding:0; margin:0;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <li><i class="fa {{ $i <= round($ratingAvg) ? 'fa-star' : 'fa-star-o' }}" style="color:#f39c12; font-size:11px;"></i></li>
                                            @endfor
                                            @if($ratingCount > 0)
                                                <li style="font-size:10px; color:#999; margin-left:3px;">({{ $ratingCount }})</li>
                                            @endif
                                        </ul>
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
    <!--product section area end-->



    <!--product section area start (Top Wishlisted)-->
    <section class="product_section womens_product product_section_six bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>{{ __('messages.top_wishlisted') }}</h2>
                        <p>{{ __('messages.top_wishlisted_desc') }}</p>
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
                                            <img loading="lazy" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        </a>
                                        @php
                                            $hoverImage = $product->images->firstWhere('image_path', '!=', $product->image);
                                        @endphp
                                        @if($hoverImage)
                                        <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                            <img loading="lazy" src="{{ $hoverImage->image_url }}" alt="{{ $product->name }}">
                                        </a>
                                        @endif
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
                                                            <a href="javascript:void(0)" class="add-to-wishlist" data-id="{{ $product->id }}"
                                                                title="Add to Wishlist">
                                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="quick_button">
                                            <a href="{{ route('product.detail', $product->slug) }}" title="{{ __('messages.quick_view') }}">+ {{ __('messages.quick_view') }}</a>
                                        </div>
                                        <div class="double_base">
                                            @if($product->price < $product->original_price)
                                                <div class="product_sale">
                                                    <span>{{ __('messages.sale') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product_content">
                                        <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                        </h3>
                                        @include('frontend.partials.product-price', ['product' => $product])
                                        @php $ratingAvg = $product->reviews->avg('rating') ?? 0; $ratingCount = $product->reviews->count(); @endphp
                                        <div class="product_ratting" style="margin-top:4px;">
                                            <ul style="display:flex; align-items:center; gap:2px; list-style:none; padding:0; margin:0;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <li><i class="fa {{ $i <= round($ratingAvg) ? 'fa-star' : 'fa-star-o' }}" style="color:#f39c12; font-size:11px;"></i></li>
                                                @endfor
                                                @if($ratingCount > 0)
                                                    <li style="font-size:10px; color:#999; margin-left:3px;">({{ $ratingCount }})</li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div style="font-size: 12px; color: #ff6a28; margin-top: 5px;">
                                            <i class="fa fa-heart"></i> {{ $product->wishlisted_by_count }} {{ __('lượt yêu thích') }}
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

    @if($midBanner && $midBanner->image)
    <!--Middle Banner area start-->
    <section class="middle_banner_section mb-30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="middle_banner_thumb">
                        @if($midBanner->link)
                            <a href="{{ $midBanner->link }}">
                                <img src="{{ asset('storage/' . $midBanner->image) }}" alt="{{ $midBanner->title ?? 'Banner' }}">
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $midBanner->image) }}" alt="{{ $midBanner->title ?? 'Banner' }}">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .middle_banner_thumb img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .middle_banner_thumb img:hover {
            transform: translateY(-5px);
        }
        .middle_banner_section {
            margin-bottom: 40px;
            margin-top: 20px;
        }
    </style>
    <!--Middle Banner area end-->
    @endif

    @if($bannerBottom && $bannerBottom->image)
    <!--Bottom Banner area start-->
    <section class="bottom_banner_section mb-30 mt-10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="banner_thumb">
                        @if($bannerBottom->link)
                            <a href="{{ $bannerBottom->link }}">
                                <img src="{{ asset('storage/' . $bannerBottom->image) }}" alt="{{ $bannerBottom->title ?? 'Bottom Banner' }}" style="width: 100%; height: auto;">
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $bannerBottom->image) }}" alt="{{ $bannerBottom->title ?? 'Bottom Banner' }}" style="width: 100%; height: auto;">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

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
                                        <h2>Túi Xách</h2>
                                    </div>
                                    <div class="modal_price mb-10">
                                        <span class="new_price">1.299.000 đ</span>
                                        <span class="old_price">1.599.000 đ</span>
                                    </div>
                                    <div class="modal_description mb-15">
                                        <p>Sản phẩm chất lượng cao, thiết kế hiện đại và sang trọng. Phù hợp với nhiều phong cách khác nhau.</p>
                                    </div>
                                    <div class="variants_selects">
                                        <div class="variants_size">
                                            <h2>Kích cỡ</h2>
                                            <select class="select_option">
                                                <option selected value="1">S</option>
                                                <option value="1">M</option>
                                                <option value="1">L</option>
                                                <option value="1">XL</option>
                                                <option value="1">XXL</option>
                                            </select>
                                        </div>
                                        <div class="variants_color">
                                            <h2>Màu sắc</h2>
                                            <select class="select_option">
                                                <option selected value="1">Trắng</option>
                                                <option value="1">Đen</option>
                                                <option value="1">Đỏ</option>
                                                <option value="1">Xanh</option>
                                                <option value="1">Nâu</option>
                                            </select>
                                        </div>
                                        <div class="modal_add_to_cart">
                                            <form action="#">
                                                <input min="0" max="100" step="2" value="1" type="number">
                                                <button type="submit">{{ __('messages.add_to_cart') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal_social">
                                        <h2>Chia sẻ sản phẩm</h2>
                                        <ul>
                                            <li class="facebook"><a href="{{ $settings['social_facebook'] ?? 'https://www.facebook.com/profile.php?id=61577211110743' }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook"></i></a></li>
                                            <li class="twitter"><a href="https://twitter.com/" target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter"></i></a></li>
                                            <li class="pinterest"><a href="https://www.pinterest.com/" target="_blank" rel="noopener noreferrer"><i class="fa fa-pinterest"></i></a></li>
                                            <li class="google-plus"><a href="https://plus.google.com/" target="_blank" rel="noopener noreferrer"><i class="fa fa-google-plus"></i></a></li>
                                            <li class="linkedin"><a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer"><i class="fa fa-linkedin"></i></a></li>
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

@endsection
