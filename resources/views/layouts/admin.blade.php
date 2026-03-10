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
</head><!-- [Head] end --><!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
  data-pc-theme_contrast="" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  {{-- Include Sidebar --}}
  @include('layouts.partials.admin.sidebar')

  {{-- Include Header --}}
  @include('layouts.partials.admin.header')

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
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
  <script src="{{ asset('admin-assets') }}/js/script.js"></script>
  <script src="{{ asset('admin-assets') }}/js/theme.js"></script>
  {{--
  <script src="{{ asset('admin-assets') }}/js/multi-lang.js"></script> --}}
  <script src="{{ asset('admin-assets') }}/js/plugins/feather.min.js"></script>
  {{--
  <script defer="defer" src="https://fomo.codedthemes.com/pixel/CDkpF1sQ8Tt5wpMZgqRvKpQiUhpWE3bc"></script> --}}

  <script>
    layout_change("light");
  </script>
  <script>
    change_box_container("false");
  </script>
  <script>
    layout_caption_change("true");
  </script>
  <script>
    layout_rtl_change("false");
  </script>
  <script>
    preset_change("preset-1");
  </script>
  <script>
    main_layout_change("vertical");
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
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(formId).submit();
        }
      })
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
      setInterval(function() {
        fetch('{{ route("admin.notifications.unread_count") }}', {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
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