@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Quản lý Sản phẩm</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Sản phẩm</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@push('css')
<style>
    .product-table th, .product-table td {
        vertical-align: middle !important;
    }
    .thumb-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    .product-name-cell {
        max-width: 250px;
    }
    .product-name-cell .slug {
        font-size: 0.75rem;
        color: #999;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .badge-status {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
    .table-responsive {
        overflow-x: auto;
    }
    /* Ensure action column doesn't swallow too much space */
    .sticky-action-column {
        white-space: nowrap;
        min-width: 100px;
        background-color: #fff !important;
        box-shadow: -5px 0 10px rgba(0,0,0,0.05);
    }
    .table td, .table th {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.875rem;
    }
</style>
@endpush

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách Sản phẩm</h5>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search m-0"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm kiếm tên sản phẩm..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="category_id" class="form-select">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">Lọc</button>
                                @if(request()->hasAny(['search', 'category_id']))
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-light px-3" title="Xóa bộ lọc"><i class="ti ti-x m-0"></i></a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered product-table mt-0">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center; vertical-align: middle;">
                                        <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; cursor: pointer;">
                                    </th>
                                    <th style="width: 70px;">Hình ảnh</th>
                                    <th>Thông tin sản phẩm</th>
                                    <th style="width: 110px;">Trạng thái</th>
                                    <th style="width: 140px;">Danh mục</th>
                                    <th style="width: 120px;">Giá</th>
                                    <th style="width: 90px;">Biến thể</th>
                                    <th class="sticky-action-column" style="width: 100px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input class="product-checkbox" type="checkbox" name="ids[]" value="{{ $product->id }}" style="width: 18px; height: 18px; cursor: pointer;">
                                        </td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="thumb-img" alt="{{ $product->name }}">
                                            @else
                                                <div class="thumb-img d-flex align-items-center justify-content-center bg-light text-muted" style="font-size: 10px;">No Image</div>
                                            @endif
                                        </td>
                                        <td class="product-name-cell">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-light-secondary text-muted me-2" style="font-size: 10px;">#{{ $product->id }}</span>
                                                <span class="fw-bold text-dark">{{ $product->name }}</span>
                                            </div>
                                            <span class="slug shadow-none">{{ $product->slug }}</span>
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-light-success border border-success text-success badge-status" style="font-size: 11px;">Active</span>
                                            @else
                                                <span class="badge bg-light-danger border border-danger text-danger badge-status" style="font-size: 11px;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $product->category->name ?? 'N/A' }}</td>
                                        <td class="fw-bold text-dark">
                                            @if($product->variants->isNotEmpty())
                                                @php
                                                    $minPrice = $product->variants->min('price');
                                                    $maxPrice = $product->variants->max('price');
                                                @endphp
                                                @if($minPrice == $maxPrice)
                                                    {{ number_format($minPrice) }}đ
                                                @else
                                                    {{ number_format($minPrice) }} - {{ number_format($maxPrice) }}đ
                                                @endif
                                            @else
                                                {{ number_format($product->price) }}đ
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light-info text-info border border-info">{{ $product->variants_count }}</span></td>
                                        <td class="sticky-action-column">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="btn btn-warning btn-sm">Sửa</a>



                                            <form id="delete-form-prod-{{ $product->id }}"
                                                action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                                class="d-inline no-pjax">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('delete-form-prod-{{ $product->id }}')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="mb-3 mt-3 d-flex gap-2">
                <form id="bulk-delete-form" action="{{ route('admin.products.bulk-delete') }}" method="POST" class="bulk-action-form">
                    @csrf
                    @method('DELETE')
                    <div id="bulk-delete-inputs"></div>
                    <button type="button" class="btn btn-warning btn-sm btn-bulk-delete" disabled>
                        <i class="ti ti-trash"></i> Xóa các mục đã chọn
                    </button>
                </form>

                <form id="delete-all-form" action="{{ route('admin.products.delete-all') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm btn-delete-all">
                        <i class="ti ti-alert-triangle"></i> Xóa tất cả
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
