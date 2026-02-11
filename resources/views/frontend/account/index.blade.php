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
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <!-- Tab panes -->
                    <div class="tab-content dashboard_content">
                        <div class="tab-pane fade show active" id="dashboard">
                            <h3>Dashboard </h3>
                            <p>Hello, <strong>{{ $user->name }}</strong> (not <strong>{{ $user->name }}</strong>? <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>)</p>
                            <p>From your account dashboard. you can easily check &amp; view your <a href="#">recent orders</a>, manage your <a href="#">shipping and billing addresses</a> and <a href="#">Edit your password and account details.</a></p>
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
                        </div>
                        <div class="tab-pane fade" id="downloads">
                            <h3>Downloads</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Downloads</th>
                                            <th>Expires</th>
                                            <th>Download</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Shopnovilla - Free Real Estate PSD Template</td>
                                            <td>May 10, 2022</td>
                                            <td><span class="danger">Expired</span></td>
                                            <td><a href="#" class="view">Click Here To Download Your File</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="address">
                           <p>The following addresses will be used on the checkout page by default.</p>
                            <h4 class="billing-address">Billing address</h4>
                            <a href="#" class="view">Edit</a>
                            <p><strong>{{ $user->name }}</strong></p>
                            <address>
                                House #15<br>
                                Road #8<br>
                                Your address <br>
                                goes here <br>
                                1212
                            </address>
                            <p> New York</p>
                        </div>
                        <div class="tab-pane fade" id="account-details">
                            <h3>Account details </h3>
                            <div class="login">
                                <div class="login_form_container">
                                    <div class="account_login_form">
                                        <form action="#">
                                            <p>Already have an account? <a href="#">Log in instead!</a></p>
                                            <label>Name</label>
                                            <input type="text" name="name" value="{{ $user->name }}">
                                            <label>Email</label>
                                            <input type="text" name="email" value="{{ $user->email }}">
                                            <label>Password</label>
                                            <input type="password" name="password">
                                            <label>Birthdate</label>
                                            <input type="text" placeholder="MM/DD/YYYY" value="" name="birthday">
                                            <span class="example">
                                              (E.g.: 05/31/1970)
                                            </span>
                                            <br>
                                            <div class="save_button primary_btn default_button">
                                                <a href="#">Save</a>
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
