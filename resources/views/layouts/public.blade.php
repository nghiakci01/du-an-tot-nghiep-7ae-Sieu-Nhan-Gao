<!doctype html>
<html class="no-js" lang="en">


<!-- Mirrored from htmldemo.net/reid/reid/index-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 15 Jan 2026 03:35:37 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Reid - Fashion eCommerce HTML Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend-assets/img/favicon.ico') }}">
    
    <!-- CSS 
    ========================= -->


    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/plugins.css') }}">
    
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}">

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




@include('partials.chatbot-widget')

</body>


<!-- Mirrored from htmldemo.net/reid/reid/index-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 15 Jan 2026 03:35:39 GMT -->
</html>