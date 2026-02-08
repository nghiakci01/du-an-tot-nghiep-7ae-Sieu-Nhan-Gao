@extends('layouts.admin')

@section('title', 'Thêm Banner Mới')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Banner Mới</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề (Tùy chọn)</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="image" name="image" required>
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="link" class="form-label">Liên kết (Tùy chọn)</label>
                                <input type="text" class="form-control" id="link" name="link" value="{{ old('link') }}"
                                    placeholder="https://example.com/san-pham">
                            </div>

                            <div class="mb-3">
                                <label for="position" class="form-label">Vị trí hiển thị <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="position" name="position" required>
                                    <option value="slider">Slider Chính (Trang chủ)</option>
                                    <option value="banner_top">Banner Top (Bên phải Slider)</option>
                                    <option value="banner_bottom">Banner Bottom (Cuối trang)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Thứ tự hiển thị</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">Hiển thị ngay</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Lưu Banner</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection