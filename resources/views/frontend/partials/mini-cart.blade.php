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
                        <span style="font-size: 12px;">{{ $details['quantity'] }}x {{ number_format($details['price']) }} đ</span>
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
if (typeof miniCartInitialized === 'undefined') {
    var miniCartInitialized = true;
    $(document).ready(function() {
        // Mini cart remove item - using delegated listener for durability
        $(document).on('click', '.mini-cart-remove', function(e) {
            e.preventDefault();
            var itemId = $(this).data('id');
            
            console.log('Mini-cart removing item:', itemId);
            
            if(confirm('Xóa sản phẩm này khỏi giỏ hàng?')) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE',
                        id: itemId
                    },
                    success: function(response) {
                        console.log('Mini-cart remove success:', response);
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error('Mini-cart remove failed:', xhr.responseText);
                        let errorMsg = 'Lỗi hệ thống';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) errorMsg = response.message;
                        } catch(e) {}

                        if(confirm("Lỗi khi xóa bằng AJAX (" + errorMsg + "). Thử dùng phương pháp xóa dự phòng?")) {
                            window.location.href = '{{ route('cart.remove') }}?id=' + itemId;
                        }
                    }
                });
            }
        });
    });
}
</script>
@endpush
