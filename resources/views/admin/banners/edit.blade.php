@extends('layouts.admin')

@section('title', 'Chỉnh sửa Banner')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chỉnh sửa Banner</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.banners.update', $banner) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                                <label for="title" class="form-label">Tiêu đề (Tùy chọn)</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                    value="{{ old('title', $banner->title) }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh (Chỉ chọn nếu muốn thay đổi)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Current Image"
                                        style="height: 100px;">
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                                <label for="link" class="form-label">Liên kết (Tùy chọn)</label>
                                <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link"
                                    value="{{ old('link', $banner->link) }}">
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="position" class="form-label">Vị trí hiển thị <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('position') is-invalid @enderror" id="position" name="position">
                                    <option value="slider" {{ old('position', $banner->position) == 'slider' ? 'selected' : '' }}>Slider Chính
                                        (Trang chủ)</option>
                                    <option value="banner_top" {{ old('position', $banner->position) == 'banner_top' ? 'selected' : '' }}>
                                        Banner Top (Bên phải Slider)</option>
                                    <option value="banner_bottom" {{ old('position', $banner->position) == 'banner_bottom' ? 'selected' : '' }}>Banner Bottom (Cuối trang)</option>
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ $banner->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Hiển thị</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Cập nhật Banner</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection