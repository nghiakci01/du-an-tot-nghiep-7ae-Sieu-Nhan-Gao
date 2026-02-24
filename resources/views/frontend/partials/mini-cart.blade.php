{{-- Mini Cart Widget --}}
@php
    $cart = session()->get('cart', []);
    $cartCount = 0;
    $cartTotal = 0;
    foreach($cart as $item) {
        $cartCount += $item['quantity'];
        $cartTotal += $item['price'] * $item['quantity'];
    }
@endphp

<div class="cart_link">
    <a href="{{ route('cart.index') }}">
        <i class="fa fa-shopping-basket"></i>
        <span id="cart-count">{{ $cartCount }}</span> {{ __('messages.product') }}
    </a>
    
    <!--mini cart-->
    <div class="mini_cart">
        @if(count($cart) > 0)
            <div class="cart_items_wrapper" style="max-height: 335px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                @foreach($cart as $id => $details)
                <div class="cart_item {{ $loop->first ? 'top' : ($loop->last ? 'bottom' : '') }}">
                    <div class="cart_img" style="width: 60px; height: 60px; flex: 0 0 60px;">
                        <a href="{{ route('product.detail', $details['slug']) }}">
                            <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : asset('frontend-assets/img/s-product/product.jpg') }}" 
                                 alt="{{ $details['name'] }}" 
                                 style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #f1f1f1;">
                        </a>
                    </div>
                    <div class="cart_info" style="padding-left: 15px;">
                        <a href="{{ route('product.detail', $details['slug']) }}" style="font-size: 13px;">{{ Str::limit($details['name'], 25) }}</a>
                        <span style="font-size: 12px;">{{ $details['quantity'] }}x {{ number_format($details['price']) }} VND</span>
                    </div>
                    <div class="cart_remove">
                        <a href="javascript:void(0)" class="mini-cart-remove" data-id="{{ $id }}">
                            <i class="ion-android-close"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <style>
                .cart_items_wrapper::-webkit-scrollbar {
                    width: 5px;
                }
                .cart_items_wrapper::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }
                .cart_items_wrapper::-webkit-scrollbar-thumb {
                    background: #ffd541;
                    border-radius: 5px;
                }
                .cart_items_wrapper::-webkit-scrollbar-thumb:hover {
                    background: #555;
                }
                .mini_cart .cart_item {
                    padding: 10px 0;
                    display: flex;
                    align-items: center;
                }
            </style>
            
            <div class="cart__table">
                <table>
                    <tbody>
                        <tr>
                            <td class="text-left">{{ __('messages.subtotal') }}:</td>
                            <td class="text-right">{{ number_format($cartTotal) }} VND</td>
                        </tr>
                        <tr>
                            <td class="text-left">{{ __('messages.total') }}:</td>
                            <td class="text-right">{{ number_format($cartTotal) }} VND</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="cart_button view_cart">
                <a href="{{ route('cart.index') }}">{{ __('messages.view_cart') }}</a>
            </div>
            <div class="cart_button checkout">
                <a href="{{ route('checkout.index') }}">{{ __('messages.checkout') }}</a>
            </div>
        @else
            <div class="cart_item">
                <div class="cart_info text-center">
                    <p class="text-muted">{{ __('messages.cart_empty') }}</p>
                </div>
            </div>
        @endif
    </div>
    <!--mini cart end-->
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Mini cart remove item
    $('.mini-cart-remove').click(function(e) {
        e.preventDefault();
        var itemId = $(this).data('id');
        
            if(confirm('Remove this product from cart?')) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE',
                        id: itemId
                    },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'An error occurred.');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});
</script>
@endpush
