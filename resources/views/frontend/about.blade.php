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

@endsection