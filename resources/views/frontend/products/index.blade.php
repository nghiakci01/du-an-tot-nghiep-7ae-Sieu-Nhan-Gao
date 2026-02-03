@extends('layouts.public')

@section('title', 'Shop | FashionStore')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold">All Products</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <form action="{{ route('shop') }}" method="GET" class="d-inline-block">
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>
        
    </div>

    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <a href="{{ route('product.detail', $product->slug) }}">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=Product' }}" class="card-img-top product-img" alt="{{ $product->name }}">
                    </a>
                    @if(!$product->is_active)
                        <span class="position-absolute top-0 start-0 badge bg-danger m-2">Out of Stock</span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <small class="text-muted mb-1">{{ $product->category->name }}</small>
                    <h5 class="card-title text-truncate">
                        <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                    </h5>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">{{ number_format($product->price) }} đ</span>
                        <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h3>No products found.</h3>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
