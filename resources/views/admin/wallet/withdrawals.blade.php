@extends('layouts.admin')

@section('title', 'Yêu cầu Rút tiền ví')

@section('content')
<div class="container-fluid">

  <div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-box-arrow-up me-2"></i>Yêu cầu Rút tiền</h4>
    {{-- Filter --}}
    <div class="d-flex gap-2">
      @foreach(['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối', 'all' => 'Tất cả'] as $s => $label)
      <a href="{{ route('admin.wallet.withdrawals', ['status' => $s]) }}"
         class="btn btn-sm rounded-pill px-3 {{ $status === $s ? 'btn-dark' : 'btn-outline-secondary' }}">
        {{ $label }}
      </a>
      @endforeach
    </div>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  <div class="row g-4">
    {{-- Withdraw Requests --}}
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:0.88rem;">
              <thead style="background:#f8f9fa;">
                <tr>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Người dùng</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Số tiền rút</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Tài khoản ngân hàng nhận</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Cập nhật (Minh chứng)</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Ngày gửi</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Trạng thái</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @forelse($requests as $req)
                <tr class="border-top">
                  <td class="px-4 py-3">
                    <div class="fw-semibold">{{ optional($req->user)->name }}</div>
                    <div class="text-muted small">{{ optional($req->user)->email }}</div>
                    <div class="text-muted small">Số dư ví hiện tại: <strong class="{{ optional($req->user)->wallet_balance < $req->amount ? 'text-danger' : 'text-success' }}">{{ number_format(optional($req->user)->wallet_balance) }}đ</strong></div>
                  </td>
                  <td class="px-4 py-3 fw-bold text-danger">{{ number_format($req->amount) }}đ</td>
                  <td class="px-4 py-3">
                    @if($req->bankAccount)
                      <div class="fw-semibold">{{ $req->bankAccount->bank_name }}</div>
                      <div>STK: <code>{{ $req->bankAccount->account_number }}</code></div>
                      <div>Chủ thẻ: {{ Str::upper($req->bankAccount->account_name) }}</div>
                    @else
                      <span class="text-danger small">Tài khoản ngân hàng đã bị xoá</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($req->proof_image)
                    <a href="{{ Storage::url($req->proof_image) }}" target="_blank">
                      <img src="{{ Storage::url($req->proof_image) }}" alt="proof" style="height:40px;width:60px;object-fit:cover;border-radius:6px;">
                    </a>
                    @elseif($req->admin_note)
                    <span class="text-muted small">{{ Str::limit($req->admin_note, 30) }}</span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-muted">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                  <td class="px-4 py-3">
                    @if($req->isPending())
                      <span class="badge rounded-pill" style="background:#fff3cd;color:#856404;">Chờ duyệt</span>
                    @elseif($req->isApproved())
                      <span class="badge rounded-pill" style="background:#d1e7dd;color:#0a3622;">Đã chuyển khoản</span>
                    @else
                      <span class="badge rounded-pill bg-danger text-white">Từ chối</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($req->isPending())
                    <div class="d-flex gap-2 flex-wrap">
                      <button class="btn btn-sm btn-success rounded-pill px-3" onclick="openApproveModal({{ $req->id }})">
                        <i class="bi bi-check-lg me-1"></i>Đã CK
                      </button>
                      <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="openRejectModal({{ $req->id }})">
                        <i class="bi bi-x-lg me-1"></i>Từ chối
                      </button>
                    </div>
                    @else
                    <span class="text-muted small">
                         Xử lý bới {{ optional($req->processor)->name ?? 'System' }}<br>
                         lúc {{ optional($req->processed_at)->format('H:i d/m') }}
                    </span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:10px;color:#ccc;"></i>
                    Không có yêu cầu rút tiền nào.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($requests->hasPages())
          <div class="px-4 py-3">{{ $requests->links('pagination::bootstrap-5') }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Approve Modal --}}
<div class="modal fade" id="modalApprove" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-0">
        <h6 class="modal-title fw-bold"><i class="bi bi-check-circle me-2 text-success"></i>Xác nhận đã chuyển khoản</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formApprove" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <p class="small text-muted mb-3">Vui lòng upload ảnh minh chứng chuyển khoản (UNC) để người dùng có thể xem lại khi cần.</p>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Ảnh UNC (Tùy chọn)</label>
            <input type="file" name="proof_image" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
          <button type="submit" class="btn btn-success rounded-pill px-4">Xác nhận duyệt</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="modalReject" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-0">
        <h6 class="modal-title fw-bold"><i class="bi bi-x-circle me-2 text-danger"></i>Từ chối yêu cầu rút tiền</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formReject" method="POST">
        @csrf
        <div class="modal-body">
          <div class="alert alert-warning py-2 small">
            Tiền sẽ được <strong>hoàn lại</strong> vào ví của người dùng sau khi bạn từ chối.
          </div>
          <label class="form-label fw-semibold small">Lý do từ chối (bắt buộc)</label>
          <textarea name="admin_note" class="form-control" rows="3" placeholder="Nhập lý do..." required></textarea>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
          <button type="submit" class="btn btn-danger rounded-pill px-4">Xác nhận Từ chối</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openApproveModal(id) {
  var baseUrl = '{{ url("/admin/wallet/withdraw") }}';
  document.getElementById('formApprove').action = baseUrl + '/' + id + '/approve';
  new bootstrap.Modal(document.getElementById('modalApprove')).show();
}

function openRejectModal(id) {
  var baseUrl = '{{ url("/admin/wallet/withdraw") }}';
  document.getElementById('formReject').action = baseUrl + '/' + id + '/reject';
  new bootstrap.Modal(document.getElementById('modalReject')).show();
}
</script>
@endpush
@endsection
