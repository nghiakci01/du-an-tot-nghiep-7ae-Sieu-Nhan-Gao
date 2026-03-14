@extends('layouts.admin')

@section('title', 'Lịch sử thanh toán')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Lịch sử thanh toán</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Lịch sử thanh toán</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách Giao dịch</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-history.index') }}" method="GET" class="mb-3 d-flex align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <label for="payment_method" class="form-label mb-0 me-2 text-nowrap">P.thức:</label>
                            <select name="payment_method" id="payment_method" class="form-select form-select-sm w-auto me-2">
                                <option value="">Tất cả</option>
                                <option value="COD" {{ request('payment_method') == 'COD' ? 'selected' : '' }}>COD</option>
                                <option value="VNPAY" {{ request('payment_method') == 'VNPAY' ? 'selected' : '' }}>VNPAY</option>
                                <option value="MOMO" {{ request('payment_method') == 'MOMO' ? 'selected' : '' }}>MOMO</option>
                                <option value="ZALOPAY" {{ request('payment_method') == 'ZALOPAY' ? 'selected' : '' }}>ZALOPAY</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <label for="payment_status" class="form-label mb-0 me-2 text-nowrap">Trạng thái:</label>
                            <select name="payment_status" id="payment_status" class="form-select form-select-sm w-auto me-2">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center mt-2 mt-md-0">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Mã GD, Mã ĐH, Tên..." value="{{ request('search') }}" style="width: 200px;">
                            <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
                            @if(request()->hasAny(['payment_method', 'payment_status', 'search']))
                                <a href="{{ route('admin.payment-history.index') }}" class="btn btn-secondary btn-sm ms-2">Xóa lọc</a>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Khách hàng</th>
                                    <th>Mã Giao dịch (TNX)</th>
                                    <th>Phương thức</th>
                                    <th>T.Thái Thanh Toán</th>
                                    <th>Tổng tiền</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($payments) > 0)
                                    @foreach($payments as $payment) @php /** @var \App\Models\PaymentHistory $payment */ @endphp
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $payment) }}" class="fw-bold text-dark">#{{ $payment->id }}</a></td>
                                        <td>
                                            @if($payment->user)
                                                <strong>{{ $payment->user->name }}</strong><br>
                                            @else
                                                <strong class="text-secondary">{{ $payment->name }}</strong><br>
                                                <small>(Khách vãng lai)</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->transaction_id)
                                                <span class="text-primary fw-medium">{{ $payment->transaction_id }}</span>
                                            @else
                                                <span class="text-muted fst-italic">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $payment->payment_method }}</span>
                                        </td>
                                        <td>
                                            @if($payment->payment_status == 'paid')
                                                <span class="badge bg-success">Đã thanh toán</span>
                                            @elseif($payment->payment_status == 'pending')
                                                <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                            @elseif($payment->payment_status == 'refunded')
                                                <span class="badge bg-info">Đã hoàn tiền</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($payment->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-danger">{{ number_format($payment->final_total ?: $payment->total_price, 0, ',', '.') }} đ</td>
                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $payment) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="ti ti-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="ti ti-receipt text-muted" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0">Không có dữ liệu giao dịch nào.</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $payments->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
