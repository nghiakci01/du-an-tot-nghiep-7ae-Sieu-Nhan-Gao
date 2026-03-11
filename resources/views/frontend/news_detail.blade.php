@extends('layouts.public')

@section('title', $post->title . ' | Elite')

@section('content')
    <style>
        .atino-blog-detail {
            background-color: #fff;
            padding-bottom: 60px;
        }
        .atino-breadcrumb {
            background: #f8f9fa;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        .atino-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .atino-breadcrumb ul li {
            font-size: 14px;
            color: #6c757d;
        }
        .atino-breadcrumb ul li a {
            color: #333;
            text-decoration: none;
            transition: 0.3s;
        }
        .atino-breadcrumb ul li a:hover {
            color: #ff6a28;
        }
        .atino-breadcrumb ul li.separator {
            margin: 0 10px;
            color: #ccc;
        }
        .atino-post-header {
            text-align: center;
            max-width: 900px;
            margin: 0 auto 40px;
        }
        .atino-post-title {
            font-size: 36px;
            line-height: 1.3;
            font-weight: 700;
            color: #222;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif; /* Phù hợp blog thời trang */
        }
        .atino-post-meta {
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }
        .atino-post-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .atino-post-thumbnail {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto 40px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .atino-post-thumbnail img {
            width: 100%;
            height: auto;
            max-height: 550px;
            object-fit: cover;
            display: block;
        }
        .atino-post-content {
            max-width: 800px;
            margin: 0 auto;
            font-size: 18px;
            line-height: 1.8;
            color: #444;
        }
        .atino-post-content p {
            margin-bottom: 25px;
        }
        .atino-post-content h2, .atino-post-content h3, .atino-post-content h4 {
            color: #222;
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .atino-post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .atino-post-content blockquote {
            border-left: 4px solid #ff6a28;
            padding-left: 20px;
            margin: 30px 0;
            font-style: italic;
            color: #555;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
        }
        .atino-divider {
            max-width: 800px;
            margin: 60px auto;
            border: 0;
            height: 1px;
            background: #eee;
        }
        .atino-related-section {
            background-color: #fcfcfc;
            padding: 60px 0;
            border-top: 1px solid #f1f1f1;
        }
        .atino-section-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #222;
            text-transform: uppercase;
        }
        
        /* Box Related Post giống màn news */
        .single_blog {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            height: 100%;
        }
        .single_blog:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transform: translateY(-5px);
        }
        .blog_thumb {
            overflow: hidden;
        }
        .blog_thumb img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .single_blog:hover .blog_thumb img {
            transform: scale(1.05);
        }
        .blog_content {
            padding: 20px;
        }
        .blog_content .post_title {
            font-size: 18px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 10px;
            height: 50px;
            overflow: hidden;
        }
        .blog_content .post_title a {
            color: #222;
            text-decoration: none;
            transition: color 0.3s;
        }
        .blog_content .post_title a:hover {
            color: #ff6a28;
        }
        .blog_content .articles_date {
            font-size: 12px;
            color: #888;
            margin-bottom: 15px;
        }
        .blog_content .articles_date a {
            color: #ff6a28;
        }
    </style>

    <div class="atino-blog-detail">
        <!-- Breadcrumbs -->
        <div class="atino-breadcrumb">
            <div class="container border-bottom-0">
                <ul>
                    <li><a href="{{ route('welcome') }}"><i class="fa fa-home"></i> Trang chủ</a></li>
                    <li class="separator">/</li>
                    <li><a href="{{ route('news') }}">Tin tức</a></li>
                    <li class="separator">/</li>
                    <li>{{ $post->title }}</li>
                </ul>
            </div>
        </div>

        <div class="container">
            <!-- Header bài viết -->
            <div class="atino-post-header">
                <h1 class="atino-post-title">{{ $post->title }}</h1>
                <div class="atino-post-meta">
                    <span><i class="fa fa-calendar-o"></i> {{ $post->created_at->format('d/m/Y') }}</span>
                    <span><i class="fa fa-folder-o"></i> {{ $post->category ? $post->category->name : 'Tin tức' }}</span>
                    <span><i class="fa fa-user-o"></i> Admin</span>
                </div>
            </div>

            <!-- Ảnh đại diện -->
            @if($post->image)
            <div class="atino-post-thumbnail">
                <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}">
            </div>
            @endif

            <!-- Nội dung bài viết -->
            <div class="atino-post-content">
                {!! $post->content !!}
            </div>
            
            <hr class="atino-divider">
            
            <div class="text-center mb-5">
                <a href="{{ route('news') }}" class="btn btn-dark px-4 py-2" style="border-radius: 30px; letter-spacing: 1px; text-transform: uppercase; font-size: 14px;">Kho lưu trữ bài viết</a>
            </div>
        </div>
    </div>

    <!-- Tin tức liên quan (Grid layout under content) -->
    @if($relatedPosts->count() > 0)
    <div class="atino-related-section">
        <div class="container">
            <h3 class="atino-section-title">Tin Tức Liên Quan</h3>
            <div class="row">
                @foreach($relatedPosts as $related)
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="single_blog">
                        <figure>
                            <div class="blog_thumb">
                                <a href="{{ route('news.detail', $related->slug) }}">
                                    <img src="{{ $related->image ? asset('storage/'.$related->image) : asset('frontend-assets/img/blog/blog1.jpg') }}" alt="{{ $related->title }}">
                                </a>
                            </div>
                            <figcaption class="blog_content">
                                <h4 class="post_title">
                                    <a href="{{ route('news.detail', $related->slug) }}">{{ $related->title }}</a>
                                </h4>
                                <div class="articles_date">
                                    <p>{{ $related->created_at->format('d/m/Y') }} | <a href="#">{{ $related->category ? $related->category->name : 'Tin tức' }}</a></p>
                                </div>
                            </figcaption>
                        </figure>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection
