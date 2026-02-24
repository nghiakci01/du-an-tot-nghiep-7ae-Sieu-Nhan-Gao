@extends('layouts.public')

@section('title', 'News | Elite')

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
                                <h4 class="post_title"><a href="#">Spring - Summer 2026 Fashion Trends</a></h4>
                                <div class="articles_date">
                                    <p>February 04, 2026 | <a href="#">Fashion</a> </p>
                                </div>
                                <p class="post_desc">Discover the latest collections with a youthful, dynamic style for this year's festive season...</p>
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
                                <h4 class="post_title"><a href="#">Minimalist styling tips for men</a></h4>
                                <div class="articles_date">
                                    <p>February 01, 2026 | <a href="#">Tips & Tricks</a> </p>
                                </div>
                                <p class="post_desc">Minimalist style never goes out of fashion. Let's learn standard fashion mixes with Elite...</p>
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
                                <h4 class="post_title"><a href="#">Notice: Opening of new branch</a></h4>
                                <div class="articles_date">
                                    <p>January 25, 2026 | <a href="#">Notice</a> </p>
                                </div>
                                <p class="post_desc">Elite is pleased to announce the opening of its 5th branch in Ho Chi Minh City with many attractive deals...</p>
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