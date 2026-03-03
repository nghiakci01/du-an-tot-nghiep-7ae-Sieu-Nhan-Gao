@extends('layouts.admin')

@section('title', 'Tạo Đơn hàng mới')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Tạo Đơn hàng mới</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item"><a href="#!">Tạo mới</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <form action="{{ route('admin.orders.store') }}" method="POST" id="create-order-form">
            @csrf
            <div class="row">
                <!-- Customer Info -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Thông tin khách hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-label">Loại khách hàng</label>
                                <select name="customer_type" id="customer_type" class="form-control">
                                    <option value="NEW">Khách hàng mới / Vãng lai</option>
                                    <option value="EXISTING">Khách hàng đã có tài khoản</option>
                                </select>
                            </div>

                            <div id="existing_customer_section" style="display: none;" class="mb-3">
                                <label class="form-label">Tìm kiếm khách hàng</label>
                                <input type="text" id="customer_search" class="form-control" placeholder="Nhập tên, email hoặc SĐT...">
                                <input type="hidden" name="user_id" id="user_id">
                                <div id="customer_results" class="list-group mt-2" style="position: absolute; z-index: 1000; width: 90%;"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control" required value="{{ old('phone') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                <select name="province" id="province" class="form-control" required>
                                    <option value="">Chọn tỉnh thành</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p }}" {{ old('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Thanh toán & Trạng thái</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-label">Phương thức thanh toán</label>
                                <select name="payment_method" class="form-control">
                                    <option value="CASH">Tiền mặt (tại quầy)</option>
                                    <option value="COD">Thu hộ (COD)</option>
                                    <option value="BANK_TRANSFER">Chuyển khoản</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Trạng thái ban đầu</label>
                                <select name="status" class="form-control">
                                    <option value="{{ \App\Models\Order::STATUS_CONFIRMED }}">Đã xác nhận</option>
                                    <option value="{{ \App\Models\Order::STATUS_PENDING }}">Chờ xác nhận</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Selection -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Danh sách sản phẩm</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Tìm kiếm sản phẩm</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather icon-search"></i></span>
                                    <input type="text" id="product_search" class="form-control" placeholder="Nhập tên sản phẩm hoặc SKU...">
                                </div>
                                <div id="product_results" class="list-group mt-1" style="position: absolute; z-index: 1000; width: 95%;"></div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="selected-products-table">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th width="150">Giá</th>
                                            <th width="120">Số lượng</th>
                                            <th width="150">Thành tiền</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-items-body">
                                        <tr class="empty-row">
                                            <td colspan="5" class="text-center py-4">Chưa có sản phẩm nào được chọn.</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Tổng tiền hàng:</th>
                                            <th id="subtotal">0đ</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Giảm giá trực tiếp:</th>
                                            <th><input type="number" name="manual_discount" id="manual_discount" class="form-control form-control-sm" value="0" min="0"></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Phí vận chuyển (tự động):</th>
                                            <th id="shipping_fee">0đ</th>
                                            <th></th>
                                        </tr>
                                        <tr class="table-primary">
                                            <th colspan="3" class="text-end h5 mb-0">TỔNG CỘNG:</th>
                                            <th id="final_total" class="h5 mb-0">0đ</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="text-end mt-4">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary px-5">Tạo đơn hàng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let orderItems = [];

    // --- Customer Selection Logic ---
    $('#customer_type').change(function() {
        if ($(this).val() === 'EXISTING') {
            $('#existing_customer_section').show();
            $('#name, #phone, #email').attr('readonly', true);
        } else {
            $('#existing_customer_section').hide();
            $('#user_id').val('');
            $('#name, #phone, #email').val('').attr('readonly', false);
        }
    });

    $('#customer_search').on('keyup', function() {
        let q = $(this).val();
        if (q.length < 2) {
            $('#customer_results').hide();
            return;
        }

        $.ajax({
            url: "{{ route('admin.orders.customers.search') }}",
            data: { q: q },
            success: function(data) {
                let html = '';
                data.forEach(user => {
                    html += `<a href="#" class="list-group-item list-group-item-action select-customer" 
                        data-id="${user.id}" data-name="${user.name}" data-email="${user.email}" data-phone="${user.phone}" data-address="${user.address || ''}" data-province="${user.province || ''}">
                        <strong>${user.name}</strong> - ${user.phone} (${user.email})
                    </a>`;
                });
                $('#customer_results').html(html).show();
            }
        });
    });

    $(document).on('click', '.select-customer', function(e) {
        e.preventDefault();
        $('#user_id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#phone').val($(this).data('phone'));
        $('#email').val($(this).data('email'));
        $('#address').val($(this).data('address'));
        
        let province = $(this).data('province');
        if (province) {
            $('#province').val(province);
        }

        $('#customer_results').hide();
        $('#customer_search').val('');
    });

    // --- Product Selection Logic ---
    $('#product_search').on('keyup', function() {
        let q = $(this).val();
        if (q.length < 2) {
            $('#product_results').hide();
            return;
        }

        $.ajax({
            url: "{{ route('admin.api.variants.search') }}",
            data: { q: q },
            success: function(data) {
                let html = '';
                data.forEach(variant => {
                    html += `<a href="#" class="list-group-item list-group-item-action select-variant" 
                        data-id="${variant.id}" data-name="${variant.product.name}" data-sku="${variant.sku}" data-price="${variant.price}" data-size="${variant.size?.name || ''}" data-color="${variant.color?.name || ''}">
                        [${variant.sku}] <strong>${variant.product.name}</strong> - ${variant.size?.name || ''}/${variant.color?.name || ''} 
                        <span class="float-end text-primary">${new Intl.NumberFormat('vi-VN').format(variant.price)}đ</span>
                    </a>`;
                });
                $('#product_results').html(html).show();
            }
        });
    });

    $(document).on('click', '.select-variant', function(e) {
        e.preventDefault();
        let variantId = $(this).data('id');
        let name = $(this).data('name');
        let sku = $(this).data('sku');
        let price = $(this).data('price');
        let size = $(this).data('size');
        let color = $(this).data('color');

        // Check if exists
        let existing = orderItems.find(i => i.variant_id == variantId);
        if (existing) {
            existing.quantity++;
        } else {
            orderItems.push({
                variant_id: variantId,
                name: name,
                sku: sku,
                price: price,
                size: size,
                color: color,
                quantity: 1
            });
        }

        $('#product_results').hide();
        $('#product_search').val('');
        renderTable();
    });

    $(document).on('click', '.remove-item', function() {
        let idx = $(this).data('idx');
        orderItems.splice(idx, 1);
        renderTable();
    });

    $(document).on('change', '.item-qty', function() {
        let idx = $(this).data('idx');
        let qty = parseInt($(this).val());
        if (qty < 1) qty = 1;
        orderItems[idx].quantity = qty;
        renderTable();
    });

    $('#manual_discount').on('input', function() {
        renderTable();
    });

    function renderTable() {
        let tbody = $('#order-items-body');
        tbody.empty();

        if (orderItems.length === 0) {
            tbody.append('<tr class="empty-row"><td colspan="5" class="text-center py-4">Chưa có sản phẩm nào được chọn.</td></tr>');
            updateSummary(0);
            return;
        }

        let subtotal = 0;
        orderItems.forEach((item, idx) => {
            let itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            tbody.append(`
                <tr>
                    <td>
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">SKU: ${item.sku} | ${item.size}/${item.color}</small>
                        <input type="hidden" name="items[${idx}][variant_id]" value="${item.variant_id}">
                    </td>
                    <td>${new Intl.NumberFormat('vi-VN').format(item.price)}đ</td>
                    <td>
                        <input type="number" name="items[${idx}][quantity]" class="form-control form-control-sm item-qty" data-idx="${idx}" value="${item.quantity}" min="1">
                    </td>
                    <td>${new Intl.NumberFormat('vi-VN').format(itemTotal)}đ</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-item" data-idx="${idx}"><i class="feather icon-trash-2"></i></button>
                    </td>
                </tr>
            `);
        });

        updateSummary(subtotal);
    }

    function updateSummary(subtotal) {
        let discount = parseFloat($('#manual_discount').val()) || 0;
        let finalForShipping = subtotal - discount;
        
        let shippingFee = 0;
        if (finalForShipping > 0) {
            // Match Setting::getShippingFee logic
            if (finalForShipping < 799000) {
                shippingFee = 30000;
            }
        }

        let finalTotal = finalForShipping + shippingFee;

        $('#subtotal').text(new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ');
        $('#shipping_fee').text(new Intl.NumberFormat('vi-VN').format(shippingFee) + 'đ');
        $('#final_total').text(new Intl.NumberFormat('vi-VN').format(finalTotal) + 'đ');
    }

    // Hide results when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#existing_customer_section').length) {
            $('#customer_results').hide();
        }
        if (!$(e.target).closest('.col-md-8').length) {
            $('#product_results').hide();
        }
    });
});
</script>
@endpush
@endsection
