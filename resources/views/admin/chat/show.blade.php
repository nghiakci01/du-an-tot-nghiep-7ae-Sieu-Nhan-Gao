@extends('layouts.admin')

@section('title', 'Chi tiết hội thoại')

@section('content')
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
                <div class="page-header-title">
                    <h2 class="mb-0">Hội thoại: {{ substr($sessionId, 0, 8) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5>Lịch sử tin nhắn</h5>
            </div>
            <div class="card-body chat-scroll" style="height: 450px; overflow-y: auto;">
                @foreach($messages as $msg)
                    <div class="mb-3 d-flex {{ $msg->sender_type == 'user' ? 'justify-content-start' : 'justify-content-end' }}">
                        <div class="p-3 rounded {{ $msg->sender_type == 'user' ? 'bg-light text-dark' : 'bg-primary text-white' }}" style="max-width: 75%;">
                            <p class="mb-1">{{ $msg->message }}</p>
                            <small class="opacity-75 d-block text-end" style="font-size: 0.7rem;">
                                {{ $msg->sender_type == 'user' ? 'Khách' : ($msg->sender_type == 'bot' ? 'Bot' : 'Bạn') }} - {{ $msg->created_at->format('H:i') }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <form action="{{ route('admin.chat.reply', $sessionId) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" placeholder="Nhập tin nhắn trả lời..." required>
                        <button class="btn btn-primary" type="submit">Gửi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Scroll to bottom on load
    const chatScroll = document.querySelector('.chat-scroll');
    chatScroll.scrollTop = chatScroll.scrollHeight;
</script>
@endsection
@endsection
