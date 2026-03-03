@extends('layouts.public')

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
    <style>
        /* Mobile Responsive Account Page */
        @media (max-width: 767px) {
            .dashboard-list {
                display: flex;
                flex-direction: row;
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 10px;
                margin-bottom: 20px;
                border-bottom: 1px solid #ddd;
            }

            .dashboard-list li {
                display: inline-block;
                width: auto;
                margin-right: 15px;
            }

            .dashboard_content {
                padding: 15px;
                background: #f9f9f9;
                border-radius: 5px;
                margin-top: 15px;
            }

            .account_dashboard .table {
                font-size: 13px;
            }

            .account_dashboard .table th,
            .account_dashboard .table td {
                padding: 10px 5px;
                white-space: nowrap;
            }

            /* Style buttons inside table */
            .account_dashboard .table .btn {
                padding: 4px 8px;
                font-size: 12px;
                margin-bottom: 2px;
            }

            #avatar-preview {
                width: 90px !important;
                height: 90px !important;
            }
        }
    </style>
    <section class="main_content_area">
        <div class="container">
            <div class="account_dashboard">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        @include('frontend.account.partials.sidebar')
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <!-- Tab panes -->
                        <div class="tab-content dashboard_content">
                            <div class="tab-pane fade" id="dashboard">
                                <h3>{{ __('messages.dashboard') }} </h3>
                                <p>{!! __('messages.dashboard_desc') !!}</p>
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
                                                    <td colspan="5" class="text-center">{{ __('messages.no_orders') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="wishlist">
                                <h3>{{ __('messages.my_wishlist') }}</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.image') }}</th>
                                                <th>{{ __('messages.product') }}</th>
                                                <th>{{ __('messages.price') }}</th>
                                                <th>{{ __('messages.status') }}</th>
                                                <th>{{ __('messages.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($wishlists as $wish)
                                                @php $product = $wish->product; @endphp
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('product.detail', $product->slug) }}">
                                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}" style="width: 50px;">
                                                        </a>
                                                    </td>
                                                    <td><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></td>
                                                    <td>{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</td>
                                                    <td>
                                                        @if($product->stock > 0)
                                                            <span class="text-success">{{ __('messages.in_stock') }}</span>
                                                        @else
                                                            <span class="text-danger">{{ __('messages.out_of_stock') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-sm btn-primary">{{ __('messages.view') }}</a>
                                                            <form action="{{ route('wishlist.destroy', $wish->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_remove_item') }}');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">{{ __('messages.wishlist_empty') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="coupons">
                                <h3>{{ __('messages.my_coupons') }}</h3>
                                <div class="row">
                                    @forelse($coupons as $coupon)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-primary border-2 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h5 class="card-title text-primary mb-0">{{ $coupon->code }}</h5>
                                                        <span class="badge bg-primary">{{ $coupon->getFormattedValue() }}</span>
                                                    </div>
                                                    <p class="card-text small text-muted mb-2">{{ $coupon->description }}</p>
                                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                                        <span class="small fw-bold">{{ __('messages.welcome_coupon_desc') }}</span>
                                                        <button class="btn btn-sm btn-outline-primary copy-coupon" data-code="{{ $coupon->code }}">
                                                            <i class="fa fa-copy"></i> Copy
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-center py-4">{{ __('messages.no_orders') }} (Chưa có mã giảm giá nào)</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="account-details">
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

                                                <div class="row">
                                                    <div class="col-12">
                                                        <label>{{ __('messages.full_name') }}</label>
                                                        <input type="text" name="name"
                                                            value="{{ old('name', $user->name) }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label>{{ __('messages.phone_number') }}</label>
                                                        <input type="tel" name="phone"
                                                            value="{{ old('phone', $user->phone) }}"
                                                            pattern="^(03|05|07|08|09)\d{8}$"
                                                            title="Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số">
                                                    </div>
                                                    <div class="col-12">
                                                        <label>{{ __('messages.email') }}</label>
                                                        <input type="text" name="email" value="{{ $user->email }}" readonly
                                                            disabled>
                                                    </div>
                                                </div>

                                                <h5 class="mt-4">{{ __('messages.change_password') }}</h5>
                                                <small class="text-muted">{{ __('messages.leave_blank') }}</small>

                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <label>{{ __('messages.current_password') }}</label>
                                                        <input type="password" name="current_password">
                                                    </div>
                                                    <div class="col-12">
                                                        <label>{{ __('messages.new_password') }}</label>
                                                        <input type="password" name="new_password">
                                                    </div>
                                                    <div class="col-12">
                                                        <label>{{ __('messages.confirm_new_password') }}</label>
                                                        <input type="password" name="new_password_confirmation">
                                                    </div>
                                                </div>

                                                <div class="save_button primary_btn default_button mt-3">
                                                    <button type="submit">{{ __('messages.save_changes') }}</button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Copy Coupon
        document.querySelectorAll('.copy-coupon').forEach(button => {
            button.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(code).then(() => {
                        showCopySuccess(code);
                    }).catch(err => {
                        fallbackCopyTextToClipboard(code);
                    });
                } else {
                    fallbackCopyTextToClipboard(code);
                }
            });
        });

        function showCopySuccess(code) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ __("messages.applied") }}'.replace('đã được áp dụng', 'Đã sao chép') + ': ' + code,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        }

        function fallbackCopyTextToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showCopySuccess(text);
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }
            document.body.removeChild(textArea);
        }

        // Handle URL hash for tabs
        const hash = window.location.hash;
        if (hash) {
            const targetTab = document.querySelector(`.dashboard_tab_button a[href*="${hash}"]`);
            if (targetTab) {
                const tabTrigger = new bootstrap.Tab(targetTab);
                tabTrigger.show();
            }
        }
    });
</script>
@endpush