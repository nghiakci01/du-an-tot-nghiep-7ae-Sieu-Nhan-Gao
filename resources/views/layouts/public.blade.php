<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<!-- Mirrored from htmldemo.net/reid/reid/index-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 15 Jan 2026 03:35:37 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', ($settings['site_title'] ?? 'Elite') . ' - ' . __('messages.home'))</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend-assets/img/favicon.ico') }}">

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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</body>


<!-- Mirrored from htmldemo.net/reid/reid/index-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 15 Jan 2026 03:35:39 GMT -->

</html>