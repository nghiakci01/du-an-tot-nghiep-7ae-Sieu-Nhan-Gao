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
                        
                        <!-- AI Try On Button Indicator -->
                        <div class="ai-try-on-section mt-4 mb-3 text-center">
                            <button type="button" class="btn btn-outline-dark w-100 py-3" style="border: 1px dashed #333; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" data-bs-toggle="modal" data-bs-target="#aiTryOnModal" onmouseover="this.style.background='#333'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#333';">
                                <i class="fa fa-magic mr-2"></i> Thử đồ với AI (Phòng thử đồ ảo)
                            </button>
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
                                    <h3>Size</h3>
                                    <select class="niceselect_option" id="select_size_nice" name="size_id">
                                        <option selected value="">Size</option>
                                        @foreach($uniqueSizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="select_size" value="">
                                </div>

                                <div class="product_variant color">
                                    <h3>Color</h3>
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
                                            return new Intl.NumberFormat('en-US').format(amount) + ' VND';
                                        };

                                        function checkSelection() {
                                            const selectedSize = sizeInput.value;
                                            const selectedColor = colorInput.value;
                                            
                                            // Reset variant input if either is missing
                                            if (!selectedSize || !selectedColor) {
                                                variantInput.value = '';
                                                addToCartBtn.disabled = true;
                                                buyNowBtn.disabled = true;
                                            }

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
                                                                    <span class="old_price" style="text-decoration: line-through; color: #999; margin-right: 15px;">${formatCurrency(matchedVariant.price)}</span>
                                                                    <span style="color: #ef233c; font-weight: bold;">${formatCurrency(matchedVariant.sale_price)}</span>
                                                                </span>`;
                                                    } else {
                                                        html = `<span class="current_price" style="font-weight: bold;">${formatCurrency(matchedVariant.price)}</span>`;
                                                    }

                                                    if (matchedVariant.stock_quantity > 0) {
                                                        variantInput.value = matchedVariant.id;
                                                        msg.style.display = 'none';
                                                        addToCartBtn.disabled = false;
                                                        buyNowBtn.disabled = false;
                                                        addToCartBtn.textContent = 'ADD TO CART';
                                                    } else {
                                                        variantInput.value = '';
                                                        msg.textContent = 'This variant is temporarily out of stock';
                                                        msg.style.display = 'block';
                                                        addToCartBtn.disabled = true;
                                                        buyNowBtn.disabled = true;
                                                        addToCartBtn.textContent = 'OUT OF STOCK';
                                                    }
                                                } else {
                                                    // Only one or none selected
                                                    const activePrices = filteredVariants.map(v => v.sale_price && v.sale_price < v.price ? v.sale_price : v.price).filter(p => p > 0);
                                                    const minPrice = Math.min(...activePrices);
                                                    const maxPrice = Math.max(...activePrices);
                                                    
                                                    if (minPrice === maxPrice) {
                                                        html = `<span class="current_price">${formatCurrency(minPrice)}</span>`;
                                                    } else {
                                                        html = `<span class="current_price">${formatCurrency(minPrice)} - ${formatCurrency(maxPrice)}</span>`;
                                                    }
                                                    
                                                    // Don't enable buttons if not both selected
                                                    variantInput.value = '';
                                                    addToCartBtn.disabled = true;
                                                    buyNowBtn.disabled = true;
                                                    addToCartBtn.textContent = 'ADD TO CART';
                                                    msg.style.display = 'none';
                                                }

                                                priceContainer.innerHTML = html;
                                            } else {
                                                variantInput.value = '';
                                                msg.textContent = 'This combination is not available';
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
                                <button class="button" type="submit" name="action" value="add_to_cart">ADD TO CART</button>
                                <button class="button buy_now" type="submit" name="action" value="buy_now">BUY NOW</button>  
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

    <!-- AI Try On Modal -->
    <div class="modal fade" id="aiTryOnModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1050;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 text-center d-block position-relative">
                    <h5 class="modal-title" style="font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"><i class="fa fa-magic text-primary"></i> Trải nghiệm Thử Đồ AI</h5>
                    <button type="button" class="close position-absolute" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="top: 15px; right: 20px; font-size: 28px; background:transparent; border:none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p class="text-muted mt-2" style="font-size: 14px;">Xem thử trang phục này trên người bạn sẽ trông như thế nào</p>
                </div>
                <div class="modal-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">Ảnh mẫu của bạn</p>
                            <div class="upload-area p-4 d-flex flex-column align-items-center justify-content-center" style="border: 2px dashed #ccc; border-radius: 8px; background: #fafafa; cursor: pointer; position: relative; min-height: 250px;" id="uploadBtn">
                                <div id="uploadPlaceholder">
                                    <i class="fa fa-cloud-upload fa-3x text-muted mb-3"></i>
                                    <p class="m-0" style="font-size: 14px; font-weight: 600;">Nhấn để tải ảnh lên</p>
                                    <p class="text-muted mt-1" style="font-size: 11px;">Hỗ trợ JPG, PNG (Tối đa 5MB)<br>Vui lòng chọn ảnh chụp thẳng dáng người</p>
                                </div>
                                <input type="file" id="userImageUpload" accept="image/*" style="opacity: 0; position: absolute; top:0; left:0; width: 100%; height: 100%; cursor: pointer; z-index: 5;">
                                <img id="userImagePreview" src="" style="max-width: 100%; max-height: 230px; border-radius: 6px; display: none; position: relative; z-index: 2;" alt="User Image">
                                <button type="button" class="btn btn-sm btn-light position-absolute shadow-sm" id="btnChangeImage" style="display:none; bottom: 10px; right: 10px; z-index: 10; font-size: 11px; font-weight: bold;"><i class="fa fa-refresh"></i> Đổi ảnh</button>
                            </div>
                        </div>
                        <div class="col-md-2 text-center d-none d-md-block">
                            <i class="fa fa-long-arrow-right fa-2x text-muted"></i>
                        </div>
                        <div class="col-md-5 text-center">
                            <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">Kết quả AI</p>
                            <div class="result-area p-4 d-flex align-items-center justify-content-center" style="border: 1px solid #eee; border-radius: 8px; background: #f8f9fa; min-height: 250px; position: relative; overflow: hidden;" id="aiResultArea">
                                <div class="text-muted text-center" id="aiWaitingText">
                                    <i class="fa fa-user-circle-o fa-3x mb-3" style="color: #ddd;"></i><br>
                                    <span style="font-size: 13px;">Vui lòng tải ảnh lên<br>để bắt đầu mô phỏng</span>
                                </div>
                                <div id="aiLoading" class="text-center" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%;">
                                    <i class="fa fa-spinner fa-spin fa-3x mb-3 text-dark"></i>
                                    <p style="font-size: 13px; font-weight: 600; color: #111;" class="m-0">AI đang xử lý ghép đồ...</p>
                                    <p style="font-size: 11px; color: #666;" class="m-0">Thường mất khoảng 3-5 giây</p>
                                </div>
                                <div id="aiSuccessResult" style="display: none; width: 100%; height: 100%;">
                                    <div style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <img id="resultBaseImage" src="" style="max-height: 230px; max-width: 100%; border-radius: 6px;">
                                        <!-- Overlay the product product using CSS blend mode -->
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-height: 180px; mix-blend-mode: multiply;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                    <button type="button" class="btn btn-dark px-5 py-3" id="btnRunAI" style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; min-width: 250px; transition: all 0.3s;" disabled>
                        <i class="fa fa-gears mr-2"></i> Bắt đầu Thử đồ
                    </button>
                    <button type="button" class="btn btn-success px-5 py-3" id="btnDownloadResult" style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; min-width: 250px; display: none;">
                        <i class="fa fa-download mr-2"></i> Tải ảnh về
                    </button>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function () {
                // --- AI Try On Logic ---
                const uploadBtn = document.getElementById('uploadBtn');
                const fileInput = document.getElementById('userImageUpload');
                const imgPreview = document.getElementById('userImagePreview');
                const placeholder = document.getElementById('uploadPlaceholder');
                const btnChange = document.getElementById('btnChangeImage');
                const btnRunAI = document.getElementById('btnRunAI');
                
                const aiWaitingText = document.getElementById('aiWaitingText');
                const aiLoading = document.getElementById('aiLoading');
                const aiSuccessResult = document.getElementById('aiSuccessResult');
                const resultBaseImage = document.getElementById('resultBaseImage');
                const btnDownloadResult = document.getElementById('btnDownloadResult');

                if (uploadBtn) {
                    // Trigger file input when clicking the upload area
                    uploadBtn.addEventListener('click', function(e) {
                        if(e.target !== fileInput && e.target !== btnChange && e.target.parentElement !== btnChange) {
                            fileInput.click();
                        }
                    });

                    btnChange.addEventListener('click', function(e) {
                        e.stopPropagation();
                        fileInput.click();
                    });

                    // Handle file selection
                    fileInput.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                imgPreview.src = e.target.result;
                                placeholder.style.display = 'none';
                                imgPreview.style.display = 'block';
                                btnChange.style.display = 'block';
                                
                                // Enable run button
                                btnRunAI.disabled = false;
                                btnRunAI.classList.remove('btn-dark');
                                btnRunAI.classList.add('btn-primary');
                                
                                // Reset AI area
                                aiWaitingText.style.display = 'block';
                                aiLoading.style.display = 'none';
                                aiSuccessResult.style.display = 'none';
                                btnDownloadResult.style.display = 'none';
                                btnRunAI.style.display = 'inline-block';
                            }
                            reader.readAsDataURL(this.files[0]);
                        }
                    });

                    // Handle Run AI
                    btnRunAI.addEventListener('click', function() {
                        // Start loading
                        aiWaitingText.style.display = 'none';
                        aiSuccessResult.style.display = 'none';
                        aiLoading.style.display = 'block';
                        btnRunAI.disabled = true;
                        btnRunAI.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Đang xử lý...';

                        // Simulate API delay (3 seconds)
                        setTimeout(() => {
                            aiLoading.style.display = 'none';
                            
                            // Set base image to user's uploaded image
                            resultBaseImage.src = imgPreview.src;
                            
                            // Show result
                            aiSuccessResult.style.display = 'block';
                            
                            // Update buttons
                            btnRunAI.style.display = 'none';
                            btnDownloadResult.style.display = 'inline-block';
                            
                            // Reset Run AI button state for next time
                            btnRunAI.disabled = false;
                            btnRunAI.innerHTML = '<i class="fa fa-gears mr-2"></i> Bắt đầu Thử đồ';
                        }, 3000);
                    });
                    
                    // Allow downloading the "result"
                    btnDownloadResult.addEventListener('click', function() {
                        alert('Chức năng tải ảnh đang được cập nhật!');
                    });
                }

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
                                alert('An error occurred, please try again!');
                            }
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection