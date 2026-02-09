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
                            <li>product details</li>
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
                                <img id="zoom1"
                                    src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}"
                                    data-zoom-image="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}"
                                    alt="{{ $product->name }}">
                            </a>
                        </div>
                        <div class="single-zoom-thumb">
                            <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                                <li>
                                    <a href="#" class="elevatezoom-gallery active" data-update=""
                                        data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}"
                                        data-zoom-image="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}"
                                            alt="{{ $product->name }}" />
                                    </a>
                                </li>
                                @foreach($product->images as $image)
                                    <li>
                                        <a href="#" class="elevatezoom-gallery" data-update=""
                                            data-image="{{ asset('storage/' . $image->image_path) }}"
                                            data-zoom-image="{{ asset('storage/' . $image->image_path) }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                alt="zo-th-{{ $loop->iteration }}" />
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
                            <div class="product_ratting">
                                <ul>
                                    @php $rating = $product->reviews->avg('rating') ?? 0; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <li><a href="#"><i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i></a>
                                        </li>
                                    @endfor
                                    <li class="review"><a href="#reviews"> ({{ $product->reviews->count() }} reviews) </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="product_price">
                                @include('frontend.partials.product-price', ['product' => $product])
                            </div>
                            <div class="product_desc">
                                <p>{{ $product->short_description }}</p>
                            </div>

                            @if($product->variants->count() > 0 && $product->variants->min('price') > 0)
                                @php
                                    $uniqueSizes = $product->variants->pluck('sizeRelationship')->filter()->unique('id');
                                    $uniqueColors = $product->variants->pluck('colorRelationship')->filter()->unique('id');
                                @endphp

                                <div class="product_variant size">
                                    <h3>Kích thước</h3>
                                    <select class="niceselect_option" id="select_size_nice" name="size_id">
                                        <option selected value="">Size</option>
                                        @foreach($uniqueSizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="select_size" value="">
                                </div>

                                <div class="product_variant color">
                                    <h3>Màu sắc</h3>
                                    <select class="niceselect_option" id="select_color_nice" name="color_id">
                                        <option selected value="">Color</option>
                                        @foreach($uniqueColors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="select_color" value="">
                                </div>

                                <input type="hidden" id="variant_select" name="variant_id" required>
                                <div id="variant-message" class="text-danger mt-2 mb-3"
                                    style="font-weight: bold; display: none;"></div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const variants = @json($product->variants);
                                        const niceSize = document.getElementById('select_size_nice');
                                        const niceColor = document.getElementById('select_color_nice');
                                        const sizeInput = document.getElementById('select_size');
                                        const colorInput = document.getElementById('select_color');
                                        const variantInput = document.getElementById('variant_select');
                                        const msg = document.getElementById('variant-message');
                                        const addToCartBtn = document.querySelector('.button[value="add_to_cart"]');
                                        const buyNowBtn = document.querySelector('.button[value="buy_now"]');
                                        const priceContainer = document.querySelector('.product_price');

                                        const originalPriceHtml = priceContainer.innerHTML;

                                        // Handle Nice Select changes
                                        $(niceSize).on('change', function () {
                                            sizeInput.value = this.value;
                                            checkSelection();
                                        });
                                        $(niceColor).on('change', function () {
                                            colorInput.value = this.value;
                                            checkSelection();
                                        });

                                        const formatCurrency = (amount) => {
                                            return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
                                        };

                                        function checkSelection() {
                                            const selectedSize = sizeInput.value;
                                            const selectedColor = colorInput.value;
<<<<<<< HEAD
                                            
                                            // Reset variant input if either is missing
                                            if (!selectedSize || !selectedColor) {
                                                variantInput.value = '';
                                                addToCartBtn.disabled = true;
                                                buyNowBtn.disabled = true;
                                            }
=======
>>>>>>> 8405ad433ff61de144486d273bb15067fc6ab3c1

                                            let filteredVariants = variants;
                                            if (selectedSize) {
                                                filteredVariants = filteredVariants.filter(v => v.size_id == selectedSize);
                                            }
                                            if (selectedColor) {
                                                filteredVariants = filteredVariants.filter(v => v.color_id == selectedColor);
                                            }

                                            if (filteredVariants.length > 0) {
                                                let html = '';

                                                if (selectedSize && selectedColor) {
                                                    const matchedVariant = filteredVariants[0];

                                                    if (matchedVariant.sale_price && matchedVariant.sale_price > 0 && matchedVariant.sale_price < matchedVariant.price) {
                                                        html = `<span class="current_price">
<<<<<<< HEAD
                                                                    <span class="old_price" style="text-decoration: line-through; color: #999; margin-right: 15px;">${formatCurrency(matchedVariant.price)}</span>
                                                                    <span style="color: #ef233c; font-weight: bold;">${formatCurrency(matchedVariant.sale_price)}</span>
                                                                </span>`;
=======
                                                                            <span class="old_price" style="text-decoration: line-through; color: #999; margin-right: 10px;">${formatCurrency(matchedVariant.price)}</span>
                                                                            ${formatCurrency(matchedVariant.sale_price)}
                                                                        </span>`;
>>>>>>> 8405ad433ff61de144486d273bb15067fc6ab3c1
                                                    } else {
                                                        html = `<span class="current_price" style="font-weight: bold;">${formatCurrency(matchedVariant.price)}</span>`;
                                                    }

                                                    if (matchedVariant.stock_quantity > 0) {
                                                        variantInput.value = matchedVariant.id;
                                                        msg.style.display = 'none';
                                                        addToCartBtn.disabled = false;
                                                        buyNowBtn.disabled = false;
                                                        addToCartBtn.textContent = 'THÊM VÀO GIỎ HÀNG';
                                                    } else {
                                                        variantInput.value = '';
                                                        msg.textContent = 'Sản phẩm tạm hết hàng mẫu này';
                                                        msg.style.display = 'block';
                                                        addToCartBtn.disabled = true;
                                                        buyNowBtn.disabled = true;
                                                        addToCartBtn.textContent = 'HẾT HÀNG';
                                                    }
                                                } else {
<<<<<<< HEAD
                                                    // Only one or none selected
                                                    const activePrices = filteredVariants.map(v => v.sale_price && v.sale_price < v.price ? v.sale_price : v.price).filter(p => p > 0);
                                                    const minPrice = Math.min(...activePrices);
                                                    const maxPrice = Math.max(...activePrices);
                                                    
=======
                                                    const minPrice = Math.min(...filteredVariants.map(v => v.sale_price && v.sale_price < v.price ? v.sale_price : v.price).filter(p => p > 0));
                                                    const maxPrice = Math.max(...filteredVariants.map(v => v.sale_price && v.sale_price < v.price ? v.sale_price : v.price).filter(p => p > 0));

>>>>>>> 8405ad433ff61de144486d273bb15067fc6ab3c1
                                                    if (minPrice === maxPrice) {
                                                        html = `<span class="current_price">${formatCurrency(minPrice)}</span>`;
                                                    } else {
                                                        html = `<span class="current_price">${formatCurrency(minPrice)} - ${formatCurrency(maxPrice)}</span>`;
                                                    }
<<<<<<< HEAD
                                                    
                                                    // Don't enable buttons if not both selected
=======

>>>>>>> 8405ad433ff61de144486d273bb15067fc6ab3c1
                                                    variantInput.value = '';
                                                    addToCartBtn.disabled = true;
                                                    buyNowBtn.disabled = true;
                                                    addToCartBtn.textContent = 'THÊM VÀO GIỎ HÀNG';
                                                    msg.style.display = 'none';
                                                }

                                                priceContainer.innerHTML = html;
                                            } else {
                                                variantInput.value = '';
                                                msg.textContent = 'Kết hợp này không có sẵn';
                                                msg.style.display = 'block';
                                                addToCartBtn.disabled = true;
                                                buyNowBtn.disabled = true;
                                                priceContainer.innerHTML = originalPriceHtml;
                                            }
                                        }

                                        addToCartBtn.disabled = true;
                                        buyNowBtn.disabled = true;
                                    });
                                </script>
                            @endif

                            <div class="product_variant quantity">
                                <label>quantity</label>
                                <input min="1" max="100" value="1" type="number" name="quantity">
<<<<<<< HEAD
                                <button class="button" type="submit" name="action" value="add_to_cart">ADD TO CART</button>
                                <button class="button buy_now" type="submit" name="action" value="buy_now">BUY NOW</button>  
=======
                                <button class="button" type="submit" name="action" value="add_to_cart">add to cart</button>
                                <button class="button" type="submit" name="action" value="buy_now"
                                    style="background: #ef233c; border-color: #ef233c; margin-left: 10px;">Buy now</button>
>>>>>>> 8405ad433ff61de144486d273bb15067fc6ab3c1
                            </div>
                            <style>
                                .product_variant.quantity .button.buy_now {
                                    background: #ef233c; 
                                    border-color: #ef233c; 
                                    margin-left: 10px;
                                }
                                .product_variant.quantity .button.buy_now:hover {
                                    background: #333;
                                    border-color: #333;
                                }
                                .product_variant.quantity .button:disabled {
                                    background: #ccc;
                                    border-color: #ccc;
                                    cursor: not-allowed;
                                }
                            </style>
                            <div class="product_d_action">
                                <ul>
                                    <li>
                                        <a href="#" class="add-to-wishlist" data-id="{{ $product->id }}"
                                            title="Add to wishlist">
                                            <i class="fa fa-heart-o" aria-hidden="true"></i> Add to Wish List
                                        </a>
                                    </li>
                                    <li><a href="#" title="Add to Compare"><i class="fa fa-sliders" aria-hidden="true"></i>
                                            Compare this Product</a></li>
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
                                <li>
                                    <a class="active" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info"
                                        aria-selected="false">More info</a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews"
                                        aria-selected="false">Reviews ({{ $product->reviews->count() }})</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <div class="product_info_content" style="max-height: 400px; overflow-y: auto;">
                                    <p>{!! nl2br(e($product->description)) !!}</p>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div class="product_info_content">
                                    <p>Customer reviews for {{ $product->name }}</p>
                                </div>
                                @foreach($product->reviews as $review)
                                    <div class="product_info_inner">
                                        <div class="product_ratting mb-10">
                                            <ul>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <li><a href="#"><i
                                                                class="fa {{ $i <= $review->rating ? 'fa-star' : 'fa-star-o' }}"></i></a>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <strong>{{ $review->user->name ?? 'Guest' }}</strong>
                                            <p>{{ $review->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="product_demo">
                                            <p>{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                                <div class="product_review_form">
                                    <form action="#">
                                        <h2>Add a review </h2>
                                        <p>Your email address will not be published. Required fields are marked </p>
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="review_comment">Your review </label>
                                                <textarea name="comment" id="review_comment"></textarea>
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
                                        <a class="primary_img" href="{{ route('product.detail', $related->slug) }}">
                                            <img src="{{ $related->image ? asset('storage/' . $related->image) : asset('frontend-assets/img/product/product21.jpg') }}"
                                                alt="{{ $related->name }}">
                                        </a>
                                        <div class="product_action">
                                            <div class="hover_action">
                                                <a href="{{ route('product.detail', $related->slug) }}"><i
                                                        class="fa fa-plus"></i></a>
                                                <div class="action_button">
                                                    <ul>
                                                        <li><a title="add to cart"
                                                                href="{{ route('product.detail', $related->slug) }}"><i
                                                                    class="fa fa-shopping-basket" aria-hidden="true"></i></a>
                                                        </li>
                                                        <li><a href="#" class="add-to-wishlist" data-id="{{ $related->id }}"
                                                                title="Add to Wishlist"><i class="fa fa-heart-o"
                                                                    aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product_content">
                                        <h3><a href="{{ route('product.detail', $related->slug) }}">{{ $related->name }}</a>
                                        </h3>
                                        @include('frontend.partials.product-price', ['product' => $related])
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