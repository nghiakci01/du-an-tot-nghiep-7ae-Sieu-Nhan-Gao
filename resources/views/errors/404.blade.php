@extends('layouts.public')

@section('title', '404 Không tìm thấy trang | ' . config('app.name', 'Elite'))

@section('content')
    <!--error section area start-->
    <div class="error_section">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="error_form">
                        <h1>404</h1>
                        <h2>Opps! KHÔNG TÌM THẤY TRANG</h2>
                        <p>Xin lỗi nhưng trang bạn đang tìm kiếm không tồn tại, đã bị xóa, bị đổi tên hoặc tạm thời không thể truy cập.</p>
                        <form action="{{ route('shop') }}" method="GET">
                            <input name="search" placeholder="Bạn muốn tìm sản phẩm nào?..." type="text">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                        <a href="{{ route('welcome') }}">Quay Lại Trang Chủ</a>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!--error section area end-->
@endsection