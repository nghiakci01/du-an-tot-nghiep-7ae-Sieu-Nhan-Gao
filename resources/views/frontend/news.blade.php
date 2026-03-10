@extends('layouts.public')

@section('title', 'News | Elite')

@section('content')
    <style>
        .blog_thumb img {
            width: 378px;
            height: 240px;
            object-fit: cover;
        }
    </style>
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
                @forelse($posts as $post)
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="single_blog">
                        <figure>
                            <div class="blog_thumb">
                                <a href="{{ route('news.detail', $post->slug) }}"><img src="{{ $post->image ? asset('storage/'.$post->image) : asset('frontend-assets/img/blog/blog1.jpg') }}" alt="{{ $post->title }}"></a>
                            </div>
                            <figcaption class="blog_content">
                                <h4 class="post_title" style="min-height: 50px;"><a href="{{ route('news.detail', $post->slug) }}">{{ $post->title }}</a></h4>
                                <div class="articles_date">
                                    <p>{{ $post->created_at->format('M d, Y') }} | <a href="#">{{ $post->category ? $post->category->name : 'Tin tức' }}</a> </p>
                                </div>
                                <p class="post_desc">{{ Str::limit(strip_tags(html_entity_decode($post->content)), 120) }}</p>
                                <footer class="btn_more">
                                    <a href="{{ route('news.detail', $post->slug) }}"> {{ __('messages.read_more') }}</a>
                                </footer>
                            </figcaption>
                        </figure>
                    </article>
                </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">Chưa có bài viết nào.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="row">
                <div class="col-12 mt-4 d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
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