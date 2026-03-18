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

                                <div class="d-flex flex-wrap gap-2 justify-content-md-end mt-4">
                                    <button type="button" class="btn btn-outline-dark d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="ti ti-lock me-2 fs-5"></i> Đổi mật khẩu
                                    </button>
                                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                                        <i class="ti ti-check me-2 fs-5"></i> Cập nhật hồ sơ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Đổi mật khẩu -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title text-white d-flex align-items-center" id="changePasswordModalLabel">
                        <i class="ti ti-lock-password me-2 fs-4"></i> Đổi mật khẩu truy cập
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.profile.password.update') }}" method="POST" id="changePasswordForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu hiện tại</label>
                            <div class="input-group input-group-merge border rounded-3 overflow-hidden">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-key"></i></span>
                                <input type="password" class="form-control border-0 bg-light" name="current_password" id="current_password" required placeholder="Nhập mật khẩu đang dùng">
                            </div>
                            <small class="text-danger d-none" id="error-current_password"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới</label>
                            <div class="input-group input-group-merge border rounded-3 overflow-hidden">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-lock"></i></span>
                                <input type="password" class="form-control border-0 bg-light" name="new_password" id="new_password" required placeholder="Tối thiểu 8 ký tự">
                            </div>
                            <small class="text-danger d-none" id="error-new_password"></small>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                            <div class="input-group input-group-merge border rounded-3 overflow-hidden">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-circle-check"></i></span>
                                <input type="password" class="form-control border-0 bg-light" name="new_password_confirmation" id="new_password_confirmation" required placeholder="Nhập lại mật khẩu mới">
                            </div>
                            <small class="text-danger d-none" id="error-new_password_confirmation"></small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-dark fw-bold px-4 shadow-sm" id="btnSubmitPassword">Xác nhận thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#changePasswordForm');
            const currentPass = document.querySelector('#current_password');
            const newPass = document.querySelector('#new_password');
            const confirmPass = document.querySelector('#new_password_confirmation');
            const submitBtn = document.querySelector('#btnSubmitPassword');

            const errors = {
                current: document.querySelector('#error-current_password'),
                new: document.querySelector('#error-new_password'),
                confirm: document.querySelector('#error-new_password_confirmation')
            };

            function validate() {
                let isValid = true;
                
                // Reset Errors
                Object.values(errors).forEach(el => {
                    el.classList.add('d-none');
                    el.textContent = '';
                });

                // Check Current Password
                if (!currentPass.value) {
                    errors.current.textContent = 'Vui lòng nhập mật khẩu hiện tại.';
                    errors.current.classList.remove('d-none');
                    isValid = false;
                }

                // Check New Password
                if (newPass.value) {
                    if (newPass.value.length < 8) {
                        errors.new.textContent = 'Mật khẩu mới phải có ít nhất 8 ký tự.';
                        errors.new.classList.remove('d-none');
                        isValid = false;
                    } else if (newPass.value === currentPass.value) {
                        errors.new.textContent = 'Mật khẩu mới không được trùng với mật khẩu cũ.';
                        errors.new.classList.remove('d-none');
                        isValid = false;
                    }
                } else if (form.classList.contains('was-validated')) {
                     errors.new.textContent = 'Vui lòng nhập mật khẩu mới.';
                     errors.new.classList.remove('d-none');
                     isValid = false;
                }

                // Check Confirmation
                if (confirmPass.value !== newPass.value) {
                    errors.confirm.textContent = 'Mật khẩu xác nhận không khớp.';
                    errors.confirm.classList.remove('d-none');
                    isValid = false;
                }

                return isValid;
            }

            // Real-time validation on input
            [currentPass, newPass, confirmPass].forEach(input => {
                input.addEventListener('input', validate);
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                form.classList.add('was-validated');
                
                if (!validate()) {
                    return;
                }

                // Show loading state
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Đang kiểm tra...';

                try {
                    const response = await fetch('{{ route("admin.profile.check-password") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            current_password: currentPass.value
                        })
                    });

                    const data = await response.json();

                    if (!data.valid) {
                        errors.current.textContent = data.message;
                        errors.current.classList.remove('d-none');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        return;
                    }

                    // If valid, submit the form finally
                    form.submit();

                } catch (error) {
                    console.error('Lỗi kiểm tra mật khẩu:', error);
                    alert('Đã có lỗi xảy ra. Vui lòng thử lại sau.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        });
    </script>

    <style>
        /* Tối ưu cho Dark Mode của Modal */
        [data-pc-theme="dark"] .modal-content {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        [data-pc-theme="dark"] .modal-header.bg-dark {
            background: #111 !important;
        }
        [data-pc-theme="dark"] .modal-body .bg-light, 
        [data-pc-theme="dark"] .modal-body .input-group-text {
            background: #2a2a2a !important;
            color: #ccc !important;
        }
        [data-pc-theme="dark"] .modal-body input.form-control {
            color: #fff !important;
        }
        [data-pc-theme="dark"] .btn-outline-dark {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
        }
        [data-pc-theme="dark"] .btn-outline-dark:hover {
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
@endsection