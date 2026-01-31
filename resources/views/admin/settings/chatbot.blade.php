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
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.settings.chatbot.update') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Cấu hình chung</h5>
                    <div class="form-check form-switch mr-3">
                        <input class="form-check-input" type="checkbox" name="chatbot_enabled" id="chatbot_enabled" value="1" {{ ($settings['chatbot_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="chatbot_enabled">Kích hoạt Chatbot</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Chế độ hoạt động</label>
                        <select name="chatbot_mode" id="chatbot_mode" class="form-select">
                            <option value="rules" {{ ($settings['chatbot_mode'] ?? '') == 'rules' ? 'selected' : '' }}>Rule-based (Từ khóa)</option>
                            <option value="ai" {{ ($settings['chatbot_mode'] ?? '') == 'ai' ? 'selected' : '' }}>AI Assistant (AI thông minh)</option>
                        </select>
                    </div>

                    <div id="ai_provider_section" style="display: {{ ($settings['chatbot_mode'] ?? '') == 'ai' ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label class="form-label">Nhà cung cấp AI</label>
                            <select name="ai_provider" id="ai_provider" class="form-select">
                                <option value="gemini" {{ ($settings['ai_provider'] ?? '') == 'gemini' ? 'selected' : '' }}>Google Gemini (Khuyên dùng)</option>
                                <option value="openai" {{ ($settings['ai_provider'] ?? '') == 'openai' ? 'selected' : '' }}>OpenAI (GPT-3.5/4)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Câu chào mừng</label>
                        <textarea name="greeting_message" class="form-control" rows="3">{{ $settings['greeting_message'] ?? '' }}</textarea>
                        <small class="text-muted">Hiển thị khi khách hàng lần đầu mở khung chat.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Câu phản hồi khi không hiểu (Fallback)</label>
                        <textarea name="fallback_message" class="form-control" rows="3">{{ $settings['fallback_message'] ?? '' }}</textarea>
                        <small class="text-muted">Sử dụng {hotline} để tự động chèn số điện thoại hỗ trợ.</small>
                    </div>

                    <div id="system_instruction_section" style="display: {{ ($settings['chatbot_mode'] ?? '') == 'ai' ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label class="form-label">System Instruction (Chỉ dẫn cho AI)</label>
                            <textarea name="system_instruction" class="form-control" rows="10">{{ $settings['system_instruction'] ?? '' }}</textarea>
                            <div class="mt-2">
                                <small class="text-muted d-block">Đây là "linh hồn" của AI. Hãy định nghĩa <b>Bạn là ai</b>, <b>Bạn phải làm gì</b> và <b>Dữ liệu</b>.</small>
                                <small class="text-info d-block">Có thể dùng biến: {hotline}, {email}, {categories}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Thông tin hỗ trợ & AI Keys</h5>
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

                    <div id="gemini_key_section" class="provider-key-section" style="display: {{ ($settings['ai_provider'] ?? 'gemini') == 'gemini' ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label class="form-label">Gemini API Key</label>
                            <div class="input-group">
                                <input type="password" name="gemini_api_key" id="gemini_api_key" class="form-control" value="{{ $settings['gemini_api_key'] ?? '' }}">
                                <button type="button" class="btn btn-outline-info btn-test-connection" data-provider="gemini">
                                    <i class="ti ti-plug me-1"></i> Kiểm tra Gemini
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="openai_key_section" class="provider-key-section" style="display: {{ ($settings['ai_provider'] ?? '') == 'openai' ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label class="form-label">OpenAI API Key</label>
                            <div class="input-group">
                                <input type="password" name="openai_api_key" id="openai_api_key" class="form-control" value="{{ $settings['openai_api_key'] ?? '' }}">
                                <button type="button" class="btn btn-outline-info btn-test-connection" data-provider="openai">
                                    <i class="ti ti-plug me-1"></i> Kiểm tra OpenAI
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="test-connection-result" class="mt-2" style="display: none;"></div>
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
document.addEventListener('DOMContentLoaded', function() {
    const chatbotMode = document.getElementById('chatbot_mode');
    const aiProviderSection = document.getElementById('ai_provider_section');
    const systemInstructionSection = document.getElementById('system_instruction_section');
    const aiProvider = document.getElementById('ai_provider');
    const geminiSection = document.getElementById('gemini_key_section');
    const openaiSection = document.getElementById('openai_key_section');

    // Chuyển đổi hiển thị AI Provider & System Instruction
    chatbotMode.addEventListener('change', function() {
        const isAI = this.value === 'ai';
        aiProviderSection.style.display = isAI ? 'block' : 'none';
        systemInstructionSection.style.display = isAI ? 'block' : 'none';
    });

    // Chuyển đổi hiển thị API Keys
    aiProvider.addEventListener('change', function() {
        geminiSection.style.display = this.value === 'gemini' ? 'block' : 'none';
        openaiSection.style.display = this.value === 'openai' ? 'block' : 'none';
        document.getElementById('test-connection-result').style.display = 'none';
    });

    // Xử lý kiểm tra kết nối
    document.querySelectorAll('.btn-test-connection').forEach(btn => {
        btn.addEventListener('click', function() {
            const provider = this.getAttribute('data-provider');
            const apiKey = document.getElementById(provider + '_api_key').value;
            const resultDiv = document.getElementById('test-connection-result');
            
            // Reset state
            resultDiv.style.display = 'block';
            resultDiv.className = 'mt-2 alert alert-warning';
            resultDiv.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Đang kết nối thử với ' + provider + '...';
            this.disabled = true;

            fetch('{{ route("admin.settings.chatbot.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    ai_provider: provider,
                    api_key: apiKey 
                })
            })
            .then(response => response.json())
            .then(data => {
                this.disabled = false;
                if (data.success) {
                    resultDiv.className = 'mt-2 alert alert-success';
                    resultDiv.innerHTML = data.message;
                } else {
                    resultDiv.className = 'mt-2 alert alert-danger';
                    resultDiv.innerHTML = data.message;
                }
            })
            .catch(error => {
                this.disabled = false;
                resultDiv.className = 'mt-2 alert alert-danger';
                resultDiv.innerHTML = 'Lỗi hệ thống khi gửi yêu cầu. Vui lòng thử lại sau!';
                console.error('Error:', error);
            });
        });
    });
});
</script>
@endsection
@endsection
