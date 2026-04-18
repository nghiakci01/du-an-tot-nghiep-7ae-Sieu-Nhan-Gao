@extends('layouts.admin')

@section('title', 'Thùng rác hội thoại')

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
        display: block;
        color: inherit;
        text-decoration: none !important;
    }
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.chat.index') }}">Hỗ trợ khách hàng</a></li>
                    <li class="breadcrumb-item" aria-current="page">Thùng rác</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Thùng rác hội thoại</h2>
                    <a href="{{ route('admin.chat.index') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="card chat-list-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-danger"><i class="ti ti-trash me-2"></i>Các hội thoại đã xóa</h5>
            </div>
            <div class="card-body p-0">
                @if(count($conversations) > 0)
                    @foreach($conversations as $chat)
                    <div class="chat-item-wrapper position-relative border-bottom">
                        <div class="chat-item">
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
                                                {{ $chat->last_message }}
                                            </p>
                                        </div>
                                        <div class="text-end me-5">
                                            <span class="chat-time d-block mb-1">Xóa: {{ $chat->deleted_at }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Actions -->
                                <div class="d-flex gap-2 ms-3 flex-shrink-0">
                                    <form action="{{ route('admin.chat.restore', $chat->session_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light-success px-3" title="Khôi phục">
                                            <i class="ti ti-refresh me-1"></i> Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.chat.permanent', $chat->session_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa VĨNH VIỄN hội thoại này? Hành động này không thể hoàn tác.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger px-3" title="Xóa vĩnh viễn">
                                            <i class="ti ti-trash me-1"></i> Xóa vĩnh viễn
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="p-5 text-center">
                        <div class="mb-3">
                            <i class="ti ti-trash-x text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="text-muted">Thùng rác trống.</h6>
                    </div>
                @endif

                <div class="p-4 border-top">
                    {{ $conversations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
