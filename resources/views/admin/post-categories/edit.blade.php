@extends('layouts.admin')

@section('title', 'Sửa Danh mục Tin tức')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Sửa Danh mục Tin tức</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                     class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.post-categories.index') }}">Danh mục tin</a></li>
                        <li class="breadcrumb-item"><a href="#!">Sửa</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin danh mục: {{ $category->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.post-categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>Khóa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.post-categories.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
