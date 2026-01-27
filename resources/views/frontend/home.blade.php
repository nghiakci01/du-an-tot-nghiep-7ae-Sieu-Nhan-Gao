@extends('layouts.public')

@section('title', 'Trang chủ | Reid Fashion')

@section('content')

<!--slider area start-->
<section class="slider_section slider_s_six">
    <div class="slider_area owl-carousel">
        <div class="single_slider d-flex align-items-center" data-bgimg="{{ asset('frontend-assets/img/slider/slider12.jpg') }}">
             <div class="container">
                 <div class="row">
                     <div class="col-12">
                         <div class="slider_content">
                             <h1>Thời trang Mùa Hè 2026</h1>
                             <p>Giảm giá tới 50% cho bộ sưu tập mới nhất</p>
                             <a class="button" href="{{ route('shop') }}">Mua ngay</a>
                         </div>
                     </div>
                 </div>
             </div>
        </div>
        <div class="single_slider d-flex align-items-center" data-bgimg="{{ asset('frontend-assets/img/slider/slider11.jpg') }}">
             <div class="container">
                 <div class="row">
                     <div class="col-12">
                         <div class="slider_content">
                             <h1>Phong cách Tối giản</h1>
                             <p>Sự thanh lịch đến từ những điều giản đơn</p>
                             <a class="button" href="{{ route('shop') }}">Khám phá</a>
                         </div>
                     </div>
                 </div>
             </div>
        </div>
    </div>
</section>
<!--slider area end-->

<!--banner area start-->
<div class="banner_area banner_six mb-50 mt-50">
    <div class="container">
        <div class="row">
            @foreach($categories->take(2) as $category)
            <div class="col-lg-6 col-md-6">
                <div class="single_banner">
                    <div class="banner_thumb">
                        <a href="{{ route('shop') }}"><img src="{{ $category->image ? asset('storage/' . $category->image) : asset('frontend-assets/img/bg/banner' . ($loop->index + 16) . '.jpg') }}" alt="{{ $category->name }}" style="max-height: 250px; object-fit: cover;"></a> 
                    </div>
                    <div class="banner_content">
                        <h3>{{ $category->name }}</h3>
                        <p>Bộ sưu tập mới nhất</p>
                        <a href="{{ route('shop') }}">Xem ngay</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!--banner area end-->

<!--product area start-->
<div class="product_area product_six mb-45">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section_title">
                   <h2>Sản phẩm Mới nhất</h2>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="product_carousel product_column4 owl-carousel">
                @foreach($latestProducts as $product)
                <div class="col-lg-3">
                    <div class="single_product">
                        <div class="product_thumb">
                            <a class="primary_img" href="{{ route('product.detail', $product->slug) }}"><img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product1.jpg') }}" alt="{{ $product->name }}"></a>
                            <!-- <a class="secondary_img" href="product-details.html"><img src="assets/img/product/product2.jpg" alt=""></a> -->
                            <div class="label_product">
                                <span class="label_sale">Mới</span>
                            </div>
                            <div class="action_links">
                                <ul>
                                    <li class="add_to_cart">
                                        {{-- Quick add logic or link to detail --}}
                                        <a href="{{ route('product.detail', $product->slug) }}" title="Add to Cart">Add to Cart</a>
                                    </li>
                                    <li class="quick_button"><a href="{{ route('product.detail', $product->slug) }}" title="View"><i class="ion-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="product_content">
                            <div class="product_name">
                                <h4><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h4>
                            </div>
                            <div class="price_box"> 
                                <span class="current_price">{{ number_format($product->price) }} đ</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!--product area end-->

<!--newletter area start-->
<div class="newletter_area jumbotron_newletter">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="newletter_container">
                    <div class="newletter_inner">
                        <div class="section_title">
                           <h2>Tham gia cùng chúng tôi</h2>
                           <p>Đăng ký email để nhận mã giảm giá 20% cho đơn hàng đầu tiên.</p>
                        </div>
                        <div class="subscribe_form">
                            <form action="#">
                                <input placeholder="Your email address..." type="text">
                                <button type="submit">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--newletter area end-->

@endsection

@section('scripts')
<script>
    // Since we are using owl-carousel from the template, ensuring it initializes
    /*
    $(document).ready(function() {
         $('.slider_area').owlCarousel({ ... });
         $('.product_column4').owlCarousel({ ... });
    });
    */
   // The main.js from Reid should handle this automatically if the classes match.
   // I matched classes: .slider_area, .product_column4
</script>
@endsection
