@extends('layouts.public')

@section('title', '404 Không tìm thấy trang | FashionStore')

@section('content')
    <!--error section area start-->
    <div class="error_section" style="padding: 100px 0;">
        <div class="container">   
            <div class="row">
                <div class="col-12 text-center">
                    <div class="error_form">
                        <h1 style="font-size: 150px; font-weight: bold; color: #ef233c; line-height: 1;">404</h1>
                        <h2 style="font-size: 30px; margin-bottom: 20px;">Opps! KHÔNG TÌM THẤY TRANG</h2>
                        <p style="margin-bottom: 30px;">Xin lỗi nhưng trang bạn đang tìm kiếm không tồn tại, đã bị xóa, bị thay đổi tên hoặc tạm thời không có sẵn.</p>
                        <form action="{{ route('shop') }}" method="GET" style="max-width: 500px; margin: 0 auto 30px auto; position: relative;">
                            <input name="search" placeholder="Tìm kiếm sản phẩm..." type="text" style="width: 100%; height: 50px; border: 1px solid #ddd; border-radius: 25px; padding: 0 20px;">
                            <button type="submit" style="position: absolute; right: 0; top: 0; width: 50px; height: 50px; border: none; background: #ef233c; color: #fff; border-radius: 0 25px 25px 0;"><i class="fa fa-search"></i></button>
                        </form>
                        <a href="{{ route('welcome') }}" class="btn btn-primary" style="background: #252525; border: none; padding: 10px 30px; border-radius: 25px; color: #fff; text-transform: uppercase; font-weight: bold;">Quay Lại Trang Chủ</a>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!--error section area end-->
@endsection