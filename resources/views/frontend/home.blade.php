@extends('layouts.public')

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
                                   <h2>top trending</h2>
                                    <h1>handbag</h1>
                                    <p>Lorem ipsum dolor amet, consectetur adipisicing <br> elit. Vel similique perspiciatis, tempore unde </p>
                                    <a href="#">Discover Now</a>
                                </div>  
                            </div>     
                        </div>
                        <div class="single_slider" data-bgimg="{{ asset('frontend-assets/img/slider/slider11.jpg') }}">
                           <div class="slider_content_inner">
                                <div class="slider_content">
                                    <h2>new arrivals</h2>
                                    <h1>zip hoodie</h1>
                                    <p>Lorem ipsum dolor amet, consectetur adipisicing <br> elit. Vel similique perspiciatis, tempore unde </p>
                                    <a href="#">Discover Now</a>
                                </div> 
                            </div>   
                        </div>
                        <div class="single_slider" data-bgimg="{{ asset('frontend-assets/img/slider/slider12.jpg') }}">
                           <div class="slider_content_inner">
                                <div class="slider_content">
                                    <h2>top trending</h2>
                                    <h1>clothing</h1>
                                    <p>Lorem ipsum dolor amet, consectetur adipisicing <br> elit. Vel similique perspiciatis, tempore unde </p>
                                    <a href="#">Discover Now</a>
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
                                        <a href="#"><img src="{{ asset('frontend-assets/img/bg/banner18.jpg') }}" alt="#"></a>
                                        <div class="banner_content">
                                           <h1>Men's <br> Summer Sneaker</h1>
                                           <h3>Big Sale Off This Week</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="banner_area">
                                    <div class="banner_thumb">
                                        <a href="#"><img src="{{ asset('frontend-assets/img/bg/banner19.jpg') }}" alt="#"></a>
                                        <div class="banner_content">
                                           <h1>Clothing.No18</h1>
                                           <h3>Sale Off 20% All Store</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="banner_area">
                                    <div class="banner_thumb">
                                        <a href="#"><img src="{{ asset('frontend-assets/img/bg/banner20.jpg') }}" alt="#"></a>
                                        <div class="banner_content">
                                           <h1>Bag.No1</h1>
                                           <h3>Big Sale No Limited</h3>
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
                       <h2>Sản Phẩm Mới</h2>
                       <p>Khám phá những sản phẩm mới nhất của chúng tôi</p>
                   </div>
                </div> 
            </div>    
            <div class="product_area"> 
                <div class="product_container">
                    <div class="row product_column4">
                        @forelse($newProducts as $product)
                            @include('frontend.partials.product-card', ['product' => $product, 'columnClass' => 'col-lg-3'])
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
    <!--product section area end-->

    <!--banner area start-->
    <div class="banner_slider_section section_fullwidth">
       <div class="container-fluid">
           <div class="row ">
               <div class="col-12">
                    <div class="banner_area">
                        <div class="banner_thumb">
                            <a href="#"><img src="{{ asset('frontend-assets/img/bg/banner21.jpg') }}" alt="#"></a>
                        </div>
                    </div>
                </div>
            </div>
       </div>  
    </div>
    <!--banner area end-->

    <!--product section area start-->
    <section class="product_section womens_product product_section_six bottom">
        <div class="container-fluid">
            <div class="row">   
                <div class="col-12">
                   <div class="section_title">
                       <h2>Sản Phẩm Nổi Bật</h2>
                       <p>Những sản phẩm được yêu thích nhất</p>
                   </div>
                </div> 
            </div>    
            <div class="product_area"> 
                <div class="product_container">
                    <div class="row product_row1">
                        @forelse($featuredProducts as $product)
                            @include('frontend.partials.product-card', ['product' => $product, 'columnClass' => 'col-lg-3'])
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
