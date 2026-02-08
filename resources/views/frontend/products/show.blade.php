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
                                @if($product->variants->isNotEmpty() && $product->variants->min('price') > 0)
                                    <span class="current_price">
                                        @if($product->variants->min('price') == $product->variants->max('price'))
                                            {{ number_format($product->variants->min('price')) }} đ
                                        @else
                                            {{ number_format($product->variants->min('price')) }} - {{ number_format($product->variants->max('price')) }} đ
                                        @endif
                                    </span>
                                @elseif($product->sale_price && $product->sale_price > 0)
                                    <span class="current_price">{{ number_format($product->sale_price) }} đ</span>
                                    <span class="old_price" style="text-decoration: line-through; color: #999; margin-left: 10px;">{{ number_format($product->price) }} đ</span>
                                @else
                                    <span class="current_price">{{ number_format($product->price) }} đ</span>
                                @endif
                            </div>

                            <div class="product_desc">
                                <p>{{ $product->short_description }}</p>
                            </div>

                                @if($product->variants->count() > 0 && $product->variants->min('price') > 0)
                                    @php
                                        $uniqueSizes = $product->variants->pluck('sizeRelationship')->filter()->unique('id');
                                        $uniqueColors = $product->variants->pluck('colorRelationship')->filter()->unique('id');
                                    @endphp

                                    <style>
                                        .product_variant h3 {
                                            margin-bottom: 12px;
                                            font-size: 15px;
                                            font-weight: 600;
                                            text-transform: uppercase;
                                            letter-spacing: 0.5px;
                                            color: #222;
                                        }
                                        .variant-group {
                                            display: flex;
                                            flex-wrap: wrap;
                                            gap: 12px;
                                            margin-bottom: 25px;
                                        }
                                        .variant-option {
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            padding: 10px 24px;
                                            border: 1px solid #e1e1e1;
                                            background: #fff;
                                            cursor: pointer;
                                            min-width: 45px;
                                            font-weight: 500;
                                            font-size: 14px;
                                            color: #555;
                                            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                                            user-select: none;
                                            position: relative;
                                            overflow: hidden;
                                        }
                                        
                                        /* Sharp & Sophisticated Look */
                                        .variant-option:hover {
                                            border-color: #333;
                                            color: #000;
                                        }
                                        
                                        .variant-option.active {
                                            border-color: #222;
                                            background: #222;
                                            color: #fff;
                                            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                        }
                                        
                                        /* Specific styling for Size */
                                        .product_variant.size .variant-option {
                                            min-width: 50px;
                                        }

                                        .variant-option.disabled {
                                            opacity: 0.4;
                                            cursor: not-allowed;
                                            background: #f8f9fa;
                                            border-color: #eee;
                                            color: #aaa;
                                            box-shadow: none !important;
                                            border-style: dashed;
                                        }
                                    </style>

                                    <div class="product_variant size">
                                        <h3>Size</h3>
                                        <div class="variant-group" id="group_size">
                                            @foreach($uniqueSizes as $size)
                                                <div class="variant-option" data-value="{{ $size->id }}" data-type="size">{{ $size->name }}</div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" id="select_size" value="">
                                    </div>
                                    
                                    <div class="product_variant color">
                                        <h3>Màu sắc</h3>
                                        <div class="variant-group" id="group_color">
                                            @foreach($uniqueColors as $color)
                                                <div class="variant-option" data-value="{{ $color->id }}" data-type="color">{{ $color->name }}</div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" id="select_color" value="">
                                    </div>

                                    <input type="hidden" id="variant_select" name="variant_id" required>
                                    <div id="variant-message" class="text-danger mt-2 mb-3" style="font-weight: bold; display: none;"></div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const variants = @json($product->variants);
                                            const sizeInput = document.getElementById('select_size');
                                            const colorInput = document.getElementById('select_color');
                                            const variantInput = document.getElementById('variant_select');
                                            const msg = document.getElementById('variant-message');
                                            const addToCartBtn = document.querySelector('button[type="submit"]');
                                            const priceContainer = document.querySelector('.product_price');
                                            
                                            const originalPriceHtml = priceContainer.innerHTML;
                                            
                                            // Handle button clicks
                                            document.querySelectorAll('.variant-option').forEach(opt => {
                                                opt.addEventListener('click', function() {
                                                    if (this.classList.contains('disabled')) return;
                                                    
                                                    const type = this.getAttribute('data-type');
                                                    const value = this.getAttribute('data-value');
                                                    const group = type === 'size' ? 'group_size' : 'group_color';
                                                    const input = type === 'size' ? sizeInput : colorInput;
                                                    
                                                    // Remove active from siblings
                                                    document.querySelectorAll(`#${group} .variant-option`).forEach(el => el.classList.remove('active'));
                                                    
                                                    // Set active to clicked
                                                    this.classList.add('active');
                                                    input.value = value;
                                                    
                                                    checkSelection();
                                                });
                                            });

                                            // Helper to format currency
                                            const formatCurrency = (amount) => {
                                                return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
                                            };
                                            
                                            function checkSelection() {
                                                const selectedSize = sizeInput.value;
                                                const selectedColor = colorInput.value;

                                                if (selectedSize && selectedColor) {
                                                    const matchedVariant = variants.find(v => v.size_id == selectedSize && v.color_id == selectedColor);

                                                    if (matchedVariant) {
                                                        // Update Price
                                                        let html = '';
                                                        if (matchedVariant.price !== null) {
                                                            if (matchedVariant.sale_price !== null && matchedVariant.sale_price < matchedVariant.price) {
                                                                html = `<span class="current_price">${formatCurrency(matchedVariant.sale_price)}</span>
                                                                        <span class="old_price" style="text-decoration: line-through; color: #999; margin-left: 10px;">${formatCurrency(matchedVariant.price)}</span>`;
                                                            } else {
                                                                html = `<span class="current_price">${formatCurrency(matchedVariant.price)}</span>`;
                                                            }
                                                            priceContainer.innerHTML = html;
                                                        } else {
                                                            priceContainer.innerHTML = originalPriceHtml;
                                                        }

                                                        if (matchedVariant.stock_quantity > 0) {
                                                            variantInput.value = matchedVariant.id;
                                                            msg.style.display = 'none';
                                                            addToCartBtn.disabled = false;
                                                            addToCartBtn.textContent = 'Thêm vào giỏ hàng';
                                                            // Enable Buy Now button if handled similarly, or ensure it uses same form
                                                        } else {
                                                            variantInput.value = '';
                                                            msg.textContent = 'Sản phẩm tạm hết hàng mẫu này';
                                                            msg.style.display = 'block';
                                                            addToCartBtn.disabled = true;
                                                            addToCartBtn.textContent = 'Hết hàng';
                                                        }
                                                    } else {
                                                        variantInput.value = '';
                                                        msg.textContent = 'Phiên bản này không tồn tại';
                                                        msg.style.display = 'block';
                                                        addToCartBtn.disabled = true;
                                                        addToCartBtn.textContent = 'Không khả dụng';
                                                        priceContainer.innerHTML = originalPriceHtml;
                                                    }
                                                } else {
                                                    // Incomplete selection
                                                    addToCartBtn.disabled = true;
                                                }
                                            }
                                            
                                            // Init
                                            addToCartBtn.disabled = true;
                                        });
                                    </script>
                                @endif

                            <div class="product_variant quantity">
                                <label>quantity</label>
                                <input min="1" max="100" value="1" type="number" name="quantity">
                                <button class="button" type="submit" name="action" value="add_to_cart">add to cart</button>
                                <button class="button" type="submit" name="action" value="buy_now" style="background: #ef233c; border-color: #ef233c; margin-left: 10px;">Mua ngay</button>  
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
