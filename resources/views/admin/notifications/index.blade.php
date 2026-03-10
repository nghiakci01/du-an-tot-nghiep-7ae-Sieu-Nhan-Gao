@extends('layouts.admin')

@section('title', 'Tất cả thông báo')

@section('content')
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Thông báo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Danh sách thông báo</h5>
                        @if($admin_unread_count > 0)
                            <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light-primary">Đánh dấu tất cả đã đọc</button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($notifications as $notification)
                                <a href="{{ route('admin.notifications.markAsRead', $notification->id) }}" 
                                   class="list-group-item list-group-item-action d-flex align-items-center {{ $notification->read_at ? '' : 'bg-light-primary border-start border-primary border-4' }}">
                                    <div class="flex-shrink-0">
                                        @php
                                            $icon = match($notification->data['type'] ?? '') {
                                                'new_order' => 'ti ti-shopping-cart',
                                                'payment_success' => 'ti ti-credit-card',
                                                'low_stock' => 'ti ti-alert-triangle',
                                                'new_review' => 'ti ti-star',
                                                default => 'ti ti-bell'
                                            };
                                            $color = match($notification->data['type'] ?? '') {
                                                'new_order' => 'text-primary',
                                                'payment_success' => 'text-success',
                                                'low_stock' => 'text-danger',
                                                'new_review' => 'text-warning',
                                                default => 'text-muted'
                                            };
                                        @endphp
                                        <div class="avtar avtar-s bg-light-{{ str_replace('text-', '', $color) }}">
                                            <i class="{{ $icon }} f-20"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ $notification->data['message'] ?? 'Thông báo' }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i:s') }} ({{ $notification->created_at->diffForHumans() }})</small>
                                    </div>
                                    @if(!$notification->read_at)
                                        <span class="badge bg-primary rounded-pill">Mới</span>
                                    @endif
                                </a>
                            @empty
                                <div class="text-center py-5">
                                    <i class="ti ti-bell-off f-50 text-muted"></i>
                                    <p class="mt-2 text-muted">Không có thông báo nào</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
