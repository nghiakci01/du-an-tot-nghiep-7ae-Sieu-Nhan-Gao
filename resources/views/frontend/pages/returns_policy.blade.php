@extends('layouts.public')

@section('title', __('messages.returns') . ' | Elite')

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
                            <li>{{ __('messages.returns') }}</li>
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
                        <h1 class="mb-4 text-center fw-bold">{{ __('messages.returns') }}</h1>
                        <hr class="mb-4">
                        
                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3"><i class="fa fa-refresh me-2 text-danger"></i> 1. Chính sách trả hàng</h3>
                            <p>Elite mong muốn mang lại sự hài lòng tuyệt đối cho khách hàng. Nếu bạn không hài lòng với sản phẩm nhận được, bạn hoàn toàn có thể yêu cầu trả hàng trong vòng <strong>7 ngày</strong> kể từ ngày nhận hàng thành công.</p>
                            <div class="alert alert-warning border-0 shadow-sm mt-3">
                                <i class="fa fa-info-circle me-2"></i> <strong>Lưu ý:</strong> Hiện tại Elite hỗ trợ chính sách <strong>Trả hàng & Hoàn tiền</strong>. Nếu bạn vẫn muốn mua sản phẩm đó (nhưng đổi kích cỡ, màu sắc khác), vui lòng thực hiện yêu cầu trả hàng và <strong>đặt một đơn hàng mới</strong> trên website.
                            </div>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3"><i class="fa fa-check-circle me-2 text-danger"></i> 2. Điều kiện đổi trả</h3>
                            <p>Sản phẩm chỉ được chấp nhận đổi trả khi thỏa mãn các điều kiện sau:</p>
                            <ul>
                                <li>Sản phẩm còn nguyên tem mác, bao bì và chưa qua sử dụng, chưa qua giặt tẩy.</li>
                                <li>Sản phẩm không bị hư hỏng, trầy xước hoặc bám mùi lạ do tác động từ phía khách hàng.</li>
                                <li>Cung cấp được hóa đơn mua hàng hoặc thông tin đơn hàng trên hệ thống.</li>
                                <li>Sản phẩm bị lỗi từ nhà sản xuất hoặc giao sai mẫu mã, kích thước.</li>
                            </ul>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3"><i class="fa fa-money me-2 text-danger"></i> 3. Chính sách hoàn tiền</h3>
                            <p>Trong trường hợp trả hàng thành công, Elite sẽ hoàn tiền vào tài khoản ngân hàng của bạn trong vòng <strong>3-5 ngày làm việc</strong> sau khi chúng tôi nhận lại và kiểm tra sản phẩm đạt điều kiện.</p>
                        </div>

                        <div class="content_section">
                            <h3 class="fw-bold mb-3"><i class="fa fa-phone-square me-2 text-danger"></i> 4. Quy trình trả hàng</h3>
                            <ol>
                                <li>Đăng nhập và gửi yêu cầu trả hàng tại <a href="{{ route('account.index', ['tab' => 'orders']) }}" class="text-danger fw-bold">Đơn hàng của tôi</a>.</li>
                                <li>Hoặc liên hệ Hotline <strong>0372.844.577</strong> để nhân viên hỗ trợ trực tiếp.</li>
                                <li>Đóng gói sản phẩm và gửi về địa chỉ: Số 7 Ngõ 91 Lai Xá - Hoài Đức - TP Hà Nội.</li>
                                <li>Sau khi nhận được hàng trả lại, Elite sẽ tiến hành hoàn tiền. <strong>Để có màu sắc hoặc kích cỡ khác, quý khách vui lòng đặt một đơn hàng mới.</strong></li>
                                <li>Phí vận chuyển: Elite miễn phí nếu lỗi do shop. Trường hợp quý khách đổi ý, vui lòng thanh toán phí ship.</li>
                            </ol>
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
    .content_section p, .content_section li, .content_section ol li { line-height: 1.8; color: #666; }
</style>
