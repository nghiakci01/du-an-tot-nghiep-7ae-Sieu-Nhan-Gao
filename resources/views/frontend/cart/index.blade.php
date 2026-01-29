@extends('layouts.public')

@section('title', 'Giỏ hàng | FashionStore')

@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area other_bread">
    <div class="container">   
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{ route('welcome') }}">Trang chủ</a></li>
                        <li>/</li>
                        <li>Giỏ hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>         
</div>
<!--breadcrumbs area end-->

<!--shopping cart area start -->
<div class="shopping_cart_area">
    <div class="container">  
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
        <form action="#"> 
            <div class="row">
                <div class="col-12">
                    <div class="table_desc">
                        <div class="cart_page table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="product_remove">Xóa</th>
                                        <th class="product_thumb">Hình ảnh</th>
                                        <th class="product_name">Sản phẩm</th>
                                        <th class="product-price">Giá</th>
                                        <th class="product_quantity">Số lượng</th>
                                        <th class="product_total">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('cart') as $id => $details)
                                    <tr data-id="{{ $id }}">
                                        <td class="product_remove">
                                            <a href="javascript:void(0)" class="remove-from-cart">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                        <td class="product_thumb">
                                            <a href="{{ route('product.detail', $details['slug']) }}">
                                                <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : asset('frontend-assets/img/s-product/product.jpg') }}" alt="{{ $details['name'] }}" style="width: 100px; height: 100px; object-fit: cover;">
                                            </a>
                                        </td>
                                        <td class="product_name">
                                            <a href="{{ route('product.detail', $details['slug']) }}">{{ $details['name'] }}</a>
                                            <br>
                                            <small class="text-muted">Size: {{ $details['size'] }} | Màu: {{ $details['color'] }}</small>
                                        </td>
                                        <td class="product-price">{{ number_format($details['price']) }} đ</td>
                                        <td class="product_quantity">
                                            <input min="1" max="100" value="{{ $details['quantity'] }}" type="number" class="quantity update-cart">
                                        </td>
                                        <td class="product_total">{{ number_format($details['price'] * $details['quantity']) }} đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>   
                        </div>  
                        <div class="cart_submit">
                            <a href="{{ route('shop') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Tiếp tục mua sắm
                            </a>
                            <button type="button" class="btn btn-danger" id="clear-cart">
                                <i class="fa fa-trash"></i> Xóa giỏ hàng
                            </button>
                        </div>      
                    </div>
                </div>
            </div>
            
            <!--coupon code area start-->
            <div class="coupon_area">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="coupon_code left">
                            <h3>Mã giảm giá</h3>
                            <div class="coupon_inner">   
                                <p>Nhập mã giảm giá nếu bạn có.</p>                                
                                <input placeholder="Mã giảm giá" type="text" disabled>
                                <button type="button" disabled>Áp dụng</button>
                                <small class="text-muted d-block mt-2">
                                    <i class="fa fa-info-circle"></i> Tính năng sắp ra mắt
                                </small>
                            </div>    
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="coupon_code right">
                            <h3>Tổng đơn hàng</h3>
                            <div class="coupon_inner">
                               <div class="cart_subtotal">
                                   <p>Tạm tính</p>
                                   <p class="cart_amount">{{ number_format($total) }} đ</p>
                               </div>
                               <div class="cart_subtotal ">
                                   <p>Vận chuyển</p>
                                   <p class="cart_amount"><span>Miễn phí</span></p>
                               </div>

                               <div class="cart_subtotal">
                                   <p>Tổng cộng</p>
                                   <p class="cart_amount">{{ number_format($total) }} đ</p>
                               </div>
                               <div class="checkout_btn">
                                   <a href="{{ route('checkout.index') }}">Tiến hành thanh toán</a>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--coupon code area end-->
            
        </form> 
        @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fa fa-shopping-cart" style="font-size: 100px; color: #ccc;"></i>
            </div>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted">Hãy thêm vài sản phẩm vào giỏ hàng nhé!</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
                <i class="fa fa-shopping-bag"></i> Đến cửa hàng
            </a>
        </div>
        @endif
    </div>     
</div>
<!--shopping cart area end -->
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Update cart quantity
        $(".update-cart").change(function (e) {
            e.preventDefault();
            var ele = $(this);
            var row = ele.parents("tr");
            
            $.ajax({
                url: '{{ route('cart.update') }}',
                method: "PATCH",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: row.attr("data-id"), 
                    quantity: ele.val()
                },
                success: function (response) {
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            });
        });

        // Remove item from cart
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
            var ele = $(this);
            var row = ele.parents("tr");
            
            if(confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: row.attr("data-id")
                    },
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                });
            }
        });

        // Clear entire cart
        $("#clear-cart").click(function(e) {
            e.preventDefault();
            
            if(confirm("Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?")) {
                $.ajax({
                    url: '{{ route('cart.clear') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                });
            }
        });
    });
</script>
@endsection
