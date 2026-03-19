@extends('layouts.admin')

@section('title', 'Quản lý Yêu cầu Hoàn hàng')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">Yêu cầu hoàn trả cửa hàng</h1>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'all' ? 'active' : '' }}" href="{{ route('admin.returns.index', ['status' => 'all']) }}">Tất cả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning {{ $tab == 'pending' ? 'active font-weight-bold border-bottom-warning' : '' }}" href="{{ route('admin.returns.index', ['status' => 'pending']) }}">Chờ duyệt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-info {{ $tab == 'approved' ? 'active font-weight-bold border-bottom-info' : '' }}" href="{{ route('admin.returns.index', ['status' => 'approved']) }}">Chờ gửi hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary {{ $tab == 'shipping' ? 'active font-weight-bold border-bottom-primary' : '' }}" href="{{ route('admin.returns.index', ['status' => 'shipping']) }}">Đang di chuyển</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark {{ $tab == 'received' ? 'active font-weight-bold border-bottom-dark' : '' }}" href="{{ route('admin.returns.index', ['status' => 'received']) }}">Đã nhận hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success {{ $tab == 'completed' ? 'active font-weight-bold border-bottom-success' : '' }}" href="{{ route('admin.returns.index', ['status' => 'completed']) }}">Hoàn thành</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger {{ $tab == 'rejected' ? 'active font-weight-bold border-bottom-danger' : '' }}" href="{{ route('admin.returns.index', ['status' => 'rejected']) }}">Từ chối</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Lý do</th>
                            <th>Tiền hoàn</th>
                            <th>Ngày YC</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $req->order_id) }}">#{{ str_pad($req->order_id, 6, '0', STR_PAD_LEFT) }}</a>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $req->user->name }}</div>
                                    <div class="small text-muted">{{ $req->user->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $req->reason }}</span>
                                </td>
                                <td class="text-danger fw-bold">
                                    {{ number_format($req->refund_amount) }}đ
                                </td>
                                <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $req->status_badge }}">
                                        {{ $req->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $req->id }}" title="Chi tiết / Xử lý">
                                        <i class="fas fa-eye"></i> Xử lý
                                    </button>
                                </td>
                            </tr>

                            <!-- Detail & Action Modal -->
                            <div class="modal fade" id="detailModal{{ $req->id }}" tabindex="-1" aria-labelledby="detailLabel{{ $req->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailLabel{{ $req->id }}">Chi tiết Yêu cầu Hoàn trả #{{ $req->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Mã đơn hàng:</strong> <a href="{{ route('admin.orders.show', $req->order_id) }}">#{{ str_pad($req->order_id, 6, '0', STR_PAD_LEFT) }}</a><br>
                                                    <strong>Lý do:</strong> {{ $req->reason }}<br>
                                                    <strong>Tiền cần hoàn:</strong> <span class="text-danger fw-bold">{{ number_format($req->refund_amount) }}đ</span>
                                                </div>
                                                <div class="col-md-6 text-md-end">
                                                    <strong>Khách hàng:</strong> {{ $req->user->name }}<br>
                                                    <strong>Ngày gửi:</strong> {{ $req->created_at->format('d/m/Y H:i') }}<br>
                                                    <strong>Trạng thái:</strong> <span class="badge {{ $req->status_badge }}">{{ $req->status_text }}</span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Mô tả chi tiết từ khách:</strong>
                                                <p class="p-2 bg-light border rounded">{{ $req->note ?: 'Không có' }}</p>
                                            </div>
                                            
                                            @if($req->images && is_array($req->images) && count($req->images) > 0)
                                            <div class="mb-4">
                                                <strong>Ảnh từ khách hàng:</strong>
                                                <div class="d-flex flex-wrap gap-2 mt-2">
                                                    @foreach($req->images as $img)
                                                        <a href="{{ asset('storage/'.$img) }}" target="_blank">
                                                            <img src="{{ asset('storage/'.$img) }}" class="img-thumbnail" style="width:100px; height:100px; object-fit:cover;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            @if($req->admin_note)
                                            <div class="mb-3">
                                                <strong>Ghi chú từ cửa hàng (Mã vận chuyển/Lý do):</strong>
                                                <p class="p-2 bg-light border rounded text-danger">{{ $req->admin_note }}</p>
                                            </div>
                                            @endif

                                            <hr>

                                            <!-- Form Hành Động -->
                                            @if($req->isPending())
                                                <h6><strong>Xử lý Yêu cầu (Duyệt cho trả hàng / Từ chối)</strong></h6>
                                                <form method="POST" id="actionForm{{$req->id}}">
                                                    @csrf
                                                    <div class="col-md-6 mb-3">
                                                        <label class="text-muted small d-block">TRẠNG THÁI</label>
                                                        <span class="badge {{ $req->status_badge }}">{{ $req->status_text }}</span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <label class="form-label mb-0">Phản hồi / Mã vận chuyển Gửi trả <span class="text-danger">*</span></label>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('admin_note_{{ $req->id }}').value = 'Mã KS-RET-{{ $req->order_id }}-{{ strtoupper(Str::random(5)) }}\nVui lòng gửi hàng về địa chỉ kho, ghi rõ mã này trên kiện hàng.'">
                                                                <i class="fas fa-magic"></i> Tạo mã tự động
                                                            </button>
                                                        </div>
                                                        <textarea id="admin_note_{{ $req->id }}" name="admin_note" class="form-control" rows="3" required placeholder="Ví dụ mã vận chuyển GHTK, hướng dẫn đóng gói... hoặc lý do từ chối"></textarea>
                                                        <small class="text-muted">Khách hàng sẽ nhìn thấy nội dung này.</small>
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="submit" formaction="{{ route('admin.returns.reject', $req->id) }}" class="btn btn-outline-danger" onclick="return confirm('Xác nhận từ chối yêu cầu?');">Từ chối Yêu cầu</button>
                                                        <button type="submit" formaction="{{ route('admin.returns.approve', $req->id) }}" class="btn btn-success" onclick="return confirm('Chấp nhận cho trả hàng?');">Đồng ý (Chờ gửi hàng)</button>
                                                    </div>
                                                </form>
                                            @elseif($req->isApproved())
                                                <h6><strong>Xử lý tiếp theo</strong></h6>
                                                <div class="alert alert-info">Bạn đã duyệt yêu cầu. Bây giờ bạn có thể theo dõi hàng gửi về hoặc <b>Hoàn tiền ngay</b> nếu muốn.</div>
                                                <div class="d-flex flex-column gap-2 mt-3">
                                                    <form action="{{ route('admin.returns.shipping', $req->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-info w-100" onclick="return confirm('Xác nhận hàng đã bắt đầu di chuyển?');">
                                                            <i class="fas fa-truck me-1"></i> 1. Đã bắt đầu di chuyển
                                                        </button>
                                                    </form>
                                                    <div class="text-center small text-muted">Hoặc</div>
                                                    <form action="{{ route('admin.returns.complete', $req->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Xác nhận HOÀN TIỀN NGAY vào ví khách hàng?');">
                                                            <i class="fas fa-money-bill-wave me-1"></i> 2. Hoàn tiền ngay (Bỏ qua các bước sau)
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($req->isShipping())
                                                <h6><strong>Hàng đang trên đường về kho</strong></h6>
                                                <div class="alert alert-primary">Bạn đang theo dõi hàng gửi trả. Bấm nút dưới đây khi đã nhận hàng hoặc hoàn tiền luôn.</div>
                                                <div class="d-flex flex-column gap-2 mt-3">
                                                    <form action="{{ route('admin.returns.received', $req->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-primary w-100" onclick="return confirm('Xác nhận đã nhận hàng tại kho?');">
                                                            <i class="fas fa-warehouse me-1"></i> 1. Xác nhận đã nhận hàng tại kho
                                                        </button>
                                                    </form>
                                                    <div class="text-center small text-muted">Hoặc</div>
                                                    <form action="{{ route('admin.returns.complete', $req->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Xác nhận HOÀN TIỀN NGAY vào ví khách hàng?');">
                                                            <i class="fas fa-money-bill-wave me-1"></i> 2. Hoàn tiền ngay
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($req->isReceived())
                                                <h6><strong>Hàng đã ở trong kho</strong></h6>
                                                <div class="alert alert-success">Hàng đã về kho thành công. Hãy bấm nút dưới đây để cộng tiền hoàn lại vào ví khách.</div>
                                                <form action="{{ route('admin.returns.complete', $req->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success w-100 py-3" onclick="return confirm('Xác nhận hoàn tiền vào ví khách?');">
                                                        <i class="fas fa-check-circle me-2 fa-lg"></i> XÁC NHẬN HOÀN TIỀN
                                                    </button>
                                                </form>
                                            @elseif($req->isCompleted() || $req->isRejected())
                                                <div class="alert alert-secondary mb-0">Yêu cầu này đã được xử lý xong bởi {{ $req->processor->name ?? 'Admin' }} lúc {{ $req->processed_at ? $req->processed_at->format('d/m/Y H:i') : '' }}.</div>
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
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $requests->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
