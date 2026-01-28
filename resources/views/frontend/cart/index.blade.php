@extends('layouts.public')

@section('title', 'Giỏ hàng | FashionStore')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50%">Sản phẩm</th>
                                <th style="width: 15%">Giá</th>
                                <th style="width: 20%">Số lượng</th>
                                <th style="width: 15%" class="text-end">Tổng</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('cart') as $id => $details)
                                <tr data-id="{{ $id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('product.detail', $details['slug']) }}">
                                                <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/80x80' }}" alt="{{ $details['name'] }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            </a>
                                            <div>
                                                <h5 class="mb-1">
                                                    <a href="{{ route('product.detail', $details['slug']) }}" class="text-decoration-none text-dark">{{ $details['name'] }}</a>
                                                </h5>
                                                <small class="text-muted">Size: {{ $details['size'] }} | Màu: {{ $details['color'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($details['price']) }} đ</td>
                                    <td>
                                        <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" min="1" style="width: 80px;">
                                    </td>
                                    <td class="text-end fw-bold">{{ number_format($details['price'] * $details['quantity']) }} đ</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger remove-from-cart">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Tiếp tục mua sắm</a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title fw-bold mb-3">Tổng đơn hàng</h4>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span>Tạm tính:</span>
                            <span class="fw-bold">{{ number_format($total) }} đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span>Vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Tổng cộng:</span>
                            <span class="fs-5 fw-bold text-primary">{{ number_format($total) }} đ</span>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">Tiến hành Thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-cart-x display-1 text-muted"></i>
            </div>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted">Hãy thêm vài món đồ thời trang nhé!</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Đến cửa hàng</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("data-id"), 
                quantity: ele.parents("tr").find(".quantity").val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
@endsection
