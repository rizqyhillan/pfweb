<!doctype html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="layout-menu-fixed layout-compact"
  data-assets-path="{{ asset('admin-assets/') }}/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'PawPet') }} {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Admin' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('admin-assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin-assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- PawPet Orange Accent Override -->
    <style>
      :root {
        --bs-primary: #f59e0b !important;
        --bs-primary-rgb: 245, 158, 11 !important;
      }
      .bg-primary, .btn-primary {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
      }
      .btn-primary:hover, .btn-primary:focus {
        background-color: #d97706 !important;
        border-color: #d97706 !important;
      }
      .text-primary { color: #f59e0b !important; }
      .menu-vertical .menu-item.active > .menu-link {
        background-color: rgba(245, 158, 11, 0.16) !important;
        color: #f59e0b !important;
      }
      .menu-vertical .menu-item.active > .menu-link .menu-icon {
        color: #f59e0b !important;
      }
      .menu-vertical .menu-item .menu-link:hover {
        color: #f59e0b !important;
      }
      .form-check-input:checked {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
      }
      a { color: #d97706; }
      a:hover { color: #f59e0b; }
      .page-item.active .page-link {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
      }
      .nav-pills .nav-link.active {
        background-color: #f59e0b !important;
      }
      .progress-bar {
        background-color: #f59e0b !important;
      }
      .app-brand-text {
        color: #92400e !important;
      }
    </style>

    @yield('page-css')

    <!-- Helpers -->
    <script src="{{ asset('admin-assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('admin-assets/js/config.js') }}"></script>
    
    @vite(['resources/js/app.js'])
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Sidebar Menu -->
        @if(Auth::check() && Auth::user()->role === 'dokter')
          @include('doctor.partials.sidebar')
        @elseif(Auth::check() && Auth::user()->role === 'karyawan')
          @include('karyawan.partials.sidebar')
        @else
          @include('admin.partials.sidebar')
        @endif

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Navbar -->
          @include('admin.partials.navbar')

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">

              {{-- Flash Messages --}}
              @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              @yield('content')
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="text-body mb-2 mb-md-0">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Pawpet') }}. All rights reserved.
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('admin-assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin-assets/js/main.js') }}"></script>

    <!-- Real-time Toast Notification Container -->
    <div id="realtime-toast-container" style="position:fixed;top:20px;right:20px;z-index:99999;max-width:400px;"></div>

    <script>
      // Global real-time notification helper
      window.PawPetRealtime = {
          showToast: function(title, message, type) {
              type = type || 'info';
              let colors = {info: '#0ea5e9', success: '#22c55e', warning: '#f59e0b', danger: '#ef4444'};
              let icons  = {info: 'bx-info-circle', success: 'bx-check-circle', warning: 'bx-error', danger: 'bx-x-circle'};
              let container = document.getElementById('realtime-toast-container');
              let toast = document.createElement('div');
              toast.style.cssText = 'background:#fff;border-radius:10px;box-shadow:0 8px 32px rgba(0,0,0,.15);padding:16px 20px;margin-bottom:12px;display:flex;align-items:flex-start;gap:12px;animation:slideIn .3s ease;border-left:4px solid '+colors[type]+';min-width:300px;';
              toast.innerHTML = `
                <i class="bx ${icons[type]}" style="font-size:24px;color:${colors[type]};margin-top:2px;"></i>
                <div style="flex:1;">
                  <strong style="display:block;margin-bottom:2px;font-size:14px;">${title}</strong>
                  <span style="font-size:13px;color:#666;">${message}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;font-size:18px;cursor:pointer;color:#999;">&times;</button>
              `;
              container.appendChild(toast);
              setTimeout(() => { if(toast.parentElement) toast.remove(); }, 8000);
          }
      };
    </script>
    <style>
      @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>

    @yield('page-js')
  </body>
</html>
