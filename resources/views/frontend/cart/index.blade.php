@extends('layouts.public')

@section('title', __('messages.shopping_cart') . ' | FashionStore')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.shopping_cart') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!--shopping cart area start -->
    <div class="shopping_cart_area">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('cart') && count(session('cart')) > 0)
                <form action="#">
                    <div class="row">
                        <div class="col-12">
                            <div class="table_desc">
                                <div class="cart_page table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product_remove">{{ __('messages.remove') }}</th>
                                                <th class="product_thumb">{{ __('messages.image') }}</th>
                                                <th class="product_name">{{ __('messages.product') }}</th>
                                                <th class="product-price">{{ __('messages.price') }}</th>
                                                <th class="product_quantity">{{ __('messages.quantity') }}</th>
                                                <th class="product_total">{{ __('messages.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(session('cart') as $id => $details)
                                                                                        <tr data-id="{{ $id }}">
                                                    <td class="product_remove">
                                                        <a href="javascript:void(0)" class="remove-from-cart">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </td>
                                                    <td class="product_thumb">
                                                        <a href="{{ route('product.detail', $details['slug']) }}">
                                                            <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : asset('frontend-assets/img/s-product/product.jpg') }}"
                                                                alt="{{ $details['name'] }}"
                                                                style="width: 100px; height: 100px; object-fit: cover;">
                                                        </a>
                                                    </td>
                                                    <td class="product_name">
                                                        <a href="{{ route('product.detail', $details['slug']) }}">{{ $details['name'] }}</a>
                                                        <div class="cart-variant-info mt-2">
                                                            <span class="text-muted small">
                                                                {{ __('messages.size') }}: <strong>{{ $details['size'] }}</strong> | 
                                                                {{ __('messages.color') }}: <strong>{{ $details['color'] }}</strong>
                                                            </span>
                                                            <button type="button" class="btn btn-sm btn-link p-0 ms-2 edit-variant-btn" style="text-decoration: underline;">
                                                                {{ __('messages.edit') ?? 'Đổi' }}
                                                            </button>
                                                        </div>

                                                        <div class="cart-variant-selectors mt-2" style="display: none;">
                                                            @if(isset($details['available_sizes']) && count($details['available_sizes']) > 0)
                                                                <div class="d-inline-block me-2">
                                                                    <label class="small text-muted d-block">{{ __('messages.size') }}</label>
                                                                    <select class="form-select form-select-sm variant-select size-select" data-type="size">
                                                                        @foreach($details['available_sizes'] as $size)
                                                                            <option value="{{ $size->id }}" {{ (isset($details['size_id']) && $details['size_id'] == $size->id) ? 'selected' : '' }}>
                                                                                {{ $size->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            @if(isset($details['available_colors']) && count($details['available_colors']) > 0)
                                                                <div class="d-inline-block">
                                                                    <label class="small text-muted d-block">{{ __('messages.color') }}</label>
                                                                    <select class="form-select form-select-sm variant-select color-select" data-type="color">
                                                                        @foreach($details['available_colors'] as $color)
                                                                            <option value="{{ $color->id }}" {{ (isset($details['color_id']) && $details['color_id'] == $color->id) ? 'selected' : '' }}>
                                                                                {{ $color->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                            <div class="mt-1">
                                                                <button type="button" class="btn btn-sm btn-secondary cancel-variant-btn">Hủy</button>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" class="product-id" value="{{ $details['product_id'] }}">
                                                        <input type="hidden" class="current-variant-id" value="{{ $id }}">
                                                    </td>
                                                    <td class="product-price">{{ number_format($details['price']) }} VND</td>
                                                    <td class="product_quantity">
                                                        <input min="1" max="100" value="{{ $details['quantity'] }}" type="number"
                                                            class="quantity update-cart">
                                                    </td>
                                                    <td class="product_total">
                                                        {{ number_format($details['price'] * $details['quantity']) }} VND</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="cart_submit">
                                    <a href="{{ route('shop') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> {{ __('messages.continue_shopping') }}
                                    </a>
                                    <button type="button" class="btn btn-danger" id="clear-cart">
                                        <i class="fa fa-trash"></i> {{ __('messages.clear_cart') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--coupon code area start-->
                    <div class="coupon_area">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="coupon_code left">
                                    <h3>{{ __('messages.coupon_code') }}</h3>
                                    <div class="coupon_inner">
                                        <p>{{ __('messages.enter_coupon_desc') }}</p>
                                        <input placeholder="{{ __('messages.coupon_code') }}" type="text" disabled>
                                        <button type="button" disabled>{{ __('messages.apply') }}</button>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fa fa-info-circle"></i> {{ __('messages.feature_in_development') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="coupon_code right">
                                    <h3>{{ __('messages.cart_totals') }}</h3>
                                    <div class="coupon_inner">
                                        <div class="cart_subtotal">
                                            <p>{{ __('messages.subtotal') }}</p>
                                            <p class="cart_amount" id="cart-subtotal">{{ number_format($total) }} VND</p>
                                        </div>
                                        <div class="cart_subtotal ">
                                            <p>{{ __('messages.shipping') }}</p>
                                            @php
                                                $shippingFee = \App\Models\Setting::getShippingFee($total);
                                            @endphp
                                            <p class="cart_amount" id="shipping-fee">
                                                <span>{{ $shippingFee > 0 ? (number_format($shippingFee) . ' đ') : __('messages.free') }}</span>
                                            </p>
                                        </div>

                                        <div class="cart_subtotal">
                                            <p>{{ __('messages.grand_total') }}</p>
                                            <p class="cart_amount" id="cart-grand-total">{{ number_format($total + $shippingFee) }} đ</p>
                                        </div>
                                        <div class="checkout_btn">
                                            <a href="{{ route('checkout.index') }}">{{ __('messages.proceed_to_checkout') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--coupon code area end-->

                </form>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fa fa-shopping-cart" style="font-size: 100px; color: #ccc;"></i>
                    </div>
                    <h3>{{ __('messages.cart_empty') }}</h3>
                    <p class="text-muted">{{ __('messages.cart_empty_desc') }}</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
                        <i class="fa fa-shopping-bag"></i> {{ __('messages.continue_shopping') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
    <!--shopping cart area end -->
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Update cart quantity
        $(".update-cart").on('change keyup', function (e) {
            var ele = $(this);
            var row = ele.parents("tr");
            var id = row.attr("data-id");
            var quantity = ele.val();
            
            // Prevent duplicate triggers for same value
            if (ele.data('prev-val') == quantity) return;
            ele.data('prev-val', quantity);

            if (quantity < 1) return;

            $.ajax({
                url: '{{ route('cart.update') }}',
                method: "PATCH",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id, 
                    quantity: quantity
                },
                success: function (response) {
                    if(response.success) {
                        // Update item total
                        row.find('.product_total').text(response.item_total);
                        
                        // Update cart totals
                        $('#cart-subtotal').text(response.cart_total);
                        $('#shipping-fee span').text(response.shipping_fee);
                        $('#cart-grand-total').text(response.grand_total);
                        
                        // Update header cart count
                        $('#cart-count').text(response.cart_count);
                        
                        // Optional: Show a small toast or visual feedback instead of alert
                        // alert(response.message); 
                    } else {
                        alert("{{ __('messages.error_occurred') }}");
                        window.location.reload(); // Fallback
                    }
                },
                error: function(xhr) {
                    var errorMsg = xhr.responseJSON ? xhr.responseJSON.message : "{{ __('messages.cart_update_error') }}";
                    alert(errorMsg);
                    window.location.reload(); // Reset to valid state
                }
            });
        });

        // Remove item from cart
        $(".remove-from-cart").on('click', function (e) {
            e.preventDefault();
            var ele = $(this);
            var row = ele.parents("tr");
            var id = row.attr("data-id");
            
            if(confirm("{{ __('messages.confirm_remove_cart_item') }}")) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        _method: 'DELETE',
                        id: id
                    },
                    success: function (response) {
                        if(response.success) {
                            row.fadeOut(300, function() { $(this).remove(); });
                            
                            // Update cart totals
                            $('#cart-subtotal').text(response.cart_total);
                            $('#shipping-fee span').text(response.shipping_fee);
                            $('#cart-grand-total').text(response.grand_total);
                            
                             // Update header cart count
                            $('#cart-count').text(response.cart_count);
                            
                            // Check if cart is empty
                            if(response.cart_count == 0) {
                                setTimeout(function() { window.location.reload(); }, 500);
                            }
                        } else {
                            alert(response.message || "{{ __('messages.error_occurred') }}");
                        }
                    },
                    error: function(xhr) {
                        alert("{{ __('messages.error_occurred') }}");
                    }
                });
            }
        });

        // Clear entire cart
        $("#clear-cart").on('click', function(e) {
            e.preventDefault();
            
            if(confirm("{{ __('messages.confirm_clear_cart') }}")) {
                $.ajax({
                    url: '{{ route('cart.clear') }}',
                    method: "POST",
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert("{{ __('messages.error_occurred') }}");
                    }
                });
            }
        });
        // Toggle variant selectors
        $(".edit-variant-btn").on('click', function() {
            var row = $(this).parents("td");
            row.find(".cart-variant-info").hide();
            row.find(".cart-variant-selectors").fadeIn();
        });

        $(".cancel-variant-btn").on('click', function() {
            var row = $(this).parents("td");
            row.find(".cart-variant-selectors").hide();
            row.find(".cart-variant-info").fadeIn();
        });

        // Change variant (Size/Color)
        $(".variant-select").on('change', function() {
            var ele = $(this);
            var row = ele.parents("tr");
            var productId = row.find(".product-id").val();
            var oldVariantId = row.find(".current-variant-id").val();
            var sizeId = row.find(".size-select").val();
            var colorId = row.find(".color-select").val();

            $.ajax({
                url: '{{ route('cart.changeVariant') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    old_variant_id: oldVariantId,
                    product_id: productId,
                    size_id: sizeId,
                    color_id: colorId
                },
                beforeSend: function() {
                    // Show loading state if needed
                    row.css('opacity', '0.5');
                },
                success: function(response) {
                    if (response.success) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    } else {
                        alert(response.message || "{{ __('messages.error_occurred') }}");
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    var errorMsg = xhr.responseJSON ? xhr.responseJSON.message : "{{ __('messages.error_occurred') }}";
                    alert(errorMsg);
                    window.location.reload();
                }
            });
        });
    });
</script>
@endsection
