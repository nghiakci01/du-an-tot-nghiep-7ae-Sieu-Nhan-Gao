@extends('layouts.admin')

@section('title', 'Quản lý Câu hỏi gợi ý')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Câu hỏi gợi ý</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Chatbot</a></li>
                    <li class="breadcrumb-item"><a href="#!">Câu hỏi gợi ý</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Danh sách Câu hỏi gợi ý</h5>
                <a href="{{ route('admin.chatbot.questions.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="50">Sắp xếp</th>
                                <th>Câu hỏi</th>
                                <th>Câu trả lời (Tùy chọn)</th>
                                <th width="100">Trạng thái</th>
                                <th width="150">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $question)
                            <tr>
                                <td>{{ $question->order }}</td>
                                <td>{{ $question->question }}</td>
                                <td>
                                    @if($question->answer)
                                        <div class="text-truncate" style="max-width: 300px;">{{ $question->answer }}</div>
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>
                                    @if($question->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Nháp</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.chatbot.questions.edit', $question) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    <form action="{{ route('admin.chatbot.questions.destroy', $question) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Chưa có câu hỏi gợi ý nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
