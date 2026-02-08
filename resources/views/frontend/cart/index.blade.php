@extends('layouts.public')

@section('title', 'Shopping Cart | FashionStore')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">Home</a></li>
                            <li>/</li>
                            <li>Shopping Cart</li>
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
                                                        <a
                                                            href="{{ route('product.detail', $details['slug']) }}">{{ $details['name'] }}</a>
                                                        <br>
                                                        <small class="text-muted">Size: {{ $details['size'] }} | Color:
                                                            {{ $details['color'] }}</small>
                                                    </td>
                                                    <td class="product-price">{{ number_format($details['price']) }} đ</td>
                                                    <td class="product_quantity">
                                                        <input min="1" max="100" value="{{ $details['quantity'] }}" type="number"
                                                            class="quantity update-cart">
                                                    </td>
                                                    <td class="product_total">
                                                        {{ number_format($details['price'] * $details['quantity']) }} đ</td>
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
                                            <i class="fa fa-info-circle"></i> Feature coming soon
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
                                            <p class="cart_amount">{{ number_format($total) }} đ</p>
                                        </div>
                                        <div class="cart_subtotal ">
                                            <p>{{ __('messages.shipping') }}</p>
                                            <p class="cart_amount"><span>{{ __('messages.free') }}</span></p>
                                        </div>

                                        <div class="cart_subtotal">
                                            <p>{{ __('messages.grand_total') }}</p>
                                            <p class="cart_amount">{{ number_format($total) }} đ</p>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Update cart quantity
            $(".update-cart").change(function (e) {
                e.preventDefault();
                var ele = $(this);
                var row = ele.parents("tr");

                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: "PATCH",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: row.attr("data-id"),
                        quantity: ele.val()
                    },
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function (xhr) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });

            // Remove item from cart
            $(".remove-from-cart").click(function (e) {
                e.preventDefault();
                var ele = $(this);
                var row = ele.parents("tr");

                if (confirm("Are you sure you want to remove this product?")) {
                    $.ajax({
                        url: '{{ route('cart.remove') }}',
                        method: "DELETE",
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: row.attr("data-id")
                        },
                        success: function (response) {
                            window.location.reload();
                        },
                        error: function (xhr) {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            // Clear entire cart
            $("#clear-cart").click(function (e) {
                e.preventDefault();

                if (confirm("Are you sure you want to clear your cart?")) {
                    $.ajax({
                        url: '{{ route('cart.clear') }}',
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            window.location.reload();
                        },
                        error: function (xhr) {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
@endsection