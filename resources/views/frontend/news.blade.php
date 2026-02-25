@extends('layouts.public')

@section('title', 'Tin tức | Elite')

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
                            <li>{{ __('messages.news') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!--news section area -->
    <div class="blog_page_section mt-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>{{ __('messages.latest_news') }}</h2>
                        <p>{{ __('messages.latest_news_desc') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <article class="single_blog">
                        <figure>
                            <div class="blog_thumb">
                                <a href="#"><img src="{{ asset('frontend-assets/img/blog/blog1.jpg') }}" alt=""></a>
                            </div>
                            <figcaption class="blog_content">
                                <h4 class="post_title"><a href="#">Xu hướng thời trang Xuân - Hè 2026</a></h4>
                                <div class="articles_date">
                                    <p>04 Tháng 2, 2026 | <a href="#">Thời trang</a> </p>
                                </div>
                                <p class="post_desc">Khám phá những bộ sưu tập mới nhất mang đậm phong cách trẻ trung, năng
                                    động cho mùa lễ hội năm nay...</p>
                                <footer class="btn_more">
                                    <a href="#"> {{ __('messages.read_more') }}</a>
                                </footer>
                            </figcaption>
                        </figure>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="single_blog">
                        <figure>
                            <div class="blog_thumb">
                                <a href="#"><img src="{{ asset('frontend-assets/img/blog/blog2.jpg') }}" alt=""></a>
                            </div>
                            <figcaption class="blog_content">
                                <h4 class="post_title"><a href="#">Bí quyết phối đồ tối giản cho nam giới</a></h4>
                                <div class="articles_date">
                                    <p>01 Tháng 2, 2026 | <a href="#">Tips & Tricks</a> </p>
                                </div>
                                <p class="post_desc">Phong cách Minimalism chưa bao giờ lỗi mốt. Hãy cùng Elite tìm
                                    hiểu cách phối đồ chuẩn "soái ca"...</p>
                                <footer class="btn_more">
                                    <a href="#"> {{ __('messages.read_more') }}</a>
                                </footer>
                            </figcaption>
                        </figure>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="single_blog">
                        <figure>
                            <div class="blog_thumb">
                                <a href="#"><img src="{{ asset('frontend-assets/img/blog/blog3.jpg') }}" alt=""></a>
                            </div>
                            <figcaption class="blog_content">
                                <h4 class="post_title"><a href="#">Thông báo: Khai trương chi nhánh mới</a></h4>
                                <div class="articles_date">
                                    <p>25 Tháng 1, 2026 | <a href="#">Thông báo</a> </p>
                                </div>
                                <p class="post_desc">Elite vui mừng thông báo khai trương chi nhánh thứ 5 tại TP. Hồ
                                    Chí Minh với nhiều ưu đãi hấp dẫn...</p>
                                <footer class="btn_more">
                                    <a href="#"> {{ __('messages.read_more') }}</a>
                                </footer>
                            </figcaption>
                        </figure>
                    </article>
                </div>
            </div>
        </div>
    </div>
        <div class="counterup_section">
        <div class="container">   
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single_counterup">
                       <div class="counter_img">
                            <img src="assets/img/about/count.png" alt="">
                        </div>
                        <div class="counter_info">
                            <h2 class="counter_number">2170</h2>
                            <p>happy customers</p>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single_counterup count-two">
                        <div class="counter_img">
                            <img src="assets/img/about/count2.png" alt="">
                        </div>
                        <div class="counter_info">
                            <h2 class="counter_number">8080</h2>
                            <p>AWARDS won</p>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single_counterup">
                        <div class="counter_img">
                            <img src="assets/img/about/count3.png" alt="">
                        </div>
                        <div class="counter_info">
                            <h2 class="counter_number">2150</h2>
                            <p>HOURS WORKED</p>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single_counterup count-two">
                        <div class="counter_img">
                            <img src="assets/img/about/count4.png" alt="">
                        </div>
                        <div class="counter_info">
                            <h2 class="counter_number">2170</h2>
                            <p>COMPLETE PROJECTS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!--counterup end-->

    <!--about progress bar -->
    <div class="about_progressbar">
        <div class="container">   
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="progressbar_inner">
                       <h2>We have Skills to show</h2>
                        <div class="progress_skill one">
                            <div class="progress">
                                <div class="progress-bar about_prog wow fadeInLeft" data-wow-duration="0.8s" data-wow-delay=".3s" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    <span class="progress_persent">html/css</span>    
                                </div>
                            </div>
                            <span class="progress_discount">60%</span>
                        </div>
                        <div class="progress_skill two">
                            <div class="progress">
                                <div class="progress-bar about_prog wow fadeInLeft" data-wow-duration="0.8s" data-wow-delay=".5s" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">

                                    <span class="progress_persent">ecommerce theme </span>
                                </div>

                            </div>
                             <span class="progress_discount">90%</span>
                        </div> 
                        <div class="progress_skill three">
                            <div class="progress">
                                <div class="progress-bar about_prog wow fadeInLeft" data-wow-duration="0.8s" data-wow-delay=".7s" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">

                                    <span class="progress_persent">Typhography </span>
                                </div>

                            </div>
                             <span class="progress_discount">70%</span>
                        </div> 
                         <div class="progress_skill four">
                            <div class="progress">
                                <div class="progress-bar about_prog wow fadeInLeft" data-wow-duration="0.8s" data-wow-delay=".7s" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">

                                    <span class="progress_persent">Branding  </span>
                                </div>

                            </div>
                             <span class="progress_discount">80%</span>
                        </div> 
                    </div>           
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="about__img">
                        <img src="assets/img/about/about2.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!--about progress bar end -->
    <!--news section end-->

    <!--newsletter area start-->
    <!-- <div class="newsletter_area mt-60 mb-60">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="newsletter_text">
                        <h3>{{ __('messages.newsletter_signup') }}</h3>
                        <p>{{ __('messages.newsletter_signup_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="newsletter_form">
                        <form id="mc-form" class="mc-form footer-newsletter">
                            <input id="mc-email" type="email" autocomplete="off"
                                placeholder="{{ __('messages.enter_email') }}" />
                            <button id="mc-submit">{{ __('messages.subscribe') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--newsletter area end-->
@endsection