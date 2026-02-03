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
        <span id="cart-count">{{ $cartCount }}</span> sản phẩm
    </a>
    
    <!--mini cart-->
    <div class="mini_cart">
        @if(count($cart) > 0)
            @foreach($cart as $id => $details)
            <div class="cart_item {{ $loop->first ? 'top' : ($loop->last ? 'bottom' : '') }}">
                <div class="cart_img">
                    <a href="{{ route('product.detail', $details['slug']) }}">
                        <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : asset('frontend-assets/img/s-product/product.jpg') }}" alt="{{ $details['name'] }}">
                    </a>
                </div>
                <div class="cart_info">
                    <a href="{{ route('product.detail', $details['slug']) }}">{{ Str::limit($details['name'], 30) }}</a>
                    <span>{{ $details['quantity'] }}x {{ number_format($details['price']) }} đ</span>
                </div>
                <div class="cart_remove">
                    <a href="javascript:void(0)" class="mini-cart-remove" data-id="{{ $id }}">
                        <i class="ion-android-close"></i>
                    </a>
                </div>
            </div>
            @endforeach
            
            <div class="cart__table">
                <table>
                    <tbody>
                        <tr>
                            <td class="text-left">Tạm tính:</td>
                            <td class="text-right">{{ number_format($cartTotal) }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-left">Tổng:</td>
                            <td class="text-right">{{ number_format($cartTotal) }} đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="cart_button view_cart">
                <a href="{{ route('cart.index') }}">Xem giỏ hàng</a>
            </div>
            <div class="cart_button checkout">
                <a href="{{ route('checkout.index') }}">Thanh toán</a>
            </div>
        @else
            <div class="cart_item">
                <div class="cart_info text-center">
                    <p class="text-muted">Giỏ hàng trống</p>
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
        
        if(confirm('Xóa sản phẩm này khỏi giỏ hàng?')) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: itemId
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            });
        }
    });
});
</script>
@endpush
