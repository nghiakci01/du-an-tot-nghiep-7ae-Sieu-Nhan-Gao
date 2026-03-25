@extends('layouts.public')

@section('title', 'Yêu cầu hoàn trả - Đơn #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@push('styles')
<style>
.return-form-wrapper {
  background: #f5f5f7;
  padding: 40px 0 60px;
  min-height: 80vh;
}
.detail-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 20px rgba(0,0,0,0.07);
  overflow: hidden;
  margin-bottom: 20px;
}
.detail-header {
  padding: 18px 24px;
  border-bottom: 1px solid #f0f0f0;
}
.detail-header h5 { margin: 0; font-weight: 700; font-size: 1rem; }
.detail-body { padding: 22px 24px; }

/* Image Upload UI */
.image-upload-wrap {
  position: relative;
  border: 2px dashed #ddd;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px;
  cursor: pointer;
  background: #fafafa;
  transition: all 0.2s;
}
.image-upload-wrap:hover { background: #f0f0f0; border-color: #bbb; }
.image-upload-wrap i { font-size: 2rem; color: #888; margin-bottom: 10px; }
.image-upload-wrap input[type=file] {
  position: absolute; width: 100%; height: 100%;
  opacity: 0; cursor: pointer; top: 0; left: 0;
}

#preview-container {
  display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;
}
.img-preview {
  width: 80px; height: 80px;
  border-radius: 8px; object-fit: cover;
  border: 1px solid #ddd;
}

/* Video Upload UI */
.video-upload-wrap {
  position: relative;
  border: 2px dashed #ddd;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px;
  cursor: pointer;
  background: #fafafa;
  transition: all 0.2s;
}
.video-upload-wrap:hover { background: #f0f0f0; border-color: #bbb; }
.video-upload-wrap i { font-size: 2rem; color: #888; margin-bottom: 10px; }
.video-upload-wrap input[type=file] {
  position: absolute; width: 100%; height: 100%;
  opacity: 0; cursor: pointer; top: 0; left: 0;
}
#video-preview-container {
  margin-top: 15px;
}
#video-preview-container video {
  max-width: 100%;
  max-height: 240px;
  border-radius: 10px;
  border: 1px solid #ddd;
}
</style>
@endpush

@section('content')
<div class="breadcrumbs_area other_bread">
  <div class="container">
    <div class="breadcrumb_content">
      <ul>
        <li><a href="{{ route('welcome') }}">Trang chủ</a></li>
        <li>/</li>
        <li><a href="{{ route('account.index') }}?tab=orders">Tài khoản</a></li>
        <li>/</li>
        <li><a href="{{ route('account.orders.show', $order->id) }}">Đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</a></li>
        <li>/</li>
        <li>Yêu cầu hoàn trả</li>
      </ul>
    </div>
  </div>
</div>

<div class="return-form-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        @if(session('error'))
          <div class="alert alert-danger rounded-3 mb-3 shadow-sm border-0">{{ session('error') }}</div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger rounded-3 mb-3 shadow-sm border-0">
            <ul class="mb-0 px-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="detail-card">
          <div class="detail-header">
            <h5><i class="bi bi-arrow-return-left me-2"></i>Tạo Yêu cầu Hoàn trả</h5>
          </div>
          <div class="detail-body">
            
            <div class="alert alert-info rounded-3 mb-4 border-0 shadow-sm" style="font-size:0.9rem;">
              <strong>Lưu ý:</strong> Yêu cầu hoàn trả sẽ được cửa hàng xem xét. Sau khi được chấp thuận, hệ thống sẽ cấp mã vận chuyển để bạn gửi hàng về cửa hàng. Tiền sẽ được hoàn vào <strong>Ví của bạn</strong> sau khi cửa hàng nhận được sản phẩm.
            </div>

            <form action="{{ route('account.orders.return_submit', $order->id) }}" method="POST" enctype="multipart/form-data">
              @csrf

              {{-- Reason --}}
              <div class="mb-4">
                <label class="form-label fw-bold">Lý do hoàn trả <span class="text-danger">*</span></label>
                <select name="reason" class="form-select form-select-lg" required style="border-radius:10px; font-size:15px;">
                  <option value="" disabled selected>-- Chọn lý do --</option>
                  <option value="Hàng lỗi / Hỏng hóc">Hàng lỗi / Hỏng hóc</option>
                  <option value="Đổi kích cỡ (Size)">Đổi kích cỡ (Size)</option>
                  <option value="Sản phẩm không giống mô tả">Sản phẩm không giống mô tả</option>
                  <option value="Đóng gói lộn xộn, thiếu hàng">Thiếu hàng / Mất hàng</option>
                  <option value="Khác">Lý do khác</option>
                </select>
              </div>

              {{-- Note --}}
              <div class="mb-4">
                <label class="form-label fw-bold">Chi tiết lỗi / Yêu cầu thêm</label>
                <textarea name="note" class="form-control" rows="4" placeholder="Vui lòng mô tả chi tiết tình trạng sản phẩm..." style="border-radius:10px;"></textarea>
              </div>

              {{-- Images --}}
              <div class="mb-4">
                <label class="form-label fw-bold">Ảnh minh chứng <span class="text-muted fw-normal">(Tùy chọn, tối đa 4 ảnh)</span></label>
                <div class="image-upload-wrap">
                  <div class="text-center">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p class="mb-0 text-muted">Nhấn vào đây để tải ảnh lên</p>
                  </div>
                  <input type="file" name="images[]" id="return-images" multiple accept="image/*">
                </div>
                <div id="preview-container"></div>
              {{-- Videos --}}
              <div class="mb-5">
                <label class="form-label fw-bold"><i class="bi bi-camera-reels me-1"></i>Video minh chứng <span class="text-muted fw-normal">(Tùy chọn, tối đa 1 video, 50MB)</span></label>
                <div class="video-upload-wrap">
                  <div class="text-center">
                    <i class="bi bi-film"></i>
                    <p class="mb-0 text-muted">Nhấn vào đây để tải video lên (MP4, MOV, AVI, WebM)</p>
                  </div>
                  <input type="file" name="videos[]" id="return-videos" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
                </div>
                <div id="video-preview-container"></div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-light rounded-pill px-4">Hủy bỏ</a>
                <button type="submit" class="btn btn-warning rounded-pill px-4" style="background:#1a1a2e; color:white; border:none;">
                  <i class="bi bi-send me-1"></i> Gửi yêu cầu
                </button>
              </div>

            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Image preview
document.getElementById('return-images').addEventListener('change', function(e) {
  const container = document.getElementById('preview-container');
  container.innerHTML = '';
  const files = Array.from(e.target.files).slice(0, 4);
  files.forEach(file => {
    if(file.type.startsWith('image/')) {
      const img = document.createElement('img');
      img.className = 'img-preview';
      img.src = URL.createObjectURL(file);
      container.appendChild(img);
    }
  });
});

// Video preview
document.getElementById('return-videos').addEventListener('change', function(e) {
  const container = document.getElementById('video-preview-container');
  container.innerHTML = '';
  const file = e.target.files[0];
  if (!file) return;

  // Validate size (50MB)
  if (file.size > 50 * 1024 * 1024) {
    alert('Video không được vượt quá 50MB');
    e.target.value = '';
    return;
  }

  if (file.type.startsWith('video/')) {
    const video = document.createElement('video');
    video.controls = true;
    video.src = URL.createObjectURL(file);
    container.appendChild(video);

    const info = document.createElement('p');
    info.className = 'text-muted small mt-1';
    info.textContent = file.name + ' (' + (file.size / (1024*1024)).toFixed(1) + ' MB)';
    container.appendChild(info);
  }
});
</script>
@endpush
