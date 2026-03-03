<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('admin.dashboard') }}"
                class="b-brand text-primary"><!-- ========   Change your logo from here   ============ -->
                <img src="{{ asset('admin-assets') }}/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo" />
                <span class="badge bg-light-success rounded-pill ms-2 theme-version">v9.6.2</span></a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset('admin-assets/images/user/avatar-1.jpg') }}"
                                alt="user-image" class="user-avtar wid-45 rounded-circle" style="object-fit: cover;" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <small>{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse"
                            href="#pc_sidebar_userlink"><svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg></a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="{{ route('admin.profile.index') }}"><i class="ti ti-user"></i>
                                <span data-i18n="My Account">My Account</span> </a><a
                                href="{{ route('admin.settings.index') }}"><i class="ti ti-settings"></i>
                                <span data-i18n="Settings">Settings</span> </a><a href="{{ route('admin.lock') }}"><i
                                    class="ti ti-lock"></i>
                                <span data-i18n="Lock Screen">Lock Screen</span> </a><a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="ti ti-power"></i>
                                <span data-i18n="Logout">Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.dashboard') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{ route('welcome') }}" class="pc-link" target="_blank">
                        <span class="pc-micon">
                            <i class="ti ti-world"></i>
                        </span>
                        <span class="pc-mtext">Xem Website</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Quản lý</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-presentation-chart"></use>
                    </svg>
                </li>
                @if (auth()->user()->isAdmin() || auth()->user()->isStaff())
                    <li class="pc-item">
                        <a href="{{ route('admin.categories.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-list"></i>
                            </span>
                            <span class="pc-mtext">Danh mục</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.orders.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-shopping-cart"></i>
                            </span>
                            <span class="pc-mtext">Đơn hàng</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.products.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-box"></i>
                            </span>
                            <span class="pc-mtext">Sản phẩm</span>
                        </a>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-palette"></i>
                            </span>
                            <span class="pc-mtext">Thuộc tính</span>
                            <span class="pc-arrow">
                                <i data-feather="chevron-right"></i>
                            </span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item">
                                <a href="{{ route('admin.sizes.index') }}" class="pc-link">
                                    <span class="pc-mtext">Kích thước</span>
                                </a>
                            </li>
                            <li class="pc-item">
                                <a href="{{ route('admin.colors.index') }}" class="pc-link">
                                    <span class="pc-mtext">Màu sắc</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->isAdmin())
                    <li class="pc-item">
                        <a href="{{ route('admin.payment-history.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-receipt"></i>
                            </span>
                            <span class="pc-mtext">Lịch sử thanh toán</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.banners.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-photo"></i>
                            </span>
                            <span class="pc-mtext">Quản lý Banner</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.coupons.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-ticket"></i>
                            </span>
                            <span class="pc-mtext">Mã Giảm Giá</span>
                        </a>
                    </li>
                @endif
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="ti ti-archive"></i>
                        </span>
                        <span class="pc-mtext">Quản lý Kho</span>
                        <span class="pc-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('admin.vouchers.index') }}" class="pc-link">
                                <span class="pc-mtext">Phiếu Nhập/Xuất</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.warehouses.index') }}" class="pc-link">
                                <span class="pc-mtext">Danh sách Kho</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.suppliers.index') }}" class="pc-link">
                                <span class="pc-mtext">Nhà cung cấp</span>
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('admin.stock.index') }}" class="pc-link">
                                <span class="pc-mtext">Báo cáo tồn kho</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.chat.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ti ti-messages"></i>
                        </span>
                        <span class="pc-mtext">Hỗ trợ khách hàng</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.contact-messages.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ti ti-mail"></i>
                        </span>
                        <span class="pc-mtext">Tin nhắn liên hệ</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('admin.reviews.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ti ti-star"></i>
                        </span>
                        <span class="pc-mtext">Đánh giá sản phẩm</span>
                    </a>
                </li>
                @if (auth()->user()->isAdmin())
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-news"></i>
                            </span>
                            <span class="pc-mtext">Quản lý Blog</span>
                            <span class="pc-arrow">
                                <i data-feather="chevron-right"></i>
                            </span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item">
                                <a href="{{ route('admin.post-categories.index') }}" class="pc-link">
                                    <span class="pc-mtext">Danh mục tin</span>
                                </a>
                            </li>
                            <li class="pc-item">
                                <a href="{{ route('admin.posts.index') }}" class="pc-link">
                                    <span class="pc-mtext">Bài viết</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.loyalty-points.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-coin"></i>
                            </span>
                            <span class="pc-mtext">Cấu hình Tích điểm</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->isAdmin())
                    <li class="pc-item">
                        <a href="{{ route('admin.users.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="pc-mtext">Người dùng</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.audit-logs.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-history"></i>
                            </span>
                            <span class="pc-mtext">Nhật ký hệ thống</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.settings.chatbot') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-settings"></i>
                            </span>
                            <span class="pc-mtext">Cấu hình Chatbot</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>