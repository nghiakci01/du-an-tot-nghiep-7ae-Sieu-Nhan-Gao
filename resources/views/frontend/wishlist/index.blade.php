@extends('layouts.public')

@section('title', __('messages.my_wishlist') . ' | Elite')

@push('styles')
@endpush

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
                        <li>{{ __('messages.my_wishlist') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>         
</div>
<!--breadcrumbs area end-->

<div class="wishlist_area mt-60 mb-60">
    <div class="container">
        <div class="wishlist_container">
            <!-- Sidebar -->
            <aside class="wishlist_sidebar" id="wishlistSidebar">
                <h4 class="mb-4">{{ __('messages.my_account') }}</h4>
                @include('frontend.account.partials.sidebar')
            </aside>

            <!-- Main Content -->
            <div class="wishlist_main">
                <div class="mobile_sidebar_toggle" id="toggleSidebar">
                    <i class="fa fa-bars mr-2"></i> {{ __('messages.account_menu') }}
                </div>

                @if(count($wishlists) > 0)
                    <div class="table_desc wishlist bg-white p-3 rounded shadow-sm">
                        <div class="cart_page table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="product_remove">{{ __('messages.remove') }}</th>
                                        <th class="product_thumb">{{ __('messages.image') }}</th>
                                        <th class="product_name">{{ __('messages.product') }}</th>
                                        <th class="product-price">{{ __('messages.price') }}</th>
                                        <th class="product_quantity">{{ __('messages.status') }}</th>
                                        <th class="product_total">{{ __('messages.add_to_cart') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wishlists as $wish)
                                        @php $product = $wish->product; @endphp
                                        <tr>
                                            <td class="product_remove">
                                                <form action="{{ route('wishlist.destroy', $wish->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger" onclick="return confirm('{{ __('messages.confirm_remove_item') }}')">
                                                        X
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="product_thumb">
                                                <a href="{{ route('product.detail', $product->slug) }}">
                                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}" style="width: 80px;">
                                                </a>
                                            </td>
                                            <td class="product_name">
                                                <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                            </td>
                                            <td class="product-price">
                                                @if($product->sale_price)
                                                    <span class="old-price text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                    <span class="new-price font-weight-bold ml-2">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                                @else
                                                    <span>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                @endif
                                            </td>
                                            <td class="product_quantity">
                                                @if($product->stock > 0)
                                                    <span class="text-success">{{ __('messages.in_stock') }}</span>
                                                @else
                                                    <span class="text-danger">{{ __('messages.out_of_stock') }}</span>
                                                @endif
                                            </td>
                                            <td class="product_total">
                                                @if($product->stock > 0)
                                                    <a href="javascript:void(0)" class="btn btn-primary add-to-cart-btn" data-id="{{ $product->id }}">
                                                        {{ __('messages.add_to_cart') }}
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary disabled" disabled>{{ __('messages.out_of_stock') }}</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>   
                        </div>  
                    </div>
                @else
                    <div class="text-center bg-white p-5 border-radius-8 shadow-sm">
                        <i class="fa fa-heart-o text-muted mb-4" style="font-size: 60px; opacity: 0.3;"></i>
                        <h3>{{ __('messages.wishlist_empty') }}</h3>
                        <p class="text-muted">{{ __('messages.wishlist_empty_desc') }}</p>
                        <a href="{{ route('shop') }}" class="btn btn-primary mt-3">{{ __('messages.continue_shopping') }}</a>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12">
                         <div class="wishlist_share">
                            <h4>{{ __('messages.share_on') }}</h4>
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>           
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>           
                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>           
                                <li><a href="#"><i class="fa fa-pinterest"></i></a></li>        
                            </ul>      
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const toggleSidebar = $('#toggleSidebar');
        const sidebar = $('#wishlistSidebar');

        // Mobile Sidebar Toggle
        toggleSidebar.click(function() {
            sidebar.toggleClass('show');
            $(this).find('i').toggleClass('fa-bars fa-times');
        });

        // Add to Cart AJAX
        $('.add-to-cart-btn').click(function(e) {
            e.preventDefault();
            const productId = $(this).data('id');
            const btn = $(this);
            
            btn.addClass('disabled').attr('disabled', true);

            $.ajax({
                url: "{{ route('cart.add') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload(); // Reload to update mini-cart and header
                        });
                    } else {
                         Swal.fire('Thông báo', response.message, 'info');
                         btn.removeClass('disabled').removeAttr('disabled');
                    }
                },
                error: function(xhr) {
                    btn.removeClass('disabled').removeAttr('disabled');
                    if(xhr.status === 422) {
                        // Product has variants, need to select
                        Swal.fire({
                            title: 'Thông báo',
                            text: 'Sản phẩm này có nhiều lựa chọn. Vui lòng chọn chi tiết tại trang sản phẩm.',
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'Xem chi tiết',
                            cancelButtonText: 'Đóng'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = btn.closest('tr').find('.product_name a').attr('href');
                            }
                        });
                    } else if(xhr.status === 401) {
                        Swal.fire('Thông báo', 'Vui lòng đăng nhập để thêm vào giỏ hàng', 'warning');
                    } else {
                        Swal.fire('Lỗi', 'Không thể thêm sản phẩm vào giỏ hàng', 'error');
                    }
                }
            });
        });
    });
</script>
@endsection