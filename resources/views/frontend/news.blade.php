@extends('layouts.public')

@section('title', 'Tin tức | Reid Fashion')

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
                            <li>tin tức</li>
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
                                <p class="post_desc">Phong cách Minimalism chưa bao giờ lỗi mốt. Hãy cùng Reid Fashion tìm
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
                                <p class="post_desc">Reid Fashion vui mừng thông báo khai trương chi nhánh thứ 5 tại TP. Hồ
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
    <!--news section end-->

    <!--newsletter area start-->
    <div class="newsletter_area mt-60 mb-60">
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
    </div>
    <!--newsletter area end-->
@endsection