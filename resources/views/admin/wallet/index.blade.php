@extends('layouts.admin')

@section('title', 'Quản lý Ví người dùng')

@section('content')
<div class="container-fluid">

  <div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2"></i>Quản lý Ví người dùng</h4>
    {{-- Filter --}}
    <div class="d-flex gap-2">
      @foreach(['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối', 'all' => 'Tất cả'] as $s => $label)
      <a href="{{ route('admin.wallet.index', ['status' => $s]) }}"
         class="btn btn-sm rounded-pill px-3 {{ $status === $s ? 'btn-dark' : 'btn-outline-secondary' }}">
        {{ $label }}
      </a>
      @endforeach
    </div>
  </div>


  <div class="row g-4">
    {{-- Top-up Requests --}}
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold mb-0">Yêu cầu nạp tiền</h6>
          {{-- Manual Adjust --}}
          <button class="btn btn-sm btn-outline-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalManualAdjust">
            <i class="bi bi-pencil-square me-1"></i>Điều chỉnh thủ công
          </button>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:0.88rem;">
              <thead style="background:#f8f9fa;">
                <tr>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Người dùng</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Số tiền</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Ngân hàng</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Nội dung CK</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Minh chứng</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Ngày gửi</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Trạng thái</th>
                  <th class="px-4 py-3 text-muted fw-semibold" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;">Hành động</th>
                </tr>
              </thead>
              <tbody>
                @forelse($requests as $req)
                <tr class="border-top">
                  <td class="px-4 py-3">
                    <div class="fw-semibold">{{ $req->user->name }}</div>
                    <div class="text-muted small">{{ $req->user->email }}</div>
                    <div class="text-muted small">Ví: <strong>{{ number_format($req->user->wallet_balance) }}đ</strong></div>
                  </td>
                  <td class="px-4 py-3 fw-bold text-success">{{ number_format($req->amount) }}đ</td>
                  <td class="px-4 py-3">
                    @if($req->dest_bank_name)
                      <div class="fw-bold">{{ $req->dest_bank_name }}</div>
                      <div class="text-muted small">{{ $req->dest_account_number }}</div>
                    @else
                      {{ $req->bank_name ?: '—' }}
                    @endif
                  </td>
                  <td class="px-4 py-3 text-muted">{{ $req->transfer_note ?: '—' }}</td>
                  <td class="px-4 py-3">
                    @if($req->proof_image)
                    <a href="{{ Storage::url($req->proof_image) }}" target="_blank">
                      <img src="{{ Storage::url($req->proof_image) }}" alt="proof" style="height:40px;width:60px;object-fit:cover;border-radius:6px;">
                    </a>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-muted">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                  <td class="px-4 py-3">
                    @if($req->isPending())
                      <span class="badge rounded-pill" style="background:#fff3cd;color:#856404;">Chờ duyệt</span>
                    @elseif($req->isApproved())
                      <span class="badge rounded-pill" style="background:#d1e7dd;color:#0a3622;">Đã duyệt</span>
                    @else
                      <span class="badge rounded-pill bg-danger text-white">Từ chối</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($req->isPending())
                    <div class="d-flex gap-2 flex-wrap">
                      <form action="{{ route('admin.wallet.approve', $req) }}" method="POST"
                            onsubmit="return confirm('Duyệt và cộng {{ number_format($req->amount) }}đ vào ví {{ $req->user->name }}?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                          <i class="bi bi-check-lg me-1"></i>Duyệt
                        </button>
                      </form>
                      <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                              onclick="openRejectModal({{ $req->id }})">
                        <i class="bi bi-x-lg me-1"></i>Từ chối
                      </button>
                    </div>
                    @else
                    <span class="text-muted small">Đã xử lý</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:10px;color:#ccc;"></i>
                    Không có yêu cầu nào.
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

{{-- Reject Modal --}}
<div class="modal fade" id="modalReject" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-0">
        <h6 class="modal-title fw-bold"><i class="bi bi-x-circle me-2 text-danger"></i>Từ chối yêu cầu</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formReject" method="POST">
        @csrf
        <div class="modal-body">
          <label class="form-label fw-semibold small">Lý do từ chối (tùy chọn)</label>
          <textarea name="admin_note" class="form-control" rows="3" placeholder="Nhập lý do..."></textarea>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
          <button type="submit" class="btn btn-danger rounded-pill px-4">Từ chối</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Manual Adjust Modal --}}
<div class="modal fade" id="modalManualAdjust" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-0">
        <h6 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Điều chỉnh ví thủ công</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.wallet.manual-adjust') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold small">Người dùng <span class="text-danger">*</span></label>
            <select name="user_id" class="form-select" required>
              <option value="">-- Chọn user --</option>
              @foreach(\App\Models\User::where('role', 'user')->orderBy('name')->get() as $u)
              <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }}) — {{ number_format($u->wallet_balance) }}đ</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Loại <span class="text-danger">*</span></label>
            <select name="type" class="form-select" required>
              <option value="credit">➕ Cộng tiền</option>
              <option value="debit">➖ Trừ tiền</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Số tiền (VND) <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control" min="1000" step="1000" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Mô tả <span class="text-danger">*</span></label>
            <input type="text" name="description" class="form-control" placeholder="Lý do điều chỉnh" required>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
          <button type="submit" class="btn btn-dark rounded-pill px-5">Xác nhận</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openRejectModal(id) {
  var baseUrl = '{{ url("/admin/wallet") }}';
  document.getElementById('formReject').action = baseUrl + '/' + id + '/reject';
  new bootstrap.Modal(document.getElementById('modalReject')).show();
}
</script>
@endpush
@endsection
