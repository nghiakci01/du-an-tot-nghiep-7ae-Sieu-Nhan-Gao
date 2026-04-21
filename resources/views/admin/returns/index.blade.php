@extends('layouts.admin')

@section('title', 'Quản lý hoàn trả')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Yêu cầu hoàn trả cửa hàng</h3>
            </div>
            <div class="card-body">
                <!-- Tab điều hướng trạng thái -->
                @php
                    $returnReceiverName = $settings['return_receiver_name'] ?? ($settings['site_title'] ?? 'Elite');
                    $returnReceiverPhone = $settings['return_receiver_phone'] ?? ($settings['site_phone'] ?? '');
                    $returnReceiverAddress = $settings['return_receiver_address'] ?? ($settings['site_address'] ?? '');
                    $returnReceiverNote = $settings['return_receiver_note'] ?? 'Vui lòng ghi rõ mã đơn hàng và giữ lại biên nhận khi gửi trả hàng.';
                @endphp

                <div class="card border-info mb-4 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center p-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#ghnConfigCollapse">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 text-info me-2">
                                <i class="fas fa-truck-loading me-2"></i>Cấu hình GHN Hoàn trả
                            </h6>
                            <small class="text-muted d-none d-md-inline">(Click để mở rộng/thu gọn)</small>
                        </div>
                        <i class="fas fa-chevron-down text-info"></i>
                    </div>
                    <div class="collapse" id="ghnConfigCollapse">
                        <div class="card-body p-3">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Người nhận</label>
                                    <input type="text" name="return_receiver_name" class="form-control form-control-sm" value="{{ old('return_receiver_name', $returnReceiverName) }}" placeholder="Nhập tên người nhận hoàn trả">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Số điện thoại nhận hàng</label>
                                    <input type="text" name="return_receiver_phone" class="form-control form-control-sm" value="{{ old('return_receiver_phone', $returnReceiverPhone) }}" placeholder="Nhập số điện thoại nhận hàng">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Địa chỉ nhận hàng hoàn trả</label>
                                    <textarea name="return_receiver_address" class="form-control form-control-sm" rows="2" placeholder="Nhập địa chỉ chi tiết để khách gửi hàng trả lại">{{ old('return_receiver_address', $returnReceiverAddress) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Ghi chú cho khách</label>
                                    <textarea name="return_receiver_note" class="form-control form-control-sm" rows="1" placeholder="Nhập hướng dẫn thêm cho khách">{{ old('return_receiver_note', $returnReceiverNote) }}</textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-sm btn-info text-white shadow-sm">
                                        <i class="fas fa-save me-1"></i> Lưu cấu hình
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.returns.index') }}">Tất cả</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'processing' ? 'active alert-info' : '' }}" href="{{ route('admin.returns.index', ['status' => 'processing']) }}">Admin đã nhận</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'received' ? 'active alert-dark' : '' }}" href="{{ route('admin.returns.index', ['status' => 'received']) }}">Đã nhận được hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'completed' ? 'active alert-success' : '' }}" href="{{ route('admin.returns.index', ['status' => 'completed']) }}">Xác nhận đã chuyển khoản</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'rejected' ? 'active alert-danger' : '' }}" href="{{ route('admin.returns.index', ['status' => 'rejected']) }}">Bị từ chối</a>
                    </li>
                </ul>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>MÃ ĐH</th>
                            <th>KHÁCH HÀNG</th>
                            <th>LÝ DO</th>
                            <th>TIỀN HOÀN</th>
                            <th>NGÀY YC</th>
                            <th>TRẠNG THÁI</th>
                            <th class="sticky-action-column">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $req->order_id) }}" class="fw-bold">#{{ str_pad($req->order_id, 6, '0', STR_PAD_LEFT) }}</a></td>
                                <td>
                                    <strong>{{ $req->user->name }}</strong><br>
                                    <small class="text-muted">{{ $req->user->email }}</small>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">{{ $req->reason_type_text }}</span>
                                </td>
                                <td><span class="text-danger fw-bold">{{ number_format($req->refund_amount) }}đ</span></td>
                                <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="badge {{ $req->status_badge }}">{{ $req->status_text }}</span></td>
                                <td class="sticky-action-column">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $req->id }}">
                                        <i class="fas fa-eye"></i> Xử lý
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Chi tiết & Xử lý -->
                            <div class="modal fade" id="detailModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chi tiết yêu cầu #{{ $req->id }} (Đơn hàng #{{ $req->order_id }})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3 bg-light p-3 border rounded shadow-sm mx-0">
                                                <div class="col-md-6 border-end">
                                                    <h6 class="fw-bold text-primary"><i class="fas fa-university me-1"></i> Thông tin khách hàng:</h6>
                                                    <p class="mb-1">Họ tên: <strong>{{ $req->user->name }}</strong></p>
                                                    <p class="mb-0">Email: {{ $req->user->email }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-danger text-end"><i class="fas fa-money-bill-wave me-1"></i> Thông tin hoàn trả:</h6>
                                                    <p class="mb-1 text-end">Số tiền: <strong class="fs-5 text-danger">{{ number_format($req->refund_amount) }}đ</strong></p>
                                                    <p class="mb-1 text-end">Lý do: <span class="badge bg-white text-dark border">{{ $req->reason_text }}</span></p>
                                                    <p class="mb-0 text-end">Phương thức: <span class="badge bg-info text-white">{{ $req->return_method_text }}</span></p>
                                                </div>
                                            </div>

                                            {{-- Bank Info Section --}}
                                            @if($req->bank_name || $req->account_number)
                                            <div class="mb-3 p-3 border rounded border-info bg-light-info" style="background-color: #f0faff;">
                                                <h6 class="text-info fw-bold mb-3 d-flex align-items-center">
                                                    <i class="fas fa-piggy-bank me-2"></i> THÔNG TIN NHẬN TIỀN HOÀN (BANK TRANSFER)
                                                </h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block">Ngân hàng:</small>
                                                        <div class="d-flex align-items-center">
                                                            @if($req->bank_bin)
                                                                <img src="https://api.vietqr.io/img/{{ $req->bank_bin }}.png" style="height: 20px;" class="me-1" onerror="this.style.display='none'">
                                                            @endif
                                                            <strong class="text-uppercase">{{ $req->bank_name }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block">Số tài khoản:</small>
                                                        <div class="d-flex align-items-center">
                                                            <strong class="fs-6">{{ $req->account_number }}</strong>
                                                            <button type="button" class="btn btn-xs btn-outline-info ms-2" onclick="navigator.clipboard.writeText('{{ $req->account_number }}').then(() => alert('Đã chép STK!'))">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block">Chủ tài khoản:</small>
                                                        <strong class="text-uppercase">{{ $req->account_name }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="mb-3">
                                                <h6>Mô tả chi tiết:</h6>
                                                <div class="p-2 bg-light border rounded">{{ $req->note ?: 'Không có mô tả' }}</div>
                                            </div>

                                            @if($req->images)
                                            <div class="mb-3">
                                                <h6>Hình ảnh minh chứng:</h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($req->images as $img)
                                                        <a href="{{ asset('storage/'.$img) }}" target="_blank">
                                                            <img src="{{ asset('storage/'.$img) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            @if($req->videos && count($req->videos) > 0)
                                            <div class="mb-3">
                                                <h6><i class="bi bi-camera-reels me-1"></i>Video minh chứng:</h6>
                                                @foreach($req->videos as $vid)
                                                <div class="mb-2">
                                                    <video controls style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 1px solid #ddd;">
                                                        <source src="{{ asset('storage/'.$vid) }}" type="video/mp4">
                                                        Trình duyệt không hỗ trợ video.
                                                    </video>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif

                                            @if($req->admin_note)
                                            <div class="mb-3">
                                                <h6>Ghi chú/Phản hồi cửa hàng:</h6>
                                                <p class="p-2 border rounded text-danger bg-light">{{ $req->admin_note }}</p>
                                            </div>
                                            @endif

                                            @if($req->shipping_info || $req->shipping_proof || $req->video_proof)
                                            <div class="mb-3 p-3 border rounded border-warning bg-light-warning">
                                                <h6 class="text-warning fw-bold"><i class="bi bi-truck me-1"></i> Thông tin gửi hàng hoàn trả:</h6>
                                                @if($req->shipping_info)
                                                    <p class="mb-2">{{ $req->shipping_info }}</p>
                                                @endif
                                                @if($req->shipping_proof && is_array($req->shipping_proof))
                                                <div class="mt-2">
                                                    <label class="small fw-bold d-block mb-1 text-warning">Ảnh minh chứng:</label>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($req->shipping_proof as $proof)
                                                        <a href="{{ asset($proof) }}" target="_blank">
                                                            <img src="{{ asset($proof) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                                        </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @elseif($req->shipping_proof && !is_array($req->shipping_proof))
                                                <div class="mt-2">
                                                    <label class="small fw-bold d-block mb-1 text-warning">Ảnh minh chứng:</label>
                                                    <a href="{{ asset($req->shipping_proof) }}" target="_blank">
                                                        <img src="{{ asset($req->shipping_proof) }}" class="img-thumbnail" style="max-width: 200px;">
                                                    </a>
                                                </div>
                                                @endif

                                                @if($req->video_proof && is_array($req->video_proof))
                                                <div class="mt-2 text-warning">
                                                    <label class="small fw-bold d-block mb-1">Video minh chứng:</label>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($req->video_proof as $vProof)
                                                        <video width="140" height="80" controls class="rounded border border-warning shadow-sm">
                                                            <source src="{{ asset($vProof) }}" type="video/mp4">
                                                        </video>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @elseif($req->video_proof && !is_array($req->video_proof))
                                                <div class="mt-2 text-warning">
                                                    <label class="small fw-bold d-block mb-1">Video minh chứng:</label>
                                                    <video width="260" height="150" controls class="rounded border border-warning">
                                                        <source src="{{ asset($req->video_proof) }}" type="video/mp4">
                                                    </video>
                                                </div>
                                                @endif
                                            </div>
                                            @endif

                                            <hr>

                                            <!-- Xử lý hành động (Simplified 3-Step) -->
                                            @if($req->isCompleted())
                                                <div class="alert alert-success mb-0 text-center py-3">
                                                    <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                                    <strong>XÁC NHẬN ĐÃ CHUYỂN KHOẢN</strong><br>
                                                    <small class="text-muted">Quy trình hoàn trả đã kết thúc thành công.</small>
                                                </div>
                                            @elseif($req->isRejected())
                                                <div class="alert alert-danger mb-0">Yêu cầu này đã bị từ chối.</div>
                                            @elseif(in_array($req->status, [\App\Models\OrderReturnRequest::STATUS_PENDING, \App\Models\OrderReturnRequest::STATUS_APPROVED, \App\Models\OrderReturnRequest::STATUS_SHIPPING_BACK]))
                                                <div class="card border-info mb-0 shadow-sm">
                                                    <div class="card-body">
                                                        @if($req->status === 'pending')
                                                            <h6 class="fw-bold text-warning mb-3"><i class="fas fa-exclamation-circle me-1"></i> Bước 1: Tiếp nhận yêu cầu</h6>
                                                        @else
                                                            <h6 class="fw-bold text-info mb-3"><i class="fas fa-truck-loading me-1"></i> Bước 2: Kiểm tra hàng hoàn tại kho</h6>
                                                            
                                                            @if($req->status === 'approved')
                                                                <div class="alert alert-warning py-2 small mb-3 border-warning">
                                                                    <i class="fas fa-hourglass-half me-1"></i> <strong>Đang chờ khách hàng gửi hàng hoàn trả.</strong>
                                                                </div>
                                                            @endif
                                                            
                                                            @php
                                                                $hasShipmentProof = !empty($req->shipping_proof) || !empty($req->video_proof);
                                                            @endphp
                                                            
                                                            @if($hasShipmentProof)
                                                            <div class="mb-3 p-2 border rounded bg-light border-info">
                                                                <div class="small fw-bold mb-2 text-info">Minh chứng khách gửi:</div>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @if($req->shipping_proof)
                                                                        @php $proofs = is_array($req->shipping_proof) ? $req->shipping_proof : [$req->shipping_proof]; @endphp
                                                                        @foreach($proofs as $p)
                                                                        <div class="position-relative">
                                                                            <a href="{{ asset($p) }}" target="_blank">
                                                                                <img src="{{ asset($p) }}" class="img-thumbnail shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                                                                <span class="position-absolute bottom-0 end-0 badge bg-dark opacity-75" style="font-size: 0.6rem;">Ảnh</span>
                                                                            </a>
                                                                        </div>
                                                                        @endforeach
                                                                    @endif
                                                                    @if($req->video_proof)
                                                                        @php $vProofs = is_array($req->video_proof) ? $req->video_proof : [$req->video_proof]; @endphp
                                                                        @foreach($vProofs as $vp)
                                                                        <div class="position-relative" style="width: 120px;">
                                                                            <video width="120" height="80" class="rounded border shadow-sm" style="object-fit: cover;">
                                                                                <source src="{{ asset($vp) }}" type="video/mp4">
                                                                            </video>
                                                                            <a href="{{ asset($vp) }}" target="_blank" class="position-absolute top-50 start-50 translate-middle text-white shadow-lg">
                                                                                <i class="fas fa-play-circle fa-2x"></i>
                                                                            </a>
                                                                            <span class="position-absolute bottom-0 end-0 badge bg-dark opacity-75" style="font-size: 0.6rem;">Video</span>
                                                                        </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @endif

                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <label class="small fw-bold">Phản hồi / Ghi chú <span class="text-muted fw-normal">(Bắt buộc khi Từ chối)</span></label>
                                                                <div class="d-flex align-items-center gap-2" id="ghn_badge_container_{{ $req->id }}">
                                                                    @if($req->tracking_code)
                                                                        <span class="badge bg-success" id="badge_ghn_{{ $req->id }}"><i class="fas fa-check-circle me-1"></i>GHN: {{ $req->tracking_code }}</span>
                                                                    @endif
                                                                    {{-- Chỉ hiện nút GHN sau khi đã xác nhận tiếp nhận --}}
                                                                    @if($req->status !== 'pending')
                                                                        <button type="button" class="btn btn-xs btn-outline-primary px-2" id="btn_generate_ghn_{{ $req->id }}" onclick="generateGhnTrackingCode({{ $req->id }})">
                                                                            <i class="fas fa-shipping-fast me-1"></i> {{ $req->tracking_code ? 'Tạo lại mã GHN' : 'Tạo mã GHN thực tế' }}
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <textarea id="admin_note_{{ $req->id }}" class="form-control" rows="2" placeholder="Nhập mã vận chuyển hoặc lý do xử lý...">{{ $req->admin_note }}</textarea>
                                                        </div>

                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-outline-danger" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.reject', $req->id) }}', 'Từ chối yêu cầu này?')">Từ chối</button>
                                                            
                                                            @if($req->status === 'pending')
                                                                <button type="button" class="btn btn-warning flex-grow-1 fw-bold" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.approve', $req->id) }}', 'Xác nhận Admin đã tiếp nhận yêu cầu này?')">
                                                                    <i class="fas fa-check-circle me-1"></i> XÁC NHẬN TIẾP NHẬN
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-primary flex-grow-1 fw-bold" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.received', $req->id) }}', 'Xác nhận ĐÃ NHẬN ĐƯỢC HÀNG tại kho?')">
                                                                    <i class="fas fa-box me-1"></i> ĐÃ NHẬN ĐƯỢC HÀNG
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($req->status === \App\Models\OrderReturnRequest::STATUS_RECEIVED)
                                                <div class="card border-success mb-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <h6 class="fw-bold text-success mb-3"><i class="fas fa-box-open me-1"></i> Hàng đã ở trong kho - Sẵn sàng hoàn tất</h6>
                                                        <button type="button" class="btn btn-success w-100 py-3 fw-bold" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.complete', $req->id) }}', 'Cập nhật trạng thái và HOÀN TẤT chuyển khoản cho khách?')">
                                                            <i class="fas fa-money-check-alt me-2"></i> XÁC NHẬN ĐÃ CHUYỂN KHOẢN
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Không có yêu cầu nào!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $requests->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Hidden form moved inside content for Pjax --}}
    <form id="globalReturnForm" method="POST" style="display: none;" class="no-pjax" data-pjax="false">
        @csrf
        <input type="hidden" name="admin_note" id="global_admin_note">
    </form>

    <script>
        console.log('Return management script loaded/re-loaded.');
        
        function submitReturnAction(requestId, actionUrl, confirmMessage) {
            console.log('Action triggered:', actionUrl, 'for ID:', requestId);
            
            if (!confirm(confirmMessage)) return;

            const form = document.getElementById('globalReturnForm');
            const noteArea = document.getElementById('admin_note_' + requestId);
            const globalInput = document.getElementById('global_admin_note');

            if (!form) {
                console.error('globalReturnForm not found!');
                alert('Có lỗi hệ thống: Không tìm thấy form xử lý.');
                return;
            }

            form.action = actionUrl;
            
            if (noteArea) {
                // Chỉ bắt buộc nhập ghi chú khi Từ chối
                if (actionUrl.includes('/reject') && !noteArea.value.trim()) {
                    alert('Vui lòng nhập lý do từ chối!');
                    noteArea.focus();
                    return;
                }
                globalInput.value = noteArea.value;
            } else {
                globalInput.value = '';
            }

            console.log('Submitting form to:', actionUrl);
            form.submit();
        }

        function generateGhnTrackingCode(requestId) {
            const btn = document.getElementById('btn_generate_ghn_' + requestId);
            const noteArea = document.getElementById('admin_note_' + requestId);
            
            if (!confirm('Hệ thống sẽ gọi API GHN để tạo vận đơn thu hồi hàng thực tế. Tiếp tục?')) return;

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Đang tạo...';

            fetch(`/admin/returns/${requestId}/generate-ghn`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    noteArea.value = 'Mã vận đơn GHN: ' + data.tracking_code + '\nVui lòng đóng gói và mang ra bưu cục GHN gần nhất để gửi trả hàng.';
                    alert('Đã tạo mã vận đơn GHN thành công: ' + data.tracking_code);
                    
                    // Update UI if possible
                    let badge = document.getElementById('badge_ghn_' + requestId);
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.id = 'badge_ghn_' + requestId;
                        badge.className = 'badge bg-success';
                        const container = document.getElementById('ghn_badge_container_' + requestId);
                        if (container) {
                            container.prepend(badge);
                        }
                    }
                    badge.innerHTML = '<i class="fas fa-check-circle me-1"></i>GHN: ' + data.tracking_code;
                } else {
                    let errorMsg = data.message || 'Không thể tạo mã vận đơn.';
                    if (errorMsg.includes('Chưa cấu hình địa chỉ')) {
                        errorMsg += '\n\nVui lòng điền "Thông tin nhận hàng hoàn trả" ở bảng phía trên đầu trang trước khi tiếp tục.';
                        // Scroll to top
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                    alert('Lỗi: ' + errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gọi API tạo mã.');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        }

        // Cleanup Modal Backdrop
        if (typeof clearBackdrop !== 'function') {
            window.clearBackdrop = function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css({'padding-right': '', 'overflow': ''});
            };
        }
        
        // Immediate cleanup on load
        clearBackdrop();
        
        // Re-bind to pjax:end if not already
        $(document).off('pjax:end.returns').on('pjax:end.returns', clearBackdrop);
    </script>
</div>
@endsection
