<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Home') | Able Pro Dashboard Template</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Able Pro admin template" />
    <meta name="author" content="Phoenixcoded" />

    <link rel="icon" href="{{ asset('ableproadmin.com/assets/images/favicon.svg') }}" type="image/x-icon" />

    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/fonts/inter/inter.css') }}" id="main-font-link" />

    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/fonts/phosphor/duotone/style.css') }}" />

    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/fonts/tabler-icons.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/fonts/feather.css') }}" />

    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/fonts/fontawesome.css') }}" />

    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/fonts/material.css') }}" />

    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('ableproadmin.com/assets/css/style-preset.css') }}" />

    <script src="{{ asset('ableproadmin.com/assets/js/tech-stack.js') }}"></script>

    @stack('css')
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="" data-pc-theme="light">

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    @include('layouts.partials.admin-sidebar')
    @include('layouts.partials.admin-topbar')

    <div class="pc-container">
        <div class="pc-content">
            {{-- @include('layouts.partials.alerts') --}}
            @yield('content')
        </div>
    </div>

    @include('layouts.partials.admin-announcement')
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col my-1">
                    <p class="m-0">
                        © Able Pro crafted with ♥ by Team
                        <a href="https://www.phoenixcoded.net/" target="_blank">Phoenixcoded</a>
                    </p>
                </div>
                <div class="col-md-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="../index.html">Home</a></li>
                        <li class="list-inline-item">
                            <a href="https://phoenixcoded.gitbook.io/able-pro/" target="_blank">Documentation</a>
                        </li>
                        <li class="list-inline-item tf" style="display: none;">
                            <a href="https://phoenixcoded.authordesk.app/" target="_blank">Support</a>
                        </li>
                        <li class="list-inline-item ct">
                            <a href="https://codedthemes.support-hub.io/" target="_blank">Support</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <div class="pct-c-btn">
        <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout"><i
                class="ph-duotone ph-gear-six"></i></a>
    </div>
    <div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Settings</h5>
            <button type="button" class="btn btn-icon btn-link-danger ms-auto" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="pct-body customizer-body simplebar-scrollable-y" data-simplebar="init">
            <div class="simplebar-wrapper" style="margin: 0px;">
                <div class="simplebar-height-auto-observer-wrapper">
                    <div class="simplebar-height-auto-observer"></div>
                </div>
                <div class="simplebar-mask">
                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                        <div class="simplebar-content-wrapper" tabindex="0" role="region"
                            aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                            <div class="simplebar-content" style="padding: 0px;">
                                <div class="offcanvas-body py-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="pc-dark">
                                                <h6 class="mb-1">Theme Mode</h6>
                                                <p class="text-muted text-sm">
                                                    Choose light or dark mode or Auto
                                                </p>
                                                <div class="row theme-color theme-layout">
                                                    <div class="col-4">
                                                        <div class="d-grid">
                                                            <button class="preset-btn btn active" data-value="true"
                                                                onclick="layout_change('light');"
                                                                data-bs-toggle="tooltip" aria-label="Light"
                                                                data-bs-original-title="Light">
                                                                <svg class="pc-icon text-warning">
                                                                    <use xlink:href="#custom-sun-1"></use>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-grid">
                                                            <button class="preset-btn btn" data-value="false"
                                                                onclick="layout_change('dark');"
                                                                data-bs-toggle="tooltip" aria-label="Dark"
                                                                data-bs-original-title="Dark">
                                                                <svg class="pc-icon">
                                                                    <use xlink:href="#custom-moon"></use>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-grid">
                                                            <button class="preset-btn btn" data-value="default"
                                                                onclick="layout_change_default();"
                                                                data-bs-toggle="tooltip"
                                                                aria-label="Automatically sets the theme based on user's operating system's color scheme."
                                                                data-bs-original-title="Automatically sets the theme based on user's operating system's color scheme.">
                                                                <span
                                                                    class="pc-lay-icon d-flex align-items-center justify-content-center"><i
                                                                        class="ph-duotone ph-cpu"></i></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <h6 class="mb-1">Theme Contrast</h6>
                                            <p class="text-muted text-sm">Choose theme contrast</p>
                                            <div class="row theme-contrast">
                                                <div class="col-6">
                                                    <div class="d-grid">
                                                        <button class="preset-btn btn" data-value="true"
                                                            onclick="layout_theme_contrast_change('true');"
                                                            data-bs-toggle="tooltip" aria-label="True"
                                                            data-bs-original-title="True">
                                                            <svg class="pc-icon">
                                                                <use xlink:href="#custom-mask"></use>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-grid">
                                                        <button class="preset-btn btn active" data-value="false"
                                                            onclick="layout_theme_contrast_change('false');"
                                                            data-bs-toggle="tooltip" aria-label="False"
                                                            data-bs-original-title="False">
                                                            <svg class="pc-icon">
                                                                <use xlink:href="#custom-mask-1-outline"></use>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <h6 class="mb-1">Custom Theme</h6>
                                            <p class="text-muted text-sm">Choose your primary theme color</p>
                                            <div class="theme-color preset-color">
                                                <a href="#!" data-bs-toggle="tooltip" class="active"
                                                    data-value="preset-1" aria-label="Blue"
                                                    data-bs-original-title="Blue"><i class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-2"
                                                    aria-label="Indigo" data-bs-original-title="Indigo"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-3"
                                                    aria-label="Purple" data-bs-original-title="Purple"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-4"
                                                    aria-label="Pink" data-bs-original-title="Pink"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-5"
                                                    aria-label="Red" data-bs-original-title="Red"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-6"
                                                    aria-label="Orange" data-bs-original-title="Orange"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-7"
                                                    aria-label="Yellow" data-bs-original-title="Yellow"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-8"
                                                    aria-label="Green" data-bs-original-title="Green"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-9"
                                                    aria-label="Teal" data-bs-original-title="Teal"><i
                                                        class="ti ti-checks"></i></a>
                                                <a href="#!" data-bs-toggle="tooltip" data-value="preset-10"
                                                    aria-label="Cyan" data-bs-original-title="Cyan"><i
                                                        class="ti ti-checks"></i></a>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <h6 class="mb-1">Theme layout</h6>
                                            <p class="text-muted text-sm">Choose your layout</p>
                                            <div class="theme-main-layout d-flex align-center gap-1 w-100">
                                                <a href="#!" data-bs-toggle="tooltip" class="active"
                                                    data-value="vertical" aria-label="Vertical"
                                                    data-bs-original-title="Vertical"><img
                                                        src="../assets/images/customizer/caption-on.svg"
                                                        alt="img" class="img-fluid"> </a><a href="#!"
                                                    data-bs-toggle="tooltip" data-value="horizontal"
                                                    aria-label="Horizontal" data-bs-original-title="Horizontal"><img
                                                        src="../assets/images/customizer/horizontal.svg"
                                                        alt="img" class="img-fluid"> </a><a href="#!"
                                                    data-bs-toggle="tooltip" data-value="color-header"
                                                    aria-label="Color Header"
                                                    data-bs-original-title="Color Header"><img
                                                        src="../assets/images/customizer/color-header.svg"
                                                        alt="img" class="img-fluid"> </a><a href="#!"
                                                    data-bs-toggle="tooltip" data-value="compact"
                                                    aria-label="Compact" data-bs-original-title="Compact"><img
                                                        src="../assets/images/customizer/compact.svg" alt="img"
                                                        class="img-fluid"> </a><a href="#!"
                                                    data-bs-toggle="tooltip" data-value="tab" aria-label="Tab"
                                                    data-bs-original-title="Tab"><img
                                                        src="../assets/images/customizer/tab.svg" alt="img"
                                                        class="img-fluid"></a>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <h6 class="mb-1">Sidebar Caption</h6>
                                            <p class="text-muted text-sm">Sidebar Caption Hide/Show</p>
                                            <div class="row theme-color theme-nav-caption">
                                                <div class="col-6">
                                                    <div class="d-grid">
                                                        <button class="preset-btn btn-img btn active"
                                                            data-value="true" onclick="layout_caption_change('true');"
                                                            data-bs-toggle="tooltip" aria-label="Caption Show"
                                                            data-bs-original-title="Caption Show">
                                                            <img src="../assets/images/customizer/caption-on.svg"
                                                                alt="img" class="img-fluid">
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-grid">
                                                        <button class="preset-btn btn-img btn" data-value="false"
                                                            onclick="layout_caption_change('false');"
                                                            data-bs-toggle="tooltip" aria-label="Caption Hide"
                                                            data-bs-original-title="Caption Hide">
                                                            <img src="../assets/images/customizer/caption-off.svg"
                                                                alt="img" class="img-fluid">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pc-rtl">
                                                <h6 class="mb-1">Theme Layout</h6>
                                                <p class="text-muted text-sm">LTR/RTL</p>
                                                <div class="row theme-color theme-direction">
                                                    <div class="col-6">
                                                        <div class="d-grid">
                                                            <button class="preset-btn btn-img btn active"
                                                                data-value="false"
                                                                onclick="layout_rtl_change('false');"
                                                                data-bs-toggle="tooltip" aria-label="LTR"
                                                                data-bs-original-title="LTR">
                                                                <img src="../assets/images/customizer/ltr.svg"
                                                                    alt="img" class="img-fluid">
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid">
                                                            <button class="preset-btn btn-img btn" data-value="true"
                                                                onclick="layout_rtl_change('true');"
                                                                data-bs-toggle="tooltip" aria-label="RTL"
                                                                data-bs-original-title="RTL">
                                                                <img src="../assets/images/customizer/rtl.svg"
                                                                    alt="img" class="img-fluid">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item pc-box-width">
                                            <div class="pc-container-width">
                                                <h6 class="mb-1">Layout Width</h6>
                                                <p class="text-muted text-sm">
                                                    Choose Full or Container Layout
                                                </p>
                                                <div class="row theme-color theme-container">
                                                    <div class="col-6">
                                                        <div class="d-grid">
                                                            <button class="preset-btn btn-img btn active"
                                                                data-value="false"
                                                                onclick="change_box_container('false')"
                                                                data-bs-toggle="tooltip" aria-label="Full Width"
                                                                data-bs-original-title="Full Width">
                                                                <img src="../assets/images/customizer/full.svg"
                                                                    alt="img" class="img-fluid">
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid">
                                                            <button class="preset-btn btn-img btn" data-value="true"
                                                                onclick="change_box_container('true')"
                                                                data-bs-toggle="tooltip" aria-label="Fixed Width"
                                                                data-bs-original-title="Fixed Width">
                                                                <img src="../assets/images/customizer/fixed.svg"
                                                                    alt="img" class="img-fluid">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="d-grid">
                                                <button class="btn btn-light-danger" id="layoutreset">
                                                    Reset Layout
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simplebar-placeholder" style="width: 320px; height: 1304px;"></div>
            </div>
            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
            </div>
            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                <div class="simplebar-scrollbar"
                    style="height: 543px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
            </div>
        </div>
    </div>
    <script>document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('admin-search-input');
            const resultsContainer = document.getElementById('search-results');
            const resultsContent = document.getElementById('search-results-content');

            // 1. Phím tắt Ctrl + K để focus vào ô tìm kiếm
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });

            // 2. Xử lý tìm kiếm khi nhập liệu
            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;

                if (query.length < 2) {
                    resultsContainer.style.display = 'none';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`/admin/global-search?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            let html = '';

                            // Render Sản phẩm
                            if (data.products.length > 0) {
                                html += '<h6 class="dropdown-header">Sản phẩm</h6>';
                                data.products.forEach(p => {
                                    html +=
                                        `<a class="dropdown-item" href="/admin/products/${p.product_id}/edit">${p.product_name}</a>`;
                                });
                            }

                            // Render Danh mục
                            if (data.categories.length > 0) {
                                html +=
                                '<h6 class="dropdown-header text-primary">Danh mục</h6>';
                                data.categories.forEach(c => {
                                    html +=
                                        `<a class="dropdown-item" href="/admin/categories/${c.category_id}/edit">${c.category_name}</a>`;
                                });
                            }

                            // Render Khách hàng
                            if (data.users.length > 0) {
                                html +=
                                    '<h6 class="dropdown-header text-success">Người dùng</h6>';
                                data.users.forEach(u => {
                                    html +=
                                        `<a class="dropdown-item" href="/admin/users/${u.user_id}/edit">${u.full_name}</a>`;
                                });
                            }

                            if (html === '') {
                                html =
                                    '<div class="dropdown-item text-muted">Không tìm thấy kết quả</div>';
                            }

                            resultsContent.innerHTML = html;
                            resultsContainer.style.display = 'block';
                        });
                }, 300); // Đợi 300ms sau khi ngừng gõ để gửi request
            });

            // 3. Đóng kết quả khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.style.display = 'none';
                }
            });
        });
        </script>

    <script src="{{ asset('ableproadmin.com/assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/widgets/all-earnings-graph.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/widgets/page-views-graph.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/widgets/total-task-graph.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/widgets/download-graph.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/widgets/customer-rate-graph.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/widgets/tasks-graph.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/widgets/total-income-graph.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/plugins/i18next.min.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/plugins/i18nextHttpBackend.min.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/icon/custom-font.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/script.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/theme.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/multi-lang.js') }}"></script>
    <script src="{{ asset('ableproadmin.com/assets/js/plugins/feather.min.js') }}"></script>

    <script>
        // Khởi tạo các cấu hình mặc định của theme
        layout_change("light");
        change_box_container("false");
        layout_caption_change("true");
        layout_rtl_change("false");
        preset_change("preset-1");
        main_layout_change("vertical");

    </script>

    @stack('scripts')
</body>
<!-- [Body] end -->
<!-- Mirrored from ableproadmin.com/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 09 Jan 2026 02:18:51 GMT -->

</html>
