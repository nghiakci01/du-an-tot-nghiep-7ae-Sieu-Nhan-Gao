@extends('layouts.public')

@section('title', $product->name . ' | Reid')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 150))

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area product_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li><a href="{{ route('shop') }}">{{ __('messages.shop') }}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.product_details') }}</li>
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
                            <button type="button" class="btn btn-outline-dark w-100 py-3"
                                style="border: 1px dashed #333; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;"
                                data-bs-toggle="modal" data-bs-target="#aiTryOnModal"
                                onmouseover="this.style.background='#333'; this.style.color='#fff';"
                                onmouseout="this.style.background='transparent'; this.style.color='#333';">
                                <i class="fa fa-magic mr-2"></i> {{ __('messages.ai_try_on') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7">
                    <div class="product_d_right">
                        <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <h1>{{ $product->name }}</h1>
                            <div class="product_price">
                                @include('frontend.partials.product-price', ['product' => $product])
                            </div>
                            <div class="product_ratting">
                                <ul>
                                    @php $ratingAvg = $product->reviews->avg('rating') ?? 0; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <li><a href="javascript:void(0)"><i
                                                    class="fa {{ $i <= $ratingAvg ? 'fa-star' : 'fa-star-o' }}"></i></a></li>
                                    @endfor
                                    <li class="review">
                                        <a href="#reviews" id="view-reviews-link">
                                            ({{ $product->reviews->count() }} {{ __('messages.reviews') }})
                                        </a>
                                    </li>
                                </ul>
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
                                    <h3>{{ __('messages.size') }}</h3>
                                    <select class="niceselect_option" id="select_size_nice" name="size_id">
                                        <option selected value="">{{ __('messages.size') }}</option>
                                        @foreach($uniqueSizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="select_size" value="">
                                </div>

                                <div class="product_variant color">
                                    <h3>{{ __('messages.color') }}</h3>
                                    <select class="niceselect_option" id="select_color_nice" name="color_id">
                                        <option selected value="">{{ __('messages.color') }}</option>
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
                                            document.addEventListener('DOMContentLoaded', fun                                           ction () {
                                                const variants = @json($product->variants);
                                                const niceSize = document.getElementById('select_size_nice');
                                                const niceColor = document.getElementById('select_color_nice');
                                                const sizeInput = document.getElementById('select_size');
                                                const colorInput = document.getElementById('select_color');
                                                const variantInput = document.getElementById('variant_select');
                                                const msg = document.getElementById('variant-message');
                                                const priceContainer = document.querySelector('.product_price');
                                                const form = document.querySelector('form[action="{{ route("cart.add") }}"]');

                                                const originalPriceHtml = priceContainer.innerHTML;

                                                // Handle Nice Select changes
                                                $(niceSize).on('change', function () {
                                                    sizeInput.value = this.value;
                                                    // Remove error highlight when user selects
                                                    $(this).closest('.product_variant').find('.nice-select').css('border-color', '');
                                                    checkSelection();
                                                });
                                                $(niceColor).on('change', function () {
                                                    colorInput.value = this.value;
                                                    $(this).closest('.product_variant').find('.nice-select').css('border-color', '');
                                                    checkSelection();
                                                });

                                                // Intercept form submit for validation
                                                if (form) {
                                                    form.addEventListener('submit', function(e) {
                                                        const selectedSize = sizeInput ? sizeInput.value : '1';
                                                        const selectedColor = colorInput ? colorInput.value : '1';
                                                        const hasVariants = @json($product->variants->count() > 0 && $product->variants->min('price') > 0);

                                                        if (hasVariants && (!selectedSize || !selectedColor || !variantInput.value)) {
                                                            e.preventDefault();

                                                            let missingFields = [];
                                                            if (!selectedSize) {
                                                                missingFields.push('{{ __("messages.size") }}');
                                                                // Highlight size select
                                                                $('#select_size_nice').next('.nice-select').css('border-color', '#ef233c');
                                                            }
                                                            if (!selectedColor) {
                                                                missingFields.push('{{ __("messages.color") }}');
                                                                // Highlight color select
                                                                $('#select_color_nice').next('.nice-select').css('border-color', '#ef233c');
                                                            }

                                                            let message = missingFields.length > 0
                                                                ? `Vui lòng chọn: <strong>${missingFields.join(', ')}</strong> trước khi thêm vào giỏ hàng!`
                                                                : 'Vui lòng chọn đầy đủ thuộc tính sản phẩm!';

                                                            Swal.fire({
                                                                icon: 'warning',
                                                                title: 'Chưa chọn thuộc tính!',
                                                                html: message,
                                                                confirmButtonColor: '#ef233c',
                                                                confirmButtonText: 'Chọn ngay',
                                                                timer: 4000,
                                                                timerProgressBar: true,
                                                                showClass: {
                                                                    popup: 'animate__animated animate__fadeInDown'
                                                                },
                                                            });
                                                                return false;
                                                        }

                                                        // Kiểm tra số lượng vượt tồn kho
                                                        const qtyInput = document.getElementById('quantity_input');
                                                        const requestedQty = parseInt(qtyInput ? qtyInput.value : 1);
                                                        const maxQty = parseInt(qtyInput ? qtyInput.max : 100);

                                                        if (maxQty && requestedQty > maxQty) {
                                                            e.preventDefault();
                                                            qtyInput.value = maxQty;
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Vượt quá số lượng tồn kho!',
                                                                html: `Sản phẩm này chỉ còn <strong>${maxQty}</strong> trong kho.<br>Số lượng đã được điều chỉnh về mức tối đa.`,
                                                                confirmButtonColor: '#ef233c',
                                                                confirmButtonText: 'Đồng ý',
                                                                timer: 5000,
                                                                timerProgressBar: true,
                                                            });
                                                            return false;
                                                        }
                                                    });

                                                    // Realtime check khi nhập số lượng
                                                    const qtyInputEl = document.getElementById('quantity_input');
                                                    if (qtyInputEl) {
                                                        qtyInputEl.addEventListener('input', function() {
                                                            const max = parseInt(this.max);
                                                            const val = parseInt(this.value);
                                                            if (max && val > max) {
                                                                Swal.fire({
                                                                    toast: true,
                                                                    position: 'top-end',
                                                                    icon: 'warning',
                                                                    title: `Chỉ còn ${max} sản phẩm trong kho!`,
                                                                    showConfirmButton: false,
                                                                    timer: 2500,
                                                                    timerProgressBar: true,
                                                                });
                                                                this.value = max;
                                                            }
                                                            if (val < 1 || isNaN(val)) {
                                                                this.value = 1;
                                                            }
                                                        });
                                                    }
                                                }

                                                const formatCurrency = (amount) => {
                                                    return new Intl.NumberFormat('en-US').format(amount) + ' VND';
                                                };

                                                function checkSelection() {
                                                    const selectedSize = sizeInput.value;
                                                    const selectedColor = colorInput.value;

                                                    if (!selectedSize || !selectedColor) {
                                                        variantInput.value = '';
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
                                                                // Cập nhật stock info và max quantity
                                                                const qtyInput = document.getElementById('quantity_input');
                                                                const stockInfo = document.getElementById('stock-info');
                                                                if (qtyInput) {
                                                                    qtyInput.max = matchedVariant.stock_quantity;
                                                                    if (parseInt(qtyInput.value) > matchedVariant.stock_quantity) {
                                                                        qtyInput.value = matchedVariant.stock_quantity;
                                                                    }
                                                                }
                                                                if (stockInfo) {
                                                                    stockInfo.textContent = `(Còn ${matchedVariant.stock_quantity} sản phẩm)`;
                                                                    stockInfo.style.display = 'inline';
                                                                    stockInfo.style.color = matchedVariant.stock_quantity <= 5 ? '#ef233c' : '#666';
                                                                }
                                                            } else {
                                                                variantInput.value = '';
                                                                msg.textContent = "{{ __('messages.variant_out_of_stock') }}";
                                                                msg.style.display = 'block';
                                                                const stockInfo = document.getElementById('stock-info');
                                                                if (stockInfo) stockInfo.style.display = 'none';
                                                            }
                                                        } else {
                                                            const activePrices = filteredVariants.map(v => v.sale_price && v.sale_price < v.price ? v.sale_price : v.price).filter(p => p > 0);
                                                            const minPrice = Math.min(...activePrices);
                                                            const maxPrice = Math.max(...activePrices);

                                                            if (minPrice === maxPrice) {
                                                                html = `<span class="current_price">${formatCurrency(minPrice)}</span>`;
                                                            } else {
                                                                html = `<span class="current_price">${formatCurrency(minPrice)} - ${formatCurrency(maxPrice)}</span>`;
                                                            }

                                                            variantInput.value = '';
                                                            msg.style.display = 'none';
                                                        }

                                                        priceContainer.innerHTML = html;
                                                    } else {
                                                        variantInput.value = '';
                                                        msg.textContent = "{{ __('messages.combination_not_available') }}";
                                                        msg.style.display = 'block';
                                                        priceContainer.innerHTML = originalPriceHtml;
                                                    }
                                                }
                                            });

                                        </script>
                            @endif

                                <div class="product_variant quantity">
                                    <label>{{ __('messages.quantity') }}</label>
                                    <input min="1" max="100" value="1" type="number" name="quantity" id="quantity_input">
                                    <span id="stock-info" style="display:none; font-size:13px; color:#666; margin-left:8px;"></span>
                                    <div style="margin-top:10px;">
                                        <input type="hidden" name="action" id="action_input" value="add_to_cart">
                                        <button class="button" type="button" id="btn-add-to-cart">{{ __('messages.add_to_cart') }}</button>
                                        <button class="button buy_now" type="button" id="btn-buy-now">{{ __('messages.buy_now') }}</button>
                                    </div>
                                </div>
                                <style>
                                    .product_variant.quantity .button {
                                        height: 50px;
                                        line-height: 50px;
                                        padding: 0 35px;
                                        background: #111;
                                        border: 1px solid #111;
                                        color: #fff;
                                        font-weight: 700;
                                        text-transform: uppercase;
                                        letter-spacing: 1px;
                                        transition: all 0.3s ease;
                                    }
                                    .product_variant.quantity .button:hover {
                                        background: #ef233c;
                                        border-color: #ef233c;
                                    }
                                    .product_variant.quantity .button.buy_now {
                                        background: #ef233c; 
                                        border-color: #ef233c; 
                                        margin-left: 10px;
                                    }
                                    .product_variant.quantity .button.buy_now:hover {
                                        background: #111;
                                        border-color: #111;
                                    }
                                    .product_variant.quantity .button:disabled {
                                        background: #ebebeb !important;
                                        border-color: #ebebeb !important;
                                        color: #999 !important;
                                        cursor: not-allowed;
                                    }
                                    /* Highlight chưa chọn thuộc tính */
                                    .nice-select[style*="border-color: rgb(239, 35, 60)"],
                                    .nice-select[style*="border-color: #ef233c"] {
                                        border: 2px solid #ef233c !important;
                                        animation: shake 0.4s ease;
                                    }
                                    @keyframes shake {
                                        0%, 100% { transform: translateX(0); }
                                        25% { transform: translateX(-5px); }
                                        75% { transform: translateX(5px); }
                                    }
                                    .star-rating {
                                        display: inline-flex;
                                        flex-direction: row-reverse;
                                        justify-content: flex-end;
                                        gap: 5px;
                                    }
                                    .star-rating input {
                                        display: none;
                                    }
                                    .star-rating label {
                                        font-size: 24px;
                                        color: #ccc;
                                        cursor: pointer;
                                        transition: color 0.2s;
                                        margin: 0;
                                    }
                                    .star-rating label:hover i,
                                    .star-rating label:hover ~ label i,
                                    .star-rating input:checked ~ label i {
                                        color: #f39c12 !important;
                                    }
                                    .star-rating label i {
                                        pointer-events: none;
                                    }
                                </style>
                                <div class="product_d_action">
                                    <ul>
                                        <li>
                                            <a href="#" class="add-to-wishlist" data-id="{{ $product->id }}"
                                                title="{{ __('messages.add_to_wishlist') }}">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i> {{ __('messages.add_to_wishlist') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </form>
                            <div class="priduct_social">
                                <h3>{{ __('messages.share_on') }}</h3>
                                <ul>
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" title="Share on Facebook"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}" target="_blank" title="Share on Twitter"><i class="fa fa-twitter"></i></a></li>
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
                                            aria-selected="false">{{ __('messages.more_info') }}</a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews"
                                            aria-selected="false">{{ __('messages.reviews') }} ({{ $product->reviews->count() }})</a>
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
                                        <p>{{ __('messages.customer_reviews_for') }} {{ $product->name }}</p>
                                    </div>
                                    @forelse($product->reviews as $review)
                                        <div class="product_info_inner">
                                            <div class="product_ratting mb-10">
                                                <ul>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <li><a href="javascript:void(0)"><i
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
                                    @empty
                                        <div class="alert alert-light text-center py-4" style="border: 1px dashed #ddd; border-radius: 8px;">
                                            <i class="fa fa-commenting-o mb-2" style="font-size: 24px; color: #ccc; display: block;"></i>
                                            <span class="text-muted">{{ __('messages.no_reviews_yet') }}</span>
                                        </div>
                                    @endforelse
                                    <div class="product_review_form">
                                        @if(session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif
                                        @if(session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif

                                        @auth
                                            @php
                                                $userReview = $product->reviews->where('user_id', auth()->id())->first();
                                            @endphp

                                            @if($userReview)
                                                <div class="alert alert-info">
                                                    {{ __('messages.already_reviewed') }}
                                                </div>
                                            @elseif($hasPurchased)
                                                <form action="{{ route('product.review.store', $product->id) }}" method="POST">
                                                    @csrf
                                                    <h2>{{ __('messages.add_a_review') }}</h2>
                                                    <p>{{ __('messages.review_notice') }}</p>

                                                    <div class="product_ratting mb-20">
                                                        <h3>{{ __('messages.your_rating') }}</h3>
                                                        <div class="star-rating">
                                                            <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars"><i class="fa fa-star"></i></label>
                                                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"><i class="fa fa-star"></i></label>
                                                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"><i class="fa fa-star"></i></label>
                                                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"><i class="fa fa-star"></i></label>
                                                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"><i class="fa fa-star"></i></label>
                                                        </div>
                                                        <div id="likert-label" style="display:none; margin-top:6px; font-size:13px; font-weight:600; color:#ef233c; min-height:18px;"></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label for="review_comment">{{ __('messages.your_review') }}</label>
                                                            <textarea name="comment" id="review_comment" required></textarea>
                                                        </div>
                                                    </div>
                                                    <button type="submit">{{ __('messages.submit') }}</button>
                                                </form>
                                            @else
                                                <div class="alert" style="background:#fff8e1; border-left:4px solid #f39c12; padding:15px; border-radius:4px;">
                                                    <i class="fa fa-info-circle" style="color:#f39c12;"></i>
                                                    {{ __('messages.review_purchase_required') }}
                                                    <a href="{{ route('shop') }}" class="btn btn-sm" style="background:#ef233c; color:#fff; margin-left:10px; padding:4px 12px; border-radius:3px;">{{ __('messages.buy_to_review') }}</a>
                                                </div>
                                            @endif
                                        @else
                                            <p>{!! __('messages.login_to_review', ['login' => '<a href="' . route('login') . '" style="color: #ef233c; font-weight: bold;">' . __('messages.login') . '</a>']) !!}</p>
                                        @endauth
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
                            <h2>{{ __('messages.related_products') }}</h2>
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
                        <h5 class="modal-title" style="font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"><i class="fa fa-magic text-primary"></i> {{ __('messages.ai_try_on_modal_title') }}</h5>
                        <button type="button" class="close position-absolute" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="top: 15px; right: 20px; font-size: 28px; background:transparent; border:none;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p class="text-muted mt-2" style="font-size: 14px;">{{ __('messages.ai_try_on_desc') }}</p>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-5 text-center mb-4 mb-md-0">
                                <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">{{ __('messages.user_photo_sample') }}</p>
                                <div class="upload-area p-4 d-flex flex-column align-items-center justify-content-center" style="border: 2px dashed #ccc; border-radius: 8px; background: #fafafa; cursor: pointer; position: relative; min-height: 250px;" id="uploadBtn">
                                    <div id="uploadPlaceholder" style="pointer-events: none;">
                                        <i class="fa fa-cloud-upload fa-3x text-muted mb-3"></i>
                                        <p class="m-0" style="font-size: 14px; font-weight: 600;">{{ __('messages.upload_photo') }}</p>
                                        <p class="text-muted mt-1" style="font-size: 11px;">{!! __('messages.upload_photo_support') !!}</p>
                                    </div>
                                    <input type="file" id="userImageUpload" accept="image/*" style="opacity: 0; position: absolute; top:0; left:0; width: 100%; height: 100%; cursor: pointer; z-index: 100;">
                                    <img id="userImagePreview" src="" style="max-width: 100%; max-height: 230px; border-radius: 6px; display: none; position: relative; z-index: 2;" alt="User Image">
                                    <button type="button" class="btn btn-sm btn-light position-absolute shadow-sm" id="btnChangeImage" style="display:none; bottom: 10px; right: 10px; z-index: 105; font-size: 11px; font-weight: bold;"><i class="fa fa-refresh"></i> {{ __('messages.change_photo') }}</button>
                                </div>
                            </div>
                            <div class="col-md-2 text-center d-none d-md-block">
                                <i class="fa fa-long-arrow-right fa-2x text-muted"></i>
                            </div>
                            <div class="col-md-5 text-center">
                                <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">{{ __('messages.ai_result') }}</p>
                                <div class="result-area p-4 d-flex align-items-center justify-content-center" style="border: 1px solid #eee; border-radius: 8px; background: #f8f9fa; min-height: 250px; position: relative; overflow: hidden;" id="aiResultArea">
                                    <div class="text-muted text-center" id="aiWaitingText">
                                        <i class="fa fa-user-circle-o fa-3x mb-3" style="color: #ddd;"></i><br>
                                        <span style="font-size: 13px;">{!! __('messages.please_upload_photo') !!}</span>
                                    </div>
                                    <div id="aiLoading" class="text-center" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%;">
                                        <i class="fa fa-spinner fa-spin fa-3x mb-3 text-dark"></i>
                                        <p style="font-size: 13px; font-weight: 600; color: #111;" class="m-0">{{ __('messages.ai_processing') }}</p>
                                        <p style="font-size: 11px; color: #666;" class="m-0">{{ __('messages.ai_wait_time') }}</p>
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
                            <i class="fa fa-gears mr-2"></i> {{ __('messages.start_try_on') }}
                        </button>
                        <button type="button" class="btn btn-success px-5 py-3" id="btnDownloadResult" style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; min-width: 250px; display: none;">
                            <i class="fa fa-download mr-2"></i> {{ __('messages.download_result') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @section('scripts')
            <script>
                // Likert scale labels
                var likertLabels = {
                    1: 'Rất không hài lòng',
                    2: 'Không hài lòng',
                    3: 'Bình thường',
                    4: 'Hài lòng',
                    5: 'Rất hài lòng'
                };

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

                    // Keep track of the product image path to calculate its overlay later
                    const productImageSrc = "{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}";

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
                            btnRunAI.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> {{ __('messages.processing') }}';

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
                                btnRunAI.innerHTML = '<i class="fa fa-gears mr-2"></i> {{ __('messages.start_try_on') }}';
                            }, 3000);
                        });

                        // Allow downloading the real composite image using Canvas
                        btnDownloadResult.addEventListener('click', function() {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            // Wait for images to be fully loaded before drawing
                            const baseImgContent = new Image();
                            baseImgContent.crossOrigin = "Anonymous";
                            baseImgContent.src = resultBaseImage.src;

                            baseImgContent.onload = function() {
                                // Set canvas to uploaded image size
                                canvas.width = baseImgContent.width;
                                canvas.height = baseImgContent.height;

                                // Draw base user image
                                ctx.drawImage(baseImgContent, 0, 0);

                                // Load Product Image
                                const prodImg = new Image();
                                prodImg.crossOrigin = "Anonymous";
                                prodImg.src = productImageSrc;

                                prodImg.onload = function() {
                                    // Apply multiply blend mode
                                    ctx.globalCompositeOperation = "multiply";

                                    // Calculate scaling to somewhat fit the center chest area roughly
                                    // This simulates the UI "max-height: 180px" logic but mapped to original resolution
                                    const scaleRatio = (baseImgContent.height * 0.75) / prodImg.height; // Product takes 75% height of origin image
                                    const newProdWidth = prodImg.width * scaleRatio;
                                    const newProdHeight = prodImg.height * scaleRatio;

                                    const posX = (canvas.width - newProdWidth) / 2;
                                    const posY = (canvas.height - newProdHeight) / 2;

                                    // Draw product image
                                    ctx.drawImage(prodImg, posX, posY, newProdWidth, newProdHeight);

                                    // Trigger download
                                    const dataURL = canvas.toDataURL("image/png");
                                    const link = document.createElement('a');
                                    link.download = 'AI_ThuDo_Reid.png';
                                    link.href = dataURL;
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                }
                            }
                        });
                    }

                    // Star hover - show likert label
                    $('.star-rating label').on('mouseenter', function() {
                        var val = $(this).prev('input').val();
                        if (val) {
                            $('#likert-label').text(likertLabels[val]).show();
                        }
                    });

                    // Mouse leave star area - show selected or hide
                    $('.star-rating').on('mouseleave', function() {
                        var selected = $(this).find('input:checked').val();
                        if (selected) {
                            $('#likert-label').text(likertLabels[selected]).show();
                        } else {
                            $('#likert-label').hide();
                        }
                    });

                    // Star selected - keep label
                    $('.star-rating input').on('change', function() {
                        var val = $(this).val();
                        $('#likert-label').text(likertLabels[val]).show();
                        console.log('Rating selected: ' + val);
                    });

                    // Handle Review link click
                    $('#view-reviews-link').on('click', function(e) {
                        e.preventDefault();
                        var tab = new bootstrap.Tab($('a[href="#reviews"]')[0]);
                        tab.show();
                        $('html, body').animate({
                            scrollTop: $(".product_d_info").offset().top - 100
                        }, 500);
                    });

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

                    // AJAX Add to Cart
                    $('#btn-add-to-cart').on('click', function() {
                        $('#action_input').val('add_to_cart');
                        submitAddToCartForm(false);
                    });

                    $('#btn-buy-now').on('click', function() {
                        $('#action_input').val('buy_now');
                        submitAddToCartForm(true);
                    });

                    function submitAddToCartForm(isBuyNow) {
                        var form = $('#add-to-cart-form');
                        var url = form.attr('action');
                        var data = form.serialize();

                        if (isBuyNow) {
                            form.off('submit').submit();
                            return;
                        }

                        Swal.fire({
                            title: 'Đang thêm vào giỏ hàng...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: data,
                            headers: {
                                'Accept': 'application/json'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: 'Sản phẩm đã được thêm vào giỏ hàng.',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Optional: update cart count in header if we had the element
                                $.get('{{ route("cart.count") }}', function(res) {
                                    if(res && res.count !== undefined) {
                                        $('.cart_count').text(res.count);
                                        $('.cart_text_quantity').text(res.count);
                                    }
                                });
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Vui lòng chọn đầy đủ thuộc tính sản phẩm.';
                                // Custom check if user hasn't selected variant
                                if (xhr.status === 422) {
                                    // Highlight variant boxes
                                    $('.niceselect_option').next('.nice-select').css('border-color', '#ef233c');
                                    setTimeout(function() {
                                        $('.niceselect_option').next('.nice-select').css('border-color', '');
                                    }, 3000);
                                }

                                Swal.fire({
                                    title: 'Không thể thêm',
                                    text: msg,
                                    icon: 'warning',
                                    confirmButtonColor: '#ef233c'
                                });
                            }
                        });
                    }
                });
            </script>
        @endsection
@endsection