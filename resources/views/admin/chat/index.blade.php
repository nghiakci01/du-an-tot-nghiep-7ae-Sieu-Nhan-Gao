@extends('layouts.admin')

@section('title', 'Hỗ trợ khách hàng')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Hỗ trợ khách hàng</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Danh sách hội thoại</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Session ID</th>
                                <th>Hoạt động cuối</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conversations as $chat)
                            <tr>
                                <td>
                                    @if($chat->user)
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('admin-assets/images/user/avatar-1.jpg') }}" alt="user-image" class="wid-40 rounded-circle">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $chat->user->name }}</h6>
                                                <small class="text-muted">{{ $chat->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Khách vãng lai</span>
                                    @endif
                                </td>
                                <td><code>{{ substr($chat->session_id, 0, 8) }}...</code></td>
                                <td>{{ $chat->last_activity }}</td>
                                <td>
                                    <a href="{{ route('admin.chat.show', $chat->session_id) }}" class="btn btn-sm btn-light-primary">
                                        <i class="ti ti-message-dots ms-1"></i> Trả lời
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $conversations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
