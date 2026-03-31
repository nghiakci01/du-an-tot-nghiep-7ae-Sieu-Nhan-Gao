@extends('layouts.admin')

@section('title', 'Đơn hàng cần giao')

@section('content')
<div class="page-header py-3 bg-primary text-white mb-4 rounded shadow-sm">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-8">
                <h5 class="text-white mb-0"><i class="feather icon-truck me-2"></i>Chuyến hàng của tôi</h5>
            </div>
            <div class="col-4 text-end">
                <span class="badge bg-white text-primary rounded-pill">{{ $orders->total() }} Đơn</span>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-0">
    <!-- Filter Tabs -->
    <div class="mb-4 overflow-auto text-nowrap pb-2" style="-webkit-overflow-scrolling: touch;">
        <a href="{{ route('staff.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill me-2">Tất cả</a>
        <a href="{{ route('staff.orders.index', ['status' => \App\Models\Order::STATUS_CONFIRMED]) }}" class="btn btn-sm {{ request('status') == \App\Models\Order::STATUS_CONFIRMED ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill me-2">Cần giao</a>
        <a href="{{ route('staff.orders.index', ['status' => \App\Models\Order::STATUS_SHIPPED]) }}" class="btn btn-sm {{ request('status') == \App\Models\Order::STATUS_SHIPPED ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill me-2">Đang giao</a>
        <a href="{{ route('staff.orders.index', ['status' => \App\Models\Order::STATUS_COMPLETED]) }}" class="btn btn-sm {{ request('status') == \App\Models\Order::STATUS_COMPLETED ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill me-2">Đã giao</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3">{{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 shadow-sm mb-3">{{ session('warning') }}</div>
    @endif

    <div class="row g-3">
        @forelse($orders as $order)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold text-primary">#{{ $order->id }}</span>
                        <span class="badge {{ $order->status_badge }}">{{ $order->status_text }}</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start">
                            <i class="feather icon-user text-muted me-3 mt-1 fs-5"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $order->user ? $order->user->name : ($order->name ?? 'Khách vãng lai') }}</h6>
                                <p class="text-primary mb-0"><i class="feather icon-phone me-1"></i> {{ $order->user ? ($order->user->phone ?? 'N/A') : ($order->phone ?? 'N/A') }}</p>
                            </div>
                        </div>
                        <div class="mb-3 d-flex align-items-start">
                            <i class="feather icon-map-pin text-danger me-3 mt-1 fs-5"></i>
                            <div class="small">
                                {{ $order->shipping_address }}
                            </div>
                        </div>
                        <hr class="my-3 opacity-50">
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-muted">Tổng COD:</span>
                            <span class="fw-bold fs-5 text-danger">{{ number_format($order->final_total) }}đ</span>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 p-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('staff.orders.show', $order->id) }}" class="btn btn-outline-info btn-sm w-100 py-2">
                                    <i class="feather icon-eye me-1"></i> Chi tiết
                                </a>
                            </div>
                            <div class="col-6">
                                @if($order->status === \App\Models\Order::STATUS_CONFIRMED)
                                    <form action="{{ route('staff.orders.accept', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm w-100 py-2">
                                            <i class="feather icon-check me-1"></i> Nhận đơn
                                        </button>
                                    </form>
                                @elseif($order->status === \App\Models\Order::STATUS_SHIPPED)
                                    <form action="{{ route('staff.orders.complete', $order->id) }}" method="POST" class="mb-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100 py-2" onclick="return confirm('Xác nhận đã giao hàng thành công?')">
                                            <i class="feather icon-check-circle me-1"></i> Hoàn thành
                                        </button>
                                    </form>
                                @endif
                            </div>
                            @if($order->status === \App\Models\Order::STATUS_SHIPPED)
                                <div class="col-12">
                                    <button type="button" class="btn btn-danger btn-sm w-100 py-2" data-bs-toggle="modal" data-bs-target="#failModal{{ $order->id }}">
                                        <i class="feather icon-x-circle me-1"></i> Báo giao hàng thất bại
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fail Modal -->
            <div class="modal fade" id="failModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('staff.orders.fail', $order->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title text-white">Lý do giao hàng thất bại</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Ghi chú lý do:</label>
                                    <textarea name="delivery_note" class="form-control" rows="4" placeholder="VD: Khách hàng không nghe máy, Sai địa chỉ..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-danger">Xác nhận Thất bại</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="feather icon-package mb-3 fs-1 d-block opacity-25"></i>
                        <p class="mb-0">Hiện chưa có đơn hàng nào được gán cho bạn.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection

@section('scripts')
<style>
    .card { transition: transform 0.2s; }
    .card:active { transform: scale(0.98); }
</style>
@endsection
