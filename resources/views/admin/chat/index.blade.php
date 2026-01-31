@extends('layouts.admin')

@section('title', 'Hỗ trợ khách hàng')

@section('content')
<style>
    .chat-list-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .chat-item {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none !important;
        display: block;
        color: inherit;
    }
    .chat-item:hover {
        background-color: #f8fafc;
        transform: translateX(5px);
    }
    .chat-item.unread {
        background-color: rgba(113, 70, 206, 0.03);
        border-left: 4px solid var(--primary-color, #7146ce);
    }
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #7146ce 0%, #9063f2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }
    .unread-badge {
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
    }
    .last-message-snippet {
        color: #64748b;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px;
    }
    .chat-time {
        font-size: 12px;
        color: #94a3b8;
    }
</style>

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
                    <h2 class="mb-0">Trung tâm hỗ trợ Reid</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card chat-list-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Danh sách hội thoại</h5>
            </div>
            <div class="card-body p-0">
                @forelse($conversations as $chat)
                    <a href="{{ route('admin.chat.show', $chat->session_id) }}" 
                       class="chat-item {{ $chat->unread_count > 0 ? 'unread' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar shadow-sm">
                                {{ $chat->user ? strtoupper(substr($chat->user->name, 0, 1)) : 'K' }}
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold">
                                            {{ $chat->user ? $chat->user->name : 'Khách vãng lai (' . substr($chat->session_id, 0, 8) . ')' }}
                                        </h6>
                                        <p class="last-message-snippet mb-0">
                                            @if($chat->unread_count > 0)
                                                <strong>{{ $chat->last_message }}</strong>
                                            @else
                                                {{ $chat->last_message }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <span class="chat-time d-block mb-1">{{ $chat->created_at ? $chat->created_at->diffForHumans() : '' }}</span>
                                        @if($chat->unread_count > 0)
                                            <span class="unread-badge ms-auto">{{ $chat->unread_count }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-chevron-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-5 text-center">
                        <div class="mb-3">
                            <i class="ti ti-message-x text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="text-muted">Chưa có hội thoại nào.</h6>
                    </div>
                @endforelse

                <div class="p-4 border-top">
                    {{ $conversations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
