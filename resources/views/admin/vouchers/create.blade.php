@extends('layouts.admin')

@section('title', 'Tạo Phiếu Kho')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Tạo Phiếu Nhập/Xuất Kho</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Phiếu Kho</a></li>
                        <li class="breadcrumb-item"><a href="#!">Tạo phiếu</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf

                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Loại phiếu <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="INBOUND">Nhập kho</option>
                                        <option value="OUTBOUND">Xuất kho</option>
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Kho hàng <span class="text-danger">*</span></label>
                                    <select name="warehouse_id"
                                        class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn kho --</option>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Nhà cung cấp (Nếu nhập)</label>
                                    <select name="supplier_id" class="form-select">
                                        <option value="">-- Chọn NCC (Không bắt buộc) --</option>
                                        @foreach($suppliers as $sup)
                                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Ngày lập <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="voucher_date" class="form-control"
                                        value="{{ date('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea name="note" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Danh sách sản phẩm</h5>
                        <button type="button" class="btn btn-success btn-sm" id="add-item-btn"><i
                                class="feather icon-plus"></i> Thêm sản phẩm</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="items-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Sản phẩm/Biến thể</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá (Nếu có)</th>
                                        <th>Thành tiền</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][product_variant_id]" class="form-select select2-variant"
                                                required>
                                                <option value="">-- Chọn sản phẩm --</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]"
                                                class="form-control quantity-input" value="1" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][unit_price]"
                                                class="form-control price-input" value="0" min="0">
                                        </td>
                                        <td class="subtotal-cell">0 đ</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-item-btn"><i
                                                    class="feather icon-trash-2"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Tổng cộng:</th>
                                        <th id="total-amount-display">0 đ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Lưu phiếu (Chưa duyệt)</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let itemIndex = 1;

            function initSelect2(element) {
                $(element).select2({
                    placeholder: "-- Tìm sản phẩm (Tên hoặc SKU) --",
                    ajax: {
                        url: "{{ route('admin.api.variants.search') }}", // We need to create this route
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { q: params.term };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: `${item.product.name} - ${item.size}/${item.color} (${item.sku})`,
                                    price: item.price
                                }))
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function (e) {
                    let data = e.params.data;
                    let row = $(this).closest('tr');
                    row.find('.price-input').val(data.price || 0);
                    calculateSubtotal(row);
                });
            }

            initSelect2('.select2-variant');

            $('#add-item-btn').click(function () {
                let newRow = `
                    <tr class="item-row">
                        <td>
                            <select name="items[${itemIndex}][product_variant_id]" class="form-select select2-variant" required>
                                <option value="">-- Chọn sản phẩm --</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input" value="1" min="1" required>
                        </td>
                        <td>
                            <input type="number" name="items[${itemIndex}][unit_price]" class="form-control price-input" value="0" min="0">
                        </td>
                        <td class="subtotal-cell">0 đ</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-item-btn"><i class="feather icon-trash-2"></i></button>
                        </td>
                    </tr>
                `;
                $('#items-table tbody').append(newRow);
                initSelect2($(`#items-table tbody tr:last .select2-variant`));
                itemIndex++;
            });

            $(document).on('click', '.remove-item-btn', function () {
                if ($('.item-row').length > 1) {
                    $(this).closest('tr').remove();
                    calculateTotal();
                }
            });

            $(document).on('input', '.quantity-input, .price-input', function () {
                calculateSubtotal($(this).closest('tr'));
            });

            function calculateSubtotal(row) {
                let qty = parseFloat(row.find('.quantity-input').val()) || 0;
                let price = parseFloat(row.find('.price-input').val()) || 0;
                let subtotal = qty * price;
                row.find('.subtotal-cell').text(new Intl.NumberFormat('vi-VN').format(subtotal) + ' đ');
                calculateTotal();
            }

            function calculateTotal() {
                let total = 0;
                $('.item-row').each(function () {
                    let qty = parseFloat($(this).find('.quantity-input').val()) || 0;
                    let price = parseFloat($(this).find('.price-input').val()) || 0;
                    total += qty * price;
                });
                $('#total-amount-display').text(new Intl.NumberFormat('vi-VN').format(total) + ' đ');
            }
        });
    </script>
@endsection