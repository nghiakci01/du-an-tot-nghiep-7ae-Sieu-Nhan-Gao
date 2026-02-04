@extends('layouts.public')

@section('title', 'Về chúng tôi | Reid Fashion')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">trang chủ</a></li>
                            <li>/</li>
                            <li>về chúng tôi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
    <!--about section area -->
    <div class="about_section mt-60">
        <div class="container">  
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="about_thumb">
                        <img src="{{ asset('frontend-assets/img/about/about1.jpg') }}" alt="Về chúng tôi">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="about_content">
                        <h1>Chào mừng bạn đến với Reid Fashion!</h1>
                        <p>Reid Fashion là điểm đến tin cậy cho những tín đồ thời trang yêu thích sự tối giản, hiện đại và chất lượng. Chúng tôi không chỉ cung cấp quần áo, mà còn mang đến phong cách sống và sự tự tin cho mỗi khách hàng.</p>
                        <p>Với sứ mệnh mang lại những sản phẩm chất lượng cao nhất với giá thành hợp lý, đội ngũ của chúng tôi luôn nỗ lực không ngừng để tuyển chọn những mẫu thiết kế mới nhất, bắt kịp xu hướng thời trang thế giới.</p>
                        <div class="view__work">
                            <a href="{{ route('shop') }}">Xem sản phẩm ngay <i class="fa fa-angle-right"></i></a>
                        </div>  
                    </div>
                </div>
            </div>
        </div>     
    </div>
    <!--about section end-->

    <!--chose us area start-->
    <div class="choseus_area mt-60 mb-60">
        <div class="container">   
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="{{ asset('frontend-assets/img/about/shipping1.png') }}" alt="">
                        </div>
                        <div class="chose_content">
                            <h3>Chất lượng cao</h3>
                            <p>Mỗi sản phẩm đều được kiểm tra kỹ lưỡng về chất liệu và đường may trước khi đến tay bạn.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="{{ asset('frontend-assets/img/about/shipping2.png') }}" alt="">
                        </div>
                        <div class="chose_content">
                            <h3>Vận chuyển nhanh</h3>
                            <p>Chúng tôi cam kết giao hàng nhanh chóng và đóng gói cẩn thận để đảm bảo sản phẩm nguyên vẹn.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="{{ asset('frontend-assets/img/about/shipping3.png') }}" alt="">
                        </div>
                        <div class="chose_content">
                            <h3>Hỗ trợ 24/7</h3>
                            <p>Đội ngũ chăm sóc khách hàng luôn sẵn sàng giải đáp mọi thắc mắc của bạn bất cứ lúc nào.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!--chose us area end-->
@endsection
