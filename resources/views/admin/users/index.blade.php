@extends('layouts.admin')

@section('title', 'Quản lý Người dùng')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Người dùng</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Người dùng</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-s bg-light-primary">
                            <i class="ti ti-users f-20"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Tổng người dùng</h6>
                        <h4 class="mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-s bg-light-danger">
                            <i class="ti ti-shield-lock f-20 text-danger"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Quản trị viên</h6>
                        <h4 class="mb-0 fw-bold text-danger">{{ number_format($stats['admins']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-s bg-light-success">
                            <i class="ti ti-user-check f-20 text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Khách hàng</h6>
                        <h4 class="mb-0 fw-bold text-success">{{ number_format($stats['users']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-s bg-light-warning">
                            <i class="ti ti-user-plus f-20 text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Mới hôm nay</h6>
                        <h4 class="mb-0 fw-bold text-warning">{{ number_format($stats['new_today']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Danh sách người dùng</h5>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                </div>
                
                <!-- Filters Bar -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group border rounded bg-light">
                                <span class="input-group-text bg-transparent border-0"><i class="ti ti-search m-0"></i></span>
                                <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Tìm tên, email hoặc SĐT..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="role" class="form-select border-0 bg-light">
                                <option value="">Tất cả vai trò</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Khách hàng</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-select border-0 bg-light">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                <option value="order_count" {{ request('sort') == 'order_count' ? 'selected' : '' }}>Đơn hàng (Giảm)</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 shadow-none">Lọc</button>
                            @if(request()->hasAny(['search', 'role', 'sort']))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light-secondary px-3" title="Xóa lọc"><i class="ti ti-x m-0"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body px-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top">
                        <thead class="bg-light-secondary border-0">
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Người dùng</th>
                                <th style="width: 150px;">Số điện thoại</th>
                                <th style="width: 150px;">Vai trò</th>
                                <th style="width: 120px;">Hoạt động</th>
                                <th style="width: 150px;">Ngày tham gia</th>
                                <th class="sticky-action-column text-center" style="width: 100px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="text-muted">{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $user->avatar_url }}" 
                                                 alt="user" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->phone)
                                        <span class="text-dark"><i class="ti ti-phone me-1 text-muted"></i>{{ $user->phone }}</span>
                                    @else
                                        <span class="text-muted italic small">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge bg-light-danger text-danger border border-danger-subtle px-2">
                                            <i class="ti ti-shield-check me-1"></i>Administrator
                                        </span>
                                    @else
                                        <span class="badge bg-light-primary text-primary border border-primary-subtle px-2">
                                            <i class="ti ti-user me-1"></i>Customer
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->orders_count > 0)
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $user->orders_count }} đơn hàng</span>
                                            <div class="progress mt-1" style="height: 4px; width: 60px;">
                                                <div class="progress-bar bg-info" style="width: {{ min(100, $user->orders_count * 10) }}%"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Chưa có đơn</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted small"><i class="ti ti-calendar-event me-1"></i>{{ $user->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="sticky-action-column text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-light-warning btn-sm p-1 px-2 border-0 shadow-none" title="Chỉnh sửa">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline no-pjax" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light-danger btn-sm p-1 px-2 border-0 shadow-none" title="Xóa">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="btn btn-light-secondary btn-sm p-1 px-2 border-0 shadow-none disabled" title="Không thể xóa chính mình">
                                                <i class="ti ti-trash text-muted"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ti ti-users-off f-30 mb-2"></i>
                                        <p>Không tìm thấy người dùng nào phù hợp với bộ lọc.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection