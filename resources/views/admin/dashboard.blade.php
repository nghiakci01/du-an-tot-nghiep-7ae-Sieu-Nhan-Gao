@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Dashboard Overview</h5>
            </div>
            <div class="card-body">
                <p>Chào mừng bạn đến với trang quản trị dự án Bán Quần Áo!</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card bg-light-primary order-card">
            <div class="card-body">
                <h6 class="text-primary">Đơn Hàng Hôm Nay</h6>
                <h2 class="text-end text-primary"><i class="feather icon-shopping-cart float-start"></i><span>5</span></h2>
                <p class="m-b-0">Đã Hoàn Thành<span class="float-end">3</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-light-success order-card">
            <div class="card-body">
                <h6 class="text-success">Doanh Thu</h6>
                <h2 class="text-end text-success"><i class="feather icon-tag float-start"></i><span>10 Tr</span></h2>
                <p class="m-b-0">Tháng Này<span class="float-end">50 Tr</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-light-warning order-card">
            <div class="card-body">
                <h6 class="text-warning">Sản Phẩm Mới</h6>
                <h2 class="text-end text-warning"><i class="feather icon-repeat float-start"></i><span>15</span></h2>
                <p class="m-b-0">Tổng Sản Phẩm<span class="float-end">150</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-light-danger order-card">
            <div class="card-body">
                <h6 class="text-danger">Khách Hàng</h6>
                <h2 class="text-end text-danger"><i class="feather icon-award float-start"></i><span>50</span></h2>
                <p class="m-b-0">Đang Online<span class="float-end">2</span></p>
            </div>
        </div>
    </div>
    <!-- [ sample-page ] end -->
</div>
@endsection
