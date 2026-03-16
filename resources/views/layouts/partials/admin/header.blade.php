  <header class="pc-header">
    <div class="header-wrapper">
      <!-- [Mobile Media Block] start -->
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <!-- ======= Menu collapse Icon ===== -->
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide"><i class="ti ti-menu-2"></i></a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse"><i class="ti ti-menu-2"></i></a>
          </li>

        </ul>
      </div>
      <!-- [Mobile Media Block end] -->
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false"><svg class="pc-icon">
                <use xlink:href="#custom-sun-1"></use>
              </svg></a>
            <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
              <a href="#!" class="dropdown-item" onclick="layout_change('dark'); localStorage.setItem('theme','dark')">
                <svg class="pc-icon">
                  <use xlink:href="#custom-moon"></use>
                </svg>
                <span>Tối</span>
              </a>
              <a href="#!" class="dropdown-item" onclick="layout_change('light'); localStorage.setItem('theme','light')">
                <svg class="pc-icon">
                  <use xlink:href="#custom-sun-1"></use>
                </svg>
                <span>Sáng</span>
              </a>
              <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                <i class="ph-duotone ph-cpu pc-icon"></i>
                <span>Tự động</span>
              </a>
            </div>
          </li>

          <!-- {{-- Setting --}}
          {{-- <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false"><svg class="pc-icon">
                <use xlink:href="#custom-setting-2"></use>
              </svg></a>
            <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
              <a href="#!" class="dropdown-item"><i class="ti ti-user"></i> <span>My Account</span> </a><a href="#!"
                class="dropdown-item"><i class="ti ti-settings"></i> <span>Settings</span> </a><a href="#!"
                class="dropdown-item"><i class="ti ti-headset"></i> <span>Support</span> </a><a href="#!"
                class="dropdown-item"><i class="ti ti-lock"></i> <span>Lock Screen</span> </a><a href="#!"
                class="dropdown-item"><i class="ti ti-power"></i> <span>Logout</span></a>
            </div>
          </li> --}} -->

          {{-- Notification Sound Toggle --}}
          <li class="pc-h-item">
            <a href="#" class="pc-head-link me-0" id="notif-sound-toggle" title="Bật/Tắt âm thanh thông báo">
              <i class="ti ti-volume fs-5" id="notif-sound-icon"></i>
            </a>
          </li>

          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false"><svg class="pc-icon">
                <use xlink:href="#custom-notification"></use>
              </svg>
              @if(isset($admin_unread_count) && $admin_unread_count > 0)
                <span class="badge bg-danger pc-h-badge" id="notif-badge">{{ $admin_unread_count }}</span>
              @else
                <span class="badge bg-danger pc-h-badge d-none" id="notif-badge">0</span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header d-flex align-items-center justify-content-between">
                <h5 class="m-0">Thông báo</h5>
                @if(isset($admin_unread_count) && $admin_unread_count > 0)
                  <form action="{{ route('admin.notifications.markAllRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm p-0">Đánh dấu đã đọc</button>
                  </form>
                @endif
              </div>
              <div class="dropdown-body text-wrap header-notification-scroll position-relative"
                style="max-height: calc(100vh - 215px)">
                @if(isset($admin_notifications) && $admin_notifications->count() > 0)
                  @foreach($admin_notifications as $notification)
                    <div class="card mb-2 notification-item {{ $notification->read_at ? '' : 'bg-light-primary' }}">
                      <div class="card-body">
                        <div class="d-flex">
                          <div class="flex-shrink-0">
                            @php
                              $icon = match($notification->data['type'] ?? '') {
                                'new_order' => 'custom-layer',
                                'payment_success' => 'custom-status-up',
                                'low_stock' => 'custom-notification-outline',
                                'new_review' => 'custom-message-2',
                                default => 'custom-sms'
                              };
                              $color = match($notification->data['type'] ?? '') {
                                'new_order' => 'text-primary',
                                'payment_success' => 'text-success',
                                'low_stock' => 'text-danger',
                                'new_review' => 'text-warning',
                                default => 'text-muted'
                              };
                            @endphp
                            <svg class="pc-icon {{ $color }}">
                              <use xlink:href="#{{ $icon }}"></use>
                            </svg>
                          </div>
                          <div class="flex-grow-1 ms-3">
                            <span class="float-end text-sm text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                            <h5 class="text-body mb-2">{{ $notification->data['message'] ?? 'Thông báo mới' }}</h5>
                            @if(isset($notification->data['link']))
                              <a href="{{ route('admin.notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-link-primary p-0">Chi tiết</a>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <p class="text-center py-3 text-muted">Không có thông báo mới</p>
                @endif
              </div>
              <div class="text-center py-2">
                <a href="{{ route('admin.notifications.index') }}" class="link-primary">Xem tất cả thông báo</a>
              </div>
            </div>
          </li>
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                @if(Auth::check() && Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="user-image" class="user-avtar" />
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::check() ? Auth::user()->name : 'Admin') }}&background=random" alt="user-image" class="user-avtar" />
                @endif
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header d-flex align-items-center justify-content-between">
                <h5 class="m-0">Hồ sơ</h5>
              </div>
              <div class="dropdown-body">
                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                  <div class="d-flex mb-1">
                    <div class="flex-shrink-0">
                      @if(Auth::check() && Auth::user()->avatar)
                          <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="user-image" class="user-avtar wid-35" />
                      @else
                          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::check() ? Auth::user()->name : 'Admin') }}&background=random" alt="user-image" class="user-avtar wid-35" />
                      @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-1">{{ Auth::check() ? Auth::user()->name : 'Admin' }} 🖖</h6>
                      <span><a href="mailto:{{ Auth::check() ? Auth::user()->email : '#' }}" class="__cf_email__">{{ Auth::check() ? Auth::user()->email : 'admin@example.com' }}</a></span>
                    </div>
                  </div>
                  <hr class="border-secondary border-opacity-50" />
                  <div class="card">
                    <div class="card-body py-3">
                      <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 d-inline-flex align-items-center">
                          <svg class="pc-icon text-muted me-2">
                            <use xlink:href="#custom-notification-outline"></use>
                          </svg>Thông báo
                        </h5>
                        <div class="form-check form-switch form-check-reverse m-0">
                          <input class="form-check-input f-18" type="checkbox" role="switch" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr class="border-secondary border-opacity-50" />
                  <p class="text-span">Quản lý</p>
                  <a href="{{ route('admin.profile.index') }}" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-setting-outline"></use>
                      </svg>
                      <span>Cài đặt</span>
                    </span></a><a href="{{ route('admin.profile.index') }}" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-lock-outline"></use>
                      </svg>
                      <span>Đổi mật khẩu</span></span></a>
                  <hr class="border-secondary border-opacity-50" />
                  <div class="d-grid mb-3 mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button class="btn btn-primary w-100" type="submit">
                        <svg class="pc-icon me-2">
                          <use xlink:href="#custom-logout-1-outline"></use>
                        </svg>Đăng xuất
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