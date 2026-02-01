@extends('layouts.admin')

@section('title', 'Sửa Câu hỏi gợi ý')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Sửa Câu hỏi gợi ý</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.chatbot', ['tab' => 'questions']) }}">Câu hỏi gợi ý</a></li>
                    <li class="breadcrumb-item"><a href="#!">Chỉnh sửa</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa Câu hỏi #{{ $question->id }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.chatbot.questions.update', $question) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label class="form-label">Câu hỏi <span class="text-danger">*</span></label>
                        <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question', $question->question) }}" placeholder="VD: Hàng mới về">
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Câu trả lời mẫu (Tùy chọn)</label>
                        <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" rows="4" placeholder="Nhập câu trả lời mẫu cho quy tắc (nếu có)">{{ old('answer', $question->answer) }}</textarea>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Thứ tự hiển thị <span class="text-danger">*</span></label>
                                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $question->order) }}">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label d-block">Trạng thái</label>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ $question->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Hiển thị</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.settings.chatbot', ['tab' => 'questions']) }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
