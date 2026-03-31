<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lock Screen | Elite</title>
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('admin-assets/images/favicon.svg') }}" type="image/x-icon">
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/inter/inter.css') }}" id="main-font-link" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style-preset.css') }}" />

</head>

<body>
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="#"><img src="{{ asset('admin-assets/images/logo-dark.svg') }}" alt="img"></a>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            @if(Auth::check() && Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="user-image"
                                    class="img-radius mb-2 img-fluid wid-100"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <img src="{{ asset('admin-assets/images/user/avatar-1.jpg') }}" alt="user-image"
                                    class="img-radius mb-2 img-fluid wid-100">
                            @endif
                            <h4 class="mb-0">{{ Auth::check() ? Auth::user()->name : 'Khách (Demo)' }}</h4>
                            <h5 class="text-muted fw-normal">Locked</h5>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('admin.unlock') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="item-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter your password">
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Unlock</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [Main Content] end -->
    <!-- Required Js -->
    <script src="{{ asset('admin-assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/feather.min.js') }}"></script>
</body>

</html>