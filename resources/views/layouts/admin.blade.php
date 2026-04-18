<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<!-- Mirrored from ableproadmin.com/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 09 Jan 2026 02:17:47 GMT -->

<head>
  <title>@yield('title', 'Admin Dashboard') | Able Pro Dashboard Template</title>
  <!-- [Meta] -->
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
  <meta name="color-scheme" content="light dark">
  <script>
    (function() {
      const savedTheme = localStorage.getItem('theme') || 'light';
      const html = document.documentElement;
      html.setAttribute('data-pc-theme', savedTheme);
      if (savedTheme === 'dark') {
          html.classList.add('dark-mode');
      }

      // Đồng bộ luôn cho body nếu nó đã có sẵn (dù script này thường chạy ở head)
      document.addEventListener('DOMContentLoaded', () => {
          document.body.setAttribute('data-pc-theme', savedTheme);
      });
    })();
  </script>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description"
    content="Able Pro is trending dashboard template made using Bootstrap 5 design framework. Able Pro is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
  <meta name="keywords"
    content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
  <meta name="author" content="Phoenixcoded" />
  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('admin-assets') }}/images/favicon.svg" type="image/x-icon" />
  <!-- [Font] Family -->
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/fonts/inter/inter.css" id="main-font-link" />
  <!-- [phosphor Icons] https://phosphoricons.com/ -->
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/fonts/phosphor/duotone/style.css" />
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/fonts/tabler-icons.min.css" />
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/fonts/feather.css" />
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/fonts/fontawesome.css" />
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/fonts/material.css" />
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/css/style.css" id="main-style-link" />
  <script src="{{ asset('admin-assets') }}/js/tech-stack.js"></script>
  {{--
  <script async src="https://www.googletagmanager.com/gtag/js?id="></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
      dataLayer.push(arguments);
    }
    gtag("js", new Date());
    gtag("config", "");
  </script>
  <script type="text/javascript">
    (function (c, l, a, r, i, t, y) {
      c[a] =
        c[a] ||
        function () {
          (c[a].q = c[a].q || []).push(arguments);
        };
      t = l.createElement(r);
      t.async = 1;
      t.src = "https://www.clarity.ms/tag/" + i;
      y = l.getElementsByTagName(r)[0];
      y.parentNode.insertBefore(t, y);
    })(window, document, "clarity", "script", "");
  </script>
  <script defer="defer" src="https://phpstack-207002-5085356.cloudwaysapps.com/pixel/"></script> --}}
  <link rel="stylesheet" href="{{ asset('admin-assets') }}/css/style-preset.css" />
  <style>
    a {
      text-decoration: none !important;
    }

    /* Layout-Aware User Profile */
    /* Ẩn Header Profile khi ở Vertical (mặc định) */
    body:not([data-pc-layout="compact"]):not([data-pc-layout="tab"]) .pc-user-profile-header {
        display: none !important;
    }

    /* Ẩn Sidebar Profile khi ở Compact hoặc Tab */
    body[data-pc-layout="compact"] .pc-user-profile-sidebar,
    body[data-pc-layout="tab"] .pc-user-profile-sidebar {
        display: none !important;
    }

    /* Dark Mode Improvements */
    [data-pc-theme="dark"] .bg-light-primary {
        background-color: rgba(70, 128, 255, 0.15) !important;
        color: #72a1ff !important;
    }
    [data-pc-theme="dark"] .bg-light-success {
        background-color: rgba(44, 168, 127, 0.15) !important;
    }

    /* Red Shake Validation */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
    }
    .shake-error {
        animation: shake 0.3s ease-in-out;
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
    .is-invalid.shake-error {
        border-color: #dc3545 !important;
    }
        color: #48d6a8 !important;
    }
    [data-pc-theme="dark"] .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.15) !important;
        color: #ffd666 !important;
    }
    [data-pc-theme="dark"] .bg-light-danger {
        background-color: rgba(220, 53, 69, 0.15) !important;
        color: #ff8e99 !important;
    }
    [data-pc-theme="dark"] .bg-light-info {
        background-color: rgba(61, 197, 255, 0.15) !important;
        color: #7cd9ff !important;
    }
    [data-pc-theme="dark"] .bg-light-secondary {
        background-color: rgba(108, 117, 125, 0.15) !important;
        color: #aeb5bc !important;
    }
    [data-pc-theme="dark"] .text-dark {
        color: var(--bs-body-color) !important;
    }
    /* Status Update Form Dark Mode Fix */
    [data-pc-theme="dark"] .status-update-form {
        background-color: rgba(0, 0, 0, 0.2) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    [data-pc-theme="dark"] .status-update-form .form-label {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    [data-pc-theme="dark"] .status-update-form .form-control,
    [data-pc-theme="dark"] .status-update-form .form-select {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
    }
    [data-pc-theme="dark"] .status-update-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.4) !important;
    }
    [data-pc-theme="dark"] .status-update-form .form-select option {
        background-color: #1a1a1a !important;
        color: #fff !important;
    }
    [data-pc-theme="dark"] {
        --barcode-brightness: 0.85;
    }

    /* --- Global Sticky Action Column --- */
    .table-responsive {
        position: relative;
    }
    .sticky-action-column {
        position: sticky !important;
        right: 0;
        z-index: 10;
        background-color: #fff !important;
        box-shadow: -5px 0 10px rgba(0,0,0,0.05);
        border-left: 1px solid #dee2e6 !important;
        text-align: center;
        vertical-align: middle;
    }
    .table-striped tbody tr:nth-of-type(odd) .sticky-action-column {
        background-color: #f8f9fa !important;
    }
    tr:hover .sticky-action-column {
        background-color: #f1f4f9 !important;
    }
    [data-pc-theme="dark"] .sticky-action-column {
        background-color: #1a1c1e !important;
        border-left: 1px solid #323539 !important;
        box-shadow: -5px 0 10px rgba(0,0,0,0.2);
    }
    [data-pc-theme="dark"] .table-striped tbody tr:nth-of-type(odd) .sticky-action-column {
        background-color: #212529 !important;
    }
    [data-pc-theme="dark"] tr:hover .sticky-action-column {
        background-color: #2b3035 !important;
    }

    /* --- Global Header Adjustments --- */
    /* Loại bỏ vách ngăn giữa các thẻ ở Header */
  </style>
  @stack('css')
</head><!-- [Head] end --><!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
  data-pc-theme_contrast="" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <script>
      // Automatically hide loader on normal page load
      document.addEventListener('DOMContentLoaded', function() {
          setTimeout(() => {
              const loader = document.querySelector('.loader-bg');
              if(loader) loader.style.display = 'none';
          }, 200);
      });
  </script>

  {{-- Include Sidebar --}}
  @include('layouts.partials.admin.sidebar')

  {{-- Include Header --}}
  @include('layouts.partials.admin.header')

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-x-4 m-t-4" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show m-x-4 m-t-4" role="alert">
          {{ session('warning') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-x-4 m-t-4" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show m-x-4 m-t-4" role="alert">
          {{ session('info') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')
    </div>
  </div>

  {{-- Footer --}}
  @include('layouts.partials.admin.footer')

  {{-- Theme setting --}}
  @include('layouts.partials.admin.theme_settings')

  <!-- [Page Specific JS] start -->
  {{--
  <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> --}}
  <!-- [Page Specific JS] end --><!-- Required Js -->
  <script src="{{ asset('admin-assets') }}/js/plugins/popper.min.js"></script>
  <script src="{{ asset('admin-assets') }}/js/plugins/simplebar.min.js"></script>
  <script src="{{ asset('admin-assets') }}/js/plugins/bootstrap.min.js"></script>
  {{--
  <script src="{{ asset('admin-assets') }}/js/plugins/i18next.min.js"></script>
  <script src="{{ asset('admin-assets') }}/js/plugins/i18nextHttpBackend.min.js"></script> --}}
  <script src="{{ asset('admin-assets') }}/js/icon/custom-font.js"></script>
  <script src="{{ asset('admin-assets') }}/js/script.js?v=1.0.3"></script>
  <script src="{{ asset('admin-assets') }}/js/theme.js?v=1.0.1"></script>
  {{--
  <script src="{{ asset('admin-assets') }}/js/multi-lang.js"></script> --}}
  <script src="{{ asset('admin-assets') }}/js/plugins/feather.min.js"></script>
  {{--
  <script defer="defer" src="https://fomo.codedthemes.com/pixel/CDkpF1sQ8Tt5wpMZgqRvKpQiUhpWE3bc"></script> --}}

  <script src="{{ asset('admin-assets') }}/js/plugins/feather.min.js"></script>

  <!-- jQuery (Cần có cho Pjax) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!-- Pjax -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
  <script>
      $(document).ready(function() {
          // Khởi tạo pjax cho các link menu và nút bấm chuyển trang
          $(document).pjax('a.pc-link:not([target="_blank"]):not([data-pjax="false"]), a.nav-link:not([data-bs-toggle]):not([target="_blank"]):not([data-pjax="false"]), .pagination a, a.btn:not([target="_blank"]):not([data-pjax="false"])', '.pc-content', {
              fragment: '.pc-content', // Bắt buộc phải có để báo Pjax chỉ rút trích phần .pc-content
              timeout: 10000,
              scrollTo: 0 // Cuộn lên đầu
          });

          // Khởi tạo pjax form submit (loại trừ form logout và form upload file)
          $(document).on('submit', 'form:not(#logout-form):not([enctype="multipart/form-data"]):not(.no-pjax)', function(event) {
              $.pjax.submit(event, '.pc-content', {
                  fragment: '.pc-content',
                  timeout: 10000,
                  scrollTo: 0
              });
          });

          // Hiển thị loading
          $(document).on('pjax:send', function() {
              $('.loader-bg').show();
          });

          // Tắt loading
          $(document).on('pjax:complete', function() {
              console.log("Pjax complete triggered.");
              $('.loader-bg').fadeOut('slow');

              // Update sidebar active menu
              if (window.update_active_menu) {
                  console.log("Calling update_active_menu()...");
                  window.update_active_menu();
              } else {
                  console.warn("window.update_active_menu is not defined!");
              }

              // Khởi tạo lại Feather icons và tooltip/popover nêú cần
              if(window.feather) feather.replace();

              const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
              tooltipTriggerList.map(function (tooltipTriggerEl) {
                  return new bootstrap.Tooltip(tooltipTriggerEl);
              });

              // the following block is commented out because jquery-pjax already executes scripts
              // within the fragment, and manual eval causes double execution (e.g., double event binding)
              /*
              $('.pc-content script').each(function() {
                  if (this.src) {
                      $.getScript(this.src);
                  } else {
                      eval($(this).text());
                  }
              });
              */

              // Nếu có sử dụng DataTables, cần re-init lại bảng
              if ($.fn.DataTable) {
                  $('.table:not(.initialized)').addClass('initialized').DataTable();
              }
          });
      });
  </script>

  {{--
  <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
    integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
    data-cf-beacon='{"version":"2024.11.0","token":"db59679aec724f808b8e535e8076f80c","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
    crossorigin="anonymous"></script> --}}

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function confirmDelete(formId) {
      Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        allowOutsideClick: false,
        allowEscapeKey: false
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.getElementById(formId);
          if (form) {
            form.submit();
          }
        }
      });
      return false;
    }
  </script>

  @yield('scripts')

  {{-- Notification Sound System --}}
  <script>
    (function() {
      const STORAGE_KEY = 'elite_notif_sound_enabled';
      const soundToggleBtn = document.getElementById('notif-sound-toggle');
      const soundIcon = document.getElementById('notif-sound-icon');
      const badge = document.getElementById('notif-badge');

      // Load saved preference (default: enabled)
      let soundEnabled = localStorage.getItem(STORAGE_KEY) !== 'false';
      let lastCount = parseInt(badge ? badge.textContent : '0') || 0;

      function updateIcon() {
        if (!soundIcon) return;
        soundIcon.className = soundEnabled ? 'ti ti-volume fs-5' : 'ti ti-volume-off fs-5';
        if(soundToggleBtn) soundToggleBtn.title = soundEnabled ? 'Tắt âm thanh thông báo' : 'Bật âm thanh thông báo';
      }

      function playChime() {
        if (!soundEnabled) return;
        try {
          const ctx = new (window.AudioContext || window.webkitAudioContext)();
          const freqs = [880, 1108, 1320];
          freqs.forEach((freq, i) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.value = freq;
            const t = ctx.currentTime + i * 0.18;
            gain.gain.setValueAtTime(0, t);
            gain.gain.linearRampToValueAtTime(0.4, t + 0.05);
            gain.gain.linearRampToValueAtTime(0, t + 0.35);
            osc.start(t);
            osc.stop(t + 0.4);
          });
        } catch(e) { console.warn('Notification sound error:', e); }
      }

      // Toggle sound on button click
      if (soundToggleBtn) {
        soundToggleBtn.addEventListener('click', function(e) {
          e.preventDefault();
          soundEnabled = !soundEnabled;
          localStorage.setItem(STORAGE_KEY, soundEnabled);
          updateIcon();
          // Demo chime when turning on
          if (soundEnabled) playChime();
        });
      }

      // Poll for new notifications every 30 seconds
      var _adminNotifPollTimer = setInterval(function() {
        fetch('{{ route("admin.notifications.unread_count") }}', {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => {
          if (r.status === 401) {
            clearInterval(_adminNotifPollTimer);
            if (badge) badge.classList.add('d-none');
            return null;
          }
          return r.json();
        })
        .then(data => {
          if (!data) return;
          const count = data.count || 0;
          if (badge) {
            badge.textContent = count;
            badge.classList.toggle('d-none', count === 0);
          }
          if (count > lastCount) {
            playChime();
          }
          lastCount = count;
        })
        .catch(() => {});
      }, 30000);

      updateIcon();
    })();
  </script>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
  </form>
</body>
<!-- [Body] end -->
<!-- Mirrored from ableproadmin.com/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 09 Jan 2026 02:18:51 GMT -->

</html>
