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
                    <div id="js-data"
                         data-old-rules='{{ old("keyword_rules") }}'
                         data-db-rules='{{ $chatbotSettings["keyword_rules"] ?? "[]" }}'
                         style="display: none;"></div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Cấu hình chung</h5>
                            <div class="form-check form-switch mr-3">
                                <input class="form-check-input" type="checkbox" name="chatbot_enabled" id="chatbot_enabled" value="1" {{ old('chatbot_enabled', $chatbotSettings['chatbot_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="chatbot_enabled">Kích hoạt Chatbot</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Chế độ hoạt động</label>
                                <select name="chatbot_mode" id="chatbot_mode" class="form-select">
                                    <option value="rules" {{ old('chatbot_mode', $chatbotSettings['chatbot_mode'] ?? '') == 'rules' ? 'selected' : '' }}>Rule-based (Từ khóa)</option>
                                    <option value="ai" {{ old('chatbot_mode', $chatbotSettings['chatbot_mode'] ?? '') == 'ai' ? 'selected' : '' }}>AI Assistant (AI thông minh)</option>
                                </select>
                            </div>

                            <div id="ai_provider_section" style="display: {{ old('chatbot_mode', $chatbotSettings['chatbot_mode'] ?? '') == 'ai' ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label class="form-label">Nhà cung cấp AI</label>
                                    <select name="ai_provider" id="ai_provider" class="form-select">
                                        <option value="gemini" {{ old('ai_provider', $chatbotSettings['ai_provider'] ?? '') == 'gemini' ? 'selected' : '' }}>Google Gemini (Khuyên dùng)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Câu chào mừng</label>
                                <textarea name="greeting_message" class="form-control" rows="3">{{ old('greeting_message', $chatbotSettings['greeting_message'] ?? '') }}</textarea>
                                <small class="text-muted">Hiển thị khi khách hàng lần đầu mở khung chat.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Câu phản hồi khi không hiểu (Fallback)</label>
                                <textarea name="fallback_message" class="form-control" rows="3">{{ old('fallback_message', $chatbotSettings['fallback_message'] ?? '') }}</textarea>
                                <small class="text-muted">Sử dụng {hotline} để tự động chèn số điện thoại hỗ trợ.</small>
                            </div>

                            <div id="system_instruction_section" style="display: {{ old('chatbot_mode', $chatbotSettings['chatbot_mode'] ?? '') == 'ai' ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label class="form-label">System Instruction (Chỉ dẫn cho AI)</label>
                                    <textarea name="system_instruction" class="form-control" rows="8">{{ old('system_instruction', $chatbotSettings['system_instruction'] ?? '') }}</textarea>
                                    <small class="text-info d-block mt-1">Có thể dùng biến: {hotline}, {email}, {categories}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4 border-0 shadow-sm border-start border-success border-4">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h5 class="mb-0 text-success"><i class="ti ti-key me-2"></i>Quản lý từ khóa (Keyword Rules)</h5>
                                <p class="text-muted small mb-0 mt-1">Chatbot sẽ phản hồi dựa trên từ khóa khớp (Quy tắc trên cùng được ưu tiên)</p>
                            </div>
                            <button type="button" class="btn btn-success btn-sm px-3 shadow-sm" id="add-keyword-rule">
                                <i class="ti ti-plus me-1"></i> Thêm quy tắc
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="keyword-rules-table">
                                    <thead class="bg-light bg-opacity-50">
                                        <tr>
                                            <th style="width: 50px" class="ps-3 text-center">STT</th>
                                            <th style="width: 30%">Từ khóa (Phân cách bằng dấu phẩy)</th>
                                            <th>Câu trả lời & Thẻ động</th>
                                            <th style="width: 120px" class="text-center ps-3 pe-3">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="keyword-rules-body">
                                        <!-- Rules will be injected here by JS -->
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="keyword_rules" id="keyword_rules_input">

                            <div class="px-3 py-3 border-top bg-light bg-opacity-25">
                                <span class="fw-bold small d-block mb-2 text-info"><i class="ti ti-info-circle me-1"></i> Các thẻ động được hỗ trợ:</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help tag-info" title="Hiện sản phẩm liên quan">{product}</span>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help tag-info" title="Số điện thoại">{hotline}</span>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help tag-info" title="Email liên hệ">{email}</span>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle cursor-help tag-info" title="Danh sách danh mục">{categories}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header"><h5>Thông tin hỗ trợ & AI Keys</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Hotline</label>
                                    <input type="text" name="hotline" class="form-control" value="{{ old('hotline', $chatbotSettings['hotline'] ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email hỗ trợ</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $chatbotSettings['email'] ?? '') }}">
                                </div>
                            </div>

                            <div id="gemini_key_section" class="provider-key-section" style="display: {{ old('ai_provider', $chatbotSettings['ai_provider'] ?? 'gemini') == 'gemini' ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label class="form-label">Gemini API Key</label>
                                    <div class="input-group">
                                        <input type="password" name="gemini_api_key" id="gemini_api_key" class="form-control" value="{{ old('gemini_api_key', $chatbotSettings['gemini_api_key'] ?? '') }}">
                                        <button type="button" class="btn btn-outline-info btn-test-connection" data-provider="gemini"><i class="ti ti-plug me-1"></i> Kiểm tra</button>
                                    </div>
                                </div>
                            </div>

                            <div id="test-connection-result" class="mt-2" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary shadow-sm px-4">Lưu cấu hình</button>
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
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th style="width: 80px" class="ps-3">Thứ tự</th>
                                        <th>Câu hỏi (Nút bấm)</th>
                                        <th>Câu trả lời mẫu</th>
                                        <th style="width: 120px">Trạng thái</th>
                                        <th style="width: 120px" class="text-center pe-3">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($questions as $question)
                                    <tr>
                                        <td class="ps-3"><span class="badge bg-light text-dark border">{{ $question->order }}</span></td>
                                        <td class="fw-bold">{{ $question->question }}</td>
                                        <td>
                                            @if($question->answer)
                                                <div class="text-muted small text-truncate" style="max-width: 300px;">{{ $question->answer }}</div>
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
                                        <td class="text-center pe-3">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.chatbot.questions.edit', $question) }}" class="btn btn-warning"><i class="ti ti-edit"></i></a>
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-question-{{ $question->id }}')"><i class="ti ti-trash"></i></button>
                                            </div>
                                            <form id="delete-question-{{ $question->id }}" action="{{ route('admin.chatbot.questions.destroy', $question) }}" method="POST" class="d-none no-pjax">
                                                @csrf @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có câu hỏi gợi ý nào.</td></tr>
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

{{-- SCRIPT ĐẶT TRONG @section('content') ĐỂ TỰ ĐỘNG CHẠY LẠI SAU KHI PJAX UPDATE --}}
<script>
(function() {
    console.log("Chatbot settings JS re-initializing (Pjax-ready)...");

    // 1. Khai báo các elements chính
    const chatbotMode = document.getElementById('chatbot_mode');
    const aiProviderSection = document.getElementById('ai_provider_section');
    const systemInstructionSection = document.getElementById('system_instruction_section');
    const aiProvider = document.getElementById('ai_provider');
    const chatbotForm = document.querySelector('form[action*="chatbot/update"]');

    if (!chatbotMode) return;

    // 2. Logic đồng bộ hiển thị các phần tử
    function syncSections() {
        const isAI = chatbotMode.value === 'ai';
        if (aiProviderSection) aiProviderSection.style.display = isAI ? 'block' : 'none';
        if (systemInstructionSection) systemInstructionSection.style.display = isAI ? 'block' : 'none';

        const provider = aiProvider ? aiProvider.value : 'gemini';
        document.querySelectorAll('.provider-key-section').forEach(s => s.style.display = 'none');

        if (isAI) {
            const activeSection = document.getElementById(provider + '_key_section');
            if (activeSection) activeSection.style.display = 'block';
        }
    }

    chatbotMode.addEventListener('change', syncSections);
    if (aiProvider) aiProvider.addEventListener('change', syncSections);
    syncSections(); // Khởi tạo lần đầu

    // 3. Xử lý Kiểm tra kết nối API
    document.querySelectorAll('.btn-test-connection').forEach(btn => {
        btn.addEventListener('click', function() {
            const provider = this.getAttribute('data-provider');
            const apiKeyInput = document.getElementById(provider + '_api_key');
            if (!apiKeyInput) return;

            const apiKey = apiKeyInput.value;
            const resultDiv = document.getElementById('test-connection-result');

            if (resultDiv) {
                resultDiv.style.display = 'block';
                resultDiv.className = 'mt-2 alert alert-warning';
                resultDiv.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang kiểm tra...';
            }
            this.disabled = true;

            fetch('{{ route("admin.settings.chatbot.test") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ai_provider: provider, api_key: apiKey })
            })
            .then(r => r.json())
            .then(data => {
                this.disabled = false;
                if (!resultDiv) return;
                resultDiv.className = 'mt-2 alert alert-' + (data.success ? 'success' : 'danger');
                resultDiv.innerHTML = data.message;
            })
            .catch(e => {
                this.disabled = false;
                if (resultDiv) {
                    resultDiv.className = 'mt-2 alert alert-danger';
                    resultDiv.innerHTML = 'Lỗi hệ thống!';
                }
            });
        });
    });

    // 4. Logic quản lý Keyword Rules
    const rulesBody = document.getElementById('keyword-rules-body');
    const addBtn = document.getElementById('add-keyword-rule');
    const rulesInput = document.getElementById('keyword_rules_input');
    let rules = [];

    // Tải dữ liệu ban đầu
    try {
        const jsData = document.getElementById('js-data');
        if (jsData) {
            let rawOld = jsData.dataset.oldRules;
            let rawDb = jsData.dataset.dbRules;
            let rawJson = (rawOld && rawOld !== 'null' && rawOld !== '') ? rawOld : rawDb;
            if (rawJson) rules = typeof rawJson === 'string' ? JSON.parse(rawJson) : rawJson;
            if (!Array.isArray(rules)) rules = [];
        }
    } catch (e) { console.error('Lỗi parse keyword rules:', e); }

    function updateHiddenInput() { if (rulesInput) rulesInput.value = JSON.stringify(rules); }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight + 2) + 'px';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function refreshControlButtons() {
        const rows = rulesBody.querySelectorAll('.rule-row');
        rows.forEach((row, index) => {
            row.dataset.index = index;
            row.querySelector('.row-index').textContent = index + 1;
            row.querySelectorAll('[data-index]').forEach(el => el.dataset.index = index);
            const upBtn = row.querySelector('.btn-move-up');
            const downBtn = row.querySelector('.btn-move-down');
            if (upBtn) upBtn.disabled = (index === 0);
            if (downBtn) downBtn.disabled = (index === rows.length - 1);
        });
    }

    function createRuleRow(rule, index) {
        const tr = document.createElement('tr');
        tr.className = 'rule-row';
        tr.dataset.index = index;
        tr.innerHTML = `
            <td class="text-center text-muted small fw-bold row-index ps-3">${index + 1}</td>
            <td><input type="text" class="form-control form-control-sm rule-keyword" value="${escapeHtml(rule.keyword || '')}" placeholder="Từ khóa..." data-index="${index}"></td>
            <td>
                <div class="position-relative">
                    <textarea class="form-control form-control-sm rule-response auto-expand" rows="1" placeholder="Câu trả lời..." data-index="${index}">${escapeHtml(rule.response || '')}</textarea>
                    <div class="mt-1 d-flex gap-1">
                        <button type="button" class="btn btn-xs btn-outline-info tag-shortcut" data-tag="{product}">+ Sản phẩm</button>
                        <button type="button" class="btn btn-xs btn-outline-info tag-shortcut" data-tag="{hotline}">+ Hotline</button>
                        <button type="button" class="btn btn-xs btn-outline-info tag-shortcut" data-tag="{categories}">+ Danh mục</button>
                    </div>
                </div>
            </td>
            <td class="text-center ps-3 pe-3">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary btn-move-up" ${index === 0 ? 'disabled' : ''}><i class="ti ti-arrow-up"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-move-down" ${index === rules.length - 1 ? 'disabled' : ''}><i class="ti ti-arrow-down"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-remove-rule" title="Xóa"><i class="ti ti-trash"></i></button>
                </div>
            </td>
        `;
        return tr;
    }

    function renderRules() {
        if (!rulesBody) return;
        rulesBody.innerHTML = '';
        if (rules.length === 0) {
            rulesBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Chưa có quy tắc nào.</td></tr>';
            return;
        }
        rules.forEach((rule, index) => rulesBody.appendChild(createRuleRow(rule, index)));
        document.querySelectorAll('.auto-expand').forEach(ta => autoResize(ta));
        updateHiddenInput();
    }

    if (addBtn) addBtn.addEventListener('click', () => {
        const index = rules.length;
        rules.push({ keyword: '', response: '' });
        if (rules.length === 1) rulesBody.innerHTML = '';
        const row = createRuleRow(rules[index], index);
        rulesBody.appendChild(row);
        row.querySelector('.rule-keyword').focus();
        refreshControlButtons();
        updateHiddenInput();
    });

    if (rulesBody) {
        rulesBody.addEventListener('click', (e) => {
            const row = e.target.closest('.rule-row');
            if (!row) return;
            const index = parseInt(row.dataset.index);

            if (e.target.closest('.btn-remove-rule')) {
                rules.splice(index, 1);
                renderRules();
            } else if (e.target.closest('.btn-move-up') && index > 0) {
                [rules[index], rules[index-1]] = [rules[index-1], rules[index]];
                renderRules();
            } else if (e.target.closest('.btn-move-down') && index < rules.length - 1) {
                [rules[index], rules[index+1]] = [rules[index+1], rules[index]];
                renderRules();
            } else if (e.target.closest('.tag-shortcut')) {
                const tag = e.target.closest('.tag-shortcut').dataset.tag;
                const textarea = row.querySelector('.rule-response');
                const start = textarea.selectionStart;
                textarea.value = textarea.value.slice(0, start) + tag + textarea.value.slice(textarea.selectionEnd);
                textarea.focus();
                rules[index].response = textarea.value;
                updateHiddenInput();
                autoResize(textarea);
            }
        });

        rulesBody.addEventListener('input', (e) => {
            const row = e.target.closest('.rule-row');
            if (!row) return;
            const index = parseInt(row.dataset.index);
            if (e.target.classList.contains('rule-keyword')) rules[index].keyword = e.target.value;
            else if (e.target.classList.contains('rule-response')) {
                rules[index].response = e.target.value;
                autoResize(e.target);
            }
            updateHiddenInput();
        });
    }

    if (chatbotForm) chatbotForm.addEventListener('submit', () => updateHiddenInput());

    renderRules();
})();
</script>
@endsection
