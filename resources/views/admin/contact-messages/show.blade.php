@extends('layouts.admin')

@section('title', 'Chi tiết Liên hệ')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Chi tiết Liên hệ</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contact-messages.index') }}">Liên hệ</a></li>
                    <li class="breadcrumb-item"><a href="#!">Chi tiết</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Thông tin tin nhắn</h5>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
            </div>
            <div class="card-body">
                <div class="row m-b-20">
                    <div class="col-md-3 font-weight-bold">Họ tên:</div>
                    <div class="col-md-9">
                        {{ $message->name }} 
                        @if($message->user_id)
                            <span class="badge bg-success ms-2">Khách hàng: {{ $message->user->name }}</span>
                            <a href="{{ route('admin.users.show', $message->user_id) }}" class="btn btn-outline-info btn-xs ms-1">Xem hồ sơ</a>
                        @endif
                    </div>
                </div>
                <div class="row m-b-20">
                    <div class="col-md-3 font-weight-bold">Email:</div>
                    <div class="col-md-9">{{ $message->email }}</div>
                </div>
                <div class="row m-b-20">
                    <div class="col-md-3 font-weight-bold">Tiêu đề:</div>
                    <div class="col-md-9">{{ $message->subject }}</div>
                </div>
                <div class="row m-b-20">
                    <div class="col-md-3 font-weight-bold">Ngày gửi:</div>
                    <div class="col-md-9">{{ $message->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="row m-b-20">
                    <div class="col-md-3 font-weight-bold">Trạng thái:</div>
                    <div class="col-md-9">
                        @if($message->status == 'unread')
                            <span class="badge bg-danger">Chưa đọc</span>
                        @elseif($message->status == 'read')
                            <span class="badge bg-info">Đã đọc</span>
                        @else
                            <span class="badge bg-success">Đã phản hồi</span>
                        @endif
                    </div>
                </div>
                <div class="row m-b-20">
                    <div class="col-md-12 font-weight-bold m-b-10">Nội dung tin nhắn:</div>
                    <div class="col-md-12">
                        <div class="p-3 bg-light border rounded">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>
                </div>

                @if($message->reply_message)
                <hr>
                <div class="row m-b-20">
                    <div class="col-md-12 font-weight-bold m-b-10 text-success">
                        <i class="ti ti-check"></i> Nội dung đã phản hồi ({{ $message->replied_at->format('d/m/Y H:i') }}):
                    </div>
                    <div class="col-md-12">
                        <div class="p-3 bg-success-light border border-success rounded">
                            {!! nl2br(e($message->reply_message)) !!}
                        </div>
                    </div>
                </div>
                @else
                <hr>
                <div class="row m-b-20">
                    <div class="col-md-12 font-weight-bold m-b-10 text-primary">Phản hồi khách hàng:</div>
                    <div class="col-md-12">
                        <form action="{{ route('admin.contact-messages.reply', $message->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="reply_message" class="form-control" rows="5" placeholder="Nhập nội dung phản hồi tại đây..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-send me-1"></i> Gửi Email Phản Hồi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa tin nhắn này</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
