@extends('layouts.admin')

@section('title', 'Sửa Danh mục')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Sửa Danh mục</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>
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
                <h5>Cập nhật Thông tin</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Danh mục</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Danh mục Cha</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                            <option value="">-- Chọn Danh mục Cha (Nếu có) --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        @if($category->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Current Image" width="100">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        <small class="text-muted">Để trống nếu không thay đổi hình ảnh.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
