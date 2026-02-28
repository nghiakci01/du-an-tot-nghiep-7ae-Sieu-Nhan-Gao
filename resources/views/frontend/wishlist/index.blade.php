@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
    <div class="breadcrumb-area bg-gray-4 breadcrumb-padding-1">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <h2 data-aos="fade-up" data-aos-delay="200">Wishlist</h2>
                <ul data-aos="fade-up" data-aos-delay="400">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><i class="ti-angle-right"></i></li>
                    <li>Wishlist</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="wishlist-area pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($wishlists->count() > 0)
                        <form action="#">
                            <div class="table-content table-responsive cart-table-content">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Add to Cart</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($wishlists as $item)
                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="{{ route('product.detail', $item->product->slug) }}">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt=""
                                                            style="width: 100px;">
                                                    </a>
                                                </td>
                                                <td class="product-name">
                                                    <a
                                                        href="{{ route('product.detail', $item->product->slug) }}">{{ $item->product->name }}</a>
                                                </td>
                                                <td class="product-price-cart">
                                                    @if($item->product->sale_price)
                                                        <span class="amount">{{ number_format($item->product->sale_price) }}₫</span>
                                                        <span class="old-price">{{ number_format($item->product->price) }}₫</span>
                                                    @else
                                                        <span class="amount">{{ number_format($item->product->price) }}₫</span>
                                                    @endif
                                                </td>
                                                <td class="product-wishlist-cart">
                                                    <a href="{{ route('product.detail', $item->product->slug) }}">View Details</a>
                                                </td>
                                                <td class="product-remove">
                                                    <a href="{{ route('wishlist.destroy', $item->id) }}"
                                                        onclick="event.preventDefault(); document.getElementById('remove-wishlist-{{ $item->id }}').submit();">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                    <form id="remove-wishlist-{{ $item->id }}"
                                                        action="{{ route('wishlist.destroy', $item->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @else
                        <div class="text-center">
                            <p>Your wishlist is empty.</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary">Shop Now</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection