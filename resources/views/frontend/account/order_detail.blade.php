@extends('layouts.public')

@section('content')
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li><a href="{{ route('account.index') }}">{{ __('messages.my_account') }}</a></li>
                            <li>{{ __('messages.order_id') }} #{{ $order->id }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="main_content_area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ __('messages.order_details') }} - #{{ $order->id }}</h4>
                            <span
                                class="badge {{ $order->status == 'COMPLETED' ? 'bg-success' : ($order->status == 'CANCELLED' ? 'bg-danger' : 'bg-warning') }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>{{ __('messages.shipping_information') }}</h5>
                                    <p>{{ $order->shipping_address }}</p>
                                    <p><strong>{{ __('messages.phone_label') }}:</strong> {{ Auth::user()->phone }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h5>{{ __('messages.date') }}</h5>
                                    <p>{{ $order->created_at->format('M d, Y H:i A') }}</p>
                                    <p><strong>{{ __('messages.payment_method') }}:</strong> {{ $order->payment_method }}
                                    </p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.product') }}</th>
                                            <th>{{ __('messages.variant') }}</th>
                                            <th>{{ __('messages.price') }}</th>
                                            <th>{{ __('messages.quantity') }}</th>
                                            <th>{{ __('messages.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    @if($item->product && $item->product->images->count() > 0)
                                                        <img src="{{ Storage::url($item->product->images->first()->image_path) }}"
                                                            alt="" style="width: 50px; margin-right: 10px;">
                                                    @endif
                                                    {{ $item->product->name ?? 'Product Deleted' }}
                                                </td>
                                                <td>
                                                    @if($item->variant)
                                                        {{ __('messages.size') }}:
                                                        {{ $item->variant->sizeRelationship->name ?? 'N/A' }} /
                                                        {{ __('messages.color') }}:
                                                        {{ $item->variant->colorRelationship->name ?? 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ number_format($item->price) }} VND</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price * $item->quantity) }} VND</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>{{ __('messages.subtotal') }}</strong>
                                            </td>
                                            <td><strong>{{ number_format($order->total_price) }} VND</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('account.index') }}"
                                    class="btn btn-secondary">{{ __('messages.back_to_orders') }}</a>
                                @if($order->status == 'PENDING')
                                    <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST"
                                        class="d-inline float-end"
                                        onsubmit="return confirm('{{ __('messages.confirm_cancel') }}');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">{{ __('messages.cancel_order') }}</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection