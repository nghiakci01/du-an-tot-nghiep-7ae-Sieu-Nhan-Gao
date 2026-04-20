@extends('layouts.admin')

@section('title', 'Thêm Người dùng mới')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Thêm Người dùng mới</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                    <li class="breadcrumb-item"><a href="#!">Thêm mới</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Main Information (Left Column) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">Thông tin cá nhân</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Tên đầy đủ <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="VD: Nguyễn Văn A" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" placeholder="VD: 0987654321">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vai trò <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Khách hàng</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" placeholder="Số nhà, tên đường, quận/huyện...">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
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
                                   value="{{ old('email') }}" placeholder="email@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-lock"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror border-start-0" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-lock-check"></i></span>
                                <input type="password" name="password_confirmation" class="form-control border-start-0" required>
                            </div>
                        </div>

                        <div class="col-12 mt-4 border-top pt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-2">Lưu người dùng</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Quay lại danh sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
