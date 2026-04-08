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

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách Sản phẩm</h5>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên sản phẩm..." value="{{ request('search') }}">
                            <select name="category_id" class="form-select" style="max-width: 200px;">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary px-3"><i class="ti ti-search m-0"></i></button>
                            @if(request()->hasAny(['search', 'category_id']))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-3" title="Xóa bộ lọc"><i class="ti ti-x m-0"></i></a>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center; vertical-align: middle;">
                                        <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; cursor: pointer;">
                                    </th>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Biến thể</th>
                                    <th>Trạng thái</th>
                                    <th class="sticky-action-column">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input class="product-checkbox" type="checkbox" name="ids[]" value="{{ $product->id }}" style="width: 18px; height: 18px; cursor: pointer;">
                                        </td>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                    width="50">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->name }} <br>
                                            <small>{{ $product->slug }}</small>
                                        </td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($product->variants->isNotEmpty())
                                                @php
                                                    $minPrice = $product->variants->min('price');
                                                    $maxPrice = $product->variants->max('price');
                                                @endphp
                                                @if($minPrice == $maxPrice)
                                                    {{ number_format($minPrice) }} đ
                                                @else
                                                    {{ number_format($minPrice) }} - {{ number_format($maxPrice) }} đ
                                                @endif
                                            @else
                                                {{ number_format($product->price) }} đ
                                            @endif
                                        </td>
                                        <td><span class="badge bg-info">{{ $product->variants_count }} variants</span></td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
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
