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
                            @if($errors->any())
                                <div class="alert alert-danger pb-0">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
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

                            <div id="address-section" @if(!old('payment_method') || old('payment_method') === 'CASH') style="display:none" @endif>
                                <div class="form-group mb-3">
                                    <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                    <select name="province" id="province" class="form-control" required>
                                        <option value="">-- Đang tải... --</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Xã / Phường</label>
                                    <select name="commune" id="commune" class="form-control" disabled>
                                        <option value="">-- Chọn tỉnh trước --</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                    <textarea name="address" id="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                                </div>
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
                                <select name="payment_method" id="payment_method" class="form-control">
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
            </div>
        </form>
    </div>
</div>

<style>
    #product_results,
    #customer_results {
        max-height: 350px;
        overflow-y: auto;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 6px;
        background: var(--bs-body-bg, #fff);
        color: var(--bs-body-color, #212529);
        position: absolute;
        z-index: 1050;
        width: 90%;
        display: none;
    }
    #product_results { width: 95%; }

    #product_results .list-group-item,
    #customer_results .list-group-item {
        background: var(--bs-body-bg, #fff);
        color: var(--bs-body-color, #212529);
        border-color: var(--bs-border-color, #dee2e6);
        padding: 10px 14px;
        transition: background 0.15s;
    }

    #product_results .list-group-item:hover,
    #customer_results .list-group-item:hover {
        background: var(--bs-tertiary-bg, #f8f9fa);
        color: var(--bs-emphasis-color, #000);
    }

    #product_results .list-group-item.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    #address-section { transition: opacity 0.2s; }
</style>

<script>
$(document).ready(function() {
    let orderItems = [];

    // --- Toggle address section based on payment method ---
    function syncAddressSection() {
        const isCash = $('#payment_method').val() === 'CASH';
        if (isCash) {
            $('#address-section').hide();
            $('#address-section select, #address-section textarea').removeAttr('required');
        } else {
            $('#address-section').show();
            $('#province').attr('required', true);
            $('#address').attr('required', true);
        }
    }
    syncAddressSection();
    $('#payment_method').on('change', syncAddressSection);

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
            // Set province after provinces are loaded
            let trySet = setInterval(function() {
                let $opt = $('#province option').filter(function() { return $(this).val() === province; });
                if ($opt.length) {
                    $('#province').val(province).trigger('change');
                    clearInterval(trySet);
                }
            }, 100);
        }

        $('#customer_results').hide();
        $('#customer_search').val('');
    });

    // --- Address Cascade ---
    $.getJSON('{{ route('api.vn-address.provinces') }}', function(provinces) {
        let html = '<option value="">-- Chọn tỉnh/thành phố --</option>';
        provinces.forEach(function(p) {
            html += `<option value="${p.name}" data-code="${p.code}">${p.name}</option>`;
        });
        $('#province').html(html);
    });

    $(document).on('change', '#province', function() {
        const code = this.options[this.selectedIndex]?.dataset.code || '';
        $('#commune').html('<option value="">-- Đang tải... --</option>').prop('disabled', true);
        if (!code) {
            $('#commune').html('<option value="">-- Chọn tỉnh trước --</option>');
            return;
        }
        $.getJSON('{{ url('api/vn-address/communes') }}/' + code, function(list) {
            let html = '<option value="">-- Chọn xã/phường --</option>';
            list.forEach(function(c) { html += `<option value="${c.name}">${c.name}</option>`; });
            $('#commune').html(html).prop('disabled', false);
        });
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
                console.log('Search Data:', data);
                let html = '';
                if (data.length === 0) {
                    html = '<div class="list-group-item text-muted">Không tìm thấy sản phẩm...</div>';
                } else {
                    data.forEach(variant => {
                        let sizeStr = variant.size ? variant.size : (variant.sizeRelationship ? variant.sizeRelationship.name : '');
                        let colorStr = variant.color ? variant.color : (variant.colorRelationship ? variant.colorRelationship.name : '');
                        let details = [];
                        if (sizeStr) details.push(sizeStr);
                        if (colorStr) details.push(colorStr);
                        let detailStr = details.length > 0 ? ` - ${details.join('/')}` : '';

                        let stockStatus = variant.stock > 0 ? `<span class="badge bg-success-light text-success">Còn ${variant.stock}</span>` : `<span class="badge bg-danger-light text-danger">Hết hàng</span>`;
                        let isOutOfStock = variant.stock <= 0;

                        html += `<a href="#" class="list-group-item list-group-item-action select-variant d-flex align-items-center ${isOutOfStock ? 'disabled opacity-50' : ''}" 
                            data-id="${variant.id}" data-name="${variant.product.name}" data-sku="${variant.sku}" data-price="${variant.price}" data-size="${sizeStr}" data-color="${colorStr}" data-img="${variant.product.image}" data-stock="${variant.stock}">
                            <img src="${variant.product.image}" alt="${variant.product.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 15px;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>[${variant.sku}] <strong>${variant.product.name}</strong>${detailStr}</span>
                                    <div>
                                        <span class="text-primary fw-bold me-2">${new Intl.NumberFormat('vi-VN').format(variant.price)}đ</span>
                                        ${stockStatus}
                                    </div>
                                </div>
                            </div>
                        </a>`;
                    });
                }
                $('#product_results').html(html).show();
            },
            error: function(xhr, status, error) {
                console.error("Lỗi AJAX search sản phẩm:", xhr.responseText);
                $('#product_results').html(`<div class="list-group-item text-danger">Lỗi tải dữ liệu. Chi tiết trong console.</div>`).show();
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
        let img = $(this).data('img');

        let stock = parseInt($(this).data('stock'));

        if (stock <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Hết hàng',
                text: 'Sản phẩm này đã hết hàng, không thể thêm vào đơn hàng.'
            });
            return;
        }

        // Check if exists
        let existing = orderItems.find(i => i.variant_id == variantId);
        if (existing) {
            if (existing.quantity >= stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Giới hạn tồn kho',
                    text: `Sản phẩm này chỉ còn ${stock} trong kho.`
                });
                return;
            }
            existing.quantity++;
        } else {
            orderItems.push({
                variant_id: variantId,
                name: name,
                sku: sku,
                price: price,
                size: size,
                color: color,
                img: img,
                quantity: 1,
                stock: stock
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
        let item = orderItems[idx];

        if (qty > item.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Giới hạn tồn kho',
                text: `Sản phẩm này chỉ còn ${item.stock} trong kho.`
            });
            qty = item.stock;
            $(this).val(qty);
        }

        if (qty < 1 || isNaN(qty)) {
            qty = 1;
            $(this).val(qty);
        }
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
            
            let details = [];
            if (item.size && item.size !== 'undefined') details.push(item.size);
            if (item.color && item.color !== 'undefined') details.push(item.color);
            let detailStr = details.length > 0 ? ` | ${details.join('/')}` : '';

            tbody.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${item.img}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 15px; border: 1px solid #eee;">
                            <div>
                                <strong style="white-space: normal;">${item.name}</strong><br>
                                <small class="text-muted">SKU: ${item.sku}${detailStr}</small>
                                <input type="hidden" name="items[${idx}][variant_id]" value="${item.variant_id}">
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">${new Intl.NumberFormat('vi-VN').format(item.price)}đ</td>
                    <td class="align-middle">
                        <input type="number" name="items[${idx}][quantity]" class="form-control form-control-sm item-qty" data-idx="${idx}" value="${item.quantity}" min="1">
                    </td>
                    <td class="align-middle fw-bold text-primary">${new Intl.NumberFormat('vi-VN').format(itemTotal)}đ</td>
                    <td class="align-middle text-center">
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
    // Prevent submit if no products
    $('#create-order-form').on('submit', function(e) {
        if (orderItems.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng chọn ít nhất một sản phẩm trước khi tạo đơn hàng.'
            });
            return false;
        }
    });
});
</script>
@endsection
