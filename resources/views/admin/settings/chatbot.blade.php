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

        <ul class="nav nav-tabs mb-3" id="chatbotTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">Cấu hình chung</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions" type="button" role="tab" aria-controls="questions" aria-selected="false">Câu hỏi gợi ý</button>
            </li>
        </ul>

        <div class="tab-content" id="chatbotTabsContent">
            <!-- Tab 1: Cấu hình chung -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Quản lý từ khóa (Keyword Rules)</h5>
                            <button type="button" class="btn btn-sm btn-success" id="add-keyword-rule">
                                <i class="ti ti-plus me-1"></i> Thêm quy tắc
                            </button>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-1">Khi khách hàng nhắn tin khớp với từ khóa, Chatbot sẽ trả lời bằng câu văn bản tương ứng. Hệ thống sẽ kiểm tra theo thứ tự từ trên xuống dưới.</p>
                            <div class="alert alert-info border-0 shadow-none py-2 px-3 mb-3" style="background: rgba(var(--bs-info-rgb), 0.1);">
                                <small class="fw-bold d-block mb-1 text-info"><i class="ti ti-info-circle me-1"></i> Các thẻ động (Dynamic Tags) hỗ trợ:</small>
                                <div class="d-flex flex-wrap gap-3">
                                    <code class="text-primary small" title="Hiện danh sách sản phẩm liên quan">{product}</code>
                                    <code class="text-primary small" title="Số hotline hệ thống">{hotline}</code>
                                    <code class="text-primary small" title="Email hỗ trợ hệ thống">{email}</code>
                                    <code class="text-primary small" title="Danh sách các danh mục sản phẩm">{categories}</code>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="keyword-rules-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%">Từ khóa / Mẫu (Regex)</th>
                                            <th>Câu trả lời</th>
                                            <th style="width: 80px" class="text-center">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="keyword-rules-body">
                                        <!-- Rules will be injected here by JS -->
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="keyword_rules" id="keyword_rules_input">
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

            <!-- Tab 2: Câu hỏi gợi ý -->
            <div class="tab-pane fade" id="questions" role="tabpanel" aria-labelledby="questions-tab">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Danh sách Câu hỏi gợi ý</h5>
                        <a href="{{ route('admin.chatbot.questions.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Thêm mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 80px">Thứ tự</th>
                                        <th>Câu hỏi (Nút bấm)</th>
                                        <th>Câu trả lời mẫu</th>
                                        <th style="width: 120px">Trạng thái</th>
                                        <th style="width: 150px" class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($questions as $question)
                                    <tr>
                                        <td><span class="badge bg-light text-dark border">{{ $question->order }}</span></td>
                                        <td class="fw-bold">{{ $question->question }}</td>
                                        <td>
                                            @if($question->answer)
                                                <div class="text-muted small text-truncate" style="max-width: 250px;">{{ $question->answer }}</div>
                                            @else
                                                <span class="text-muted small italic">Sử dụng quy tắc/AI</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($question->is_active)
                                                <span class="badge bg-success-subtle text-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Tạm ẩn</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.chatbot.questions.edit', $question) }}" class="btn btn-warning" title="Sửa">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.chatbot.questions.destroy', $question) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa câu hỏi này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Xóa">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="ti ti-help-circle fs-2 d-block mb-2"></i>
                                            Chưa có câu hỏi gợi ý nào.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    // Xử lý chuyển tab tự động từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    if (activeTab === 'questions') {
        const questionsTab = document.getElementById('questions-tab');
        if (questionsTab) {
            new bootstrap.Tab(questionsTab).show();
        }
    }

    // Logic cho Quản lý từ khóa
    const rulesBody = document.getElementById('keyword-rules-body');
    const addBtn = document.getElementById('add-keyword-rule');
    const rulesInput = document.getElementById('keyword_rules_input');
    
    // Load existing rules - Safely pass from PHP to JS
    let rules = [];
    try {
        const rawJson = @json($settings['keyword_rules'] ?? '[]');
        rules = typeof rawJson === 'string' ? JSON.parse(rawJson) : rawJson;
        if (!Array.isArray(rules)) rules = [];
    } catch (e) {
        console.error('Failed to parse keyword rules:', e);
        rules = [];
    }

    function renderRules() {
        if (!rulesBody) return;
        rulesBody.innerHTML = '';
        if (rules.length === 0) {
            rulesBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Chưa có quy tắc nào. Hãy nhấn "Thêm quy tắc" để bắt đầu.</td></tr>';
            return;
        }

        rules.forEach((rule, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="text" class="form-control rule-keyword" value="${escapeHtml(rule.keyword || '')}" placeholder="Vd: khuyến mãi, giá,..." data-index="${index}">
                </td>
                <td>
                    <textarea class="form-control rule-response" rows="2" placeholder="Nhập câu trả lời..." data-index="${index}">${escapeHtml(rule.response || '')}</textarea>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-rule" data-index="${index}">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            `;
            rulesBody.appendChild(tr);
        });
        updateHiddenInput();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function updateHiddenInput() {
        if (rulesInput) {
            rulesInput.value = JSON.stringify(rules);
        }
    }

    if (addBtn) {
        addBtn.addEventListener('click', function() {
            rules.push({ keyword: '', response: '' });
            renderRules();
        });
    }

    if (rulesBody) {
        rulesBody.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-remove-rule');
            if (btn) {
                const index = btn.dataset.index;
                rules.splice(index, 1);
                renderRules();
            }
        });

        rulesBody.addEventListener('input', function(e) {
            const input = e.target;
            const index = input.dataset.index;
            if (input.classList.contains('rule-keyword')) {
                rules[index].keyword = input.value;
            } else if (input.classList.contains('rule-response')) {
                rules[index].response = input.value;
            }
            updateHiddenInput();
        });
    }

    renderRules();
});
</script>
@endsection
@endsection
