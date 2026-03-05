@extends('layouts.public')

@section('title', __('messages.about_us') . ' | Elite')

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
                            <li>{{ __('messages.about_us') }}</li>
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
                        @if(isset($aboutBanner) && $aboutBanner->image)
                            <img src="{{ asset('storage/' . $aboutBanner->image) }}" alt="{{ $aboutBanner->title ?? __('messages.about_us') }}">
                        @else
                            <img src="{{ asset('frontend-assets/img/about/about1.jpg') }}" alt="{{ __('messages.about_us') }}">
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="about_content">
                        <h1>{{ __('messages.about_welcome') }}</h1>
                        <p>{{ __('messages.about_intro_1') }}</p>
                        <p>{{ __('messages.about_intro_2') }}</p>
                        <div class="view__work">
                            <a href="{{ route('shop') }}">{{ __('messages.view_work') }} <i
                                    class="fa fa-angle-right"></i></a>
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
                            <h3>{{ __('messages.high_quality') }}</h3>
                            <p>{{ __('messages.high_quality_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="{{ asset('frontend-assets/img/about/shipping2.png') }}" alt="">
                        </div>
                        <div class="chose_content">
                            <h3>{{ __('messages.fast_shipping') }}</h3>
                            <p>{{ __('messages.fast_shipping_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="{{ asset('frontend-assets/img/about/shipping3.png') }}" alt="">
                        </div>
                        <div class="chose_content">
                            <h3>{{ __('messages.support_247') }}</h3>
                            <p>{{ __('messages.support_247_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--chose us area end-->
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


@endsection