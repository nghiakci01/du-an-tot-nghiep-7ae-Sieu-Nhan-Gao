@extends('layouts.public')

@section('title', $post->title . ' | Elite')

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
                            <li><a href="{{ route('news') }}">{{ __('messages.news') }}</a></li>
                            <li>/</li>
                            <li>{{ Str::limit($post->title, 40) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!--blog body area start-->
    <div class="blog_details mt-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="blog_wrapper">
                        <article class="single_blog">
                            <figure>
                                <div class="post_header">
                                    <h3 class="post_title">{{ $post->title }}</h3>
                                    <div class="blog_meta">
                                        <p>Đăng bởi: Admin | Ngày: {{ $post->created_at->format('d/m/Y') }} | Chuyên mục: {{ $post->category ? $post->category->name : 'Tin tức' }}</p>
                                    </div>
                                </div>
                                <div class="blog_thumb">
                                    <img src="{{ $post->image ? asset('storage/'.$post->image) : asset('frontend-assets/img/blog/blog1.jpg') }}" alt="{{ $post->title }}" style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;">
                                </div>
                                <figcaption class="blog_content">
                                    <div class="post_content">
                                        {!! $post->content !!}
                                    </div>
                                </figcaption>
                            </figure>
                        </article>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-12">
                    <div class="blog_sidebar_widget">
                        <div class="widget_list widget_post">
                            <h3>Bài viết liên quan</h3>
                            @forelse($relatedPosts as $related)
                                <div class="post_wrapper">
                                    <div class="post_thumb">
                                        <a href="{{ route('news.detail', $related->slug) }}"><img src="{{ $related->image ? asset('storage/'.$related->image) : asset('frontend-assets/img/blog/blog2.jpg') }}" alt="{{ $related->title }}"></a>
                                    </div>
                                    <div class="post_info">
                                        <h4><a href="{{ route('news.detail', $related->slug) }}">{{ $related->title }}</a></h4>
                                        <span>{{ $related->created_at->format('d/m/Y') }} </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Không có bài viết liên quan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--blog section area end-->
@endsection
