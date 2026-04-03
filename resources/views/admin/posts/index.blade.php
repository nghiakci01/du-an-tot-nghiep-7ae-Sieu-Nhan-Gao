@extends('layouts.admin')

@section('title', 'Quản lý Tin tức')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Quản lý Tin tức</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                     class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Tin tức</a></li>
                        <li class="breadcrumb-item"><a href="#!">Danh sách tin tức</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách Tin tức</h5>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">Thêm tin tức mới</a>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Danh mục</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đăng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>
                                            @if($post->image)
                                                <img src="{{ Storage::url($post->image) }}" alt="thumb" width="60" class="rounded shadow-sm">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td class="text-start">{{ Str::limit($post->title, 40) }}</td>
                                        <td>{{ $post->category->name }}</td>
                                        <td>
                                            @if($post->is_active)
                                                <span class="badge bg-success">Hiển thị</span>
                                            @else
                                                <span class="badge bg-danger">Ẩn</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Chưa đặt' }}</td>
                                        <td>
                                            <a href="{{ route('admin.posts.edit', $post) }}"
                                                class="btn btn-warning btn-sm">Sửa</a>
                                            <form id="delete-form-{{ $post->id }}"
                                                action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('delete-form-{{ $post->id }}')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
