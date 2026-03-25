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
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.returns.index') }}">Tất cả</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'pending' ? 'active alert-warning' : '' }}" href="{{ route('admin.returns.index', ['status' => 'pending']) }}">Chờ duyệt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'approved' ? 'active alert-info' : '' }}" href="{{ route('admin.returns.index', ['status' => 'approved']) }}">Chờ gửi hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'shipping' ? 'active alert-primary' : '' }}" href="{{ route('admin.returns.index', ['status' => 'shipping']) }}">Đang di chuyển</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'received' ? 'active alert-success' : '' }}" href="{{ route('admin.returns.index', ['status' => 'received']) }}">Đã nhận hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'completed' ? 'active alert-success' : '' }}" href="{{ route('admin.returns.index', ['status' => 'completed']) }}">Hoàn thành</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'rejected' ? 'active alert-danger' : '' }}" href="{{ route('admin.returns.index', ['status' => 'rejected']) }}">Từ chối</a>
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
                            <th>THAO TÁC</th>
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
                                    <span class="badge bg-light text-dark border">{{ $req->reason_text }}</span>
                                </td>
                                <td><span class="text-danger fw-bold">{{ number_format($req->refund_amount) }}đ</span></td>
                                <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                                <td>{!! $req->status_badge !!}</td>
                                <td>
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
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6>Thông tin khách hàng:</h6>
                                                    <p>Họ tên: <strong>{{ $req->user->name }}</strong><br>Email: {{ $req->user->email }}</p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <h6>Thông tin hoàn trả:</h6>
                                                    <p>Số tiền: <strong class="text-danger">{{ number_format($req->refund_amount) }}đ</strong><br>Lý do: {{ $req->reason_text }}</p>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <h6>Mô tả chi tiết:</h6>
                                                <div class="p-2 bg-light border rounded">{{ $req->description ?: 'Không có mô tả' }}</div>
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

                                            @if($req->admin_note)
                                            <div class="mb-3">
                                                <h6>Ghi chú/Phản hồi cửa hàng:</h6>
                                                <p class="p-2 border rounded text-danger bg-light">{{ $req->admin_note }}</p>
                                            </div>
                                            @endif

                                            @if($req->shipping_info)
                                            <div class="mb-3 p-3 border rounded border-warning bg-light-warning">
                                                <h6 class="text-warning fw-bold"><i class="bi bi-truck me-1"></i> Thông tin gửi hàng từ khách:</h6>
                                                <p class="mb-2">{{ $req->shipping_info }}</p>
                                                @if($req->shipping_proof)
                                                <div class="mt-2">
                                                    <a href="{{ asset($req->shipping_proof) }}" target="_blank">
                                                        <img src="{{ asset($req->shipping_proof) }}" class="img-thumbnail" style="max-width: 200px;">
                                                    </a>
                                                    <div class="small text-muted mt-1">Click vào ảnh để xem kích thước đầy đủ</div>
                                                </div>
                                                @endif
                                            </div>
                                            @endif

                                            <hr>

                                            <!-- Xử lý hành động -->
                                            @if($req->isPending())
                                                <div class="card border-warning mb-0 shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold text-warning mb-3">Xử lý yêu cầu mới</h6>
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <label class="small fw-bold">Phản hồi / Mã vận chuyển <span class="text-danger">*</span></label>
                                                                <button type="button" class="btn btn-xs btn-link p-0 text-decoration-none" onclick="document.getElementById('admin_note_{{ $req->id }}').value = 'Mã KS-RET-{{ $req->order_id }}-{{ strtoupper(Str::random(5)) }}\nVui lòng đóng gói và gửi về kho.'">Tạo mã vận chuyển mẫu</button>
                                                            </div>
                                                            <textarea id="admin_note_{{ $req->id }}" class="form-control" rows="3" placeholder="Nhập mã vận chuyển hoặc lý do từ chối..."></textarea>
                                                        </div>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button" class="btn btn-outline-danger" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.reject', $req->id) }}', 'Từ chối yêu cầu này?')">Từ chối</button>
                                                            <button type="button" class="btn btn-success" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.approve', $req->id) }}', 'Duyệt yêu cầu hoàn trả này?')">Đồng ý & Duyệt</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($req->isApproved())
                                                <div class="card border-info mb-0 shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold text-info mb-3">Trạng thái: Đã duyệt (Chờ khách gửi hàng)</h6>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-outline-info flex-grow-1" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.shipping', $req->id) }}', 'Xác nhận khách đã bắt đầu gửi hàng?')">Hàng đang di chuyển</button>
                                                            <button type="button" class="btn btn-success flex-grow-1" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.complete', $req->id) }}', 'Bỏ qua các bước và HOÀN TIỀN NGAY?')">Hoàn tiền ngay</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($req->isShipping())
                                                <div class="card border-primary mb-0 shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold text-primary mb-3">Trạng thái: Hàng đang trên đường về kho</h6>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-outline-primary flex-grow-1" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.received', $req->id) }}', 'Xác nhận đã nhận được hàng thực tế?')">Đã nhận được hàng</button>
                                                            <button type="button" class="btn btn-success flex-grow-1" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.complete', $req->id) }}', 'Hoàn tất và HOÀN TIỀN?')">Hoàn tiền</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($req->isReceived())
                                                <div class="card border-success mb-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <h6 class="fw-bold text-success mb-3">Hàng đã ở trong kho - Sẵn sàng hoàn tiền</h6>
                                                        <button type="button" class="btn btn-success w-100 py-2" onclick="submitReturnAction({{ $req->id }}, '{{ route('admin.returns.complete', $req->id) }}', 'Cộng tiền vào ví khách hàng và đóng yêu cầu?')">BẤM VÀO ĐÂY ĐỂ HOÀN TIỀN</button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-secondary mb-0">Yêu cầu này đã xử lý xong.</div>
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
                if (!noteArea.value.trim()) {
                    alert('Vui lòng nhập phản hồi xử lý hoặc mã vận chuyển!');
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
