@extends('layouts.public')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Order Details Custom Styles */
    .dashboard-content {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .order-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .order-meta-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        height: 100%;
    }
    .nav-link.active {
        background-color: #f8f9fa !important;
        color: #0d6efd !important;
        border-right: 3px solid #0d6efd;
    }
</style>
@endsection

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li><a href="{{ route('account.index') }}">{{ __('messages.my_account') }}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.order_details') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!-- my account start  -->
    <section class="main_content_area my-5">
        <div class="container">
            <div class="account_dashboard">
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <!-- Sidebar -->
                        @include('frontend.account.partials.sidebar')
                    </div>
                    <div class="col-md-9 col-lg-9">
                        <div class="dashboard_content">
                            <div class="order-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h3 class="mb-1">{{ __('messages.order') }} #{{ $order->id }}</h3>
                                    <p class="text-muted mb-0">
                                        {{ __('messages.date') }}: {{ $order->created_at->format('M d, Y H:i') }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('account.index') }}#orders" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> {{ __('messages.back_to_orders') }}
                                    </a>
                                    <button onclick="window.print()" class="btn btn-outline-primary">
                                        <i class="bi bi-printer"></i> {{ __('messages.print_receipt') }}
                                    </button>
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <div class="order-meta-item">
                                        <h6 class="text-muted text-uppercase small mb-3">{{ __('messages.status') }}</h6>
                                        @php
                                            $statusClass = match($order->status) {
                                                'COMPLETED' => 'bg-success',
                                                'CANCELLED' => 'bg-danger',
                                                'SHIPPED' => 'bg-info text-dark',
                                                'CONFIRMED' => 'bg-primary',
                                                default => 'bg-warning text-dark'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} fs-6 px-3 py-2 rounded-pill">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="order-meta-item">
                                        <h6 class="text-muted text-uppercase small mb-3">{{ __('messages.payment_method') }}</h6>
                                        <p class="fw-bold mb-0">
                                            @if($order->payment_method == 'COD')
                                                <i class="bi bi-cash me-2"></i> {{ __('messages.cash_on_delivery') }}
                                            @elseif($order->payment_method == 'BANK_TRANSFER')
                                                <i class="bi bi-bank me-2"></i> {{ __('messages.bank_transfer') }}
                                            @else
                                                {{ $order->payment_method }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="order-meta-item">
                                        <h6 class="text-muted text-uppercase small mb-3">{{ __('messages.shipping_address') }}</h6>
                                        <p class="mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $order->shipping_address }}</p>
                                        @if($order->note)
                                            <div class="mt-2 text-muted small fst-italic">
                                                <i class="bi bi-chat-quote me-1"></i> "{{ $order->note }}"
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <h4 class="mb-4">{{ __('messages.order_details') }}</h4>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.product') }}</th>
                                            <th class="text-center">{{ __('messages.quantity') }}</th>
                                            <th class="text-end">{{ __('messages.price') }}</th>
                                            <th class="text-end">{{ __('messages.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('frontend-assets/img/product/product-1.jpg') }}" alt="Default" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1 text-dark">{{ $item->product->name }}</h6>
                                                        @if($item->variant)
                                                            <div class="small text-muted">
                                                                {{ __('messages.size') }}: <strong>{{ $item->variant->sizeRelationship->name ?? $item->variant->size ?? 'N/A' }}</strong> | 
                                                                {{ __('messages.color') }}: <strong>{{ $item->variant->colorRelationship->name ?? $item->variant->color ?? 'N/A' }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            <td class="text-end fw-bold">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top-2">
                                        <tr>
                                            <td colspan="3" class="text-end text-muted">{{ __('messages.subtotal') }}</td>
                                            <td class="text-end">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end text-muted">{{ __('messages.shipping') }}</td>
                                            <td class="text-end text-success">{{ __('messages.free') }}</td>
                                        </tr>
                                        @if($order->coupon_id)
                                            <tr>
                                                <td colspan="3" class="text-end text-muted">{{ __('messages.discount') }}</td>
                                                <td class="text-end text-danger">-{{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}đ</td>
                                            </tr>
                                        @endif
                                        <tr class="fs-5">
                                            <td colspan="3" class="text-end fw-bold">{{ __('messages.total') }}</td>
                                            <td class="text-end fw-bold text-primary">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if($order->status == 'PENDING')
                                <div class="mt-4 pt-3 border-top text-end">
                                    <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_cancel') }}');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            {{ __('messages.cancel_order') }}
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- my account end   -->
@endsection
