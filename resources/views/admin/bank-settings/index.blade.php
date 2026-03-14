@extends('layouts.admin')

@section('title', 'Quản lý Tài Khoản Ngân Hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Quản lý Tài Khoản Ngân Hàng (Mã QR)</h3>
                    <a href="{{ route('admin.bank-settings.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Ngân Hàng</th>
                                    <th>Mã Ngân Hàng (Shortcode)</th>
                                    <th>Số Tài Khoản</th>
                                    <th>Tên Chủ Tài Khoản</th>
                                    <th>Mặc định</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($banks) > 0)
                                    @foreach($banks as $index => $bank)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $bank->bank_name }}</td>
                                        <td>{{ $bank->bank_id }}</td>
                                        <td>{{ $bank->account_number }}</td>
                                        <td>{{ $bank->account_name }}</td>
                                        <td>
                                            @if($bank->is_default)
                                                <span class="badge badge-success bg-success">Mặc định</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bank->is_active)
                                                <span class="badge badge-primary bg-primary">Hoạt động</span>
                                            @else
                                                <span class="badge badge-danger bg-danger">Tạm ẩn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.bank-settings.edit', $bank->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                            <form action="{{ route('admin.bank-settings.destroy', $bank->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">Chưa có ngân hàng nào.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $banks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
