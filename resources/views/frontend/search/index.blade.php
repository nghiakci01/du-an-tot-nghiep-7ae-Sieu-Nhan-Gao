@extends('layouts.public')

@section('title', 'Search Results | Elite')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home')}}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.search_results')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
    <!--shop area start-->
    <div class="shop_area shop_reverse">
        <div class="container">
            <div class="shop_inner_area">
                <div class="row">
                    <div class="col-12">
                        <!--shop toolbar start-->
                        <div class="shop_title">
                            <h1>{{ __('messages.search_results') }}</h1>
                        </div>
                        
                        @if($query)
                        <div class="search_info mb-4">
                            <p>
                                @if($products->total() > 0)
                                    Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng số {{ $products->total() }} kết quả cho 
                                    <strong>"{{ $query }}"</strong>
                                @else
                                    Không tìm thấy kết quả nào cho <strong>"{{ $query }}"</strong>
                                @endif
                            </p>
                        </div>
                        @endif

                        <div class="shop_toolbar_wrapper mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="page_amount">
                                <p>Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} trong tổng số {{ $products->total() }} kết quả</p>
                            </div>
                            

                        </div>
                        <!--shop toolbar end-->
                        
                        <div class="row shop_wrapper">
                            @if(count($products) > 0)
                                @foreach($products as $product)
                                @include('frontend.partials.product-grid-item', [
                                    'product' => $product,
                                    'columnClass' => 'col-lg-3 col-md-4 col-sm-6 col-12',
                                    'contentClass' => 'grid_content',
                                    'showListContent' => false
                                ])
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <div class="mb-4">
                                            <i class="fa fa-search" style="font-size: 80px; color: #ccc;"></i>
                                        </div>
                                        <h3>{{ __('messages.no_products_found')}}</h3>
                                        <p class="text-muted mb-4">
                                            @if($query)
                                                Chúng tôi không tìm thấy sản phẩm nào phù hợp "{{ $query }}"
                                            @else
                                                Vui lòng nhập từ khóa tìm kiếm
                                            @endif
                                        </p>
                                        <a href="{{ route('shop') }}" class="btn btn-primary">
                                            <i class="fa fa-shopping-bag"></i> {{ __('messages.continue_shopping')}}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($products->hasPages())
                        <div class="shop_toolbar t_bottom">
                            <div class="pagination">
                                {{ $products->appends(['q' => $query])->links('vendor.pagination.reid') }}
                            </div>
                        </div>
                        @endif
                        <!--shop toolbar end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--shop area end-->
@endsection
