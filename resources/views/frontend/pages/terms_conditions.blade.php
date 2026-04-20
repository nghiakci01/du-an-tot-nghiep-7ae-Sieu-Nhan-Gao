@extends('layouts.public')

@section('title', __('messages.terms_conditions') . ' | Elite')

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
                            <li>{{ __('messages.terms_conditions') }}</li>
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
                        <h1 class="mb-4 text-center fw-bold">{{ __('messages.terms_conditions') }}</h1>
                        <hr class="mb-4">
                        
                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">1. Chấp nhận các điều khoản</h3>
                            <p>Bằng việc truy cập và sử dụng website <strong>Elite</strong>, bạn mặc định chấp nhận các điều khoản và điều kiện sử dụng được quy định tại đây. Nếu bạn không đồng ý với bất kỳ điều khoản nào, vui lòng ngừng sử dụng dịch vụ.</p>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">2. Quyền sở hữu trí tuệ</h3>
                            <p>Mọi nội dung trên website bao gồm hình ảnh, logo, văn bản, mã nguồn và thiết kế đều thuộc sở hữu của Elite. Nghiêm cấm mọi hành vi sao chép, sử dụng lại cho mục đích thương mại khi chưa có sự đồng ý bằng văn bản từ chúng tôi.</p>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">3. Thông tin đơn hàng và Giá cả</h3>
                            <p>Chúng tôi luôn nỗ lực để thông tin về giá cả và mô tả sản phẩm trên website là chính xác nhất. Tuy nhiên, Elite có quyền từ chối hoặc hủy đơn hàng trong trường hợp thông tin giá bị lỗi kỹ thuật hoặc sự cố ngoài ý muốn. Trong trường hợp này, chúng tôi sẽ liên hệ và hoàn trả tiền cho khách hàng (nếu đã thanh toán).</p>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">4. Trách nhiệm của người dùng</h3>
                            <p>Người dùng cam kết cung cấp thông tin chính xác khi đăng ký tài khoản và chịu trách nhiệm bảo mật thông tin đăng nhập của mình. Elite không chịu trách nhiệm cho các vấn đề phát sinh do rò rỉ thông tin từ phía người dùng.</p>
                        </div>

                        <div class="content_section">
                            <h3 class="fw-bold mb-3">5. Luật áp dụng</h3>
                            <p>Các điều khoản này được điều chỉnh theo pháp luật hiện hành của Việt Nam. Mọi tranh chấp phát sinh sẽ được giải quyết tại tòa án có thẩm quyền tại Hà Nội.</p>
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
</style>
