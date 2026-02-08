@extends('layouts.admin')

@section('title', 'Quản lý Banner')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Danh sách Banner</h4>
                        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Thêm Banner Mới</a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Hình ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Vị trí</th>
                                        <th>Thứ tự</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($banners as $banner)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner"
                                                    style="height: 50px; object-fit: cover;">
                                            </td>
                                            <td>{{ $banner->title ?? 'N/A' }}</td>
                                            <td>{{ $banner->position }}</td>
                                            <td>{{ $banner->sort_order }}</td>
                                            <td>
                                                @if($banner->is_active)
                                                    <span class="badge bg-success">Hiển thị</span>
                                                @else
                                                    <span class="badge bg-secondary">Ẩn</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                                    class="btn btn-sm btn-warning">Sửa</a>
                                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Chưa có banner nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection