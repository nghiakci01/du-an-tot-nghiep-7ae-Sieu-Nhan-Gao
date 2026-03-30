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

    <!-- Google Fonts (Reid Theme: Libre Franklin + Playfair Display) -->
    <link href="https://fonts.googleapis.com/css?family=Libre+Franklin:300,400,500,600,700|Playfair+Display:400,400i,700,700i,900,900i&display=swap" rel="stylesheet">

    <!-- CSS 
    ========================= -->


    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/plugins.css') }}">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}?v={{ filemtime(public_path('frontend-assets/css/style.css')) }}">

    <!-- Custom Header CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/custom-header.css') }}">

    <!-- Search Autocomplete CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/search-autocomplete.css') }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Force Pure White Background & Remove Glow - OVERRIDE VITE/BOOTSTRAP */
        :root, [data-bs-theme=light] {
            --bs-body-bg: #ffffff !important;
            --bs-body-bg-rgb: 255, 255, 255 !important;
            --bs-tertiary-bg: #ffffff !important;
            --bs-secondary-bg: #ffffff !important;
        }

        body, html, .main_wrapper, .main-wrapper, .header_area, .footer_section, .breadcrumbs_area, .atino-breadcrumb {
            background-color: #ffffff !important;
            background: #ffffff !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Remove default link underlines */
        a {
            text-decoration: none !important;
        }

        /* Product Title Styling */
        .product_content h3 a {
            font-size: 14px !important;
            font-weight: 300 !important;
            color: #333 !important;
            line-height: 1.4 !important;
            transition: color 0.3s ease !important;
        }

        .product_content h3 a:hover {
            color: #ef233c !important;
        }

        /* Product Price Styling */
        .current_price {
            font-weight: 700 !important;
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
            transition: transform 0.6s ease !important;
        }

        .single_product:hover .product_thumb img {
            transform: scale(1.05) !important;
        }

        /* ----- ZARA STYLE OVERRIDES START ----- */
        .single_product {
            position: relative;
            overflow: hidden;
            border-radius: 0; 
            box-shadow: none !important;
            border: 1px solid transparent;
            transition: border 0.3s ease;
        }

        .single_product:hover .product_thumb a.secondary_img {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Clean up action buttons (Hide default cluttered view) */
        .single_product .hover_action > a {
            display: none !important;
        }

        .single_product .product_action {
            top: 10px;
            right: 10px;
            opacity: 1 !important;
            visibility: visible !important;
            transform: none;
        }

        .single_product .action_button {
            opacity: 1 !important;
            visibility: hidden;
            transform: translateX(10px);
            transition: all 0.3s ease;
            position: relative;
            max-height: none;
        }

        .single_product:hover .action_button {
            visibility: visible;
            transform: translateX(0);
        }

        .single_product .action_button ul {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .single_product .action_button ul li a {
            width: 38px;
            height: 38px;
            line-height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            color: #000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            border: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .single_product .action_button ul li a:hover {
            background: #000;
            color: #fff;
        }

        .single_product .action_button ul li a::before {
            display: none;
        }

        /* Redesign Quick view / Add to cart button to slide from bottom like Zara */
        .single_product .quick_button {
            bottom: -60px;
            left: 0;
            right: 0;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 5;
            padding: 0;
            margin: 0;
            border-radius: 0;
        }

        .single_product:hover .quick_button {
            bottom: 0;
            opacity: 1;
            visibility: visible;
        }

        .single_product .quick_button a {
            line-height: 48px;
            background: rgba(255, 255, 255, 0.95);
            color: #000;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 0;
            transition: background 0.3s, color 0.3s;
            border-top: 1px solid #ebebeb;
        }

        .single_product .quick_button a:hover {
            background: #000;
            color: #fff;
            border-top: 1px solid #000;
        }

        /* Content styling - align left, clean font */
        .single_product .product_content {
            text-align: left;
            padding: 0 5px 15px 5px !important;
        }

        .single_product .product_content h3 {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .single_product .product_content h3 a {
            color: #333;
        }
        .single_product .product_content h3 a:hover {
            color: #000;
            text-decoration: underline;
        }

        .single_product .product_content .price_box {
            margin-top: 5px;
        }

        /* Hide rating on grid for Zara minimal look */
        .single_product .product_ratting {
            display: none !important;
        }
        /* ----- ZARA STYLE OVERRIDES END ----- */

        /* Sticky Header Global Styles */
        .sticky-header.sticky {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999 !important;
            background: #fff !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: block !important;
            animation: fadeInDown 0.5s ease;
        }

        /* Remove underlines from header and breadcrumb links */
        .header_area a, 
        .breadcrumbs_area a, 
        .main_menu a, 
        .offcanvas_menu a {
            text-decoration: none !important;
        }

        .header_area a:hover, 
        .breadcrumbs_area a:hover, 
        .main_menu a:hover, 
        .offcanvas_menu a:hover {
            text-decoration: none !important;
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

    <!-- Global UX Fixes -->
    <script src="{{ asset('frontend-assets/js/ux-fixes.js') }}"></script>

    @stack('styles')
    @stack('scripts')
    @yield('scripts')

    @if($chatbot_enabled ?? false)
        @include('frontend.partials.chatbot-widget')
    @endif
    <div id="layout-config" style="display: none;"
        data-session-error="{{ session('error') }}"
        data-session-success="{{ session('success') }}"
        data-session-warning="{{ session('warning') }}"
        data-session-info="{{ session('info') }}"
        data-auth-check="{{ auth()->check() ? 'true' : 'false' }}"
        data-auth-id="{{ auth()->id() }}"
        data-session-id="{{ session()->getId() }}"
    ></div>

    <script type="text/javascript">
        $(document).ready(function () {
            const config = document.getElementById('layout-config').dataset;

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
            if (config.sessionError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    html: config.sessionError,
                    confirmButtonColor: '#ef233c',
                    confirmButtonText: 'Đóng',
                });
            }

            if (config.sessionSuccess) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: config.sessionSuccess,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }

            if (config.sessionWarning) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo',
                    html: config.sessionWarning,
                    confirmButtonColor: '#ef233c',
                });
            }

            if (config.sessionInfo) {
                Swal.fire({
                    icon: 'info',
                    title: 'Thông báo',
                    html: config.sessionInfo,
                    confirmButtonColor: '#333',
                });
            }
        });
    </script>

    <script type="module">
        const config = document.getElementById('layout-config').dataset;
        if (window.Echo) {
            let channel;
            if (config.authCheck === 'true') {
                channel = window.Echo.private('App.Models.User.' + config.authId);
            } else {
                channel = window.Echo.channel('cart.' + config.sessionId);
            }

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
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.add-to-wishlist', function (e) {
                e.preventDefault();
                var productId = $(this).data('id');
                var icon = $(this).find('i');

                $.ajax({
                    url: '{{ route("wishlist.add") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function (response) {
                        if (response.wishlisted) {
                            icon.removeClass('fa-heart-o').addClass('fa-heart').css('color', 'red');
                        } else {
                            icon.removeClass('fa-heart').addClass('fa-heart-o').css('color', '');
                        }
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: response.wishlisted ? 'success' : 'info',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                        });
                    },
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        } else {
                            Swal.fire({ icon: 'error', title: 'Lỗi!', text: 'Có lỗi xảy ra, vui lòng thử lại!' });
                        }
                    }
                });
            });
        });
    </script>
</body>


<!-- Mirrored from htmldemo.net/reid/reid/index-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 15 Jan 2026 03:35:39 GMT -->

</html>