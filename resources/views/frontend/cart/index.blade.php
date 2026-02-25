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
                                                        <a
                                                            href="{{ route('product.detail', $details['slug']) }}">{{ $details['name'] }}</a>
                                                        <br>
                                                        <small class="text-muted">{{ __('messages.size') }}: {{ $details['size'] }} | {{ __('messages.color') }}:
                                                            {{ $details['color'] }}</small>
                                                    </td>
                                                    <td class="product-price">{{ number_format($details['price']) }} VND</td>
                                                    <td class="product_quantity">
                                                        @php
                                                            $stockQty = \App\Models\ProductVariant::find($id)?->stock_quantity ?? 100;
                                                        @endphp
                                                        <input min="1" max="{{ $stockQty }}" value="{{ $details['quantity'] }}" type="number"
                                                            class="quantity update-cart"
                                                            data-stock="{{ $stockQty }}"
                                                            title="Còn {{ $stockQty }} sản phẩm trong kho">
                                                        <small class="d-block text-muted mt-1" style="font-size:11px;"
                                                            data-stock-label>Kho: {{ $stockQty }}</small>
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
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Realtime stock check khi nhập số lượng
        $(document).on('input', '.update-cart', function() {
            const max = parseInt($(this).attr('max')) || 100;
            const val = parseInt($(this).val());
            const productName = $(this).closest('tr').find('.product_name a').text().trim();

            if (val > max) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: `Chỉ còn ${max} sản phẩm trong kho!`,
                    text: productName,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                });
                $(this).val(max);
            }
            if (val < 1 || isNaN(val)) $(this).val(1);
        });

        // Update cart quantity (AJAX)
        $(".update-cart").on('change', function (e) {
            const ele = $(this);
            const row = ele.closest("tr");
            const id = row.attr("data-id");
            const quantity = parseInt(ele.val());
            const max = parseInt(ele.attr('max')) || 100;

            if (ele.data('prev-val') == quantity) return;
            ele.data('prev-val', quantity);
            if (quantity < 1) return;

            // Block nếu vượt stock (double-check trước AJAX)
            if (quantity > max) {
                ele.val(max);
                Swal.fire({
                    icon: 'error',
                    title: 'Vượt quá tồn kho!',
                    html: `Chỉ còn <strong>${max}</strong> sản phẩm trong kho.`,
                    confirmButtonColor: '#ef233c',
                    confirmButtonText: 'Đồng ý',
                });
                return;
            }

            $.ajax({
                url: '{{ route("cart.update") }}',
                method: "PATCH",
                data: { _token: '{{ csrf_token() }}', id: id, quantity: quantity },
                success: function (response) {
                    if (response.success) {
                        row.find('.product_total').text(response.item_total);
                        $('#cart-subtotal').text(response.cart_total);
                        $('#shipping-fee span').text(response.shipping_fee);
                        $('#cart-grand-total').text(response.grand_total);
                        $('#cart-count').text(response.cart_count);

                        // Toast nhỏ xác nhận cập nhật
                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Đã cập nhật số lượng',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            html: response.message || '{{ __("messages.error_occurred") }}',
                            confirmButtonColor: '#ef233c',
                        });
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : '{{ __("messages.cart_update_error") }}';
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể cập nhật!',
                        html: msg,
                        confirmButtonColor: '#ef233c',
                        confirmButtonText: 'Đóng',
                    });
                    window.location.reload();
                }
            });
        });

        // Xóa sản phẩm khỏi giỏ
        $(".remove-from-cart").on('click', function (e) {
            e.preventDefault();
            const ele = $(this);
            const row = ele.closest("tr");
            const id = row.attr("data-id");
            const productName = row.find('.product_name a').text().trim();

            Swal.fire({
                title: 'Xóa sản phẩm?',
                html: `Bạn có chắc muốn xóa <strong>${productName}</strong> khỏi giỏ hàng?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef233c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("cart.remove") }}',
                        method: "POST",
                        data: { _token: '{{ csrf_token() }}', _method: 'DELETE', id: id },
                        success: function (response) {
                            if (response.success) {
                                row.fadeOut(300, function() { $(this).remove(); });
                                $('#cart-subtotal').text(response.cart_total);
                                $('#shipping-fee span').text(response.shipping_fee);
                                $('#cart-grand-total').text(response.grand_total);
                                $('#cart-count').text(response.cart_count);
                                if (response.cart_count == 0) {
                                    setTimeout(function() { window.location.reload(); }, 600);
                                }
                            } else {
                                Swal.fire({ icon: 'error', title: 'Lỗi!', text: response.message || '{{ __("messages.error_occurred") }}' });
                            }
                        },
                        error: function() {
                            Swal.fire({ icon: 'error', title: 'Lỗi!', text: '{{ __("messages.error_occurred") }}' });
                        }
                    });
                }
            });
        });

        // Xóa toàn bộ giỏ hàng
        $("#clear-cart").on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Xóa toàn bộ giỏ hàng?',
                text: 'Tất cả sản phẩm trong giỏ sẽ bị xóa. Bạn chắc chưa?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef233c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa tất cả',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("cart.clear") }}',
                        method: "POST",
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() { window.location.reload(); },
                        error: function() {
                            Swal.fire({ icon: 'error', title: 'Lỗi!', text: '{{ __("messages.error_occurred") }}' });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
