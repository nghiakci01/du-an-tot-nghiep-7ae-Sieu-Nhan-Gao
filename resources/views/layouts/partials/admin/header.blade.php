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
              <a href="#!" class="dropdown-item" onclick="layout_change('dark')"><svg class="pc-icon">
                  <use xlink:href="#custom-moon"></use>
                </svg>
                <span>Tối</span> </a><a href="#!" class="dropdown-item" onclick="layout_change('light')"><svg
                  class="pc-icon">
                  <use xlink:href="#custom-sun-1"></use>
                </svg>
                <span>Sáng</span> </a><a href="#!" class="dropdown-item" onclick="layout_change_default()"><svg
                  class="pc-icon">
                  <use xlink:href="#custom-setting-2"></use>
                </svg>
                <span>Mặc định</span></a>
            </div>
          </li>
          {{-- Language Switcher (Removed)
          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false"><svg class="pc-icon">
                <use xlink:href="#custom-language"></use>
              </svg></a>
            <div class="dropdown-menu dropdown-menu-end pc-h-dropdown lng-dropdown">
              <a href="#!" class="dropdown-item" data-lng="en"><span>English <small>(UK)</small> </span></a><a href="#!"
                class="dropdown-item" data-lng="fr"><span>français <small>(French)</small> </span></a><a href="#!"
                class="dropdown-item" data-lng="ro"><span>Română <small>(Romanian)</small> </span></a><a href="#!"
                class="dropdown-item" data-lng="cn"><span>中国人 <small>(Chinese)</small></span></a>
            </div>
          </li>
          --}}

          {{-- Setting --}}
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
          </li> --}}

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
              aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false"><img
                src="{{ asset('admin-assets') }}/images/user/avatar-2.jpg" alt="user-image" class="user-avtar" /></a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header d-flex align-items-center justify-content-between">
                <h5 class="m-0">Profile</h5>
              </div>
              <div class="dropdown-body">
                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                  <div class="d-flex mb-1">
                    <div class="flex-shrink-0">
                      <img src="{{ asset('admin-assets') }}/images/user/avatar-2.jpg" alt="user-image"
                        class="user-avtar wid-35" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-1">Carson Darrin 🖖</h6>
                      <span><a href="../cdn-cgi/l/email-protection.html" class="__cf_email__"
                          data-cfemail="b9dad8cbcad6d797ddd8cbcbd0d7f9dad6d4c9d8d7c097d0d6">[email&#160;protected]</a></span>
                    </div>
                  </div>
                  <hr class="border-secondary border-opacity-50" />
                  <div class="card">
                    <div class="card-body py-3">
                      <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 d-inline-flex align-items-center">
                          <svg class="pc-icon text-muted me-2">
                            <use xlink:href="#custom-notification-outline"></use>
                          </svg>Notification
                        </h5>
                        <div class="form-check form-switch form-check-reverse m-0">
                          <input class="form-check-input f-18" type="checkbox" role="switch" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <p class="text-span">Manage</p>
                  <a href="#" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-setting-outline"></use>
                      </svg>
                      <span>Settings</span>
                    </span></a><a href="#" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-share-bold"></use>
                      </svg>
                      <span>Share</span>
                    </span></a><a href="#" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-lock-outline"></use>
                      </svg>
                      <span>Change Password</span></span></a>
                  <hr class="border-secondary border-opacity-50" />
                  <p class="text-span">Team</p>
                  <a href="#" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-profile-2user-outline"></use>
                      </svg>
                      <span>UI Design team</span></span>
                    <div class="user-group">
                      <img src="{{ asset('admin-assets') }}/images/user/avatar-1.jpg" alt="user-image" class="avtar" />
                      <span class="avtar bg-danger text-white">K</span>
                      <span class="avtar bg-success text-white"><svg class="pc-icon m-0">
                          <use xlink:href="#custom-user"></use>
                        </svg> </span><span class="avtar bg-light-primary text-primary">+2</span>
                    </div>
                  </a><a href="#" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-profile-2user-outline"></use>
                      </svg>
                      <span>Friends Groups</span></span>
                    <div class="user-group">
                      <img src="{{ asset('admin-assets') }}/images/user/avatar-1.jpg" alt="user-image" class="avtar" />
                      <span class="avtar bg-danger text-white">K</span>
                      <span class="avtar bg-success text-white"><svg class="pc-icon m-0">
                          <use xlink:href="#custom-user"></use>
                        </svg></span>
                    </div>
                  </a><a href="#" class="dropdown-item"><span><svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-add-outline"></use>
                      </svg>
                      <span>Add new</span></span>
                    <div class="user-group">
                      <span class="avtar bg-primary text-white"><svg class="pc-icon m-0">
                          <use xlink:href="#custom-add-outline"></use>
                        </svg></span>
                    </div>
                  </a>
                  <hr class="border-secondary border-opacity-50" />
                  <div class="d-grid mb-3">
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button class="btn btn-primary w-100" type="submit">
                        <svg class="pc-icon me-2">
                          <use xlink:href="#custom-logout-1-outline"></use>
                        </svg>Logout
                      </button>
                    </form>
                  </div>
                  <div class="card border-0 shadow-none drp-upgrade-card mb-0" style="
                        background-image: url({{ asset('admin-assets') }}/images/layout/img-profile-card.jpg);
                      ">
                    <div class="card-body">
                      <div class="user-group">
                        <img src="{{ asset('admin-assets') }}/images/user/avatar-1.jpg" alt="user-image"
                          class="avtar" />
                        <img src="{{ asset('admin-assets') }}/images/user/avatar-2.jpg" alt="user-image"
                          class="avtar" />
                        <img src="{{ asset('admin-assets') }}/images/user/avatar-3.jpg" alt="user-image"
                          class="avtar" />
                        <img src="{{ asset('admin-assets') }}/images/user/avatar-4.jpg" alt="user-image"
                          class="avtar" />
                        <img src="{{ asset('admin-assets') }}/images/user/avatar-5.jpg" alt="user-image"
                          class="avtar" />
                        <span class="avtar bg-light-primary text-primary">+20</span>
                      </div>
                      <h3 class="my-3 text-dark">
                        245.3k <small class="text-muted">Followers</small>
                      </h3>
                      <a href="#" class="btn btn btn-warning buynowlinks"><svg class="pc-icon me-2">
                          <use xlink:href="#custom-logout-1-outline"></use>
                        </svg>
                        Upgrade to Business</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </header>