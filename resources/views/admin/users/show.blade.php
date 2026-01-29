@extends('layouts.admin')

@section('title', 'Chi tiết Người dùng')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Chi tiết Người dùng</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                    <li class="breadcrumb-item"><a href="#!">Chi tiết</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Hồ sơ: {{ $user->name }}</h5>
                <div>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <img src="{{ asset('admin-assets/images/user/avatar-1.jpg') }}" class="user-avtar wid-100 rounded-circle mb-3">
                        <h4>{{ $user->name }}</h4>
                        <p>
                            @if($user->isAdmin())
                                <span class="badge bg-danger">Quản trị viên</span>
                            @elseif($user->isStaff())
                                <span class="badge bg-warning text-dark">Nhân viên</span>
                            @else
                                <span class="badge bg-primary">Khách hàng</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th>Địa chỉ:</th>
                                <td>{{ $user->address ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th>Ngày tham gia:</th>
                                <td>{{ $user->created_at->format('H:i d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật cuối:</th>
                                <td>{{ $user->updated_at->format('H:i d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
