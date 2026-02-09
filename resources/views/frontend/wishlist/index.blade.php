@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
    <div class="breadcrumb-area bg-gray-4 breadcrumb-padding-1">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <h2 data-aos="fade-up" data-aos-delay="200">Danh sách yêu thích</h2>
                <ul data-aos="fade-up" data-aos-delay="400">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><i class="ti-angle-right"></i></li>
                    <li>Danh sách yêu thích</li>
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
                                            <th>Ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Thêm vào giỏ</th>
                                            <th>Hành động</th>
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
                                                    <a href="{{ route('product.detail', $item->product->slug) }}">Xem chi tiết</a>
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
                            <p>Danh sách yêu thích của bạn đang trống.</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary">Mua sắm ngay</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection