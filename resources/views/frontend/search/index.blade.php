@extends('layouts.public')

@section('title', 'Search Results | FashionStore')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">home</a></li>
                            <li>/</li>
                            <li>search results</li>
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
                                    Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} results for 
                                    <strong>"{{ $query }}"</strong>
                                @else
                                    No results found for <strong>"{{ $query }}"</strong>
                                @endif
                            </p>
                        </div>
                        @endif

                        <div class="shop_toolbar_wrapper mb-4">
                            <div class="page_amount">
                                <p>Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</p>
                            </div>
                        </div>
                        <!--shop toolbar end-->
                        
                        <div class="row shop_wrapper">
                            @forelse($products as $product)
                                @include('frontend.partials.product-grid-item', [
                                    'product' => $product,
                                    'columnClass' => 'col-lg-3 col-md-4 col-sm-6 col-12',
                                    'contentClass' => 'grid_content',
                                    'showListContent' => false
                                ])
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <div class="mb-4">
                                            <i class="fa fa-search" style="font-size: 80px; color: #ccc;"></i>
                                        </div>
                                        <h3>No Products Found</h3>
                                        <p class="text-muted mb-4">
                                            @if($query)
                                                We couldn't find any products matching "{{ $query }}"
                                            @else
                                                Please enter a search term
                                            @endif
                                        </p>
                                        <a href="{{ route('shop') }}" class="btn btn-primary">
                                            <i class="fa fa-shopping-bag"></i> Continue Shopping
                                        </a>
                                    </div>
                                </div>
                            @endforelse
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
