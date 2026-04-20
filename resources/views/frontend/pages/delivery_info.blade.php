@extends('layouts.public')

@section('title', __('messages.delivery_information') . ' | Elite')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.delivery_information') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <div class="static_page_content mt-60 mb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm p-4 p-md-5">
                        <h1 class="mb-4 text-center fw-bold">{{ __('messages.delivery_information') }}</h1>
                        <hr class="mb-4">
                        
                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3"><i class="fa fa-truck me-2 text-primary"></i> 1. Phương thức vận chuyển</h3>
                            <p>Elite hợp tác với các đơn vị vận chuyển uy tín như <strong>Giao Hàng Nhanh (GHN)</strong>, Giao Hàng Tiết Kiệm (GHTK) và Viettel Post để đảm bảo sản phẩm đến tay khách hàng nhanh chóng và an toàn nhất.</p>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3"><i class="fa fa-clock-o me-2 text-primary"></i> 2. Thời gian giao hàng</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Khu vực</th>
                                            <th>Thời gian dự kiến</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Nội thành Hà Nội / TP. HCM</td>
                                            <td>1 - 2 ngày làm việc</td>
                                        </tr>
                                        <tr>
                                            <td>Các khu vực tỉnh / thành khác</td>
                                            <td>3 - 5 ngày làm việc</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="small text-muted mt-2"><em>* Thời gian có thể thay đổi tùy thuộc vào điều kiện thời tiết hoặc các dịp lễ Tết.</em></p>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3"><i class="fa fa-money me-2 text-primary"></i> 3. Phí vận chuyển</h3>
                            <ul>
                                <li><strong>Miễn phí vận chuyển:</strong> Áp dụng cho các đơn hàng có giá trị từ <strong>500,000 VNĐ</strong> trở lên trên toàn quốc.</li>
                                <li><strong>Đơn hàng dưới 500,000 VNĐ:</strong> Phí vận chuyển đồng giá (Flat rate) là 30,000 VNĐ.</li>
                            </ul>
                        </div>

                        <div class="content_section">
                            <h3 class="fw-bold mb-3"><i class="fa fa-search me-2 text-primary"></i> 4. Theo dõi đơn hàng</h3>
                            <p>Ngay sau khi đơn hàng được gửi đi, Elite sẽ gửi mã vận đơn qua Email và thông báo trong tài khoản của bạn. Bạn có thể sử dụng mã này để tra cứu trực tiếp tại trang <a href="{{ route('order-tracking.index') }}" class="text-primary fw-bold">Tra cứu đơn hàng</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .breadcrumb_content ul li { display: inline-block; margin-right: 5px; }
    .content_section h3 { font-size: 1.25rem; color: #333; }
    .content_section p, .content_section li { line-height: 1.8; color: #666; }
    .static_page_content { min-height: 50vh; }
</style>
