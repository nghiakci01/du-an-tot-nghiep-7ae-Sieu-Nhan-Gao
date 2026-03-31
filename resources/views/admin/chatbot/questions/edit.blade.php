@extends('layouts.admin')

@section('title', 'Sửa Câu hỏi gợi ý')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.chatbot', ['tab' => 'questions']) }}">Cấu hình Chatbot</a></li>
                    <li class="breadcrumb-item" aria-current="page">Chỉnh sửa Câu hỏi</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Chỉnh sửa Câu hỏi gợi ý</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm border-start border-warning border-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0"><i class="ti ti-edit me-2"></i>Chỉnh sửa Câu hỏi #{{ $question->id }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.chatbot.questions.update', $question) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-bold">Câu hỏi hiển thị <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ti ti-message-dots"></i></span>
                            <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question', $question->question) }}" placeholder="VD: Xem hàng mới về, Chính sách bảo hành...">
                        </div>
                        @error('question') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <small class="text-muted mt-1 d-block"><i class="ti ti-info-circle me-1"></i> Đây là nội dung hiển thị trên nút bấm ở khung chat khách hàng.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Câu trả lời mẫu (Tùy chọn)</label>
                        <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" rows="5" placeholder="Nhập câu trả lời mẫu chatbot sẽ gửi khi khách bấm nút này...">{{ old('answer', $question->answer) }}</textarea>
                        @error('answer') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        
                        <div class="mt-2 p-3 bg-light rounded-3 border">
                            <span class="fw-bold small d-block mb-2 text-info"><i class="ti ti-info-circle me-1"></i> Các thẻ động hỗ trợ:</span>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help" title="Hiện sản phẩm">{product}</span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help" title="Số điện thoại">{hotline}</span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help" title="Email">{email}</span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help" title="Danh mục">{categories}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Thứ tự hiển thị <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-sort-ascending"></i></span>
                                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $question->order) }}">
                            </div>
                            @error('order') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold d-block">Trạng thái hoạt động</label>
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ $question->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Kích hoạt hiển thị</label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4 shadow-sm fw-bold">
                            <i class="ti ti-device-floppy me-1"></i> Cập nhật câu hỏi
                        </button>
                        <a href="{{ route('admin.settings.chatbot', ['tab' => 'questions']) }}" class="btn btn-outline-secondary">
                            Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5><i class="ti ti-bulb me-2 text-warning"></i>Mẹo nhỏ</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="ti ti-check text-success me-2 mt-1"></i>
                        <span>Việc <b>chỉnh sửa</b> câu hỏi không ảnh hưởng đến các hội thoại đang diễn ra.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="ti ti-check text-success me-2 mt-1"></i>
                        <span>Sử dụng thẻ <b>{product}</b> để tăng tỉ lệ chuyển đổi từ khách hàng.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="ti ti-check text-success me-2 mt-1"></i>
                        <span>Cân nhắc <b>tạm ẩn</b> thay vì xóa nếu bạn muốn sử dụng lại câu hỏi sau này.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
