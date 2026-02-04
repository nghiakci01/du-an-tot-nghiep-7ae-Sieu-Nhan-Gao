@extends('layouts.public')

@section('title', $product->name . ' | Reid')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area product_bread">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">home</a></li>
                            <li>/</li>
                            <li><a href="{{ route('shop') }}">shop</a></li>
                            <li>/</li>
                            <li>{{ $product->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
    <!--product details start-->
    <div class="product_details">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                   <div class="product-details-tab">

                        <div class="single-zoom-no-hover"> <!-- Changed class from single-zoom, removed id="img-1" -->
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#productImageModal">
                                <img id="current-product-img" src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" alt="{{ $product->name }}">
                            </a>
                        </div>

                        <div class="single-zoom-thumb">
                            <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                                <!-- Main Image in Thumbnail -->
                                <li>
                                    <a href="javascript:void(0)" class="elevatezoom-gallery active" data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" alt="{{ $product->name }}"/>
                                    </a>
                                </li>
                                <!-- Gallery Images -->
                                @foreach($product->images as $image)
                                <li>
                                    <a href="javascript:void(0)" class="elevatezoom-gallery" data-image="{{ asset('storage/' . $image->image_path) }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="zo-th-{{ $loop->iteration }}"/>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Product Image Modal -->
                <div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="background: transparent; border: none;">
                            <div class="modal-body p-0 text-center position-relative">
                                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1051; background-color: white; opacity: 0.8;"></button>
                                <img id="modal-product-img" src="" class="img-fluid" style="max-height: 90vh; border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Handle thumbnail click to switch main image
                        const thumbnails = document.querySelectorAll('.elevatezoom-gallery');
                        const mainImg = document.getElementById('current-product-img');
                        const modalImg = document.getElementById('modal-product-img');

                        thumbnails.forEach(thumb => {
                            thumb.addEventListener('click', function(e) {
                                e.preventDefault();
                                
                                // Update active class
                                thumbnails.forEach(t => t.classList.remove('active'));
                                this.classList.add('active');

                                // Get image source
                                const newSrc = this.getAttribute('data-image');
                                
                                // Update main image
                                mainImg.src = newSrc;
                            });
                        });

                        // Update modal image when modal opens
                        const imageModal = document.getElementById('productImageModal');
                        if (imageModal) {
                            imageModal.addEventListener('show.bs.modal', function () {
                                modalImg.src = mainImg.src;
                            });
                        }
                    });
                </script>
                <div class="col-lg-7 col-md-7">
                    <div class="product_d_right">
                       <form action="{{ route('cart.add') }}" method="POST">
                           @csrf
                           <input type="hidden" name="product_id" value="{{ $product->id }}">
                           
                            <h1>{{ $product->name }}</h1>
                            <div class=" product_ratting">
                                <ul>
                                    <!-- Dynamic Stars -->
                                    @php $rating = $product->reviews->avg('rating') ?? 0; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <li><a href="#"><i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i></a></li>
                                    @endfor
                                    <li class="review"><a href="#reviews"> {{ $product->reviews->count() }} reviews </a></li>
                                </ul>
                            </div>
                            <div class="product_price">
                                @if($product->variants->isNotEmpty())
                                    <span class="current_price">
                                        @if($product->variants->min('price') == $product->variants->max('price'))
                                            {{ number_format($product->variants->min('price')) }} đ
                                        @else
                                            {{ number_format($product->variants->min('price')) }} - {{ number_format($product->variants->max('price')) }} đ
                                        @endif
                                    </span>
                                @elseif($product->sale_price)
                                    <span class="current_price">{{ number_format($product->sale_price) }} đ</span>
                                    <span class="old_price" style="text-decoration: line-through; color: #999; margin-left: 10px;">{{ number_format($product->price) }} đ</span>
                                @else
                                    <span class="current_price">{{ number_format($product->price) }} đ</span>
                                @endif
                            </div>

                            <div class="product_desc">
                                <p>{{ $product->short_description }}</p>
                            </div>

                            @if($product->variants->count() > 0)
                                @php
                                    $uniqueSizes = $product->variants->pluck('sizeRelationship')->unique('id');
                                    $uniqueColors = $product->variants->pluck('colorRelationship')->unique('id');
                                @endphp


                                <div class="product_variant size">
                                    <h3>Size</h3>
                                    <select class="niceselect_option" id="select_size">
                                        <option selected value="">Choose Size</option>
                                        @foreach($uniqueSizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                
                                <div class="product_variant color">
                                    <h3>Color</h3>
                                    <select class="niceselect_option" id="select_color">
                                        <option selected value="">Choose Color</option>
                                        @foreach($uniqueColors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <input type="hidden" id="variant_select" name="variant_id" required>
                                <div id="variant-message" class="text-danger mt-2 mb-3" style="display:none; font-weight: bold;"></div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const variants = @json($product->variants);
                                        const sizeSelect = document.getElementById('select_size');
                                        const colorSelect = document.getElementById('select_color');
                                        const variantInput = document.getElementById('variant_select');
                                        const msg = document.getElementById('variant-message');
                                        const addToCartBtn = document.querySelector('button[type="submit"]');
                                        const priceContainer = document.querySelector('.product_price');
                                        
                                        // Store original price HTML to revert if needed
                                        const originalPriceHtml = priceContainer.innerHTML;
                                        
                                        let selectedSize = null;
                                        let selectedColor = null;

                                        // Helper to format currency
                                        const formatCurrency = (amount) => {
                                            return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
                                        };
                                        
                                        function checkSelection() {
                                            selectedSize = sizeSelect.value;
                                            selectedColor = colorSelect.value;

                                            if (selectedSize && selectedColor) {
                                                const matchedVariant = variants.find(v => v.size_id == selectedSize && v.color_id == selectedColor);

                                                
                                                if (matchedVariant) {
                                                    // Dynamic Price Update
                                                    // Priority: Variant Sale Price -> Variant Regular Price -> Original Product Logic
                                                    let html = '';
                                                    let finalPrice = matchedVariant.price; 
                                                    // If variant has specific price override (not null)
                                                    if (matchedVariant.price !== null) {
                                                        if (matchedVariant.sale_price !== null) {
                                                            html = `<span class="current_price">${formatCurrency(matchedVariant.sale_price)}</span>
                                                                    <span class="old_price" style="text-decoration: line-through; color: #999; margin-left: 10px;">${formatCurrency(matchedVariant.price)}</span>`;
                                                        } else {
                                                            html = `<span class="current_price">${formatCurrency(matchedVariant.price)}</span>`;
                                                        }
                                                        priceContainer.innerHTML = html;
                                                    } else {
                                                        // Fallback to original if variant prices are null (inherit from parent)
                                                        priceContainer.innerHTML = originalPriceHtml;
                                                    }

                                                    if (matchedVariant.stock_quantity > 0) {
                                                        variantInput.value = matchedVariant.id;
                                                        msg.style.display = 'none';
                                                        addToCartBtn.disabled = false;
                                                        addToCartBtn.textContent = 'Add to Cart';
                                                    } else {
                                                        variantInput.value = '';
                                                        msg.textContent = 'This product is out of stock';
                                                        msg.style.display = 'block';
                                                        addToCartBtn.disabled = true;
                                                        addToCartBtn.textContent = 'Out of Stock';
                                                    }
                                                } else {
                                                    variantInput.value = '';
                                                    msg.textContent = 'This variant does not exist';
                                                    msg.style.display = 'block';
                                                    addToCartBtn.disabled = true;
                                                    // Revert price
                                                    priceContainer.innerHTML = originalPriceHtml;
                                                }
                                            } else {
                                                addToCartBtn.disabled = true;
                                                // Revert price if selection matches nothing
                                                // priceContainer.innerHTML = originalPriceHtml; 
                                                // (Optional: keep last valid price or revert immediately. Let's revert.)
                                                // But typically users might change just one dropdown. Keep simple: 
                                                // Only revert if we want "no selection" state. 
                                            }
                                        }
                                        
                                        // Init button state
                                        addToCartBtn.disabled = true;
                                        
                                        // Use jQuery change event because niceselect plugin hides the original select and uses its own UI
                                        if (typeof $ !== 'undefined') {
                                            $(sizeSelect).on('change', checkSelection);
                                            $(colorSelect).on('change', checkSelection);
                                        } else {
                                            sizeSelect.addEventListener('change', checkSelection);
                                            colorSelect.addEventListener('change', checkSelection);
                                        }
                                    });
                                </script>
                            @endif

                            <div class="product_variant quantity">
                                <label>quantity</label>
                                <input min="1" max="100" value="1" type="number" name="quantity">
                                <button class="button" type="submit">add to cart</button>  
                            </div>
                            <div class=" product_d_action">
                               <ul>
                                   <li><a href="#" title="Add to wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i> Add to Wish List</a></li>
                                   <li><a href="#" title="Add to Compare"><i class="fa fa-sliders" aria-hidden="true"></i> Compare this Product</a></li>
                               </ul>
                            </div>
                            
                        </form>
                        <div class="priduct_social">
                            <h3>Share on:</h3>
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>        
                            </ul>      
                        </div>

                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!--product details end-->
    
    <!--product info start-->
    <div class="product_d_info">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="product_d_inner">   
                        <div class="product_info_button">    
                            <ul class="nav" role="tablist">
                                <li >
                                    <a class="active" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">More info</a>
                                </li>
                                <li>
                                   <a data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews ({{ $product->reviews->count() }})</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info" role="tabpanel" >
                                <div class="product_info_content" style="max-height: 400px; overflow-y: auto;">
                                    <p>{!! nl2br(e($product->description)) !!}</p>
                                </div>    
                            </div>

                            <div class="tab-pane fade" id="reviews" role="tabpanel" >
                                <div class="product_info_content">
                                    <p>Customer reviews for {{ $product->name }}</p>
                                </div>
                                <div class="product_info_inner">
                                    @foreach($product->reviews as $review)
                                    <div class="product_ratting mb-10">
                                        <ul>
                                            @for($i = 1; $i <= 5; $i++)
                                                <li><a href="#"><i class="fa {{ $i <= $review->rating ? 'fa-star' : 'fa-star-o' }}"></i></a></li>
                                            @endfor
                                        </ul>
                                        <strong>{{ $review->user->name ?? 'Guest' }}</strong> 
                                        <p>{{ $review->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="product_demo">
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                    <hr>
                                    @endforeach
                                </div> 
                                <div class="product_review_form">
                                    <form action="#">
                                        <h2>Add a review </h2>
                                        <p>Your email address will not be published. Required fields are marked </p>
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="review_comment">Your review </label>
                                                <textarea name="comment" id="review_comment" ></textarea>
                                            </div> 
                                        </div>
                                        <button type="submit">Submit</button>
                                     </form>   
                                </div>     
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
        </div>    
    </div>  
    <!--product info end-->
    
    <!--product section area start-->
    <section class="product_section related_product">
        <div class="container">
            <div class="row">   
                <div class="col-12">
                   <div class="section_title">
                       <h2>Related Products</h2>
                   </div>
                </div> 
            </div>    
            <div class="product_area"> 
                 <div class="row">
                    <div class="product_carousel product_three_column4 owl-carousel">
                        @foreach($relatedProducts as $related)
                        <div class="col-lg-3">
                            <div class="single_product">
                                <div class="product_thumb">
                                    <a class="primary_img" href="{{ route('product.detail', $related->slug) }}"><img src="{{ $related->image ? asset('storage/' . $related->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt=""></a>
                                    <div class="product_action">
                                        <div class="hover_action">
                                           <a  href="#"><i class="fa fa-plus"></i></a>
                                            <div class="action_button">
                                                <ul>
                                                    <li><a title="add to cart" href="cart.html"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                    <li><a href="wishlist.html" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                                </ul>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="product_sale">
                                        <!-- <span>-7%</span> -->
                                    </div>
                                </div>
                                <div class="product_content">
                                    <h3><a href="{{ route('product.detail', $related->slug) }}">{{ $related->name }}</a></h3>
                                    <span class="current_price">{{ number_format($related->price) }} đ</span>
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
@endsection
