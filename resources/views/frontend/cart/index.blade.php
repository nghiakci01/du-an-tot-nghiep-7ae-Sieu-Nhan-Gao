@extends('layouts.public')

@section('title', __('messages.shopping_cart') . ' | Elite')

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

            @if(isset($cart) && count($cart) > 0)
                <form action="#">
                    <div class="row">
                        <div class="col-12">
                            <div class="table_desc">
                                <div class="cart_page table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product_check" style="width: 50px;"><input type="checkbox" id="check-all" style="width: 18px; height: 18px; cursor: pointer;"></th>
                                                <!-- <th class="product_remove">{{ __('messages.remove') }}</th> -->
                                                <th class="product_thumb">{{ __('messages.image') }}</th>
                                                <th class="product_name">{{ __('messages.product') }}</th>
                                                <th class="product-price">{{ __('messages.price') }}</th>
                                                <th class="product_quantity">{{ __('messages.quantity') }}</th>
                                                <th class="product_total">{{ __('messages.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cart as $id => $details)
                                                @php
                                                    $isOutOfStock = isset($details['is_out_of_stock']) ? $details['is_out_of_stock'] : false;
                                                @endphp
                                                <tr data-id="{{ $id }}" class="{{ $isOutOfStock ? 'opacity-50' : '' }}">
                                                    <td class="product_check" style="vertical-align: middle;">
                                                        <input type="checkbox" class="check-item" value="{{ $id }}" style="width: 18px; height: 18px; cursor: pointer;" {{ $isOutOfStock ? 'disabled' : '' }}>
                                                    </td>
                                                    <!-- <td class="product_remove">
                                                        <a href="javascript:void(0)" class="remove-from-cart">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </td> -->
                                                    <td class="product_thumb" style="position: relative;">
                                                        <a href="{{ route('product.detail', $details['slug']) }}">
                                                            <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : asset('frontend-assets/img/s-product/product.jpg') }}"
                                                                alt="{{ $details['name'] }}"
                                                                style="width: 100px; height: 100px; object-fit: cover;">
                                                            @if($isOutOfStock)
                                                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.6); display: flex; flex-direction: column; align-items: center; justify-content: flex-end; padding-bottom: 5px;">
                                                                    <span class="badge bg-danger mb-1" style="font-size: 0.8rem; padding: 5px 10px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">{{ __('messages.out_of_stock') ?? 'Hết hàng' }}</span>
                                                                    <a href="{{ route('product.detail', $details['slug']) }}" class="btn btn-sm btn-dark" style="font-size: 0.65rem; padding: 2px 5px; white-space: nowrap; font-weight: normal;">Xem sản phẩm tương tự</a>
                                                                </div>
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td class="product_name">
                                                        <a href="{{ route('product.detail', $details['slug']) }}" class="cart-product-link font-weight-bold" style="font-size: 16px;">{{ $details['name'] }}</a>
                                                        <div class="cart-variant-info mt-2 text-center" style="display: flex; flex-direction: column; align-items: center;">
                                                            <div class="text-muted small mb-1" style="font-size: 15px;">
                                                                {{ __('messages.size') }}: <strong>{{ $details['size'] }}</strong> | 
                                                                {{ __('messages.color') }}: <strong>{{ $details['color'] }}</strong>
                                                            </div>
                                                            <button type="button" class="btn btn-sm edit-variant-btn mt-2" 
                                                                    style="font-size: 0.85rem; color: #ff6a28; border: 1px solid #ff6a28; background: transparent; padding: 3px 10px; border-radius: 4px;"
                                                                    {{ $isOutOfStock ? 'disabled' : '' }}>
                                                                <i class="fa fa-pencil-square-o"></i> {{ __('messages.edit') }}
                                                            </button>
                                                        </div>

                                                        <div class="cart-variant-selectors mt-2" style="display: none;">
                                                            <!-- Product selection removed based on user request -->
                                                            
                                                            @if(isset($details['available_sizes_array']) && count($details['available_sizes_array']) > 0)
                                                                <div class="d-inline-block me-2">
                                                                    <label class="small text-muted d-block">{{ __('messages.size') }}</label>
                                                                    <select class="form-select form-select-sm variant-select size-select" data-type="size">
                                                                        @foreach($details['available_sizes_array'] as $key => $name)
                                                                            <option value="{{ $key }}" {{ (isset($details['size_id']) && $details['size_id'] == $key) || (empty($details['size_id']) && isset($details['size']) && $details['size'] == $key) ? 'selected' : '' }}>
                                                                                {{ $name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            @if(isset($details['available_colors_array']) && count($details['available_colors_array']) > 0)
                                                                <div class="d-inline-block">
                                                                    <label class="small text-muted d-block">{{ __('messages.color') }}</label>
                                                                    <select class="form-select form-select-sm variant-select color-select" data-type="color">
                                                                        @foreach($details['available_colors_array'] as $key => $name)
                                                                            <option value="{{ $key }}" {{ (isset($details['color_id']) && $details['color_id'] == $key) || (empty($details['color_id']) && isset($details['color']) && $details['color'] == $key) ? 'selected' : '' }}>
                                                                                {{ $name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm save-variant-btn" style="background-color: #ff6a28; color: #fff; border-color: #ff6a28;">Đổi biến thể</button>
                            <button type="button" class="btn btn-sm btn-secondary cancel-variant-btn">Hủy</button>
                        </div>
                    </div>
                                                        <input type="hidden" class="product-id" value="{{ $details['product_id'] }}">
                                                        <input type="hidden" class="current-variant-id" value="{{ $id }}">
                                                    </td>
                                                    <td class="product-price">{{ number_format($details['price']) }} VND</td>
                                                    <td class="product_quantity">
                                                        @php
                                                            $stockQty = isset($details['stock_quantity']) ? $details['stock_quantity'] : (\App\Models\ProductVariant::find($id)?->stock_quantity ?? 100);
                                                        @endphp
                                                        @if($isOutOfStock)
                                                            <input type="text" value="0" class="quantity text-center text-muted" disabled style="background-color: #f8f9fa;">
                                                            <small class="d-block text-danger mt-1" style="font-size:11px;">Hết hàng</small>
                                                        @else
                                                            <input min="1" max="{{ $stockQty }}" value="{{ $details['quantity'] }}" type="number"
                                                                class="quantity update-cart item-quantity"
                                                                data-stock="{{ $stockQty }}"
                                                                title="Còn {{ $stockQty }} sản phẩm trong kho">
                                                            <small class="d-block text-muted mt-1" style="font-size:11px;"
                                                                data-stock-label>Kho: {{ $stockQty }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="product_total item-total-price" data-price="{{ $details['price'] }}">
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
                                    <button type="button" class="btn btn-warning" id="delete-selected" style="display: none; margin-left: 10px; color: #fff;">
                                        <i class="fa fa-trash"></i> Xóa mục đã chọn
                                    </button>
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
                            <!-- <div class="col-lg-6 col-md-6">
                                <div class="coupon_code left">
                                    <h3>{{ __('messages.coupon_code') }}</h3>
                                    <div class="coupon_inner">
                                        <p>{{ __('messages.enter_coupon_desc') }}</p>
                                        <div class="input-group mb-2">
                                            <input placeholder="{{ __('messages.coupon_code') }}" type="text" id="coupon_code" class="form-control" value="{{ session('coupon_code') }}" {{ session('coupon_code') ? 'readonly' : '' }}>
                                            @if(session('coupon_code'))
                                                <button type="button" class="btn btn-danger" id="remove-coupon">Gỡ</button>
                                            @else
                                                <button type="button" class="btn btn-dark" id="apply-coupon">{{ __('messages.apply') }}</button>
                                            @endif
                                        </div>
                                        <div id="coupon-message"></div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-lg-6 col-md-6 offset-lg-6 offset-md-6">
                                <div class="coupon_code right">
                                    <h3>{{ __('messages.cart_totals') }}</h3>
                                    <div class="coupon_inner">
                                        <div class="cart_subtotal">
                                            <p>{{ __('messages.subtotal') }}</p>
                                            <p class="cart_amount" id="cart-subtotal">{{ number_format($total) }} VND</p>
                                        </div>
                                        <div class="cart_subtotal">
                                            <p>{{ __('messages.shipping') }}</p>
                                            @php
                                                $shippingFee = \App\Models\Setting::getShippingFee($total - $discount);
                                            @endphp
                                            <p class="cart_amount" id="shipping-fee">
                                                <span>{{ $shippingFee > 0 ? (number_format($shippingFee) . ' đ') : __('messages.free') }}</span>
                                            </p>
                                        </div>
                                        
                                        <div class="cart_subtotal" id="discount-row" style="{{ $discount > 0 ? '' : 'display: none;' }}">
                                            <p>Giảm giá</p>
                                            <p class="cart_amount text-danger" id="cart-discount">- {{ number_format($discount) }} đ</p>
                                        </div>

                                        <div class="cart_subtotal">
                                            <p>{{ __('messages.grand_total') }}</p>
                                            <p class="cart_amount" id="cart-grand-total">{{ number_format($total + $shippingFee) }} đ</p>
                                        </div>
                                        <div class="checkout_btn">
                                            <a href="#" id="btn-proceed-checkout">{{ __('messages.proceed_to_checkout') }}</a>
                                        </div>

                                        <script>
                                        document.getElementById('btn-proceed-checkout').addEventListener('click', function(e) {
                                            e.preventDefault();
                                            var btn = this;
                                            btn.style.opacity = '0.7';
                                            btn.style.pointerEvents = 'none';

                                            fetch('{{ route('cart.validate') }}', {
                                                method: 'GET',
                                                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                            })
                                            .then(r => r.json())
                                            .then(data => {
                                                btn.style.opacity = '';
                                                btn.style.pointerEvents = '';

                                                if (data.valid) {
                                                    window.location.href = '{{ route('checkout.index') }}';
                                                } else if (data.errors && data.errors.length > 0) {
                                                    var list = data.errors.map(function(e) {
                                                        var icon = e.type === 'out_of_stock' ? '🚫' :
                                                                   e.type === 'not_found'    ? '❌' :
                                                                   e.type === 'inactive'     ? '⛔' : '⚠️';
                                                        return '<li style="text-align:left;margin-bottom:6px;">' + icon + ' <strong>' + e.name + '</strong>: ' + e.issue + '</li>';
                                                    }).join('');

                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Giỏ hàng có vấn đề!',
                                                        html: '<p style="margin-bottom:10px;">Vui lòng xử lý các mục sau trước khi thanh toán:</p><ul style="padding-left:10px;">' + list + '</ul>',
                                                        confirmButtonColor: '#ef233c',
                                                        confirmButtonText: 'Cập nhật giỏ hàng',
                                                        showCancelButton: true,
                                                        cancelButtonText: 'Đóng',
                                                        cancelButtonColor: '#6c757d',
                                                    });
                                                } else {
                                                    Swal.fire({ icon: 'error', title: 'Lỗi!', text: data.message || 'Không thể tiến hành thanh toán.', confirmButtonColor: '#ef233c' });
                                                }
                                            })
                                            .catch(function() {
                                                btn.style.opacity = '';
                                                btn.style.pointerEvents = '';
                                                window.location.href = '{{ route('checkout.index') }}';
                                            });
                                        });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--coupon code area end-->

                    <!--cross-sell section start-->
                    @if(isset($crossSellProducts) && $crossSellProducts->count() > 0)
                    <div class="mt-5">
                        <div class="section_title" style="margin-bottom: 20px;">
                            <h2>{{ __('messages.you_may_also_like') ?? 'Có thể bạn cũng thích' }}</h2>
                        </div>
                        <div class="row">
                            @foreach($crossSellProducts->take(4) as $crossSell)
                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                <div class="single_product">
                                    <div class="product_thumb">
                                        <a class="primary_img" href="{{ route('product.detail', $crossSell->slug) }}">
                                            <img src="{{ $crossSell->image ? asset('storage/' . $crossSell->image) : asset('frontend-assets/img/product/product21.jpg') }}"
                                                alt="{{ $crossSell->name }}" style="height: 200px; object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="product_content">
                                        <h3><a href="{{ route('product.detail', $crossSell->slug) }}">{{ Str::limit($crossSell->name, 40) }}</a></h3>
                                        @include('frontend.partials.product-price', ['product' => $crossSell])
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <!--cross-sell section end-->

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
    <style>
        .cart-product-link {
            color: inherit;
            text-decoration: none;
        }
        .cart-product-link:hover {
            color: inherit;
            text-decoration: none;
        }
    </style>
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
                        $('.cart-count').text(response.cart_count);

                        // Cập nhật giá trị html cho item
                        row.find('.product_total').text(response.item_total);
                        calculateCartTotal(); // Tính toán lại theo các dòng được check

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
                                $('.cart-count').text(response.cart_count);
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

        // Tính toán lại tổng tiền dựa trên các sản phẩm được check
        function calculateCartTotal() {
            let subtotal = 0;
            
            $('.check-item:checked').each(function() {
                var row = $(this).closest('tr');
                var price = parseFloat(row.find('.item-total-price').attr('data-price'));
                var quantity = parseInt(row.find('.item-quantity').val());
                if (!isNaN(price) && !isNaN(quantity)) {
                    subtotal += (price * quantity);
                }
            });

            // Cập nhật Subtotal
            $('#cart-subtotal').text(new Intl.NumberFormat('vi-VN').format(subtotal) + ' đ');

            // Phí ship (Giả lập logic: > 500k free ship, dưới thì 30k)
            let shippingFee = 0;
            if (subtotal > 0 && subtotal < 500000) {
                shippingFee = 30000;
            }
            
            if (shippingFee > 0) {
                $('#shipping-fee span').text(new Intl.NumberFormat('vi-VN').format(shippingFee) + ' đ');
            } else {
                $('#shipping-fee span').text('{{ __("messages.free") }}');
            }

            // Tính discount nếu có mã (logic cơ bản hiển thị lại session discount)
            let discountDoc = $('#cart-discount').text().replace(/[^\d]/g, '');
            let discount = discountDoc ? parseInt(discountDoc) : 0;
            if (!$("#discount-row").is(":visible")) {
                discount = 0; 
            }
            
            // Grand Total
            let grandTotal = subtotal + shippingFee - discount;
            if (grandTotal < 0) grandTotal = 0;
            
            $('#cart-grand-total').text(new Intl.NumberFormat('vi-VN').format(grandTotal) + ' đ');
        }

        // Check All logic
        $('#check-all').on('change', function() {
            $('.check-item:not(:disabled)').prop('checked', $(this).prop('checked'));
            toggleDeleteSelectedBtn();
            calculateCartTotal();
        });

        // Check Item logic
        $(document).on('change', '.check-item', function() {
            var totalItems = $('.check-item:not(:disabled)').length;
            var checkedItems = $('.check-item:not(:disabled):checked').length;
            $('#check-all').prop('checked', totalItems === checkedItems && totalItems > 0);
            toggleDeleteSelectedBtn();
            calculateCartTotal();
        });

        // Initialize total calculation on page load (nếu cần tự check sẵn hoặc không)
        // Mặc định tick tất cả khi load file
        $('#check-all').prop('checked', true).trigger('change');

        function toggleDeleteSelectedBtn() {
            if ($('.check-item:checked').length > 0) {
                $('#delete-selected').fadeIn(200);
            } else {
                $('#delete-selected').fadeOut(200);
            }
        }

        // Xóa các item đã chọn
        $('#delete-selected').on('click', function() {
            var selectedRows = $('.check-item:checked').closest('tr');
            if (selectedRows.length === 0) return;

            Swal.fire({
                title: 'Xóa các sản phẩm đã chọn?',
                text: `Bạn có chắc muốn xóa ${selectedRows.length} sản phẩm đã chọn khỏi giỏ hàng?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef233c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Đang xóa...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    var promises = [];
                    selectedRows.each(function() {
                        var id = $(this).attr('data-id');
                        promises.push(
                            $.ajax({
                                url: '{{ route("cart.remove") }}',
                                method: 'POST',
                                data: { _token: '{{ csrf_token() }}', _method: 'DELETE', id: id }
                            })
                        );
                    });

                    Promise.all(promises).then(function() {
                        window.location.reload();
                    }).catch(function() {
                        window.location.reload();
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

        // Change variant (Product/Size/Color) via Submit Button
        $(".save-variant-btn").on('click', function() {
            var ele = $(this);
            var row = ele.parents("tr");
            var productId = row.find(".product-id").val();
            var newProductId = row.find(".product-select").val() || productId;
            var oldVariantId = row.find(".current-variant-id").val();
            var sizeId = row.find(".size-select").val();
            var colorId = row.find(".color-select").val();
            
            var changedType = (productId !== newProductId) ? 'product' : null;

            $.ajax({
                url: '{{ route('cart.changeVariant') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    old_variant_id: oldVariantId,
                    product_id: productId,
                    new_product_id: newProductId,
                    size_id: sizeId,
                    color_id: colorId,
                    changed_type: changedType
                },
                beforeSend: function() {
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

        // Apply coupon
        $(document).on('click', '#apply-coupon', function() {
            var couponCode = $('#coupon_code').val();
            if (!couponCode) {
                Swal.fire({ icon: 'warning', title: 'Thông báo', text: 'Vui lòng nhập mã giảm giá' });
                return;
            }

            $.ajax({
                url: '{{ route('cart.apply_coupon') }}',
                method: 'POST',
                data: { coupon_code: couponCode },
                success: function(response) {
                    if (response.success) {
                        $('#coupon_code').prop('readonly', true);
                        $('#apply-coupon').replaceWith('<button type="button" class="btn btn-danger" id="remove-coupon">Gỡ</button>');
                        
                        $('#discount-row').show();
                        $('#cart-discount').text('- ' + response.data.discount);
                        $('#shipping-fee span').text(response.data.shipping_fee);
                        $('#cart-grand-total').text(response.data.grand_total);
                        
                        Swal.fire({ icon: 'success', title: 'Thành công', text: response.message });
                    }
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Có lỗi xảy ra';
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: msg });
                }
            });
        });

        // Remove coupon
        $(document).on('click', '#remove-coupon', function() {
            $.ajax({
                url: '{{ route('cart.remove_coupon') }}',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        $('#coupon_code').val('').prop('readonly', false);
                        $('#remove-coupon').replaceWith('<button type="button" class="btn btn-dark" id="apply-coupon">{{ __("messages.apply") }}</button>');
                        
                        $('#discount-row').hide();
                        $('#shipping-fee span').text(response.data.shipping_fee);
                        $('#cart-grand-total').text(response.data.grand_total);
                        
                        Swal.fire({ icon: 'info', title: 'Đã gỡ', text: response.message });
                    }
                }
            });
        });

        // Keep edit mode open after refresh if 'editing' param exists
        @if(request('editing'))
            var editingId = "{{ request('editing') }}";
            var editRow = $("tr[data-id='" + editingId + "']");
            if (editRow.length) {
                editRow.find(".cart-variant-info").hide();
                editRow.find(".cart-variant-selectors").show();
            }
        @endif
    });
</script>
@endsection
