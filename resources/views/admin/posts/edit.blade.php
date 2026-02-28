@extends('layouts.admin')

@section('title', 'Sửa Bài viết')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Sửa Bài viết</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                     class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
                        <li class="breadcrumb-item"><a href="#!">Sửa</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-horizontal">
                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header"><h5>Nội dung bài viết</h5></div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
                                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tóm tắt ngắn (Summary)</label>
                                        <textarea name="summary" class="form-control" rows="3">{{ old('summary', $post->summary) }}</textarea>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="form-label">Nội dung bài viết <span class="text-danger">*</span></label>
                                        <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                                        @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header"><h5>Phân loại & Cài đặt</h5></div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                        <select name="post_category_id" class="form-select @error('post_category_id') is-invalid @enderror" required>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('post_category_id', $post->post_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('post_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Ảnh đại diện</label>
                                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" onchange="previewImage(this)">
                                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div class="mt-2 {{ $post->image ? '' : 'd-none' }}" id="previewContainer">
                                            <img id="imagePreview" src="{{ $post->image ? Storage::url($post->image) : '#' }}" alt="Preview" class="img-fluid rounded border shadow-sm">
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="is_active" class="form-select">
                                            <option value="1" {{ old('is_active', $post->is_active) == 1 ? 'selected' : '' }}>Hiển thị</option>
                                            <option value="0" {{ old('is_active', $post->is_active) == 0 ? 'selected' : '' }}>Ẩn</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Ngày đăng</label>
                                        <input type="date" name="published_at" class="form-control" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d') : '') }}">
                                    </div>

                                    <div class="mt-4 d-grid">
                                        <button type="submit" class="btn btn-primary">Cập nhật bài viết</button>
                                        <a href="{{ route('admin.posts.index') }}" class="btn btn-link-secondary mt-2">Hủy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
