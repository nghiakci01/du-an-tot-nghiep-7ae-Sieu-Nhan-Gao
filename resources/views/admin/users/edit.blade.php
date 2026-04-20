@extends('layouts.admin')

@section('title', 'Cập nhật Người dùng')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Cập nhật Người dùng</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                    <li class="breadcrumb-item"><a href="#!">#{{ $user->id }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Main Information (Left Column) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">Thông tin cá nhân</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-12 text-center mb-3">
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle shadow-sm border p-1" style="width: 100px; height: 100px; object-fit: cover;">
                            <h6 class="mt-2 text-muted small">Tài khoản tham gia từ {{ $user->created_at->format('d/m/Y') }}</h6>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Tên đầy đủ <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" placeholder="VD: Nguyễn Văn A" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $user->phone) }}" placeholder="VD: 0987654321">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vai trò <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Khách hàng</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" placeholder="Số nhà, tên đường, quận/huyện...">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 border-start border-4 border-info">
                <div class="card-body">
                    <h6 class="fw-bold text-info"><i class="ti ti-history me-1"></i>Hoạt động gần nhất</h6>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Tổng đơn hàng đã đặt:</span>
                            <span class="badge bg-light-info text-info">{{ $user->orders_count }} đơn hàng</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0">
                            <span class="text-muted small">Cập nhật lần cuối:</span>
                            <span class="text-dark small">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Account Settings (Right Column) -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 sticky-lg-top" style="top: 2rem;">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">Tài khoản & Bảo mật</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" placeholder="email@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 border-top pt-3 mt-4">
                            <div class="alert alert-warning p-2 small mb-3 border-0 shadow-none">
                                <i class="ti ti-info-circle me-1"></i>Để trống mật khẩu nếu không muốn thay đổi.
                            </div>
                            <label class="form-label fw-bold">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-lock"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror border-start-0 text-center">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-lock-check"></i></span>
                                <input type="password" name="password_confirmation" class="form-control border-start-0 text-center">
                            </div>
                        </div>

                        <div class="col-12 mt-4 border-top pt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-2">Cập nhật người dùng</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Hủy bỏ</a>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->id !== auth()->id())
            <div class="card shadow-sm border-0 border-start border-danger border-4">
                <div class="card-body p-3 text-center">
                    <h6 class="text-danger fw-bold mb-2"><i class="ti ti-alert-triangle me-1"></i>Vùng nguy hiểm</h6>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="no-pjax" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">Xóa tài khoản này</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection
