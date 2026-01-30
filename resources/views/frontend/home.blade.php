@extends('layouts.public')

@section('title', 'Trang chủ | Reid Fashion')

@section('content')

<!--slider area start-->
<div class="slider_section slider_section_six">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="slider_area home_six_slider owl-carousel">
                    <div class="single_slider" data-bgimg="{{ asset('frontend-assets/img/slider/slider10.jpg') }}">
                       <div class="slider_content_inner">
                            <div class="slider_content">
                               <h2>Xu hướng</h2>
                                <h1>Túi xách</h1>
                                <p>Thiết kế hiện đại, sang trọng <br> Nâng tầm phong cách của bạn </p>
                                <a href="{{ route('shop') }}">Khám phá ngay</a>
                            </div>  
                        </div>     
                    </div>
                    <div class="single_slider" data-bgimg="{{ asset('frontend-assets/img/slider/slider11.jpg') }}">
                       <div class="slider_content_inner">
                            <div class="slider_content">
                                <h2>Hàng mới về</h2>
                                <h1>Áo Hoodie</h1>
                                <p>Ấm áp và thời trang <br> Cho mùa đông không lạnh </p>
                                <a href="{{ route('shop') }}">Khám phá ngay</a>
                            </div> 
                        </div>   
                    </div>
                    <div class="single_slider" data-bgimg="{{ asset('frontend-assets/img/slider/slider12.jpg') }}">
                       <div class="slider_content_inner">
                            <div class="slider_content">
                                <h2>Bán chạy nhất</h2>
                                <h1>Thời trang nữ</h1>
                                <p>Phong cách trẻ trung <br> Năng động mỗi ngày </p>
                                <a href="{{ route('shop') }}">Khám phá ngay</a>
                            </div> 
                        </div>         
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!--banner area start-->
                <div class="banner_slider_section">
                    <div class="row ">
                       <div class="col-12">
                            <div class="banner_area banner_top">
                                <div class="banner_thumb">
                                    <a href="{{ route('shop') }}"><img src="{{ asset('frontend-assets/img/bg/banner18.jpg') }}" alt="#"></a>
                                    <div class="banner_content">
                                       <h1>Nam <br> Giày Sneakers</h1>
                                       <h3>Giảm giá cực sốc tuần này</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="banner_area">
                                <div class="banner_thumb">
                                    <a href="{{ route('shop') }}"><img src="{{ asset('frontend-assets/img/bg/banner19.jpg') }}" alt="#"></a>
                                    <div class="banner_content">
                                       <h1>Thời trang</h1>
                                       <h3>Giảm 20%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="banner_area">
                                <div class="banner_thumb">
                                    <a href="{{ route('shop') }}"><img src="{{ asset('frontend-assets/img/bg/banner20.jpg') }}" alt="#"></a>
                                    <div class="banner_content">
                                       <h1>Túi xách</h1>
                                       <h3>Sale lớn</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                   <h2>New Arrivals</h2>
                   <p>Contemporary, minimal and modern designs embody the Lavish Alice handwriting</p>
               </div>
            </div> 
        </div>    
        <div class="product_area"> 
            <div class="product_container">
                <div class="product_column5" style="margin-left: -15px; margin-right: -15px;">
                    @forelse($featuredProducts as $product)
                        @include('frontend.partials.product-card', ['product' => $product])
                    @empty
                        <div class="col-12 text-center">
                            <p>Chưa có sản phẩm nổi bật nào</p>
                        </div>
                    @endforelse
                </div> 
            </div>
        </div>
    </div>
</section>
<!--product section area end-->

    <div class="slider_section slider_section_six">
       <div class="container-fluid p-0">
           <div class="row g-0">
               <div class="col-12 p-0">
                    <div class="banner_area">
                        <div class="banner_thumb">
                            <a href="shop.html"><img src="{{ asset('frontend-assets/img/bg/banner21.jpg') }}" alt="#" style="width: 100%; display: block;"></a>
                        </div>
                    </div>
                </div>
            </div>
       </div>  
    </div>

    <!--new products section start-->
<section class="product_section womens_product product_section_six" style="padding-top: 60px;">
    <div class="container-fluid">
        <div class="row">   
            <div class="col-12">
               <div class="section_title">
                   <h2>Sản phẩm mới</h2>
                   <p>Những sản phẩm mới nhất vừa được cập nhật</p>
               </div>
            </div> 
        </div>    
        <div class="product_area"> 
            <div class="product_container">
                <div class="product_column5" style="margin-left: -15px; margin-right: -15px;">
                    @forelse($newProducts as $product)
                        @include('frontend.partials.product-card', ['product' => $product])
                    @empty
                        <div class="col-12 text-center">
                            <p>Chưa có sản phẩm mới nào</p>
                        </div>
                    @endforelse
                </div> 
            </div>
        </div>
    </div>
</section>
<!--new products section end-->

<!--Instagram area start--> 
<section class="instagram_area instagram_six">
    <div class="container-fluid">
        <div class="row">
           <div class="col-12">
               <div class="section_title">
                   <h2>Follow us On Instagram</h2>
                   <p>Theo dõi chúng tôi để cập nhật xu hướng thời trang mới nhất</p>
               </div>
           </div>
       </div>
       <div class="instagram_home_block">
            <div class="row">
                <div class="instagram_wrapper instagram_column5 owl-carousel">
                   <div class="col-lg-3">
                       <div class="single_instagram">
                           <a href="#"><img src="{{ asset('frontend-assets/img/about/intagram.png') }}" alt=""></a>
                           <div class="instagram_icone">
                               <a class="instagram_pupop" href="{{ asset('frontend-assets/img/about/intagram.png') }}"><i class="fa fa-instagram"></i></a>
                           </div>
                       </div>
                   </div>
                   <div class="col-lg-3">
                       <div class="single_instagram">
                           <a href="#"><img src="{{ asset('frontend-assets/img/about/intagram1.png') }}" alt=""></a>
                           <div class="instagram_icone">
                               <a class="instagram_pupop" href="{{ asset('frontend-assets/img/about/intagram1.png') }}"><i class="fa fa-instagram"></i></a>
                           </div>
                       </div>
                   </div>
                   <div class="col-lg-3">
                       <div class="single_instagram">
                           <a href="#"><img src="{{ asset('frontend-assets/img/about/intagram2.png') }}" alt=""></a>
                           <div class="instagram_icone">
                               <a class="instagram_pupop" href="{{ asset('frontend-assets/img/about/intagram2.png') }}"><i class="fa fa-instagram"></i></a>
                           </div>
                       </div>
                   </div>
                   <div class="col-lg-3">
                       <div class="single_instagram">
                           <a href="#"><img src="{{ asset('frontend-assets/img/about/intagram3(1).png') }}" alt=""></a>
                           <div class="instagram_icone">
                               <a class="instagram_pupop" href="{{ asset('frontend-assets/img/about/intagram3(1).png') }}"><i class="fa fa-instagram"></i></a>
                           </div>
                       </div>
                   </div>
                   <div class="col-lg-3">
                       <div class="single_instagram">
                           <a href="#"><img src="{{ asset('frontend-assets/img/about/intagram4(1).png') }}" alt=""></a>
                           <div class="instagram_icone">
                               <a class="instagram_pupop" href="{{ asset('frontend-assets/img/about/intagram4(1).png') }}"><i class="fa fa-instagram"></i></a>
                           </div>
                       </div>
                   </div>
                   <div class="col-lg-3">
                       <div class="single_instagram">
                           <a href="#"><img src="{{ asset('frontend-assets/img/about/intagram1.png') }}" alt=""></a>
                           <div class="instagram_icone">
                               <a class="instagram_pupop" href="{{ asset('frontend-assets/img/about/intagram1.png') }}"><i class="fa fa-instagram"></i></a>
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

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        /*---slider activation---*/
        // Re-initialize slider specifically for the new HTML structure if needed
        // Assuming main.js handles '.home_six_slider' automatically if it's based on template code
    });
</script>
@endsection
