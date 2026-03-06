@extends('layouts.admin')

@section('title', 'Tài khoản của tôi')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Tài khoản & Cài đặt</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 text-center">
                                <h6 class="mb-3">Ảnh đại diện</h6>
                                <div class="mb-3">
                                    @if($user->avatar)
                                        <img id="admin-avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="Ảnh đại diện"
                                            class="rounded-circle img-thumbnail"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <img id="admin-avatar-preview"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                            alt="Ảnh đại diện" class="rounded-circle img-thumbnail"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="avatar" class="btn btn-outline-primary btn-sm">Tải ảnh mới lên</label>
                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*"
                                        onchange="document.getElementById('admin-avatar-preview').src = window.URL.createObjectURL(this.files[0])">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <h6 class="mb-3">Thông tin cá nhân</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $user->name) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa chỉ email</label>
                                        <input type="text" class="form-control" value="{{ $user->email }}" readonly
                                            disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ old('phone', $user->phone) }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Địa chỉ</label>
                                        <textarea class="form-control" name="address"
                                            rows="3">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="mb-3">Đổi mật khẩu</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mật khẩu hiện tại</label>
                                        <input type="password" class="form-control" name="current_password">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mật khẩu mới</label>
                                        <input type="password" class="form-control" name="new_password">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Xác nhận mật khẩu</label>
                                        <input type="password" class="form-control" name="new_password_confirmation">
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <button type="submit" class="btn btn-primary">Cập nhật hồ sơ</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection