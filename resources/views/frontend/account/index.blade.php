@extends('layouts.public')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Account Page Custom Styles */
    .dashboard-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    .dashboard-icon {
        font-size: 2.5rem;
        color: #3b82f6; /* Bootstrap Primary roughly */
    }
    .nav-link.active {
        background-color: #f8f9fa !important;
        color: #0d6efd !important;
        border-right: 3px solid #0d6efd;
    }
    .avatar-upload {
        position: relative;
        max-width: 150px;
        margin: 0 auto;
    }
    .avatar-edit {
        position: absolute;
        right: 0;
        bottom: 0;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.my_account') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!-- my account start  -->
    <section class="main_content_area my-5">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="account_dashboard">
                <div class="row">
                    <div class="col-md-3 col-lg-3">
                        <!-- Sidebar -->
                        @include('frontend.account.partials.sidebar')
                    </div>
                    <div class="col-md-9 col-lg-9">
                        <!-- Tab panes -->
                        <div class="tab-content dashboard_content p-4 bg-white shadow-sm rounded">
                            
                            <!-- Dashboard Tab -->
                            <div class="tab-pane fade show active" id="dashboard">
                                <h3 class="mb-4">{{ __('messages.dashboard') }}</h3>
                                <div class="welcome-msg mb-4">
                                    <p class="lead">{{ __('messages.hello') }}, <strong>{{ $user->name }}</strong>!</p>
                                    <p class="text-muted">{{ __('messages.welcome_back') }}</p>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="card dashboard-card bg-light">
                                            <div class="card-body d-flex align-items-center p-4">
                                                <div class="dashboard-icon me-3">
                                                    <i class="bi bi-bag-fill"></i>
                                                </div>
                                                <div>
                                                    <h6 class="text-muted mb-1">{{ __('messages.total_orders') }}</h6>
                                                    <h3 class="mb-0 fw-bold">{{ $orders->count() }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card dashboard-card bg-light">
                                            <div class="card-body d-flex align-items-center p-4">
                                                <div class="dashboard-icon me-3 text-success">
                                                    <i class="bi bi-cash-coin"></i>
                                                </div>
                                                <div>
                                                    <h6 class="text-muted mb-1">{{ __('messages.total_spent') }}</h6>
                                                    <h3 class="mb-0 fw-bold">{{ number_format($orders->where('status', '!=', 'CANCELLED')->sum('total_price'), 0, ',', '.') }}đ</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="quick-links">
                                    <p>
                                        {{ __('messages.dashboard_desc') }} 
                                        <a href="#orders" data-bs-toggle="tab" class="text-primary text-decoration-none fw-bold">{{ __('messages.recent_orders') }}</a>, 
                                        {{ __('messages.manage_shipping') }} 
                                        <a href="#address" data-bs-toggle="tab" class="text-primary text-decoration-none fw-bold">{{ __('messages.shipping_address') }}</a>, 
                                        {{ __('messages.edit_password') }}
                                        <a href="#account-details" data-bs-toggle="tab" class="text-primary text-decoration-none fw-bold">{{ __('messages.account_details') }}</a>.
                                    </p>
                                </div>
                            </div>

                            <!-- Orders Tab -->
                            <div class="tab-pane fade" id="orders">
                                <h3 class="mb-4">{{ __('messages.orders') }}</h3>
                                @if($orders->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('messages.order_id') }}</th>
                                                    <th>{{ __('messages.date') }}</th>
                                                    <th>{{ __('messages.status') }}</th>
                                                    <th>{{ __('messages.total') }}</th>
                                                    <th class="text-end">{{ __('messages.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                    <tr>
                                                        <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            @php
                                                                $statusClass = match($order->status) {
                                                                    'COMPLETED' => 'bg-success',
                                                                    'CANCELLED' => 'bg-danger',
                                                                    'SHIPPED' => 'bg-info text-dark',
                                                                    'CONFIRMED' => 'bg-primary',
                                                                    default => 'bg-warning text-dark'
                                                                };
                                                            @endphp
                                                            <span class="badge rounded-pill {{ $statusClass }}">
                                                                {{ $order->status }}
                                                            </span>
                                                        </td>
                                                        <td class="fw-bold">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                                        <td class="text-end">
                                                            <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                                <i class="bi bi-eye"></i> {{ __('messages.view') }}
                                                            </a>
                                                            @if($order->status == 'PENDING')
                                                                <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_cancel') }}');">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                                                        <i class="bi bi-x-circle"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-cart-x display-1 text-muted"></i>
                                        <p class="mt-3 text-muted">{{ __('messages.no_orders') }}</p>
                                        <a href="{{ route('shop') }}" class="btn btn-primary mt-2">{{ __('messages.continue_shopping') }}</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Addresses Tab -->
                            <div class="tab-pane fade" id="address">
                                <h3 class="mb-4">{{ __('messages.default_shipping_address') }}</h3>
                                <div class="card border-0 bg-light mb-4">
                                    <div class="card-body">
                                        @if($user->address)
                                            <p class="mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i> {{ $user->address }}</p>
                                        @else
                                            <p class="mb-0 text-muted fst-italic">{{ __('messages.no_address_set') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <h4 class="mb-3">{{ __('messages.edit_address') }}</h4>
                                <form action="{{ route('account.update') }}" method="POST">
                                    @csrf
                                    <!-- Hidden required fields to pass validation -->
                                    <input type="hidden" name="name" value="{{ $user->name }}">
                                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('messages.street_address') }}</label>
                                        <textarea name="address" class="form-control" rows="3" placeholder="Ex: 123 Main St, City, Country">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('messages.update_address') }}
                                    </button>
                                </form>
                            </div>

                            <!-- Account Details Tab -->
                            <div class="tab-pane fade" id="account-details">
                                <h3 class="mb-4">{{ __('messages.account_details') }}</h3>
                                <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Hidden address field to preserve it -->
                                    <input type="hidden" name="address" value="{{ $user->address }}">

                                    <div class="row mb-5">
                                        <div class="col-md-12 text-center">
                                            <div class="avatar-upload mb-3">
                                                @if($user->avatar)
                                                    <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                                @else
                                                    <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="Avatar" class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                                @endif
                                                <div class="mt-3">
                                                    <label for="avatar" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-camera me-1"></i> {{ __('messages.upload_image') }}
                                                    </label>
                                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="mb-3 border-bottom pb-2">{{ __('messages.personal_info') }}</h4>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('messages.full_name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('messages.phone_number') }}</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ __('messages.email') }}</label>
                                            <input type="text" class="form-control bg-light" value="{{ $user->email }}" readonly disabled>
                                        </div>
                                    </div>

                                    <h4 class="mb-3 border-bottom pb-2 mt-5">{{ __('messages.change_password') }}</h4>
                                    <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i> {{ __('messages.change_password_desc') }}</p>

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">{{ __('messages.current_password') }}</label>
                                            <input type="password" name="current_password" class="form-control" autocomplete="off">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('messages.new_password') }}</label>
                                            <input type="password" name="new_password" class="form-control" autocomplete="new-password">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('messages.confirm_new_password') }}</label>
                                            <input type="password" name="new_password_confirmation" class="form-control" autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-2 border-top">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-save me-2"></i> {{ __('messages.save_changes') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Removed Downloads Tab -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- my account end   -->
@endsection