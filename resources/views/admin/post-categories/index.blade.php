@extends('layouts.admin')

@section('title', 'Quản lý Danh mục Tin tức')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Quản lý Danh mục Tin tức</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                     class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Tin tức</a></li>
                        <li class="breadcrumb-item"><a href="#!">Danh mục</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách Danh mục</h5>
                    <a href="{{ route('admin.post-categories.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Slug</th>
                                    <th>Trạng thái</th>
                                    <th>Số bài viết</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @if($category->is_active)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Khóa</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->posts_count }}</td>
                                        <td>
                                            <a href="{{ route('admin.post-categories.edit', $category) }}"
                                                class="btn btn-warning btn-sm">Sửa</a>
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('admin.post-categories.destroy', $category) }}" method="POST"
                                                class="d-inline no-pjax">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('delete-form-{{ $category->id }}')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
