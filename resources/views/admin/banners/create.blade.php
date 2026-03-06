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
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Tiêu đề (Tùy chọn)</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Nhập tiêu đề banner">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="link" class="form-label">Liên kết (Tùy chọn)</label>
                                        <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}"
                                            placeholder="https://example.com/san-pham">
                                        @error('link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Hình ảnh <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" onchange="previewImage(this)">
                                        <div class="form-text">Định dạng hỗ trợ: jpeg, png, jpg, gif, webp. Tối đa 2MB. <br><strong>Khuyên dùng:</strong> Chiều rộng tối thiểu 1521px. Để sắc nét nhất trên mọi màn hình, hãy dùng 3042px.</div>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-3 d-none" id="imagePreviewContainer">
                                            <p class="mb-2">Xem trước hình ảnh:</p>
                                            <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 border-start">
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Vị trí hiển thị <span class="text-danger">*</span></label>
                                        <select class="form-select @error('position') is-invalid @enderror" id="position" name="position">
                                            <option value="slider" selected>Slider Chính Trang Chủ</option>
                                            <option value="about_us">Banner Trang Về Chúng Tôi</option>
                                            <option value="home_middle">Banner Giữa Trang Chủ (Thay Instagram)</option>
                                        </select>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2 small text-muted">
                                            <p><strong>Lưu ý:</strong> Vị trí hiển thị sẽ quyết định banner được xuất hiện ở đâu trên trang.</p>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label">Thứ tự ưu tiên (Tùy chọn)</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Số nhỏ hơn sẽ đứng trước.</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked value="1">
                                            <label class="form-check-label fw-bold" for="is_active">Trạng thái hiển thị</label>
                                        </div>
                                        <p class="small text-muted mb-0">Nếu tắt, banner sẽ được lưu nháp và không hiện ngoài trang chủ.</p>
                                    </div>

                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy"></i> Lưu Banner
                                        </button>
                                        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Quay lại danh sách</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('d-none');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('d-none');
        }
    }
</script>
@endsection