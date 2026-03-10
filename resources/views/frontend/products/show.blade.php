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
                                @foreach ($product->images as $image)
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
                        
                        <!-- AI Try On Button -->
                        <div class="mt-4 text-center">
                            <button type="button" id="btn-open-vton-modal" class="btn w-100 py-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, #833ab4, #fd1d1d, #fcb045); color: white; font-weight: bold; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(253, 29, 29, 0.4); transition: transform 0.2s; font-size: 16px;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="fa fa-magic" style="margin-right: 8px; font-size: 20px;"></i> ✨ {{ __('messages.ai_try_on') ?? 'Thử Đồ Thực Tế Ảo (AI)' }}
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
                                        const variants = @json($product->variants);
                                        const niceSize = document.getElementById('select_size_nice');
                                        const niceColor = document.getElementById('select_color_nice');
                                        const sizeInput = document.getElementById('select_size');
                                        const colorInput = document.getElementById('select_color');
                                        const variantInput = document.getElementById('variant_select');
                                        const msg = document.getElementById('variant-message');
                                        const priceContainer = document.querySelector('.product_price');
                                        const form = document.querySelector('form[action="{{ route('cart.add') }}"]');

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
                                                const hasVariants = @json($product->variants->count() > 0 && $product->variants->min('price') > 0);

                                                if (hasVariants && (!selectedSize || !selectedColor || !variantInput.value)) {
                                                    e.preventDefault();

                                                    let missingFields = [];
                                                    if (!selectedSize) {
                                                        missingFields.push('{{ __('messages.size') }}');
                                                        // Highlight size select
                                                        $('#select_size_nice').next('.nice-select').css('border-color', '#ef233c');
                                                    }
                                                    if (!selectedColor) {
                                                        missingFields.push('{{ __('messages.color') }}');
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
                                                        msg.textContent = "{{ __('messages.variant_out_of_stock') }}";
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
                var productId = $(this).data('id');
                var icon = $(this).find('i');

                $.ajax({
                    url: '{{ route('wishlist.add') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
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
                            window.location.href = "{{ route('login') }}";
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
                var form = $('#add-to-cart-form');
                var url = form.attr('action');
                var allVariants = @json($product->variants);

                // Kiểm tra thuộc tính bắt buộc
                var hasVariants = @json($product->variants->count() > 0 && $product->variants->min('price') > 0);

                if (hasVariants) {
                    // Đọc giá trị size & color từ select gốc
                    var selectedSize = $('#select_size_nice').val();
                    var selectedColor = $('#select_color_nice').val();

                    // Validate: phải chọn đủ size và color
                    if (!selectedSize || !selectedColor) {
                        var missingFields = [];
                        if (!selectedSize) {
                            missingFields.push('{{ __('messages.size') }}');
                            $('#select_size_nice').next('.nice-select').css('border-color', '#ef233c');
                        }
                        if (!selectedColor) {
                            missingFields.push('{{ __('messages.color') }}');
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
                            $.get('{{ route('cart.count') }}', function(res) {
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

                var hasVariants = @json($product->variants->count() > 0 && $product->variants->min('price') > 0);

                if (hasVariants) {
                    var selectedSize = $('#select_size_nice').val();
                    var selectedColor = $('#select_color_nice').val();

                    if (!selectedSize || !selectedColor) {
                        var missingFields = [];
                        if (!selectedSize) {
                            missingFields.push('{{ __('messages.size') }}');
                            $('#select_size_nice').next('.nice-select').css('border-color', '#ef233c');
                        }
                        if (!selectedColor) {
                            missingFields.push('{{ __('messages.color') }}');
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
@endsection
