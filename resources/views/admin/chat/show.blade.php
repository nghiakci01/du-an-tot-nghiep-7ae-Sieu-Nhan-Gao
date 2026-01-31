@extends('layouts.admin')

@section('title', 'Chi tiết hội thoại')

@section('content')
<style>
    .chat-container-admin {
        height: calc(100vh - 250px);
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .chat-messages-admin {
        flex: 1;
        padding: 30px;
        overflow-y: auto;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .msg-wrapper {
        display: flex;
        flex-direction: column;
        max-width: 70%;
    }
    .msg-wrapper.user {
        align-self: flex-start;
    }
    .msg-wrapper.staff {
        align-self: flex-end;
        align-items: flex-end;
    }
    .msg-wrapper.bot {
        align-self: flex-start;
        opacity: 0.8;
    }
    .msg-bubble {
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
    }
    .user .msg-bubble {
        background: white;
        color: #1a1a1a;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .staff .msg-bubble {
        background: linear-gradient(135deg, #7146ce 0%, #9063f2 100%);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 5px 15px rgba(113, 70, 206, 0.2);
    }
    .bot .msg-bubble {
        background: #eef2ff;
        color: #4338ca;
        border: 1px dashed #c7d2fe;
        border-bottom-left-radius: 4px;
    }
    .msg-info {
        font-size: 10px;
        color: #94a3b8;
        margin-top: 5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .chat-input-admin {
        padding: 25px;
        background: white;
        border-top: 1px solid #f1f5f9;
    }
    .admin-input-group {
        display: flex;
        gap: 15px;
    }
    .admin-input-group input {
        border-radius: 12px;
        padding: 12px 20px;
        border: 2px solid #f1f5f9;
        transition: all 0.3s;
    }
    .admin-input-group input:focus {
        border-color: #7146ce;
        box-shadow: 0 0 0 4px rgba(113, 70, 206, 0.1);
    }
    .btn-send-admin {
        background: #7146ce;
        color: white;
        border-radius: 12px;
        padding: 0 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-send-admin:hover {
        background: #5b36af;
        transform: translateY(-2px);
    }
</style>

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.chat.index') }}">Hỗ trợ khách hàng</a></li>
                    <li class="breadcrumb-item" aria-current="page">Chi tiết hội thoại</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Hội thoại với {{ $user ? $user->name : substr($sessionId, 0, 8) }}</h2>
                    @if($user)
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm rounded-pill shadow-sm px-4">
                            <i class="ti ti-user-circle me-1"></i> Xem Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="chat-container-admin">
            <div class="chat-messages-admin" id="admin-chat-box">
                @foreach($messages as $msg)
                    <div class="msg-wrapper {{ $msg->sender_type }} position-relative">
                        <div class="msg-bubble">
                            {{ $msg->message }}

                            @if($msg->sender_type == 'bot' && !empty($msg->payload['products']))
                                <div class="admin-recommended-products mt-3 border-top pt-3">
                                    <p class="small text-muted mb-2"><i class="ti ti-shopping-cart"></i> Sản phẩm AI đã đề xuất:</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($msg->payload['products'] as $product)
                                            <div class="card mb-0 shadow-none border p-2" style="width: 150px;">
                                                <img src="{{ $product['image'] }}" class="card-img-top rounded mb-2" style="height: 80px; object-fit: cover;">
                                                <div class="card-body p-0">
                                                    <h6 class="card-title mb-1" style="font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product['name'] }}</h6>
                                                    <p class="text-primary fw-bold mb-0" style="font-size: 10px;">{{ $product['price_formatted'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="msg-info d-flex justify-content-between align-items-center w-100 mt-2">
                            <span class="flex-grow-1">
                                @if($msg->sender_type == 'user')
                                    KHÁCH HÀNG
                                @elseif($msg->sender_type == 'bot')
                                    AI ASSISTANT
                                @else
                                    NHÂN VIÊN
                                @endif
                                • {{ $msg->created_at->format('H:i') }}
                            </span>
                            
                            <!-- Delete Message Action -->
                            <div class="ms-2">
                                <form action="{{ route('admin.chat.destroy_message', $msg->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 d-flex align-items-center" title="Xóa tin nhắn">
                                        <i class="ti ti-x fs-6"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="chat-input-admin">
                <form action="{{ route('admin.chat.reply', $sessionId) }}" method="POST">
                    @csrf
                    <div class="admin-input-group">
                        <input type="text" name="message" class="form-control" placeholder="Nhập câu trả lời cho khách hàng..." required autocomplete="off">
                        <button class="btn btn-send-admin" type="submit">Gửi tin nhắn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Scroll to bottom
    const box = document.getElementById('admin-chat-box');
    box.scrollTop = box.scrollHeight;
</script>
@endsection
@endsection
