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
                    <div class="col-md-9">{{ $message->name }}</div>
                </div>
                <div class="row m-b-20">
                    <div class="col-md-3 font-weight-bold">Email:</div>
                    <div class="col-md-9">{{ $message->email }} <a href="mailto:{{ $message->email }}" class="btn btn-outline-primary btn-xs ml-2">Gửi email phản hồi</a></div>
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
                <hr>
                <div class="row m-b-20">
                    <div class="col-md-12 font-weight-bold m-b-10">Nội dung tin nhắn:</div>
                    <div class="col-md-12">
                        <div class="p-3 bg-light border rounded">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>
                </div>

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
