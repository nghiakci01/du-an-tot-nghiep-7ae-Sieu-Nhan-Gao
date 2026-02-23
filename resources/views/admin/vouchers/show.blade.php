@extends('layouts.admin')

@section('title', 'Chi tiết Phiếu Kho')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Chi tiết Phiếu: {{ $voucher->voucher_code }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Phiếu Kho</a></li>
                        <li class="breadcrumb-item"><a href="#!">Chi tiết</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px">Mã phiếu:</th>
                            <td><strong>{{ $voucher->voucher_code }}</strong></td>
                        </tr>
                        <tr>
                            <th>Loại:</th>
                            <td>
                                @if($voucher->type === 'INBOUND')
                                    <span class="badge bg-success">Nhập kho</span>
                                @else
                                    <span class="badge bg-warning">Xuất kho</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kho:</th>
                            <td>{{ $voucher->warehouse->name }}</td>
                        </tr>
                        <tr>
                            <th>Nhà cung cấp:</th>
                            <td>{{ $voucher->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Người lập:</th>
                            <td>{{ $voucher->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Ngày lập:</th>
                            <td>{{ $voucher->voucher_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                @if($voucher->status === 'PENDING')
                                    <span class="badge bg-secondary">Chờ xử lý</span>
                                @elseif($voucher->status === 'COMPLETED')
                                    <span class="badge bg-primary">Hoàn tất</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tổng tiền:</th>
                            <td><strong class="text-primary">{{ number_format($voucher->total_amount) }} đ</strong></td>
                        </tr>
                    </table>

                    @if($voucher->status === 'PENDING')
                        <hr>
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.vouchers.complete', $voucher) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block"
                                    onclick="return confirm('Xác nhận hoàn tất phiếu này?')">Duyệt & Cập nhật tồn kho</button>
                            </form>
                            <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-block"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa phiếu</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Danh sách sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>SKU</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($voucher->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $detail->variant->product->name }} <br>
                                            <small class="text-muted">{{ $detail->variant->size }} /
                                                {{ $detail->variant->color }}</small>
                                        </td>
                                        <td>{{ $detail->variant->sku }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ number_format($detail->unit_price) }} đ</td>
                                        <td>{{ number_format($detail->quantity * $detail->unit_price) }} đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Tổng cộng:</th>
                                    <th>{{ number_format($voucher->total_amount) }} đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @if($voucher->note)
                        <div class="mt-3">
                            <h6>Ghi chú:</h6>
                            <p class="text-muted">{{ $voucher->note }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection