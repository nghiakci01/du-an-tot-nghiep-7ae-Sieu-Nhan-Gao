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
        background: #f5f5f5; /* Placeholder color for loading */
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

    /* Custom Slider Arrows to match user image */
    .product_section .prev_arrow, 
    .product_section .next_arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: transparent;
        border: none;
        font-size: 50px; /* Large arrow */
        color: #cccccc; /* Light grey */
        cursor: pointer;
        transition: color 0.3s;
        padding: 0;
        line-height: 1;
    }

    .product_section .prev_arrow {
        left: -40px;
    }

    .product_section .next_arrow {
        right: -40px;
    }

    .product_section .prev_arrow:hover, 
    .product_section .next_arrow:hover {
        color: #333;
    }

    /* Adjust container to give space for arrows */
    .product_container {
        position: relative;
        padding: 0 50px;
    }

    @media (max-width: 1200px) {
        .product_section .prev_arrow { left: -10px; }
        .product_section .next_arrow { right: -10px; }
        .product_container { padding: 0 20px; }
    }
</style>
    <!--slider area start-->
    <div class="slider_section slider_section_six">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="slider_area home_six_slider owl-carousel">
                        <div class="single_slider" data-bgimg="{{ asset('frontend-assets') }}/img/slider/slider10.jpg">
                           <div class="slider_content_inner">
                                <div class="slider_content">
                                   <h2>top trending</h2>
                                    <h1>handbag</h1>
                                    <p>Lorem ipsum dolor amet, consectetur adipisicing <br> elit. Vel similique perspiciatis, tempore unde </p>
                                    <a href="shop.html">Discover Now</a>
                                </div>  
                            </div>     
                        </div>
                        <div class="single_slider" data-bgimg="{{ asset('frontend-assets') }}/img/slider/slider11.jpg">
                           <div class="slider_content_inner">
                                <div class="slider_content">
                                    <h2>Featured Products</h2>
                                    <h1>zip hoodie</h1>
                                    <p>Lorem ipsum dolor amet, consectetur adipisicing <br> elit. Vel similique perspiciatis, tempore unde </p>
                                    <a href="shop.html">Discover Now</a>
                                </div> 
                            </div>   
                        </div>
                        <div class="single_slider" data-bgimg="{{ asset('frontend-assets') }}/img/slider/slider12.jpg">
                           <div class="slider_content_inner">
                                <div class="slider_content">
                                    <h2>top trending</h2>
                                    <h1>clothing</h1>
                                    <p>Lorem ipsum dolor amet, consectetur adipisicing <br> elit. Vel similique perspiciatis, tempore unde </p>
                                    <a href="shop.html">Discover Now</a>
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
                                        <a href="shop.html"><img src="{{ asset('frontend-assets') }}/img/bg/banner18.jpg" alt="#"></a>
                                        <div class="banner_content">
                                           <h1>Men’s <br> Summer Sneaker</h1>
                                           <h3>Big Sale Off This Week</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="banner_area">
                                    <div class="banner_thumb">
                                        <a href="shop.html"><img src="{{ asset('frontend-assets') }}/img/bg/banner19.jpg" alt="#"></a>
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
                                        <a href="shop.html"><img src="{{ asset('frontend-assets') }}/img/bg/banner20.jpg" alt="#"></a>
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
                       <h2>Sản Phẩm Nổi Bật</h2>
                       <p>Modern, minimalist design, bringing a delicate and trendy impression.</p>
                   </div>
                </div> 
            </div>    
            <div class="product_area"> 
                <div class="product_container">
                    <div class="product_column5 row">
                        @foreach($featuredProducts as $product)
                            @include('frontend.partials.product-grid-item', [
                                'product' => $product,
                                'columnClass' => null,
                                'contentClass' => 'grid_content'
                            ])
                        @endforeach
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
                            <a href="shop.html"><img src="{{ asset('frontend-assets') }}/img/bg/banner21.jpg" alt="#"></a>
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
                       <h2>Sản Phẩm Mới</h2>
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
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}">
                                    </a>
                                    <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                                        <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : ($product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg')) }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="product_action">
                                        <div class="hover_action">
                                           <a href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-plus"></i></a>
                                            <div class="action_button">
                                                <ul>
                                                    <li><a title="add to cart" href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
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
                                   <a class="instagram_pupop" href="{{ asset('frontend-assets') }}/img/about/intagram.png"><i class="fa fa-instagram"></i></a>
                               </div>
                           </div>
                       </div>
                       <div class="col-lg-3">
                           <div class="single_instagram">
                               <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram1.png" alt=""></a>
                               <div class="instagram_icone">
                                   <a class="instagram_pupop" href="{{ asset('frontend-assets') }}/img/about/intagram1.png"><i class="fa fa-instagram"></i></a>
                               </div>
                           </div>
                       </div>
                       <div class="col-lg-3">
                           <div class="single_instagram">
                               <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram2.png" alt=""></a>
                               <div class="instagram_icone">
                                   <a class="instagram_pupop" href="{{ asset('frontend-assets') }}/img/about/intagram2.png"><i class="fa fa-instagram"></i></a>
                               </div>
                           </div>
                       </div>
                       <div class="col-lg-3">
                           <div class="single_instagram">
                               <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram3(1).png" alt=""></a>
                               <div class="instagram_icone">
                                   <a class="instagram_pupop" href="{{ asset('frontend-assets') }}/img/about/intagram3(1).png"><i class="fa fa-instagram"></i></a>
                               </div>
                           </div>
                       </div>
                       <div class="col-lg-3">
                           <div class="single_instagram">
                               <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram4(1).png" alt=""></a>
                               <div class="instagram_icone">
                                   <a class="instagram_pupop" href="{{ asset('frontend-assets') }}/img/about/intagram4(1).png"><i class="fa fa-instagram"></i></a>
                               </div>
                           </div>
                       </div>
                       <div class="col-lg-3">
                           <div class="single_instagram">
                               <a href="#"><img src="{{ asset('frontend-assets') }}/img/about/intagram1.png" alt=""></a>
                               <div class="instagram_icone">
                                   <a class="instagram_pupop" href="{{ asset('frontend-assets') }}/img/about/intagram1.png"><i class="fa fa-instagram"></i></a>
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
    <div class="modal fade" id="modal_box" tabindex="-1" role="dialog"  aria-hidden="true">
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
                                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" >
                                            <div class="modal_tab_img">
                                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/product/product4.jpg" alt=""></a>    
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab2" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/product/product6.jpg" alt=""></a>    
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab3" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/product/product8.jpg" alt=""></a>    
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab4" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/product/product2.jpg" alt=""></a>    
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab5" role="tabpanel">
                                            <div class="modal_tab_img">
                                                <a href="#"><img src="{{ asset('frontend-assets') }}/img/product/product12.jpg" alt=""></a>    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal_tab_button">    
                                        <ul class="nav product_navactive owl-carousel" role="tablist">
                                            <li >
                                                <a class="nav-link active" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="false"><img src="{{ asset('frontend-assets') }}/img/s-product/product3.jpg" alt=""></a>
                                            </li>
                                            <li>
                                                 <a class="nav-link" data-bs-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false"><img src="{{ asset('frontend-assets') }}/img/s-product/product.jpg" alt=""></a>
                                            </li>
                                            <li>
                                               <a class="nav-link button_three" data-bs-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false"><img src="{{ asset('frontend-assets') }}/img/s-product/product2.jpg" alt=""></a>
                                            </li>
                                            <li>
                                               <a class="nav-link" data-bs-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false"><img src="{{ asset('frontend-assets') }}/img/s-product/product4.jpg" alt=""></a>
                                            </li>
                                            <li>
                                               <a class="nav-link" data-bs-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false"><img src="{{ asset('frontend-assets') }}/img/s-product/product5.jpg" alt=""></a>
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
                                        <span class="old_price" >$78.99</span>    
                                    </div>
                                    <div class="modal_description mb-15">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia iste laborum ad impedit pariatur esse optio tempora sint ullam autem deleniti nam in quos qui nemo ipsum numquam, reiciendis maiores quidem aperiam, rerum vel recusandae </p>    
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
@endsection
