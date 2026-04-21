@extends('layouts.public')

@section('title', __('messages.privacy_policy') . ' | Elite')

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
                            <li>{{ __('messages.privacy_policy') }}</li>
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
                        <h1 class="mb-4 text-center fw-bold">{{ __('messages.privacy_policy') }}</h1>
                        <hr class="mb-4">
                        
                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">1. Thu thập thông tin cá nhân</h3>
                            <p>Khi bạn truy cập và sử dụng dịch vụ tại Elite, chúng tôi có thể yêu cầu bạn cung cấp các thông tin cá nhân như: Họ tên, địa chỉ Email, số điện thoại, địa chỉ giao hàng và thông tin thanh toán. Các thông tin này giúp chúng tôi xử lý đơn hàng và cải thiện chất lượng phục vụ.</p>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">2. Sử dụng thông tin</h3>
                            <p>Thông tin của bạn được sử dụng cho các mục đích:</p>
                            <ul>
                                <li>Xử lý và giao đơn hàng của bạn.</li>
                                <li>Gửi thông báo về trạng thái đơn hàng.</li>
                                <li>Cung cấp thông tin sản phẩm mới, chương trình khuyến mãi (nếu bạn đăng ký).</li>
                                <li>Nâng cao trải nghiệm người dùng trên website.</li>
                            </ul>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">3. Bảo mật thông tin</h3>
                            <p>Elite cam kết bảo mật tuyệt đối thông tin cá nhân của khách hàng bằng các biện pháp kỹ thuật và an ninh hiện đại. Chúng tôi không bán, chia sẻ hay trao đổi thông tin cá nhân của khách hàng cho bên thứ ba, ngoại trừ các trường hợp cần thiết để thực hiện giao hàng (như đơn vị vận chuyển) hoặc theo yêu cầu của pháp luật.</p>
                        </div>

                        <div class="content_section mb-5">
                            <h3 class="fw-bold mb-3">4. Cookie</h3>
                            <p>Chúng tôi sử dụng cookie để ghi nhớ phiên đăng nhập và các tùy chọn cá nhân của bạn, giúp việc mua sắm trở nên thuận tiện hơn. Bạn có thể tùy chỉnh trình duyệt để từ chối cookie, tuy nhiên điều này có thể ảnh hưởng đến một số tính năng của website.</p>
                        </div>

                        <div class="content_section">
                            <h3 class="fw-bold mb-3">5. Thay đổi chính sách</h3>
                            <p>Elite có quyền cập nhật chính sách bảo mật này bất cứ lúc nào. Mọi thay đổi sẽ được đăng tải trực tiếp tại trang này để khách hàng có thể nắm bắt kịp thời.</p>
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
