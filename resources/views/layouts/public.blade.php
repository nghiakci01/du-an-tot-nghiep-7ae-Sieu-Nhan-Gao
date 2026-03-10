<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<!-- Mirrored from htmldemo.net/reid/reid/index-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 15 Jan 2026 03:35:37 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', ($settings['site_title'] ?? 'Elite') . ' - ' . __('messages.home'))</title>
    <meta name="description"
        content="@yield('meta_description', $settings['site_description'] ?? 'Elite E-commerce Fashion Store')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend-assets/img/favicon.ico') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS 
    ========================= -->


    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/plugins.css') }}">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}">

    <!-- Custom Header CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/custom-header.css') }}">

    <!-- Search Autocomplete CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/search-autocomplete.css') }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Global Product Hover Effect & Consistent Sizing */
        .single_product .product_thumb {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1; /* Force square ratio */
            overflow: hidden !important;
            background: #f9f9f9;
        }

        .single_product .product_thumb img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.4s ease-in-out !important;
        }

        .single_product:hover .product_thumb img {
            transform: scale(1.08) !important;
        }
    </style>
</head>

<body>

    <!-- Main Wrapper Start -->

    @include('layouts.partials.header')

    @yield('content')

    @include('layouts.partials.footer')

    <!-- JS
    ============================================ -->

    <!-- Plugins JS -->
    <script src="{{ asset('frontend-assets/js/plugins.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('frontend-assets/js/main.js') }}"></script>

    <!-- Search Autocomplete JS -->
    <script src="{{ asset('frontend-assets/js/search-autocomplete.js') }}"></script>

    @stack('styles')
    @stack('scripts')
    @yield('scripts')

    @if($chatbot_enabled)
        @include('frontend.partials.chatbot-widget')
    @endif
    <script type="text/javascript">
        if (window.location.hash && window.location.hash == '#_=_') {
            if (window.history && history.pushState) {
                window.history.pushState("", document.title, window.location.pathname + window.location.search);
            } else {
                var scroll = { top: document.body.scrollTop, left: document.body.scrollLeft };
                window.location.hash = '';
                document.body.scrollTop = scroll.top;
                document.body.scrollLeft = scroll.left;
            }
        }

        // Flash session messages → SweetAlert2
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            html: '{!! session('error') !!}',
            confirmButtonColor: '#ef233c',
            confirmButtonText: 'Đóng',
        });
        @endif

        @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo',
            html: '{{ session('warning') }}',
            confirmButtonColor: '#ef233c',
        });
        @endif

        @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Thông báo',
            html: '{{ session('info') }}',
            confirmButtonColor: '#333',
        });
        @endif
    </script>

    <script type="module">
        if (window.Echo) {
            let channel;
            @if(auth()->check())
                channel = window.Echo.private('App.Models.User.{{ auth()->id() }}');
            @else
                channel = window.Echo.channel('cart.{{ session()->getId() }}');
            @endif

            channel.listen('CartUpdatedEvent', (e) => {
                // e.cartCount is from the CartUpdatedEvent constructor
                let cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    el.innerText = e.cartCount;
                    
                    // Thêm class nháy nhẹ (animation)
                    el.classList.remove('pulse-animation');
                    void el.offsetWidth; // trigger reflow
                    el.classList.add('pulse-animation');
                });
            });
        }
    </script>

    <style>
        @keyframes cartPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.5); color: #ef233c; }
            100% { transform: scale(1); }
        }
        .pulse-animation {
            animation: cartPulse 0.5s ease-in-out;
            display: inline-block;
        }
    </style>
</body>


<!-- Mirrored from htmldemo.net/reid/reid/index-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 15 Jan 2026 03:35:39 GMT -->

</html>