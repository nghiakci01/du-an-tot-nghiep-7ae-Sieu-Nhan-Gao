@extends('layouts.admin')

@section('title', 'Lịch sử Tích điểm')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Quản lý Tích điểm</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                     class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Hội viên</a></li>
                        <li class="breadcrumb-item"><a href="#!">Lịch sử tích điểm</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Lịch sử giao dịch điểm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Khách hàng</th>
                                    <th>Điểm</th>
                                    <th>Nội dung</th>
                                    <th>Đơn hàng</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($points) > 0)
                                    @foreach($points as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            <strong>{{ $item->user->name }}</strong><br>
                                            <small class="text-muted">{{ $item->user->email }}</small>
                                        </td>
                                        <td>
                                            @if($item->points > 0)
                                                <span class="badge bg-success">+{{ $item->points }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $item->points }}</span>
                                            @endif
                                        </td>
                                        <td class="text-start">{{ $item->description }}</td>
                                        <td>
                                            @if($item->order_id)
                                                <a href="{{ route('admin.orders.show', $item->order_id) }}">#{{ $item->order_id }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">Chưa có dữ liệu tích điểm.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $points->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
