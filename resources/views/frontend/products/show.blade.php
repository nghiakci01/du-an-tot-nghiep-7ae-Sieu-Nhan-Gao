@extends('layouts.public')

@section('title', $product->name . ' | Elite')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 150))

@section('content')
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

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
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
        .star-rating label:hover~label i,
        .star-rating input:checked~label i {
            color: #f39c12 !important;
        }

        .star-rating label i {
            pointer-events: none;
        }
    </style>
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
    <div class="product_details" id="product-details-container"
        data-variants="{{ json_encode($product->variants) }}"
        data-has-variants="{{ $product->variants->count() > 0 && $product->variants->min('price') > 0 ? 'true' : 'false' }}"
        data-route-wishlist-add="{{ route('wishlist.add') }}"
        data-route-cart-count="{{ route('cart.count') }}"
        data-route-login="{{ route('login') }}"
        data-msg-size="{{ __('messages.size') }}"
        data-msg-color="{{ __('messages.color') }}"
        data-msg-variant-out-of-stock="{{ __('messages.variant_out_of_stock') }}"
        data-msg-combination-not-available="{{ __('messages.combination_not_available') }}">
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
                                @foreach ($product->images as $image)
                                    <li>
                                        <a href="#" class="elevatezoom-gallery" data-update=""
                                            data-image="{{ $image->image_url }}"
                                            data-zoom-image="{{ $image->image_url }}">
                                            <img src="{{ $image->image_url }}"
                                                alt="zo-th-{{ $loop->iteration }}" />
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- AI Try On Button -->
                        <div class="mt-4 mb-3 text-center">
                            <button type="button" class="btn w-100 py-3 d-flex align-items-center justify-content-center" 
                                style="background: linear-gradient(45deg, #833ab4, #fd1d1d, #fcb045); color: white; font-weight: bold; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(253, 29, 29, 0.4); transition: transform 0.2s; font-size: 16px;" 
                                data-bs-toggle="modal" data-bs-target="#aiTryOnModal" data-toggle="modal" data-target="#aiTryOnModal"
                                onmouseover="this.style.transform='scale(1.02)'" 
                                onmouseout="this.style.transform='scale(1)'">
                                <i class="fa fa-magic mr-2" style="font-size: 20px;"></i> ✨ {{ __('messages.ai_try_on') === 'messages.ai_try_on' ? 'Thử Đồ AI' : __('messages.ai_try_on') }}
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
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li><a href="javascript:void(0)"><i
                                                    class="fa {{ $i <= $ratingAvg ? 'fa-star' : 'fa-star-o' }}"></i></a>
                                        </li>
                                    @endfor
                                    <li class="review">
                                        <a href="#reviews" id="view-reviews-link">
                                            ({{ $product->reviews->count() }} {{ __('messages.reviews') }})
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Social Proof badges --}}
                            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin: 10px 0;">
                                @php $totalSold = $product->total_sold; @endphp
                                @if($totalSold > 0)
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0f0f0; padding: 4px 10px; border-radius: 20px; font-size: 12px; color: #555;">
                                    <i class="fa fa-shopping-bag" style="color: #ef233c;"></i> Đã bán {{ $totalSold }}
                                </span>
                                @endif
                                @if($totalSold >= 10)
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff3e0; padding: 4px 10px; border-radius: 20px; font-size: 12px; color: #e65100; font-weight: 600;">
                                    <i class="fa fa-fire"></i> Best Seller
                                </span>
                                @endif
                                @if($product->isOnFlashSale())
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #ff4b2b; padding: 4px 10px; border-radius: 20px; font-size: 12px; color: white; font-weight: 600;">
                                    <i class="fa fa-bolt"></i> Flash Sale
                                </span>
                                @endif
                                @if($ratingAvg >= 4.5 && $product->reviews->count() >= 3)
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: #e8f5e9; padding: 4px 10px; border-radius: 20px; font-size: 12px; color: #2e7d32; font-weight: 600;">
                                    <i class="fa fa-star"></i> Đánh giá cao
                                </span>
                                @endif
                            </div>

                            <div class="product_desc">
                                <p>{{ $product->short_description }}</p>
                            </div>

                            @if ($product->variants->count() > 0 && $product->variants->min('price') > 0)
                                @php
                                    $uniqueSizes = $product->variants
                                        ->pluck('sizeRelationship')
                                        ->filter()
                                        ->unique('id');
                                    $uniqueColors = $product->variants
                                        ->pluck('colorRelationship')
                                        ->filter()
                                        ->unique('id');
                                @endphp

                                <div class="product_variant size">
                                    <h3>{{ __('messages.size') }}</h3>
                                    <select class="niceselect_option" id="select_size_nice" name="size_id">
                                        <option selected value="">{{ __('messages.size') }}</option>
                                        @foreach ($uniqueSizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="select_size" value="">
                                </div>

                                <div class="product_variant color">
                                    <h3>{{ __('messages.color') }}</h3>
                                    <select class="niceselect_option" id="select_color_nice" name="color_id">
                                        <option selected value="">{{ __('messages.color') }}</option>
                                        @foreach ($uniqueColors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="select_color" value="">
                                </div>

                                <input type="hidden" id="variant_select" name="variant_id" required>
                                <div id="variant-message" class="text-danger mt-2 mb-3"
                                    style="font-weight: bold; display: none;"></div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const config = document.getElementById('product-details-container').dataset;
                                        const variants = JSON.parse(config.variants);
                                        const hasVariants = config.hasVariants === 'true';

                                        const niceSize = document.getElementById('select_size_nice');
                                        const niceColor = document.getElementById('select_color_nice');
                                        const sizeInput = document.getElementById('select_size');
                                        const colorInput = document.getElementById('select_color');
                                        const variantInput = document.getElementById('variant_select');
                                        const msg = document.getElementById('variant-message');
                                        const priceContainer = document.querySelector('.product_price');
                                        const form = document.getElementById('add-to-cart-form');

                                        const originalPriceHtml = priceContainer.innerHTML;

                                        // Handle Nice Select changes
                                        $(niceSize).on('change', function() {
                                            sizeInput.value = this.value;
                                            // Remove error highlight when user selects
                                            $(this).closest('.product_variant').find('.nice-select').css('border-color', '');
                                            checkSelection();
                                        });
                                        $(niceColor).on('change', function() {
                                            colorInput.value = this.value;
                                            $(this).closest('.product_variant').find('.nice-select').css('border-color', '');
                                            checkSelection();
                                        });

                                        // Intercept form submit for validation
                                        if (form) {
                                            form.addEventListener('submit', function(e) {
                                                const selectedSize = sizeInput ? sizeInput.value : '1';
                                                const selectedColor = colorInput ? colorInput.value : '1';

                                                if (hasVariants && (!selectedSize || !selectedColor || !variantInput.value)) {
                                                    e.preventDefault();

                                                    let missingFields = [];
                                                    if (!selectedSize) {
                                                        missingFields.push(config.msgSize);
                                                        // Highlight size select
                                                        $('#select_size_nice').next('.nice-select').css('border-color', '#ef233c');
                                                    }
                                                    if (!selectedColor) {
                                                        missingFields.push(config.msgColor);
                                                        // Highlight color select
                                                        $('#select_color_nice').next('.nice-select').css('border-color', '#ef233c');
                                                    }

                                                    let message = missingFields.length > 0 ?
                                                        `Vui lòng chọn: <strong>${missingFields.join(', ')}</strong> trước khi thêm vào giỏ hàng!` :
                                                        'Vui lòng chọn đầy đủ thuộc tính sản phẩm!';

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

                                                    if (matchedVariant.sale_price && matchedVariant.sale_price > 0 && matchedVariant
                                                        .sale_price < matchedVariant.price) {
                                                        html = `<span class="current_price">
                                                                                    <span class="old_price" style="text-decoration: line-through; color: #999; margin-right: 15px;">${formatCurrency(matchedVariant.price)}</span>
                                                                                    <span style="color: #ef233c; font-weight: bold;">${formatCurrency(matchedVariant.sale_price)}</span>
                                                                                </span>`;
                                                    } else {
                                                        html =
                                                            `<span class="current_price" style="font-weight: bold;">${formatCurrency(matchedVariant.price)}</span>`;
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
                                                        msg.textContent = config.msgVariantOutOfStock;
                                                        msg.style.display = 'block';
                                                        const stockInfo = document.getElementById('stock-info');
                                                        if (stockInfo) stockInfo.style.display = 'none';
                                                    }
                                                } else {
                                                    const activePrices = filteredVariants.map(v => v.sale_price && v.sale_price < v.price ? v
                                                        .sale_price : v.price).filter(p => p > 0);
                                                    const minPrice = Math.min(...activePrices);
                                                    const maxPrice = Math.max(...activePrices);

                                                    if (minPrice === maxPrice) {
                                                        html = `<span class="current_price">${formatCurrency(minPrice)}</span>`;
                                                    } else {
                                                        html =
                                                            `<span class="current_price">${formatCurrency(minPrice)} - ${formatCurrency(maxPrice)}</span>`;
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
                                <input min="1" value="1" type="number" name="quantity" id="quantity_input">
                                <div style="margin-top:10px;">
                                    <input type="hidden" name="action" id="action_input" value="add_to_cart">
                                    <button class="button" type="button"
                                        id="btn-add-to-cart">{{ __('messages.add_to_cart') }}</button>
                                    <button class="button buy_now" type="button"
                                        id="btn-buy-now">{{ __('messages.buy_now') }}</button>
                                </div>
                            </div>
                            <div class="product_d_action">
                                <ul>
                                    <li>
                                        <a href="#" class="add-to-wishlist" data-id="{{ $product->id }}"
                                            title="{{ __('messages.add_to_wishlist') }}">
                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            {{ __('messages.add_to_wishlist') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </form>
                        <div class="priduct_social">
                            <h3>{{ __('messages.share_on') }}</h3>
                            <ul>
                                <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        target="_blank" title="Share on Facebook"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}"
                                        target="_blank" title="Share on Twitter"><i class="fa fa-twitter"></i></a></li>
                            </ul>
                        </div>

                        @if ($product->tags && $product->tags->count() > 0)
                            <div class="product_tags mt-4 mb-3" style="border-top: 1px solid #eee; padding-top: 15px;">
                                <h4
                                    style="font-size: 14px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px; color: #333;">
                                    <i class="fa fa-tags" style="color: #ef233c; margin-right: 5px;"></i>
                                    {{ __('messages.tags') === 'messages.tags' ? 'Thẻ' : __('messages.tags') }}
                                </h4>
                                <ul
                                    style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 8px;">
                                    @foreach ($product->tags as $tag)
                                        <li>
                                            <a href="{{ route('shop', ['tag' => $tag->slug]) }}"
                                                style="display: inline-block; padding: 5px 15px; font-size: 12px; color: #666; background: #f5f5f5; border-radius: 3px; text-decoration: none; transition: all 0.3s;"
                                                onmouseover="this.style.background='#ef233c'; this.style.color='#fff';"
                                                onmouseout="this.style.background='#f5f5f5'; this.style.color='#666';">
                                                {{ $tag->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

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
                                    <a class="active" data-bs-toggle="tab" href="#info" role="tab"
                                        aria-controls="info" aria-selected="false">{{ __('messages.more_info') }}</a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews"
                                        aria-selected="false">{{ __('messages.reviews') }}
                                        ({{ $product->reviews->count() }})</a>
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
                                                @for ($i = 1; $i <= 5; $i++)
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
                                    <div class="alert alert-light text-center py-4"
                                        style="border: 1px dashed #ddd; border-radius: 8px;">
                                        <i class="fa fa-commenting-o mb-2"
                                            style="font-size: 24px; color: #ccc; display: block;"></i>
                                        <span class="text-muted">{{ __('messages.no_reviews_yet') }}</span>
                                    </div>
                                @endforelse
                                <div class="product_review_form">
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    @auth
                                        @php
                                            $userReview = $product->reviews->where('user_id', auth()->id())->first();
                                        @endphp

                                        @if ($userReview)
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
                                                        <input type="radio" id="star5" name="rating" value="5"
                                                            required /><label for="star5" title="5 stars"><i
                                                                class="fa fa-star"></i></label>
                                                        <input type="radio" id="star4" name="rating"
                                                            value="4" /><label for="star4" title="4 stars"><i
                                                                class="fa fa-star"></i></label>
                                                        <input type="radio" id="star3" name="rating"
                                                            value="3" /><label for="star3" title="3 stars"><i
                                                                class="fa fa-star"></i></label>
                                                        <input type="radio" id="star2" name="rating"
                                                            value="2" /><label for="star2" title="2 stars"><i
                                                                class="fa fa-star"></i></label>
                                                        <input type="radio" id="star1" name="rating"
                                                            value="1" /><label for="star1" title="1 star"><i
                                                                class="fa fa-star"></i></label>
                                                    </div>
                                                    <div id="likert-label"
                                                        style="display:none; margin-top:6px; font-size:13px; font-weight:600; color:#ef233c; min-height:18px;">
                                                    </div>
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
                                            <div class="alert"
                                                style="background:#fff8e1; border-left:4px solid #f39c12; padding:15px; border-radius:4px;">
                                                <i class="fa fa-info-circle" style="color:#f39c12;"></i>
                                                {{ __('messages.review_purchase_required') }}
                                                <a href="{{ route('shop') }}" class="btn btn-sm"
                                                    style="background:#ef233c; color:#fff; margin-left:10px; padding:4px 12px; border-radius:3px;">{{ __('messages.buy_to_review') }}</a>
                                            </div>
                                        @endif
                                    @else
                                        <p>{!! __('messages.login_to_review', [
                                            'login' =>
                                                '<a href="' . route('login') . '" style="color: #ef233c; font-weight: bold;">' . __('messages.login') . '</a>',
                                        ]) !!}</p>
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
                        @foreach ($relatedProducts as $related)
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
                                                                    class="fa fa-shopping-basket"
                                                                    aria-hidden="true"></i></a>
                                                        </li>
                                                        <li><a href="#" class="add-to-wishlist"
                                                                data-id="{{ $related->id }}" title="Add to Wishlist"><i
                                                                    class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product_content">
                                        <h3><a
                                                href="{{ route('product.detail', $related->slug) }}">{{ $related->name }}</a>
                                        </h3>
                                        @include('frontend.partials.product-price', [
                                            'product' => $related,
                                        ])
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
    <div class="modal fade" id="aiTryOnModal" tabindex="-1" aria-labelledby="aiTryOnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(45deg, #111, #333); color: white; border-bottom: none; padding: 20px 25px;">
                    <h5 class="modal-title" id="aiTryOnModalLabel" style="font-weight: 700; letter-spacing: 1px; margin: 0; color: white;">
                        <i class="fa fa-magic text-warning" style="margin-right: 8px;"></i> {{ __('messages.ai_try_on_modal_title') ?? 'Trải nghiệm Phòng Thử Đồ AI' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Left side: Instructions & Upload -->
                        <div class="col-md-5 p-4" style="background: #f8f9fa; border-right: 1px solid #eee;">
                            <h6 style="font-weight: 600; color: #ef233c; margin-bottom: 15px;">Hướng dẫn:</h6>
                            <ol style="padding-left: 15px; font-size: 14px; color: #555; line-height: 1.6; margin-bottom: 25px;">
                                <li>Tải lên một bức ảnh rõ nét của bạn.</li>
                                <li>Nên chọn ảnh chụp toàn thân hoặc bán thân thẳng đứng.</li>
                                <li>Đợi AI xử lý trang phục (<span style="color: #ef233c; font-weight: bold;">~15-30 giây</span>).</li>
                            </ol>
                            
                            <!-- Sample Guide (Hidden by default, shown on error) -->
                            <div id="vton-guide-sample" style="display: none; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                                <h6 style="color: #856404; margin-bottom: 10px; font-size: 14px; font-weight: bold;">
                                    <i class="fa fa-info-circle"></i> Ảnh mẫu hợp lệ:
                                </h6>
                                <p style="font-size: 13px; color: #856404; margin-bottom: 10px;">Vui lòng nhìn thẳng, rõ khuôn mặt và dáng người (không bị che khuất). Tránh ảnh phong cảnh hoặc chỉ có mỗi khuôn mặt.</p>
                                <div class="d-flex justify-content-center">
                                    <div style="width: 100px; height: 130px; background: #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; border: 2px dashed #856404;">
                                        <i class="fa fa-user" style="font-size: 40px; color: #aaa;"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <form id="vtonForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="mb-4">
                                    <label for="user_image" class="form-label fw-bold" style="font-size: 14px;">Tải ảnh của bạn lên:</label>
                                    <input class="form-control" type="file" id="user_image" name="user_image" accept="image/jpeg, image/png, image/webp" required style="font-size: 14px; padding: 10px;">
                                </div>
                                <button type="submit" class="btn w-100 py-3" id="btn-vton-submit" style="background: #111; color: white; font-weight: bold; border-radius: 6px; border: none; transition: 0.3s;">
                                    Bắt đầu thử đồ
                                </button>
                            </form>
                        </div>
                        
                        <!-- Right side: Preview area -->
                        <div class="col-md-7 p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 400px; background: #fff; position: relative;">
                            <!-- Initial State -->
                            <div id="vton-initial" class="text-center text-muted">
                                <i class="fa fa-picture-o" style="font-size: 50px; color: #ddd; margin-bottom: 15px;"></i>
                                <p style="font-size: 15px; margin: 0;">Kết quả thử đồ sẽ hiển thị tại đây</p>
                            </div>
                            
                            <!-- Loading State -->
                            <div id="vton-loading" class="text-center" style="display: none;">
                                <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h5 style="font-weight: 600; color: #333;">AI Đang Xử Lý...</h5>
                                <p style="color: #666; font-size: 14px; max-width: 250px; margin: 0 auto;">Quá trình này có thể kéo dài từ 30 đến 120 giây (đặc biệt trong lần chạy đầu tiên), vui lòng không đóng cửa sổ này.</p>
                            </div>
                            
                            <!-- Result State -->
                            <div id="vton-result" class="text-center" style="display: none; width: 100%; perspective: 1000px;">
                                <div id="vton-3d-card" style="display: inline-block; padding: 12px; background: linear-gradient(135deg, rgba(239, 35, 60, 0.05), rgba(255, 255, 255, 0.5)); backdrop-filter: blur(10px); border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.8); transform-style: preserve-3d; cursor: pointer; transition: all 0.3s ease;">
                                    <img id="vton-result-image" src="" alt="Virtual Try On Result" style="max-height: 480px; max-width: 100%; border-radius: 10px; transform: translateZ(40px); filter: drop-shadow(0 15px 25px rgba(0,0,0,0.25)); transition: transform 0.3s ease;">
                                    
                                    <!-- Hiệu ứng đổ bóng dưới cùng cho cảm giác đứng trong không gian -->
                                    <div style="position: absolute; bottom: -15px; left: 10%; right: 10%; height: 20px; background: radial-gradient(ellipse at center, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0) 70%); filter: blur(5px); transform: translateZ(-20px); z-index: -1;"></div>
                                </div>
                                <div class="mt-4 d-flex justify-content-center gap-2">
                                    <button type="button" id="vton-add-to-cart" class="btn text-white rounded-pill px-4 shadow-sm" style="background: linear-gradient(45deg, #ef233c, #d90429); border: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="fa fa-shopping-cart"></i> Giữ ản phẩm & Thêm vào giỏ
                                    </button>
                                    <a id="vton-download" href="#" download="ai-try-on.jpg" class="btn btn-dark rounded-pill px-4 shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="fa fa-download"></i> Tải ảnh 3D về
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Try On Modal -->
    <div class="modal fade" id="aiTryOnModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="false" data-backdrop="false" style="z-index: 105050; background: rgba(0,0,0,0.6);">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 text-center d-block position-relative">
                    <h5 class="modal-title" style="font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"><i
                            class="fa fa-magic text-primary"></i> {{ __('messages.ai_try_on_modal_title') === 'messages.ai_try_on_modal_title' ? 'Thử Đồ Thông Minh (AI)' : __('messages.ai_try_on_modal_title') }}</h5>
                    <button type="button" class="close position-absolute" data-bs-dismiss="modal" data-dismiss="modal"
                        aria-label="Close"
                        style="top: 15px; right: 20px; font-size: 28px; background:transparent; border:none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p class="text-muted mt-2" style="font-size: 14px;">{{ __('messages.ai_try_on_desc') === 'messages.ai_try_on_desc' ? 'Tải ảnh toàn thân của bạn lên để AI tự động ghép thử bộ áo/quần này.' : __('messages.ai_try_on_desc') }}</p>
                </div>
                <div class="modal-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">
                                {{ __('messages.user_photo_sample') === 'messages.user_photo_sample' ? 'Ảnh Của Bạn' : __('messages.user_photo_sample') }}</p>
                            <div class="upload-area p-4 d-flex flex-column align-items-center justify-content-center"
                                style="border: 2px dashed #ccc; border-radius: 8px; background: #fafafa; cursor: pointer; position: relative; min-height: 250px;"
                                id="uploadBtn">
                                <div id="uploadPlaceholder" style="pointer-events: none;">
                                    <i class="fa fa-cloud-upload fa-3x text-muted mb-3"></i>
                                    <p class="m-0" style="font-size: 14px; font-weight: 600;">
                                        {{ __('messages.upload_photo') === 'messages.upload_photo' ? 'Nhấn để Tải ảnh lên' : __('messages.upload_photo') }}</p>
                                    <p class="text-muted mt-1" style="font-size: 11px;">{!! __('messages.upload_photo_support') === 'messages.upload_photo_support' ? 'Hỗ trợ JPG, PNG (Max 5MB).' : __('messages.upload_photo_support') !!}</p>
                                </div>
                                <input type="file" id="userImageUpload" accept="image/jpeg, image/png, image/jpg"
                                    style="opacity: 0; position: absolute; top:0; left:0; width: 100%; height: 100%; cursor: pointer; z-index: 100;">
                                <img id="userImagePreview" src=""
                                    style="max-width: 100%; max-height: 230px; border-radius: 6px; display: none; position: relative; z-index: 2;"
                                    alt="User Image">
                                <button type="button" class="btn btn-sm btn-light position-absolute shadow-sm"
                                    id="btnChangeImage"
                                    style="display:none; bottom: 10px; right: 10px; z-index: 105; font-size: 11px; font-weight: bold;"><i
                                        class="fa fa-refresh"></i> {{ __('messages.change_photo') === 'messages.change_photo' ? 'Đổi ảnh khác' : __('messages.change_photo') }}</button>
                            </div>
                        </div>
                        <div class="col-md-2 text-center d-none d-md-block">
                            <i class="fa fa-long-arrow-right fa-2x text-muted"></i>
                        </div>
                        <div class="col-md-5 text-center">
                            <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">
                                {{ __('messages.ai_result') === 'messages.ai_result' ? 'Kết Quả (AI)' : __('messages.ai_result') }}</p>
                            <div class="result-area p-4 d-flex align-items-center justify-content-center"
                                style="border: 1px solid #eee; border-radius: 8px; background: #f8f9fa; min-height: 250px; position: relative; overflow: hidden;"
                                id="aiResultArea">
                                <div class="text-muted text-center" id="aiWaitingText">
                                    <i class="fa fa-user-circle-o fa-3x mb-3" style="color: #ddd;"></i><br>
                                    <span style="font-size: 13px;">{!! __('messages.please_upload_photo') === 'messages.please_upload_photo' ? 'Vui lòng tải ảnh của bạn lên trước' : __('messages.please_upload_photo') !!}</span>
                                </div>
                                <div id="aiLoading" class="text-center"
                                    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%;">
                                    
                                    <div class="progress mb-2" style="height: 10px; margin: 0 auto; width: 80%; border-radius: 10px;">
                                        <div id="aiProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                    
                                    <p id="aiProgressText" style="font-size: 13px; font-weight: 600; color: #111;" class="m-0 mt-2">Đang thiết lập...</p>
                                    <p style="font-size: 11px; color: #666;" class="m-0 mt-1">Quá trình này có thể mất 10-20 giây</p>
                                </div>
                                
                                <div id="aiNoHumanGuide" style="display: none; text-align: center; width: 100%;">
                                    <i class="fa fa-exclamation-triangle text-warning fa-3x mb-2"></i>
                                    <p style="font-weight: bold; font-size: 14px; color: #ef233c;">Video/Ảnh mẫu không đạt yêu cầu</p>
                                    <p style="font-size: 12px; color: #555;">AI không tìm thấy người hoặc tư thế không hợp lệ.</p>
                                    <p style="font-size: 11px; color: #888;">Hãy đảm bảo ảnh chụp toàn thân thẳng góc, không bị cắt xén tay chân, và có đủ ánh sáng.</p>
                                </div>

                                <div id="aiSuccessResult" style="display: none; width: 100%; height: 100%;">
                                    <div
                                        style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <img id="resultBaseImage" src=""
                                            style="max-height: 230px; max-width: 100%; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                    <button type="button" class="btn btn-dark px-5 py-3" id="btnRunAI"
                        style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; min-width: 250px; transition: all 0.3s;"
                        disabled>
                        <i class="fa fa-gears mr-2"></i> Bắt đầu thử đồ
                    </button>
                    <a href="#" class="btn btn-success px-5 py-3" id="btnDownloadResult" target="_blank" download="AI_ThuDo_SNG.jpg"
                        style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; min-width: 250px; display: none;">
                        <i class="fa fa-download mr-2"></i> Tải ảnh về
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.partials.recently-viewed')

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
    <script>
        // Likert scale labels
        var likertLabels = {
            1: 'Rất không hài lòng',
            2: 'Không hài lòng',
            3: 'Bình thường',
            4: 'Hài lòng',
            5: 'Rất hài lòng'
        };

        $(document).ready(function() {
            
            
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
            const aiNoHumanGuide = document.getElementById('aiNoHumanGuide');
            
            const aiProgressBar = document.getElementById('aiProgressBar');
            const aiProgressText = document.getElementById('aiProgressText');
            let progressInterval;

            if (uploadBtn) {
                // Trigger file input when clicking the upload area
                uploadBtn.addEventListener('click', function(e) {
                    if (e.target !== fileInput && e.target !== btnChange && e.target.parentElement !==
                        btnChange) {
                        fileInput.click();
                    }
                });

                btnChange.addEventListener('click', function(e) {
                    e.stopPropagation();
                    fileInput.click();
                });

                // Handle file selection
                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        // Validate file size and type
                        if (file.size > 5 * 1024 * 1024) {
                            Swal.fire({ icon: 'error', title: 'Ảnh quá lớn', text: 'Vui lòng chọn ảnh < 5MB' });
                            return;
                        }
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!allowedTypes.includes(file.type)) {
                            Swal.fire({ icon: 'error', title: 'Định dạng sai', text: 'Chỉ chấp nhận file JPG, PNG' });
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = new Image();
                            img.src = e.target.result;
                            img.onload = function() {
                                // Validate resolution directly
                                if (img.width < 300 || img.height < 400) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Kích thước ảnh nhỏ',
                                        text: 'Chất lượng ảnh thấp có thể khiến hình ảnh AI tạo ra bị mờ hoặc bị lỗi khuôn mặt.'
                                    });
                                }

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
                                aiNoHumanGuide.style.display = 'none';
                                btnDownloadResult.style.display = 'none';
                                btnRunAI.style.display = 'inline-block';
                            };
                        };
                        reader.readAsDataURL(file);
                    }
                });

                function resetProgress() {
                    clearInterval(progressInterval);
                    if(aiProgressBar) aiProgressBar.style.width = '0%';
                }

                function simulateProgress() {
                    let progress = 0;
                    if(aiProgressBar) aiProgressBar.style.width = '0%';
                    aiProgressText.innerText = "Đang tải ảnh của bạn lên server (10%)...";
                    
                    progressInterval = setInterval(() => {
                        progress += Math.random() * 5;
                        if (progress > 95) progress = 95; // Capping at 95% until complete
                        
                        if(aiProgressBar) aiProgressBar.style.width = progress + '%';
                        
                        if (progress > 30 && progress < 60) {
                            aiProgressText.innerText = "Đang gửi ảnh sang máy chủ HuggingFace mô hình Kolors...";
                        } else if (progress >= 60 && progress < 85) {
                            aiProgressText.innerText = "AI đang phần tích kết cấu trang phục và tư thế người...";
                        } else if (progress >= 85) {
                            aiProgressText.innerText = "Đang áp dụng chất liệu lên da và dựng ảnh (sắp xong)...";
                        }
                    }, 800);
                }

                // Handle Run AI
                btnRunAI.addEventListener('click', function() {
                    if (!fileInput.files[0]) return;

                    // Start loading UI
                    aiWaitingText.style.display = 'none';
                    aiSuccessResult.style.display = 'none';
                    aiNoHumanGuide.style.display = 'none';
                    aiLoading.style.display = 'block';
                    btnRunAI.disabled = true;
                    btnRunAI.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Đang xử lý...';

                    simulateProgress();

                    const formData = new FormData();
                    formData.append('user_image', fileInput.files[0]);
                    formData.append('product_id', '{{ $product->id }}');

                    $.ajax({
                        url: '/api/vton',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            resetProgress();
                            
                            if (response.success && response.image_url) {
                                aiLoading.style.display = 'none';
                                resultBaseImage.src = response.image_url;
                                aiSuccessResult.style.display = 'block';
                                
                                btnRunAI.style.display = 'none';
                                btnDownloadResult.href = response.image_url;
                                btnDownloadResult.style.display = 'inline-block';
                            } else {
                                handleVtonError(response);
                            }
                        },
                        error: function(xhr) {
                            resetProgress();
                            const response = xhr.responseJSON || {};
                            handleVtonError(response);
                        }
                    });
                });

                function handleVtonError(response) {
                    aiLoading.style.display = 'none';
                    aiSuccessResult.style.display = 'none';
                    btnRunAI.disabled = false;
                    btnRunAI.innerHTML = '<i class="fa fa-gears mr-2"></i> Bắt đầu thử đồ';
                    btnRunAI.style.display = 'inline-block';

                    if (response.error_code === 'NO_HUMAN_DETECTED') {
                        aiNoHumanGuide.style.display = 'block';
                    } else {
                        aiWaitingText.style.display = 'block';
                        Swal.fire({
                            icon: 'error',
                            title: 'Thử đồ thất bại',
                            text: response.message || 'Đã xảy ra lỗi kết nối. Vui lòng thử lại sau.'
                        });
                    }
                }
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

            $('.add-to-wishlist').click(function(e) {
                e.preventDefault();
                const config = document.getElementById('product-details-container').dataset;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                var productId = $(this).data('id');
                var icon = $(this).find('i');

                $.ajax({
                    url: config.routeWishlistAdd,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.status === 'success' || response.status === 'info') {
                            // Change icon to filled heart
                            icon.removeClass('fa-heart-o').addClass('fa-heart').css('color',
                                'red');
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = config.routeLogin;
                        } else {
                            alert('An error occurred, please try again!');
                        }
                    }
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
                const config = document.getElementById('product-details-container').dataset;
                var form = $('#add-to-cart-form');
                var url = form.attr('action');
                var allVariants = JSON.parse(config.variants);

                // Kiểm tra thuộc tính bắt buộc
                var hasVariants = config.hasVariants === 'true';

                if (hasVariants) {
                    // Đọc giá trị size & color từ select gốc
                    var selectedSize = $('#select_size_nice').val();
                    var selectedColor = $('#select_color_nice').val();

                    // Validate: phải chọn đủ size và color
                    if (!selectedSize || !selectedColor) {
                        var missingFields = [];
                        if (!selectedSize) {
                            missingFields.push(config.msgSize);
                            $('#select_size_nice').next('.nice-select').css('border-color', '#ef233c');
                        }
                        if (!selectedColor) {
                            missingFields.push(config.msgColor);
                            $('#select_color_nice').next('.nice-select').css('border-color', '#ef233c');
                        }
                        setTimeout(function() {
                            $('.niceselect_option').next('.nice-select').css('border-color', '');
                        }, 3000);

                        var message = missingFields.length > 0 ?
                            'Vui lòng chọn: <strong>' + missingFields.join(', ') + '</strong> trước khi tiếp tục!' :
                            'Vui lòng chọn đầy đủ thuộc tính sản phẩm!';

                        Swal.fire({
                            icon: 'warning',
                            title: 'Chưa chọn thuộc tính!',
                            html: message,
                            confirmButtonColor: '#ef233c',
                            confirmButtonText: 'Chọn ngay',
                            timer: 4000,
                            timerProgressBar: true,
                        });
                        return;
                    }

                    // Tự tìm variant khớp với size + color đã chọn
                    var matchedVariant = null;
                    for (var i = 0; i < allVariants.length; i++) {
                        var v = allVariants[i];
                        if (String(v.size_id) === String(selectedSize) && String(v.color_id) === String(
                                selectedColor)) {
                            matchedVariant = v;
                            break;
                        }
                    }

                    if (!matchedVariant) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tổ hợp không có!',
                            text: 'Không tìm thấy sản phẩm với size và màu sắc này.',
                            confirmButtonColor: '#ef233c',
                        });
                        return;
                    }

                    if (matchedVariant.stock_quantity <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hết hàng!',
                            html: 'Sản phẩm với lựa chọn này hiện đã <strong>hết hàng</strong>.<br>Vui lòng chọn thuộc tính khác.',
                            confirmButtonColor: '#ef233c',
                            confirmButtonText: 'Chọn lại',
                        });
                        return;
                    }

                    // Kiểm tra số lượng yêu cầu vượt tồn kho
                    var requestedQty = parseInt($('#quantity_input').val()) || 1;
                    if (requestedQty > matchedVariant.stock_quantity) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Vượt quá tồn kho!',
                            html: `Chỉ còn <strong>${matchedVariant.stock_quantity}</strong> sản phẩm trong kho.<br>Số lượng đã được điều chỉnh về mức tối đa.`,
                            confirmButtonColor: '#ef233c',
                            confirmButtonText: 'Đồng ý',
                        });
                        $('#quantity_input').val(matchedVariant.stock_quantity);
                        return;
                    }

                    // Gán variant_id vào form trước khi serialize
                    $('#variant_select').val(matchedVariant.id);
                }

                var data = form.serialize();

                if (isBuyNow) {
                    $('#action_input').val('buy_now');
                    data = form.serialize();
                }

                if (!isBuyNow) {
                    Swal.fire({
                        title: 'Đang thêm vào giỏ hàng...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.redirect) {
                            // buy_now → chuyển đến checkout
                            window.location.href = response.redirect;
                            return;
                        }
                        Swal.fire({
                            title: 'Thành công!',
                            text: 'Sản phẩm đã được thêm vào giỏ hàng.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // Cập nhật số lượng giỏ hàng trên header
                        let cartCountElements = document.querySelectorAll('.cart-count');
                        if (response.count !== undefined) {
                            cartCountElements.forEach(el => {
                                el.innerText = response.count;
                                el.classList.remove('pulse-animation');
                                void el.offsetWidth;
                                el.classList.add('pulse-animation');
                            });
                        } else {
                            const config = document.getElementById('product-details-container').dataset;
                            $.get(config.routeCartCount, function(res) {
                                if (res && res.count !== undefined) {
                                    cartCountElements.forEach(el => {
                                        el.innerText = res.count;
                                        el.classList.remove('pulse-animation');
                                        void el.offsetWidth;
                                        el.classList.add('pulse-animation');
                                    });
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ?
                            xhr.responseJSON.message :
                            'Vui lòng chọn đầy đủ thuộc tính sản phẩm.';
                        if (xhr.status === 422) {
                            $('.niceselect_option').next('.nice-select').css('border-color', '#ef233c');
                            setTimeout(function() {
                                $('.niceselect_option').next('.nice-select').css('border-color',
                                    '');
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

            // VTON Modal Open Handling
            $('#btn-open-vton-modal').on('click', function(e) {
                e.preventDefault();
                const config = document.getElementById('product-details-container').dataset;

                var hasVariants = config.hasVariants === 'true';

                if (hasVariants) {
                    var selectedSize = $('#select_size_nice').val();
                    var selectedColor = $('#select_color_nice').val();

                    if (!selectedSize || !selectedColor) {
                        var missingFields = [];
                        if (!selectedSize) {
                            missingFields.push(config.msgSize);
                            $('#select_size_nice').next('.nice-select').css('border-color', '#ef233c');
                        }
                        if (!selectedColor) {
                            missingFields.push(config.msgColor);
                            $('#select_color_nice').next('.nice-select').css('border-color', '#ef233c');
                        }
                        setTimeout(function() {
                            $('.niceselect_option').next('.nice-select').css('border-color', '');
                        }, 3000);

                        var message = missingFields.length > 0 ?
                            'Vui lòng chọn: <strong>' + missingFields.join(', ') + '</strong> trước khi thử đồ!' :
                            'Vui lòng chọn đầy đủ thuộc tính sản phẩm trước khi thử đồ!';

                        Swal.fire({
                            icon: 'warning',
                            title: 'Chưa chọn thuộc tính!',
                            html: message,
                            confirmButtonColor: '#ef233c',
                            confirmButtonText: 'Chọn ngay',
                            timer: 4000,
                            timerProgressBar: true,
                        });
                        return;
                    }
                }

                // If validation passes or no variants, open the modal
                var myModal = new bootstrap.Modal(document.getElementById('aiTryOnModal'));
                myModal.show();
            });

        });

        function showSmartError(message, type = 'error') {
            Swal.fire({
                icon: type === 'error' ? 'error' : 'warning',
                title: type === 'error' ? 'Lỗi ảnh' : 'Lưu ý ảnh',
                text: message,
                confirmButtonColor: '#ef233c',
            });
        }

        document.getElementById('user_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // 1. Kiểm tra dung lượng (Max 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                showSmartError("File quá lớn. Vui lòng tải ảnh dung lượng dưới 5MB.");
                e.target.value = ''; // Reset input
                return;
            }

            // 2. Kiểm tra định dạng (.jpg, .png)
            const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showSmartError("Định dạng không hợp lệ. Chỉ chấp nhận file .jpg, .png hoặc .webp.");
                e.target.value = '';
                return;
            }

            // 3. Kiểm tra độ phân giải & Cảnh báo chụp toàn thân
            const fileURL = window.URL || window.webkitURL;
            const img = new Image();
            img.onload = function() {
                fileURL.revokeObjectURL(this.src); // Xóa bộ nhớ đệm
                
                // Kiểm tra resolution tối thiểu (600x800 hoặc 800x600 nếu chụp ngang)
                const isMinResValid = (this.width >= 600 && this.height >= 800) || (this.width >= 800 && this.height >= 600);
                if (!isMinResValid) {
                    showSmartError("Ảnh quá nhỏ hoặc mờ. Vui lòng chụp ảnh có độ phân giải tối thiểu 600x800px hoặc 800x600px.");
                    e.target.value = '';
                    return;
                }

                // Cảnh báo thêm UX: Gợi ý đứng thẳng nếu khung ảnh là ảnh vuông (Square) hoặc quá dị dạng
                const ratio = this.height / this.width;
                if (ratio < 1.0) {
                    // Ảnh đang nằm ngang (landscape) -> Báo với User là server sẽ tự dựng đứng ảnh hoặc cảnh báo
                    console.info("Hệ thống sẽ tự động xoay ảnh về chiều dọc.");
                } else if (ratio < 1.2) {
                    // Ảnh gần dạng vuông -> Khả năng cao không thấy toàn thân
                    showSmartError("Vui lòng đứng thẳng và chụp rõ toàn thân từ đầu đến chân để thử đồ chính xác nhất.", 'warning');
                }
            };
            img.src = fileURL.createObjectURL(file);
        });

        // VTON handling
        $('#vtonForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var btn = $('#btn-vton-submit');
            
            // UI Changes
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang tải lên...');
            $('#vton-initial, #vton-result').hide();
            $('#vton-loading').fadeIn();
            
            $.ajax({
                url: '{{ route("api.vton.tryOn") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    btn.prop('disabled', false).text('Thử ảnh khác');
                    $('#vton-loading').hide();
                    
                    if(response.success && response.image_url) {
                        $('#vton-guide-sample').slideUp();
                        $('#vton-result-image').attr('src', response.image_url);
                        $('#vton-download').attr('href', response.image_url);
                        $('#vton-result').fadeIn(400, function() {
                            // Init 3D Tilt Effect
                            if (typeof VanillaTilt !== 'undefined') {
                                VanillaTilt.init(document.querySelector("#vton-3d-card"), {
                                    max: 12,
                                    speed: 400,
                                    glare: true,
                                    "max-glare": 0.4,
                                    perspective: 1000,
                                    scale: 1.03
                                });
                            }
                        });
                    } else {
                        $('#vton-initial').fadeIn();
                        Swal.fire({
                            icon: 'error',
                            title: 'Thử đồ thất bại',
                            text: response.message || 'Đã có lỗi xảy ra',
                        });
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('Thử đồ lại');
                    $('#vton-loading').hide();
                    $('#vton-initial').fadeIn();
                    
                    let msg = 'Máy chủ AI hiện không phản hồi hoặc đang quá tải. Hãy thử lại.';
                    
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                        // Hiển thị khung hướng dẫn nếu lỗi do không tìm thấy người
                        if(xhr.responseJSON.error_code === 'NO_HUMAN_DETECTED') {
                            $('#vton-guide-sample').slideDown();
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi hệ thống',
                        text: msg,
                    });
                }
            });
        });

        // Add to cart from VTON modal
        $('#vton-add-to-cart').on('click', function(e) {
            e.preventDefault();
            
            // Close the modal
            $('#aiTryOnModal').modal('hide');
            
            // Trigger the main add to cart button
            // If variants exist, it will show the alert if not selected
            $('#btn-add-to-cart').trigger('click');
        });

    </script>
    <!-- AI Try On Modal -->
    <div class="modal fade" id="aiTryOnModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1050;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 text-center d-block position-relative">
                    <h5 class="modal-title" style="font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"><i
                            class="fa fa-magic text-primary"></i> {{ __('messages.ai_try_on_modal_title') === 'messages.ai_try_on_modal_title' ? 'Thử Đồ Thông Minh (AI)' : __('messages.ai_try_on_modal_title') }}</h5>
                    <button type="button" class="close position-absolute" data-bs-dismiss="modal" data-dismiss="modal"
                        aria-label="Close"
                        style="top: 15px; right: 20px; font-size: 28px; background:transparent; border:none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p class="text-muted mt-2" style="font-size: 14px;">{{ __('messages.ai_try_on_desc') === 'messages.ai_try_on_desc' ? 'Tải ảnh toàn thân của bạn lên để AI tự động ghép thử bộ áo/quần này.' : __('messages.ai_try_on_desc') }}</p>
                </div>
                <div class="modal-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">
                                {{ __('messages.user_photo_sample') === 'messages.user_photo_sample' ? 'Ảnh Của Bạn' : __('messages.user_photo_sample') }}</p>
                            <div class="upload-area p-4 d-flex flex-column align-items-center justify-content-center"
                                style="border: 2px dashed #ccc; border-radius: 8px; background: #fafafa; cursor: pointer; position: relative; min-height: 250px;"
                                id="uploadBtn">
                                <div id="uploadPlaceholder" style="pointer-events: none;">
                                    <i class="fa fa-cloud-upload fa-3x text-muted mb-3"></i>
                                    <p class="m-0" style="font-size: 14px; font-weight: 600;">
                                        {{ __('messages.upload_photo') === 'messages.upload_photo' ? 'Nhấn để Tải ảnh lên' : __('messages.upload_photo') }}</p>
                                    <p class="text-muted mt-1" style="font-size: 11px;">{!! __('messages.upload_photo_support') === 'messages.upload_photo_support' ? 'Hỗ trợ JPG, PNG (Max 5MB).' : __('messages.upload_photo_support') !!}</p>
                                </div>
                                <input type="file" id="userImageUpload" accept="image/jpeg, image/png, image/jpg"
                                    style="opacity: 0; position: absolute; top:0; left:0; width: 100%; height: 100%; cursor: pointer; z-index: 100;">
                                <img id="userImagePreview" src=""
                                    style="max-width: 100%; max-height: 230px; border-radius: 6px; display: none; position: relative; z-index: 2;"
                                    alt="User Image">
                                <button type="button" class="btn btn-sm btn-light position-absolute shadow-sm"
                                    id="btnChangeImage"
                                    style="display:none; bottom: 10px; right: 10px; z-index: 105; font-size: 11px; font-weight: bold;"><i
                                        class="fa fa-refresh"></i> {{ __('messages.change_photo') === 'messages.change_photo' ? 'Đổi ảnh khác' : __('messages.change_photo') }}</button>
                            </div>
                        </div>
                        <div class="col-md-2 text-center d-none d-md-block">
                            <i class="fa fa-long-arrow-right fa-2x text-muted"></i>
                        </div>
                        <div class="col-md-5 text-center">
                            <p class="mb-2" style="font-weight: 600; font-size: 13px; text-transform: uppercase;">
                                {{ __('messages.ai_result') === 'messages.ai_result' ? 'Kết Quả (AI)' : __('messages.ai_result') }}</p>
                            <div class="result-area p-4 d-flex align-items-center justify-content-center"
                                style="border: 1px solid #eee; border-radius: 8px; background: #f8f9fa; min-height: 250px; position: relative; overflow: hidden;"
                                id="aiResultArea">
                                <div class="text-muted text-center" id="aiWaitingText">
                                    <i class="fa fa-user-circle-o fa-3x mb-3" style="color: #ddd;"></i><br>
                                    <span style="font-size: 13px;">{!! __('messages.please_upload_photo') === 'messages.please_upload_photo' ? 'Vui lòng tải ảnh của bạn lên trước' : __('messages.please_upload_photo') !!}</span>
                                </div>
                                <div id="aiLoading" class="text-center"
                                    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%;">
                                    
                                    <div class="progress mb-2" style="height: 10px; margin: 0 auto; width: 80%; border-radius: 10px;">
                                        <div id="aiProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                    
                                    <p id="aiProgressText" style="font-size: 13px; font-weight: 600; color: #111;" class="m-0 mt-2">Đang thiết lập...</p>
                                    <p style="font-size: 11px; color: #666;" class="m-0 mt-1">Quá trình này có thể mất 10-20 giây</p>
                                </div>
                                
                                <div id="aiNoHumanGuide" style="display: none; text-align: center; width: 100%;">
                                    <i class="fa fa-exclamation-triangle text-warning fa-3x mb-2"></i>
                                    <p style="font-weight: bold; font-size: 14px; color: #ef233c;">Video/Ảnh mẫu không đạt yêu cầu</p>
                                    <p style="font-size: 12px; color: #555;">AI không tìm thấy người hoặc tư thế không hợp lệ.</p>
                                    <p style="font-size: 11px; color: #888;">Hãy đảm bảo ảnh chụp toàn thân thẳng góc, không bị cắt xén tay chân, và có đủ ánh sáng.</p>
                                </div>

                                <div id="aiSuccessResult" style="display: none; width: 100%; height: 100%;">
                                    <div
                                        style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <img id="resultBaseImage" src=""
                                            style="max-height: 230px; max-width: 100%; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                    <button type="button" class="btn btn-dark px-5 py-3" id="btnRunAI"
                        style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; min-width: 250px; transition: all 0.3s;"
                        disabled>
                        <i class="fa fa-gears mr-2"></i> Bắt đầu thử đồ
                    </button>
                    <a href="#" class="btn btn-success px-5 py-3" id="btnDownloadResult" target="_blank" download="AI_ThuDo_SNG.jpg"
                        style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; min-width: 250px; display: none;">
                        <i class="fa fa-download mr-2"></i> Tải ảnh về
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
