@extends('layouts.public')

@section('title', __('messages.order_success') . ' | FashionStore')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* Success Page Styles */
.success-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

.success-header {
    text-align: center;
    margin-bottom: 40px;
}

.success-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    animation: scaleIn 0.5s ease-out;
}

.success-icon i {
    font-size: 50px;
    color: white;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.order-timeline {
    display: flex;
    justify-content: space-between;
    margin: 30px 0;
    position: relative;
}

.order-timeline::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    z-index: 0;
}

.timeline-step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 1;
}

.timeline-dot {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.timeline-step.active .timeline-dot {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.timeline-step.active .timeline-label {
    color: #667eea;
    font-weight: 600;
}

.order-summary-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 25px;
    margin-bottom: 20px;
}

.order-summary-card h5 {
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.product-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.product-item:last-child {
    border-bottom: none;
}

.product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 15px;
}

.product-details {
    flex: 1;
}

.product-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.product-variant {
    font-size: 0.9rem;
    color: #666;
}

.product-price {
    font-weight: 600;
    color: #667eea;
}

.order-totals {
    border-top: 2px solid #f0f0f0;
    padding-top: 15px;
    margin-top: 15px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.total-row.final {
    font-size: 1.2rem;
    font-weight: 700;
    color: #667eea;
    border-top: 2px solid #667eea;
    padding-top: 15px;
    margin-top: 10px;
}

.bank-transfer-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 25px;
    margin: 20px 0;
}

.bank-transfer-info h6 {
    color: white;
    margin-bottom: 15px;
}

.bank-detail {
    background: rgba(255,255,255,0.1);
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.copy-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 5px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
}

.copy-btn:hover {
    background: rgba(255,255,255,0.3);
}

.info-card {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.action-buttons .btn {
    flex: 1;
}

@media (max-width: 768px) {
    .order-timeline {
        flex-direction: column;
    }
    
    .order-timeline::before {
        width: 2px;
        height: 100%;
        left: 20px;
        top: 0;
    }
    
    .timeline-step {
        text-align: left;
        padding-left: 60px;
        margin-bottom: 20px;
    }
    
    .timeline-dot {
        position: absolute;
        left: 0;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .product-item {
        flex-direction: column;
        text-align: center;
    }
    
    .product-image {
        margin: 0 0 10px 0;
    }
}
</style>
@endsection

@section('content')
<div class="success-container">
    <!-- Success Header -->
    <div class="success-header">
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h2 class="fw-bold mb-2">{{ __('messages.order_success_title') }}</h2>
        <p class="text-muted">{{ __('messages.order_success_message') }}</p>
        <h4 class="text-primary mt-3">{{ __('messages.order_number') }}: #{{ $order->id }}</h4>
    </div>

    <!-- Order Timeline -->
    <div class="order-summary-card">
        <h5><i class="bi bi-clock-history"></i> {{ __('messages.order_status') }}</h5>
        <div class="order-timeline">
            <div class="timeline-step active">
                <div class="timeline-dot">
                    <i class="bi bi-check"></i>
                </div>
                <div class="timeline-label">{{ __('messages.ordered') }}</div>
            </div>
            <div class="timeline-step">
                <div class="timeline-dot">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="timeline-label">{{ __('messages.processing') }}</div>
            </div>
            <div class="timeline-step">
                <div class="timeline-dot">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="timeline-label">{{ __('messages.shipping') }}</div>
            </div>
            <div class="timeline-step">
                <div class="timeline-dot">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="timeline-label">{{ __('messages.delivered') }}</div>
            </div>
        </div>
        <div class="info-card">
            <i class="bi bi-calendar-check"></i> <strong>{{ __('messages.estimated_delivery') }}:</strong> 
            {{ $estimatedDeliveryMin->format('d/m/Y') }} - {{ $estimatedDeliveryMax->format('d/m/Y') }}
        </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary-card">
        <h5><i class="bi bi-bag-check"></i> {{ __('messages.order_details') }}</h5>
        
        @foreach($order->items as $item)
        <div class="product-item">
            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image">
            <div class="product-details">
                <div class="product-name">{{ $item->product->name }}</div>
                <div class="product-variant">
                    {{ __('messages.size') }}: {{ $item->variant->size }} | 
                    {{ __('messages.color') }}: {{ $item->variant->color }}
                </div>
                <div class="text-muted">{{ __('messages.quantity') }}: {{ $item->quantity }}</div>
            </div>
            <div class="product-price">
                {{ number_format($item->price * $item->quantity) }} đ
            </div>
        </div>
        @endforeach

        <div class="order-totals">
            <div class="total-row">
                <span>{{ __('messages.subtotal') }}:</span>
                <span>{{ number_format($order->total_price) }} đ</span>
            </div>
            
            @if($order->discount_amount > 0)
            <div class="total-row text-success">
                <span>{{ __('messages.discount') }} ({{ $order->coupon_code }}):</span>
                <span>-{{ number_format($order->discount_amount) }} đ</span>
            </div>
            @endif
            
            <div class="total-row final">
                <span>{{ __('messages.total') }}:</span>
                <span>{{ number_format($order->final_total ?? $order->total_price) }} đ</span>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="order-summary-card">
        <h5><i class="bi bi-person-circle"></i> {{ __('messages.customer_info') }}</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="info-card">
                    <strong><i class="bi bi-geo-alt"></i> {{ __('messages.shipping_address') }}:</strong><br>
                    {{ $order->shipping_address }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <strong><i class="bi bi-credit-card"></i> {{ __('messages.payment_method') }}:</strong><br>
                    @if($order->payment_method === 'COD')
                        <span class="badge bg-warning">{{ __('messages.cod') }}</span>
                    @else
                        <span class="badge bg-info">{{ __('messages.bank_transfer') }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        @if($order->note)
        <div class="info-card mt-2">
            <strong><i class="bi bi-sticky"></i> {{ __('messages.note') }}:</strong><br>
            {{ $order->note }}
        </div>
        @endif
    </div>

    <!-- Bank Transfer Instructions -->
    @if($order->payment_method === 'BANK_TRANSFER')
    <div class="bank-transfer-info">
        <h6><i class="bi bi-bank"></i> {{ __('messages.bank_transfer_instructions') }}</h6>
        <div class="bank-detail">
            <strong>{{ __('messages.bank_name') }}:</strong> Vietcombank
        </div>
        <div class="bank-detail">
            <strong>{{ __('messages.account_number') }}:</strong> 9999999999
            <button class="copy-btn float-end" onclick="copyToClipboard('9999999999')">
                <i class="bi bi-clipboard"></i> {{ __('messages.copy') }}
            </button>
        </div>
        <div class="bank-detail">
            <strong>{{ __('messages.account_holder') }}:</strong> NGUYEN VAN A
        </div>
        <div class="bank-detail">
            <strong>{{ __('messages.transfer_content') }}:</strong> THANHTOAN DH{{ $order->id }}
            <button class="copy-btn float-end" onclick="copyToClipboard('THANHTOAN DH{{ $order->id }}')">
                <i class="bi bi-clipboard"></i> {{ __('messages.copy') }}
            </button>
        </div>
        <div class="alert alert-light mt-3 mb-0">
            <i class="bi bi-info-circle"></i> {{ __('messages.bank_transfer_note') }}
        </div>
    </div>
    @endif

    <!-- Next Steps -->
    <div class="order-summary-card">
        <h5><i class="bi bi-list-check"></i> {{ __('messages.next_steps') }}</h5>
        <ul class="list-unstyled">
            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> {{ __('messages.next_step_1') }}</li>
            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> {{ __('messages.next_step_2') }}</li>
            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> {{ __('messages.next_step_3') }}</li>
            @if($order->payment_method === 'BANK_TRANSFER')
            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> {{ __('messages.next_step_bank') }}</li>
            @endif
        </ul>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        @if(Auth::check())
        <a href="{{ route('account.index') }}" class="btn btn-primary">
            <i class="bi bi-box-seam"></i> {{ __('messages.view_orders') }}
        </a>
        @endif
        <a href="{{ route('shop') }}" class="btn btn-outline-primary">
            <i class="bi bi-shop"></i> {{ __('messages.continue_shopping') }}
        </a>
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="bi bi-printer"></i> {{ __('messages.print_receipt') }}
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('{{ __("messages.copied_to_clipboard") }}');
    }, function() {
        alert('{{ __("messages.copy_failed") }}');
    });
}
</script>
@endsection
