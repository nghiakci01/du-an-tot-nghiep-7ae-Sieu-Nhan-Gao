@extends('layouts.public')

@section('title', $product->name . ' | FashionStore')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Cửa hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x500?text=Product' }}" class="card-img-top" alt="{{ $product->name }}" id="main-image">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="fw-bold">{{ $product->name }}</h1>
            <p class="text-muted">Danh mục: <a href="#!" class="text-decoration-none">{{ $product->category->name }}</a></p>
            <h3 class="text-primary fw-bold my-3">{{ number_format($product->price) }} đ</h3>
            
            <p class="lead" style="font-size: 1rem;">{!! nl2br(e($product->description)) !!}</p>
            
            <hr>

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                @if($product->variants->count() > 0)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn Biến thể (Size / Màu):</label>
                        <select class="form-select" name="variant_id" required>
                            <option value="">-- Vui lòng chọn --</option>
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}" {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                                    Size: {{ $variant->size }} - Màu: {{ $variant->color }} 
                                    ({{ $variant->stock_quantity > 0 ? 'Còn hàng' : 'Hết hàng' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="alert alert-warning">Sản phẩm này hiện chưa có tùy chọn.</div>
                @endif
                
                <div class="mb-3 w-50">
                    <label for="quantity" class="form-label fw-bold">Số lượng:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="10">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg" disabled>
                        <i class="bi bi-cart-plus me-2"></i> Thêm vào Giỏ hàng
                    </button>
                    <small class="text-muted text-center">* Chức năng Giỏ hàng sẽ được cập nhật trong phiên bản tới.</small>
                </div>
            </form>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="fw-bold mb-4">Sản phẩm liên quan</h3>
        <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <a href="{{ route('product.detail', $related->slug) }}">
                            <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://via.placeholder.com/300x300?text=Product' }}" class="card-img-top product-img" alt="{{ $related->name }}">
                        </a>
                    </div>
                    <div class="card-body">
                         <h5 class="card-title text-truncate">
                            <a href="{{ route('product.detail', $related->slug) }}" class="text-decoration-none text-dark">{{ $related->name }}</a>
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">{{ number_format($related->price) }} đ</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
