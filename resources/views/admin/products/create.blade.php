@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Thêm Sản phẩm Mới</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item"><a href="#!">Thêm mới</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Product Details -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Thông tin Sản phẩm</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Tên Sản phẩm</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- Chọn Danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Giá (VNĐ)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required min="0">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                         @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Variants -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Biến thể Sản phẩm (Size/Color)</h5>
                <button type="button" class="btn btn-success btn-sm" id="add-variant-btn"><i class="feather icon-plus"></i> Thêm Biến thể</button>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered" id="variants-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Màu sắc</th>
                                <th>Số lượng tồn kho</th>
                                <th>SKU (Mã kho)</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                             @if(old('variants'))
                                @foreach(old('variants') as $index => $variant)
                                    <tr class="variant-row" data-index="{{ $index }}">
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="variants[{{ $index }}][size]" value="{{ $variant['size'] }}" placeholder="VD: S, M, XL" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="variants[{{ $index }}][color]" value="{{ $variant['color'] }}" placeholder="VD: Đỏ, Xanh" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" name="variants[{{ $index }}][stock_quantity]" value="{{ $variant['stock_quantity'] }}" min="0" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="variants[{{ $index }}][sku]" value="{{ $variant['sku'] }}" placeholder="Để trống tự tạo">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-variant-btn"><i class="feather icon-trash-2"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="variant-row" data-index="0">
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="variants[0][size]" placeholder="VD: S, M, XL" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="variants[0][color]" placeholder="VD: Đỏ, Xanh" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" name="variants[0][stock_quantity]" value="0" min="0" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="variants[0][sku]" placeholder="Để trống tự tạo">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-variant-btn"><i class="feather icon-trash-2"></i></button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu Sản phẩm</button>
            </div>
        </div>

    </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let variantIndex = {{ old('variants') ? count(old('variants')) : 1 }};
        const tableBody = document.querySelector('#variants-table tbody');
        const addBtn = document.getElementById('add-variant-btn');

        addBtn.addEventListener('click', function() {
            const row = `
                <tr class="variant-row" data-index="${variantIndex}">
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][size]" placeholder="VD: S, M, XL" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][color]" placeholder="VD: Đỏ, Xanh" required>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][stock_quantity]" value="0" min="0" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][sku]" placeholder="Để trống tự tạo">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-variant-btn"><i class="feather icon-trash-2"></i></button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
            variantIndex++;
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-variant-btn')) {
                const row = e.target.closest('tr');
                if (document.querySelectorAll('.variant-row').length > 1) {
                    row.remove();
                } else {
                    alert('Phải có ít nhất một biến thể.');
                }
            }
        });
    });
</script>
@endsection
