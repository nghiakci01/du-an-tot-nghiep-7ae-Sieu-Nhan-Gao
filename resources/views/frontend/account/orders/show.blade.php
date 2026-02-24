@extends('layouts.public')

@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area other_bread">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{ route('welcome') }}">home</a></li>
                        <li>/</li>
                        <li><a href="{{ route('account.index') }}">my account</a></li>
                        <li>/</li>
                        <li>order details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->

<!-- my account start  -->
<section class="main_content_area">
    <div class="container">
        <div class="account_dashboard">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    @include('frontend.account.partials.sidebar')
                </div>
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <div class="dashboard_content">
                        <h3>Order Details (#{{ $order->id }})</h3>

                        {{-- Flash Messages --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="order_details">
                            <p><strong>Order Number:</strong> #{{ $order->id }}</p>
                            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                            <p><strong>Status:</strong> {{ $order->status_text }}</p>
                            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>

                            <h4>Order Items</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product ? $item->product->name : 'Product Unavailable' }} <br>
                                                <small>{{ $item->variant ? $item->variant->name : '' }}</small>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price) }}₫</td>
                                            <td>{{ number_format($item->total) }}₫</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                            <td>{{ number_format($order->total_price) }}₫</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td><strong>{{ number_format($order->total_price) }}₫</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h4>Shipping Address</h4>
                            <p>{{ $order->shipping_address ?? $user->address ?? 'N/A' }}</p>
                        </div>

                        <div class="mt-4">
                            <h4>Order History</h4>
                            <div class="timeline">
                                <ul class="list-unstyled">
                                    @forelse($order->histories as $history)
                                        <li class="mb-3">
                                            <p class="mb-1 text-muted"><small>{{ $history->created_at->format('H:i d/m/Y') }}</small></p>
                                            <p class="mb-1">
                                                <strong>{{ $history->user ? $history->user->name : 'System' }}</strong>:
                                                Changed status from <span class="badge bg-secondary">{{ $history->previous_status }}</span>
                                                to <span class="badge bg-primary">{{ $history->new_status }}</span>
                                            </p>
                                            @if($history->note)
                                                <p class="mb-0 text-muted"><em>{{ $history->note }}</em></p>
                                            @endif
                                        </li>
                                    @empty
                                        <li>No history available.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        {{-- ===== REVIEW SECTION (chỉ hiện khi đơn hàng hoàn thành) ===== --}}
                        @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                            <div class="mt-5" id="review-section">
                                <h4 style="border-bottom: 2px solid #ef233c; padding-bottom: 8px; color: #333;">
                                    <i class="fa fa-star" style="color:#f39c12;"></i> Đánh giá sản phẩm
                                </h4>
                                <p class="text-muted" style="font-size:14px;">Chia sẻ cảm nhận của bạn về các sản phẩm đã mua.</p>

                                @foreach($order->items as $item)
                                    @if($item->product)
                                        @php $existingReview = $userReviews->get($item->product_id); @endphp
                                        <div class="card mb-4" style="border:1px solid #eee; border-radius:8px; overflow:hidden;">
                                            {{-- Product Header --}}
                                            <div class="card-header" style="background:#f8f9fa; padding:12px 20px; display:flex; align-items:center; gap:15px;">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                         alt="{{ $item->product->name }}"
                                                         style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                                                @endif
                                                <div>
                                                    <strong style="font-size:15px;">{{ $item->product->name }}</strong>
                                                    @if($item->variant)
                                                        <br><small class="text-muted">{{ $item->variant->name }}</small>
                                                    @endif
                                                </div>
                                                @if($existingReview)
                                                    <span class="badge ms-auto" style="background:#28a745; font-size:12px; padding:6px 12px; margin-left:auto;">
                                                        <i class="fa fa-check"></i> Đã đánh giá
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="card-body" style="padding:20px;">
                                                @if($existingReview)
                                                    {{-- Hiển thị review đã có --}}
                                                    <div style="background:#f9f9f9; border-radius:6px; padding:15px;">
                                                        <div style="display:flex; align-items:center; gap:4px; margin-bottom:8px;">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fa {{ $i <= $existingReview->rating ? 'fa-star' : 'fa-star-o' }}"
                                                                   style="color:#f39c12; font-size:18px;"></i>
                                                            @endfor
                                                            <span class="ms-2 text-muted" style="font-size:13px; margin-left:8px;">{{ $existingReview->created_at->format('d/m/Y') }}</span>
                                                        </div>
                                                        <p style="margin:0; color:#555;">{{ $existingReview->comment }}</p>
                                                    </div>
                                                @else
                                                    {{-- Form đánh giá --}}
                                                    <form action="{{ route('product.review.store', $item->product_id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label style="font-weight:600; margin-bottom:8px; display:block;">Đánh giá của bạn:</label>
                                                            <div class="star-rating-order">
                                                                @for($s = 5; $s >= 1; $s--)
                                                                    <input type="radio"
                                                                           id="star{{ $s }}_p{{ $item->product_id }}"
                                                                           name="rating"
                                                                           value="{{ $s }}"
                                                                           {{ $s === 5 ? 'required' : '' }}>
                                                                    <label for="star{{ $s }}_p{{ $item->product_id }}" title="{{ $s }} sao">
                                                                        <i class="fa fa-star"></i>
                                                                    </label>
                                                                @endfor
                                                            </div>
                                                            <div class="likert-label-order" style="display:none; margin-top:6px; font-size:13px; font-weight:600; color:#ef233c; min-height:18px;"></div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label style="font-weight:600; margin-bottom:6px; display:block;">Nhận xét:</label>
                                                            <textarea name="comment"
                                                                      rows="3"
                                                                      required
                                                                      placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."
                                                                      style="width:100%; border:1px solid #ddd; border-radius:6px; padding:10px; font-size:14px; resize:vertical;"></textarea>
                                                        </div>
                                                        <button type="submit"
                                                                style="background:#ef233c; color:#fff; border:none; padding:8px 24px; border-radius:6px; font-size:14px; font-weight:600; cursor:pointer;">
                                                            <i class="fa fa-paper-plane"></i> Gửi đánh giá
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        {{-- ===== END REVIEW SECTION ===== --}}

                        @if($order->status === \App\Models\Order::STATUS_PENDING)
                            <div class="mt-4">
                                <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Cancel Order</button>
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

@section('scripts')
<script>
    // Likert scale labels
    var likertLabels = {
        1: 'Rất không hài lòng',
        2: 'Không hài lòng',
        3: 'Bình thường',
        4: 'Hài lòng',
        5: 'Rất hài lòng'
    };

    $(document).ready(function () {
        // Star hover - show likert label
        $('.star-rating-order label').on('mouseenter', function() {
            var val = $(this).prev('input').val();
            if (val) {
                $(this).closest('.mb-3').find('.likert-label-order').text(likertLabels[val]).show();
            }
        });

        // Mouse leave star area - show selected or hide
        $('.star-rating-order').on('mouseleave', function() {
            var selected = $(this).find('input:checked').val();
            var label = $(this).closest('.mb-3').find('.likert-label-order');
            if (selected) {
                label.text(likertLabels[selected]).show();
            } else {
                label.hide();
            }
        });

        // Star selected - keep label
        $('.star-rating-order input').on('change', function() {
            var val = $(this).val();
            $(this).closest('.mb-3').find('.likert-label-order').text(likertLabels[val]).show();
        });
    });
</script>
<style>
    .star-rating-order {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 4px;
        margin-bottom: 4px;
    }
    .star-rating-order input { display: none; }
    .star-rating-order label {
        font-size: 28px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.15s;
        margin: 0;
    }
    .star-rating-order label:hover i,
    .star-rating-order label:hover ~ label i,
    .star-rating-order input:checked ~ label i {
        color: #f39c12 !important;
    }
    .star-rating-order label i { pointer-events: none; }
</style>
@endsection
@endsection
