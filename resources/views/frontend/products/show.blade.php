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

                        <div id="img-1" class="zoomWrapper single-zoom">
                            <a href="#">
                                <img id="zoom1" src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" data-zoom-image="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" alt="{{ $product->name }}">
                            </a>
                        </div>

                        <div class="single-zoom-thumb">
                            <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                                <!-- Main Image in Thumbnail -->
                                <li>
                                    <a href="#" class="elevatezoom-gallery active" data-update="" data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" data-zoom-image="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" alt="{{ $product->name }}"/>
                                    </a>
                                </li>
                                <!-- Gallery Images -->
                                @foreach($product->images as $image)
                                <li>
                                    <a href="#" class="elevatezoom-gallery" data-update="" data-image="{{ asset('storage/' . $image->image_path) }}" data-zoom-image="{{ asset('storage/' . $image->image_path) }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="zo-th-{{ $loop->iteration }}"/>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
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
                                <span class="current_price">{{ number_format($product->price) }} đ</span>
                            </div>

                            @if($product->variants->count() > 0)
                                @php
                                    $uniqueSizes = $product->variants->pluck('size')->unique();
                                    $uniqueColors = $product->variants->pluck('color')->unique();
                                    
                                    // Custom color mapping for common colors (could be moved to a helper or config)
                                    $colorMap = [
                                        'Trắng' => '#ffffff',
                                        'White' => '#ffffff',
                                        'Đen' => '#000000',
                                        'Black' => '#000000',
                                        'Đỏ' => '#ff0000',
                                        'Red' => '#ff0000',
                                        'Xanh' => '#0000ff',
                                        'Blue' => '#0000ff',
                                        'Xanh lá' => '#008000',
                                        'Green' => '#008000',
                                        'Vàng' => '#ffff00',
                                        'Yellow' => '#ffff00',
                                        'Hồng' => '#ffc0cb',
                                        'Pink' => '#ffc0cb',
                                    ];
                                @endphp
                                
                                <div class="product_variant size mb-20">
                                    <h3>Size</h3>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach($uniqueSizes as $size)
                                            <button type="button" class="btn btn-outline-secondary btn-sm size-btn" data-size="{{ $size }}" style="min-width: 40px; border-radius: 0;">{{ $size }}</button>
                                        @endforeach
                                    </div>
                                    <span id="selected-size-label" class="text-primary ms-2" style="font-weight: normal; font-size: 0.9em;"></span>
                                </div>
                                
                                <div class="product_variant color mb-20">
                                    <h3>Màu sắc</h3>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach($uniqueColors as $color)
                                            <button type="button" class="btn btn-outline-secondary btn-sm color-btn p-1" data-color="{{ $color }}" title="{{ $color }}" style="min-width: 35px; height: 35px; border-radius: 0; padding: 2px;">
                                                <span style="display: block; width: 100%; height: 100%; background-color: {{ $colorMap[$color] ?? '#ccc' }}; border: 1px solid rgba(0,0,0,0.1);"></span>
                                            </button>
                                        @endforeach
                                    </div>
                                    <span id="selected-color-label" class="text-primary ms-2" style="font-weight: normal; font-size: 0.9em;"></span>
                                </div>
                                
                                <input type="hidden" id="variant_select" name="variant_id" required>
                                <div id="variant-message" class="text-danger mt-2 mb-3" style="display:none; font-weight: bold;"></div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const variants = @json($product->variants);
                                        const sizeBtns = document.querySelectorAll('.size-btn');
                                        const colorBtns = document.querySelectorAll('.color-btn');
                                        const variantInput = document.getElementById('variant_select');
                                        const msg = document.getElementById('variant-message');
                                        const addToCartBtn = document.querySelector('button[type="submit"]');
                                        
                                        let selectedSize = null;
                                        let selectedColor = null;
                                        
                                        function checkSelection() {
                                            if (selectedSize && selectedColor) {
                                                const matchedVariant = variants.find(v => v.size == selectedSize && v.color == selectedColor);
                                                
                                                if (matchedVariant) {
                                                    if (matchedVariant.stock_quantity > 0) {
                                                        variantInput.value = matchedVariant.id;
                                                        msg.style.display = 'none';
                                                        addToCartBtn.disabled = false;
                                                        addToCartBtn.textContent = 'Add to Cart';
                                                    } else {
                                                        variantInput.value = '';
                                                        msg.textContent = 'Sản phẩm này tạm hết hàng';
                                                        msg.style.display = 'block';
                                                        addToCartBtn.disabled = true;
                                                        addToCartBtn.textContent = 'Hết hàng';
                                                    }
                                                } else {
                                                    variantInput.value = '';
                                                    msg.textContent = 'Phiên bản này không tồn tại';
                                                    msg.style.display = 'block';
                                                    addToCartBtn.disabled = true;
                                                }
                                            } else {
                                                // Disable button if not fully selected
                                                addToCartBtn.disabled = true;
                                            }
                                        }
                                        
                                        // Init button state
                                        addToCartBtn.disabled = true;
                                        
                                        sizeBtns.forEach(btn => {
                                            btn.addEventListener('click', function() {
                                                sizeBtns.forEach(b => b.classList.remove('active', 'btn-primary'));
                                                this.classList.add('active', 'btn-primary');
                                                this.classList.remove('btn-outline-secondary');
                                                // Reset others
                                                sizeBtns.forEach(b => {
                                                    if (b !== this) b.classList.add('btn-outline-secondary');
                                                });
                                                
                                                selectedSize = this.dataset.size;
                                                // Check for label existence before setting
                                                const sizeLabel = document.getElementById('selected-size-label');
                                                if(sizeLabel) sizeLabel.textContent = selectedSize;
                                                
                                                checkSelection();
                                            });
                                        });
                                        
                                        colorBtns.forEach(btn => {
                                            btn.addEventListener('click', function() {
                                                colorBtns.forEach(b => b.style.border = '1px solid #ddd');
                                                this.style.border = '2px solid #000';
                                                
                                                selectedColor = this.dataset.color;
                                                // Check for label existence before setting
                                                const colorLabel = document.getElementById('selected-color-label');
                                                if(colorLabel) colorLabel.textContent = selectedColor;
                                                
                                                checkSelection();
                                            });
                                        });
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
                                <div class="product_info_content">
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
