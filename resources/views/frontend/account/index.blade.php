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
                            <li>my account</li>
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
                    <!-- Nav tabs -->
@include('frontend.account.partials.sidebar')
                </div>
            @endif
            <div class="account_dashboard">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <!-- Nav tabs -->
                        <div class="dashboard_tab_button">
                            <ul role="tablist" class="nav flex-column dashboard-list">
                                <li><a href="#dashboard" data-bs-toggle="tab"
                                        class="nav-link active">{{ __('messages.dashboard') }}</a></li>
                                <li> <a href="#orders" data-bs-toggle="tab" class="nav-link">{{ __('messages.orders') }}</a>
                                </li>
                                <li><a href="#address" data-bs-toggle="tab"
                                        class="nav-link">{{ __('messages.account_details') }}</a></li>
                                <li>
                                    <a href="{{ route('logout') }}" class="nav-link"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('messages.logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="orders">
                            <h3>Orders</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td><span class="{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ $order->status }}</span></td>
                                            <td>{{ number_format($order->total_price) }}₫ </td>
                                            <td><a href="{{ route('account.orders.show', $order->id) }}" class="view">view</a></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5">You have no orders.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="orders">
                                <h3>{{ __('messages.orders') }}</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.order_id') }}</th>
                                                <th>{{ __('messages.date') }}</th>
                                                <th>{{ __('messages.status') }}</th>
                                                <th>{{ __('messages.total') }}</th>
                                                <th>{{ __('messages.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $order->status == 'COMPLETED' ? 'bg-success' : ($order->status == 'CANCELLED' ? 'bg-danger' : 'bg-warning') }}">
                                                            {{ $order->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($order->total_price) }} VND</td>
                                                    <td>
                                                        <a href="{{ route('account.orders.show', $order->id) }}"
                                                            class="view btn btn-sm btn-primary">{{ __('messages.view') }}</a>
                                                        @if($order->status == 'PENDING')
                                                            <form action="{{ route('account.orders.cancel', $order->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('{{ __('messages.confirm_cancel') }}');">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger">{{ __('messages.cancel') }}</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">{{ __('messages.no_orders') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="address">
                                <h3>{{ __('messages.account_details') }} </h3>
                                <div class="login">
                                    <div class="login_form_container">
                                        <div class="account_login_form">
                                            <form action="{{ route('account.update') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <div class="mb-4 text-center">
                                                    @if($user->avatar)
                                                        <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}"
                                                            alt="Avatar" class="rounded-circle img-thumbnail"
                                                            style="width: 120px; height: 120px; object-fit: cover;">
                                                    @else
                                                        <img id="avatar-preview"
                                                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                                            alt="Avatar" class="rounded-circle img-thumbnail"
                                                            style="width: 120px; height: 120px; object-fit: cover;">
                                                    @endif
                                                    <div class="mt-2">
                                                        <label for="avatar"
                                                            class="btn btn-sm btn-outline-primary">{{ __('messages.choose_image') }}</label>
                                                        <input type="file" name="avatar" id="avatar" class="d-none"
                                                            accept="image/*"
                                                            onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])">
                                                    </div>
                                                </div>

                                                <label>{{ __('messages.full_name') }}</label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}">

                                                <label>{{ __('messages.phone_number') }}</label>
                                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">

                                                <label>{{ __('messages.email') }}</label>
                                                <input type="text" name="email" value="{{ $user->email }}" readonly
                                                    disabled>

                                                <h5 class="mt-4">{{ __('messages.change_password') }}</h5>
                                                <small class="text-muted">{{ __('messages.leave_blank') }}</small>

                                                <label>{{ __('messages.current_password') }}</label>
                                                <input type="password" name="current_password">

                                                <label>{{ __('messages.new_password') }}</label>
                                                <input type="password" name="new_password">

                                                <label>{{ __('messages.confirm_new_password') }}</label>
                                                <input type="password" name="new_password_confirmation">

                                                <br>
                                                <div class="save_button primary_btn default_button">
                                                    <button type="submit">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- my account end   -->
@endsection