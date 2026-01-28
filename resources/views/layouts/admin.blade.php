<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Admin Dashboard') | Du An Tot Nghiep</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Clothing E-commerce Admin Panel" />
    <meta name="author" content="Phoenixcoded" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('admin-assets/images/favicon.svg') }}" type="image/x-icon" />
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/inter/inter.css') }}" id="main-font-link" />
    <!-- [phosphor Icons] -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/phosphor/duotone/style.css') }}" />
    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] -->
    <link rel="stylesheet" href="{{ asset('admin-assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style-preset.css') }}" />
    <script src="{{ asset('admin-assets/js/tech-stack.js') }}"></script>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track"><div class="loader-fill"></div></div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('admin.dashboard') }}" class="b-brand text-primary">
                    <!-- ========   Change your logo from here   ============ -->
                    <img src="{{ asset('admin-assets/images/logo-dark.svg') }}" class="img-fluid logo-lg" alt="logo" />
                    <span class="badge bg-light-success rounded-pill ms-2 theme-version">v1.0</span>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Navigation</label>
                        <i class="ph-duotone ph-gauge"></i>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph-duotone ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="pc-item pc-caption">
                        <label>Quản lý</label>
                        <i class="ph-duotone ph-cube"></i>
                    </li>
                    
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon"><i class="ph-duotone ph-shopping-cart"></i></span>
                            <span class="pc-mtext">Sản phẩm</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin.products.create') }}">Thêm sản phẩm</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin.categories.index') }}">Danh mục</a></li>
                        </ul>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon"><i class="ph-duotone ph-users"></i></span>
                            <span class="pc-mtext">Người dùng</span>
                             <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                         <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#!">Danh sách người dùng</a></li>
                        </ul>
                    </li>

                     <li class="pc-item">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon"><i class="ph-duotone ph-notepad"></i></span>
                            <span class="pc-mtext">Đơn hàng</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper"> 
            <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph-duotone ph-sun-dim"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                                <i class="ph-duotone ph-moon"></i> <span>Dark</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                                <i class="ph-duotone ph-sun-dim"></i> <span>Light</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                                <i class="ph-duotone ph-cpu"></i> <span>Default</span>
                            </a>
                        </div>
                    </li>
                    
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                            <img src="{{ asset('admin-assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar" />
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Profile</h5>
                            </div>
                            <div class="dropdown-body">
                                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                                    <div class="d-flex mb-1">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('admin-assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar wid-35" />
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">{{ Auth::user()->name ?? 'Admin' }}</h6>
                                            <span>{{ Auth::user()->email ?? 'admin@example.com' }}</span>
                                        </div>
                                    </div>
                                    <hr class="border-secondary border-opacity-50" />
                                    <div class="d-grid mb-3">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="ph-duotone ph-sign-out me-2"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col my-1">
                    <p class="m-0">
                        © Du An Tot Nghiep crafted with ♥
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- [Page Specific JS] start -->
    <!-- Scripts will be included in child views if needed -->
    <!-- [Page Specific JS] end -->

    <!-- Required Js -->
    <script src="{{ asset('admin-assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/icon/custom-font.js') }}"></script>
    <script src="{{ asset('admin-assets/js/script.js') }}"></script>
    <script src="{{ asset('admin-assets/js/theme.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        layout_change("light");
        change_box_container("false");
        layout_caption_change("true");
        layout_rtl_change("false");
        preset_change("preset-1");
        main_layout_change("vertical");
    </script>
    
    @yield('scripts')

</body>
</html>
