@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Liên hệ</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Liên hệ</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Danh sách tin nhắn liên hệ</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Ngày gửi</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                            <tr class="{{ $message->status == 'unread' ? 'font-weight-bold' : '' }}">
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>
                                    @if($message->status == 'unread')
                                        <span class="badge bg-danger">Chưa đọc</span>
                                    @elseif($message->status == 'read')
                                        <span class="badge bg-info">Đã đọc</span>
                                    @else
                                        <span class="badge bg-success">Đã phản hồi</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-primary btn-sm">Xem</a>
                                    <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($messages->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">Không có tin nhắn nào.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
