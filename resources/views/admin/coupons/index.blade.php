@extends('layouts.admin')

@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Mã Giảm Giá</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Mã Giảm Giá</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Danh sách Mã Giảm Giá</h5>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
            </div>
            <div class="card-body">
                <!-- Search and Filters -->
                <form action="{{ route('admin.coupons.index') }}" method="GET" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search m-0"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm kiếm mã code..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Bị vô hiệu hóa</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Lọc</button>
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light px-3" title="Xóa bộ lọc"><i class="ti ti-x m-0"></i></a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Mã Voucher</th>
                                <th style="width: 120px;">Trạng thái</th>
                                <th style="width: 180px;">Giá trị</th>
                                <th style="width: 180px;">Đơn tối thiểu</th>
                                <th style="width: 180px;">Lượt dùng</th>
                                <th style="width: 200px;">Thời hạn</th>
                                <th class="sticky-action-column" style="width: 100px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-muted border">#{{ $coupon->id }}</span>
                                        <code class="fw-bold px-2 py-1 bg-light border rounded text-dark" style="font-size: 0.95rem;">{{ $coupon->code }}</code>
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 copy-code-btn" data-code="{{ $coupon->code }}" title="Sao chép mã">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                    @if($coupon->description)
                                        <div class="mt-1"><small class="text-muted d-block" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $coupon->description }}</small></div>
                                    @endif
                                </td>
                                <td>{!! $coupon->getStatusBadge() !!}</td>
                                <td>
                                    @if($coupon->type === 'percentage')
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-info">{{ $coupon->getFormattedValue() }}</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">Phần trăm</small>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-primary">{{ $coupon->getFormattedValue() }}</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">Cố định</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->min_order_amount)
                                        <span class="text-dark">{{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ</span>
                                    @else
                                        <span class="text-muted">Không giới hạn</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column" style="min-width: 120px;">
                                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.75rem;">
                                            <span class="text-dark fw-bold">{{ $coupon->used_count }}</span>
                                            <span class="text-muted">Tối đa: {{ $coupon->usage_limit ?: '∞' }}</span>
                                        </div>
                                        @php
                                            $percent = $coupon->usage_limit ? min(100, ($coupon->used_count / $coupon->usage_limit) * 100) : 0;
                                            $color = $percent >= 90 ? 'bg-danger' : ($percent >= 70 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <div class="progress" style="height: 6px; border-radius: 10px;">
                                            <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ $coupon->usage_limit ? $percent : ($coupon->used_count > 0 ? 100 : 0) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($coupon->start_date || $coupon->end_date)
                                        <div class="d-flex flex-column" style="font-size: 0.8rem;">
                                            <span class="text-success"><i class="ti ti-calendar-event me-1"></i>{{ $coupon->start_date ? $coupon->start_date->format('d/m/Y') : 'Bắt đầu' }}</span>
                                            <span class="text-danger"><i class="ti ti-calendar-off me-1"></i>{{ $coupon->end_date ? $coupon->end_date->format('d/m/Y') : 'Không hết hạn' }}</span>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted border">Vô thời hạn</span>
                                    @endif
                                </td>
                                <td class="sticky-action-column text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning btn-sm shadow-none p-1 px-2" title="Chỉnh sửa">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline no-pjax" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-none p-1 px-2" title="Xóa">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="ti ti-file-off text-muted mb-2 d-inline-block" style="font-size: 2rem;"></i>
                                    <p class="text-muted">Không tìm thấy mã giảm giá nào phù hợp với bộ lọc.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $coupons->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).off('click.copyCode').on('click.copyCode', '.copy-code-btn', function() {
    const code = $(this).data('code');
    const button = this;

    function showSuccess() {
        const icon = button.querySelector('i');
        const originalClass = icon.className;
        icon.className = 'ti ti-check text-success';
        setTimeout(function() {
            icon.className = originalClass;
        }, 1500);
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(showSuccess).catch(err => {
            const textarea = document.createElement('textarea');
            textarea.value = code;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showSuccess();
        });
    } else {
        const textarea = document.createElement('textarea');
        textarea.value = code;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showSuccess();
    }
});
</script>
@endsection
