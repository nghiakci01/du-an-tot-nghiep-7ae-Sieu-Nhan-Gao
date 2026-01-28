
<!--Trending products section area start-->
<section class="product_section womens_product product_section_six bottom">
    <div class="container-fluid">
        <div class="row">   
            <div class="col-12">
               <div class="section_title">
                   <h2>Trending Products</h2>
                   <p>Contemporary, minimal and modern designs embody the Lavish Alice handwriting</p>
               </div>
            </div> 
        </div>    
        <div class="product_area"> 
            <div class="product_container">
                <div class="product_column5">
                    @for ($i = 0; $i < 12; $i++)
                    <div class="col-lg-3">
                        <div class="single_product">
                            <div class="product_thumb">
                                <a class="primary_img" href="product-details.html"><img src="{{ asset('frontend-assets/img/product/product24.jpg') }}" alt=""></a>
                                <a class="secondary_img" href="product-details.html"><img src="{{ asset('frontend-assets/img/product/product25.jpg') }}" alt=""></a>
                                <div class="product_action">
                                    <div class="hover_action">
                                       <a href="#"><i class="fa fa-plus"></i></a>
                                        <div class="action_button">
                                            <ul>
                                                <li><a title="add to cart" href="cart.html"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                <li><a href="wishlist.html" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                                <li><a href="compare.html" title="Add to Compare"><i class="fa fa-sliders" aria-hidden="true"></i></a></li>
                                            </ul>
                                        </div>
                                   </div>
                                </div>
                                <div class="quick_button">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_box" title="quick_view">+ quick view</a>
                                </div>
                            </div>
                            <div class="product_content">
                                <h3><a href="product-details.html">Trending Product {{ $i + 1 }}</a></h3>
                                <span class="current_price">£60.00</span>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div> 
            </div>
        </div>
    </div>
</section>
<!--Trending products section area end-->
