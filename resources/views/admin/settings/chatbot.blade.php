@extends('layouts.admin')

@section('title', 'Cấu hình Chatbot')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Hệ thống</a></li>
                    <li class="breadcrumb-item" aria-current="page">Cấu hình Chatbot</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Cấu hình Chatbot</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.chatbot.update') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5>Cấu hình chung</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Chế độ hoạt động</label>
                        <select name="chatbot_mode" class="form-select">
                            <option value="rules" {{ ($settings['chatbot_mode'] ?? '') == 'rules' ? 'selected' : '' }}>Rule-based (Từ khóa)</option>
                            <option value="gemini" {{ ($settings['chatbot_mode'] ?? '') == 'gemini' ? 'selected' : '' }}>AI Assistant (Gemini AI)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Câu chào mừng</label>
                        <textarea name="greeting_message" class="form-control" rows="3">{{ $settings['greeting_message'] ?? '' }}</textarea>
                        <small class="text-muted">Hiển thị khi khách hàng lần đầu mở khung chat.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Câu phản hồi khi không hiểu (Fallback)</label>
                        <textarea name="fallback_message" class="form-control" rows="4">{{ $settings['fallback_message'] ?? '' }}</textarea>
                        <small class="text-muted">Sử dụng {hotline} để tự động chèn số điện thoại hỗ trợ.</small>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Thông tin hỗ trợ & AI</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Hotline</label>
                            <input type="text" name="hotline" class="form-control" value="{{ $settings['hotline'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email hỗ trợ</label>
                            <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gemini API Key</label>
                        <div class="input-group">
                            <input type="password" name="gemini_api_key" id="gemini_api_key" class="form-control" value="{{ $settings['gemini_api_key'] ?? '' }}">
                            <button type="button" class="btn btn-outline-info" id="btn-test-connection">
                                <i class="ti ti-plug me-1"></i> Kiểm tra kết nối
                            </button>
                        </div>
                        <div id="test-connection-result" class="mt-2" style="display: none;"></div>
                        <small class="text-muted">Chỉ cần thiết khi bật chế độ AI Assistant.</small>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('btn-test-connection').addEventListener('click', function() {
    const apiKey = document.getElementById('gemini_api_key').value;
    const resultDiv = document.getElementById('test-connection-result');
    const btn = this;
    
    // Reset state
    resultDiv.style.display = 'block';
    resultDiv.className = 'mt-2 alert alert-warning';
    resultDiv.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Đang kết nối thử với Google Gemini API...';
    btn.disabled = true;

    fetch('{{ route("admin.settings.chatbot.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ gemini_api_key: apiKey })
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            resultDiv.className = 'mt-2 alert alert-success';
            resultDiv.innerHTML = data.message;
        } else {
            resultDiv.className = 'mt-2 alert alert-danger';
            resultDiv.innerHTML = data.message;
        }
    })
    .catch(error => {
        btn.disabled = false;
        resultDiv.className = 'mt-2 alert alert-danger';
        resultDiv.innerHTML = 'Lỗi hệ thống khi gửi yêu cầu. Vui lòng thử lại sau!';
        console.error('Error:', error);
    });
});
</script>
@endsection
@endsection
