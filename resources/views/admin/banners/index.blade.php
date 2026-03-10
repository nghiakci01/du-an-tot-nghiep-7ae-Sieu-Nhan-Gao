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
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Thứ tự</th>
                                        <th>Hình ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Vị trí</th>
                                        <th>Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($banners as $banner)
                                        <tr>
                                            <td><span class="badge bg-light-primary text-primary">{{ $banner->sort_order }}</span></td>
                                            <td>
                                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner"
                                                    class="rounded" style="height: 60px; width: 120px; object-fit: cover; border: 1px solid #eee;">
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $banner->title ?? 'Không có tiêu đề' }}</div>
                                                @if($banner->link)
                                                    <div class="small text-muted text-truncate" style="max-width: 200px;">
                                                        <i class="ti ti-link"></i> {{ $banner->link }}
                                                    </div>
                                                @endif
                                            </td>
                                             <td>
                                                @switch($banner->position)
                                                    @case('slider')
                                                        <span class="badge bg-light-info text-info">Wide Slider (1521x856px)</span>
                                                        @break
                                                    @case('about_us')
                                                        <span class="badge bg-light-primary text-primary">Banner Về Chúng Tôi</span>
                                                        @break

                                                    @default
                                                        <span class="badge bg-light-secondary text-secondary">Khác: {{ $banner->position }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($banner->is_active)
                                                    <span class="badge bg-success"><i class="ti ti-check small"></i> Hiển thị</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="ti ti-eye-off small"></i> Ẩn</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.banners.edit', $banner) }}"
                                                        class="btn btn-sm btn-icon btn-light-warning" title="Chỉnh sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
                                                        class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Xóa">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="ti ti-info-circle fs-2"></i><br>
                                                Chưa có banner nào được tạo.
                                            </td>
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