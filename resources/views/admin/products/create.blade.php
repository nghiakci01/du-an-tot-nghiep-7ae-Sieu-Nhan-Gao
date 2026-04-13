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
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi nhập liệu:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                        <label for="image" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        <small class="text-muted">Tối thiểu 400x400px, tối đa 10MB</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="main-image-preview"></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="gallery_images" class="form-label">Ảnh Gallery (Tối đa 6 ảnh)</label>
                        <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror" 
                               id="gallery_images" name="gallery_images[]" multiple 
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        <small class="text-muted">Tối thiểu 400x400px, tối đa 10MB/ảnh</small>
                        @error('gallery_images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="gallery-preview" class="mt-3 d-flex gap-2 flex-wrap"></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="short_description" class="form-label">Mô tả ngắn <small class="text-muted"></small></label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="4" maxlength="500">{{ old('short_description') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <div>
                                @error('short_description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                <span id="short-char-count">0</span> / 500 ký tự
                            </small>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Mô tả chi tiết <small class="text-muted"></small></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" maxlength="5000">{{ old('description') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <div>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                <span id="char-count">0</span> / 5000 ký tự
                            </small>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
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

                <div class="table-responsive">
                    <table class="table table-bordered" id="variants-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Màu sắc</th>
                                <th>Giá (VNĐ)</th>
                                <th>Giá KM (VNĐ)</th>
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
                                            <select class="form-select form-select-sm size-select @error('variants.'.$index.'.size_id') is-invalid @enderror" name="variants[{{ $index }}][size_id]" required>
                                                <option value="">-- Chọn Size --</option>
                                                @foreach($sizes as $size)
                                                    <option value="{{ $size->id }}" {{ old("variants.{$index}.size_id") == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('variants.'.$index.'.size_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </td>

                                        <td>
                                            <select class="form-select form-select-sm color-select @error('variants.'.$index.'.color_id') is-invalid @enderror" name="variants[{{ $index }}][color_id]" required>
                                                <option value="">-- Chọn Màu --</option>
                                                @foreach($colors as $color)
                                                    <option value="{{ $color->id }}" {{ old("variants.{$index}.color_id") == $color->id ? 'selected' : '' }}>
                                                        {{ $color->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('variants.'.$index.'.color_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </td>

                                        <td>
                                            <input type="number" class="form-control form-control-sm @error('variants.'.$index.'.price') is-invalid @enderror" name="variants[{{ $index }}][price]" value="{{ $variant['price'] ?? '' }}" min="0" max="99999999" step="0.01" placeholder="Giá">
                                            @error('variants.'.$index.'.price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm @error('variants.'.$index.'.sale_price') is-invalid @enderror" name="variants[{{ $index }}][sale_price]" value="{{ $variant['sale_price'] ?? '' }}" min="0" max="99999999" step="0.01" placeholder="Giá KM">
                                            @error('variants.'.$index.'.sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm @error('variants.'.$index.'.stock_quantity') is-invalid @enderror" name="variants[{{ $index }}][stock_quantity]" value="{{ $variant['stock_quantity'] }}" min="0" required>
                                            @error('variants.'.$index.'.stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm @error('variants.'.$index.'.sku') is-invalid @enderror" name="variants[{{ $index }}][sku]" value="{{ $variant['sku'] }}" placeholder="Để trống tự tạo">
                                            @error('variants.'.$index.'.sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-variant-btn"><i class="feather icon-trash-2"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="variant-row" data-index="0">
                                    <td>
                                        <select class="form-select form-select-sm size-select @error('variants.0.size_id') is-invalid @enderror" name="variants[0][size_id]" required>
                                            <option value="">-- Chọn Size --</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('variants.0.size_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm color-select @error('variants.0.color_id') is-invalid @enderror" name="variants[0][color_id]" required>
                                            <option value="">-- Chọn Màu --</option>
                                            @foreach($colors as $color)
                                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('variants.0.color_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </td>

                                    <td>
                                        <input type="number" class="form-control form-control-sm @error('variants.0.price') is-invalid @enderror" name="variants[0][price]" min="0" max="99999999" step="0.01" placeholder="Giá">
                                        @error('variants.0.price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm @error('variants.0.sale_price') is-invalid @enderror" name="variants[0][sale_price]" min="0" max="99999999" step="0.01" placeholder="Giá KM">
                                        @error('variants.0.sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm @error('variants.0.stock_quantity') is-invalid @enderror" name="variants[0][stock_quantity]" value="0" min="0" required>
                                        @error('variants.0.stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm @error('variants.0.sku') is-invalid @enderror" name="variants[0][sku]" placeholder="Để trống tự tạo">
                                        @error('variants.0.sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
<script>
    (function() {
        let variantIndex = Number("{{ old('variants') ? count(old('variants')) : 1 }}");
        const tableBody = document.querySelector('#variants-table tbody');
        const addBtn = document.getElementById('add-variant-btn');

        addBtn.addEventListener('click', function() {
            // Find highest index to avoid collision
            let maxIndex = -1;
            document.querySelectorAll('.variant-row').forEach(row => {
                const index = parseInt(row.getAttribute('data-index'));
                if (index > maxIndex) maxIndex = index;
            });
            variantIndex = maxIndex + 1;

            const row = `
                <tr class="variant-row" data-index="${variantIndex}">
                    <td>
                        <select class="form-select form-select-sm size-select" name="variants[${variantIndex}][size_id]" required>
                            <option value="">-- Chọn Size --</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm color-select" name="variants[${variantIndex}][color_id]" required>
                            <option value="">-- Chọn Màu --</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][price]" min="0" max="99999999" step="0.01" placeholder="Giá">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][sale_price]" min="0" max="99999999" step="0.01" placeholder="Giá KM">
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

        // Prevention of duplicate variants
        tableBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('size-select') || e.target.classList.contains('color-select')) {
                const row = e.target.closest('tr');
                const sizeId = row.querySelector('.size-select').value;
                const colorId = row.querySelector('.color-select').value;

                if (sizeId && colorId) {
                    let duplicate = false;
                    document.querySelectorAll('.variant-row').forEach(otherRow => {
                        if (otherRow === row) return;
                        const otherSizeId = otherRow.querySelector('.size-select').value;
                        const otherColorId = otherRow.querySelector('.color-select').value;

                        if (sizeId === otherSizeId && colorId === otherColorId) {
                            duplicate = true;
                        }
                    });

                    if (duplicate) {
                        alert('Biến thể với Size và Màu sắc này đã được chọn.');
                        e.target.value = '';
                    }
                }
            }
        });


        // Character counter for short description
        const shortDescTextarea = document.getElementById('short_description');
        const shortCharCount = document.getElementById('short-char-count');
        
        if (shortDescTextarea && shortCharCount) {
            shortCharCount.textContent = shortDescTextarea.value.length;
            shortDescTextarea.addEventListener('input', function() {
                shortCharCount.textContent = this.value.length;
            });
        }

        // Character counter for description
        const descTextarea = document.getElementById('description');
        const charCount = document.getElementById('char-count');
        
        if (descTextarea && charCount) {
            charCount.textContent = descTextarea.value.length;
            descTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }

        // Gallery images preview
        const galleryInput = document.getElementById('gallery_images');
        const galleryPreview = document.getElementById('gallery-preview');
        
        if (galleryInput && galleryPreview) {
            galleryInput.addEventListener('change', function(e) {
                galleryPreview.innerHTML = '';
                const files = Array.from(e.target.files);
                
                if (files.length > 6) {
                    alert('Tối đa 6 ảnh gallery');
                    this.value = '';
                    return;
                }
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'position-relative';
                            div.innerHTML = `
                                <img src="${e.target.result}" width="100" class="border rounded">
                                <small class="d-block text-center">${index + 1}</small>
                            `;
                            galleryPreview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        }

        // Main image preview
        const mainImageInput = document.getElementById('image');
        const mainImagePreview = document.getElementById('main-image-preview');

        if (mainImageInput && mainImagePreview) {
            mainImageInput.addEventListener('change', function(e) {
                mainImagePreview.innerHTML = '';
                const file = e.target.files[0];
                
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        mainImagePreview.innerHTML = `<img src="${e.target.result}" width="150" class="border rounded mt-2">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    })();
</script>
@endsection
