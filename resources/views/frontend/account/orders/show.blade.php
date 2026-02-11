@extends('layouts.public')

@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area other_bread">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{ route('welcome') }}">home</a></li>
                        <li>/</li>
                        <li><a href="{{ route('account.index') }}">my account</a></li>
                        <li>/</li>
                        <li>order details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->

<!-- my account start  -->
<section class="main_content_area">
    <div class="container">
        <div class="account_dashboard">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    @include('frontend.account.partials.sidebar')
                </div>
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <div class="dashboard_content">
                        <h3>Order Details (#{{ $order->id }})</h3>
                        <div class="order_details">
                            <p><strong>Order Number:</strong> #{{ $order->id }}</p>
                            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                            <p><strong>Status:</strong> {{ $order->status_text }}</p>
                            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>

                            <h4>Order Items</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product ? $item->product->name : 'Product Unavailable' }} <br>
                                                <small>{{ $item->variant ? $item->variant->name : '' }}</small>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price) }}₫</td>
                                            <td>{{ number_format($item->total) }}₫</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                            <td>{{ number_format($order->total_price) }}₫</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td><strong>{{ number_format($order->total_price) }}₫</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h4>Shipping Address</h4>
                            <p>
                                {{ $order->shipping_address ?? $user->address ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- my account end   -->
@endsection
