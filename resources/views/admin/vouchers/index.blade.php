@extends('layouts.admin')

@section('title', 'Quản lý Phiếu Kho')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Quản lý Phiếu Kho</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Kho hàng</a></li>
                        <li class="breadcrumb-item"><a href="#!">Phiếu Nhập/Xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách Phiếu Kho</h5>
                    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary btn-sm">Tạo phiếu mới</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Mã phiếu</th>
                                    <th>Loại</th>
                                    <th>Kho</th>
                                    <th>Nhà cung cấp</th>
                                    <th>Ngày lập</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vouchers as $voucher)
                                    <tr>
                                        <td>{{ $voucher->voucher_code }}</td>
                                        <td>
                                            @if($voucher->type === 'INBOUND')
                                                <span class="badge bg-success">Nhập kho</span>
                                            @else
                                                <span class="badge bg-warning">Xuất kho</span>
                                            @endif
                                        </td>
                                        <td>{{ $voucher->warehouse->name }}</td>
                                        <td>{{ $voucher->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $voucher->voucher_date->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format($voucher->total_amount) }} đ</td>
                                        <td>
                                            @if($voucher->status === 'PENDING')
                                                <span class="badge bg-secondary">Chờ xử lý</span>
                                            @elseif($voucher->status === 'COMPLETED')
                                                <span class="badge bg-primary">Hoàn tất</span>
                                            @else
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.vouchers.show', $voucher) }}"
                                                class="btn btn-info btn-sm">Xem chi tiết</a>
                                            @if($voucher->status === 'PENDING')
                                                <form action="{{ route('admin.vouchers.complete', $voucher) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Xác nhận hoàn tất phiếu này?')">Duyệt</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $vouchers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection