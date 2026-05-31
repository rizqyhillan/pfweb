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
    @if(Auth::check())
      <meta name="user-id" content="{{ Auth::id() }}">
    @endif

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'PawPet') }} {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Admin' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/pawpet/logo/PawPet Logo New.jpg') }}" />

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
    
    @vite(['resources/css/app.css'])
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

              {{-- SweetAlert2 Flash Messages & Delete Confirmation --}}
              <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
              <script>
                document.addEventListener("DOMContentLoaded", function() {
                  @if(session('success'))
                    Swal.fire({
                      icon: 'success',
                      title: 'Berhasil!',
                      text: '{!! addslashes(session('success')) !!}',
                      timer: 3000,
                      showConfirmButton: false
                    });
                  @endif

                  @if(session('error'))
                    Swal.fire({
                      icon: 'error',
                      title: 'Gagal!',
                      text: '{!! addslashes(session('error')) !!}',
                    });
                  @endif

                  @if($errors->any())
                    Swal.fire({
                      icon: 'error',
                      title: 'Gagal!',
                      html: '{!! addslashes(implode("<br>", $errors->all())) !!}',
                    });
                  @endif

                  // Global Delete & Form Confirmation Interceptor
                  document.addEventListener('submit', function(e) {
                    if (e.target && e.target.tagName === 'FORM') {
                      let isDeleteForm = e.target.querySelector('input[name="_method"][value="DELETE"]');
                      let confirmText = e.target.getAttribute('data-confirm');
                      
                      if (isDeleteForm || confirmText) {
                        e.preventDefault();
                        
                        let title = confirmText || 'Apakah Anda yakin?';
                        let text = isDeleteForm ? "Data yang dihapus tidak dapat dikembalikan!" : "";
                        let icon = 'warning';
                        let confirmButtonColor = isDeleteForm ? '#d33' : '#3085d6';
                        let confirmButtonText = isDeleteForm ? 'Ya, hapus!' : 'Ya, yakin!';
                        
                        Swal.fire({
                          title: title,
                          text: text,
                          icon: icon,
                          showCancelButton: true,
                          confirmButtonColor: confirmButtonColor,
                          cancelButtonColor: '#6c757d',
                          confirmButtonText: confirmButtonText,
                          cancelButtonText: 'Batal'
                        }).then((result) => {
                          if (result.isConfirmed) {
                            e.target.removeAttribute('onsubmit'); // Remove native confirm if exists
                            e.target.submit();
                          }
                        });
                      }
                    }
                  });
                });
              </script>

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
          playNotificationSound: function(type) {
              // Create short beep sound using AudioContext
              try {
                  let audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                  let oscillator = audioCtx.createOscillator();
                  let gainNode = audioCtx.createGain();
                  
                  oscillator.connect(gainNode);
                  gainNode.connect(audioCtx.destination);
                  
                  if (type === 'danger' || type === 'warning') {
                      oscillator.type = 'square';
                      oscillator.frequency.setValueAtTime(400, audioCtx.currentTime); // lower pitch for warning
                      oscillator.frequency.exponentialRampToValueAtTime(300, audioCtx.currentTime + 0.3);
                  } else {
                      oscillator.type = 'sine';
                      oscillator.frequency.setValueAtTime(600, audioCtx.currentTime); // nice ping for success/info
                      oscillator.frequency.exponentialRampToValueAtTime(800, audioCtx.currentTime + 0.1);
                  }
                  
                  gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                  gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.5);
                  
                  oscillator.start(audioCtx.currentTime);
                  oscillator.stop(audioCtx.currentTime + 0.5);
              } catch(e) { console.log('AudioContext not supported'); }
          },
          showToast: function(title, message, type, url) {
              type = type || 'info';
              url = url || '#';
              
              // Play sound
              this.playNotificationSound(type);

              let colors = {info: '#0ea5e9', success: '#22c55e', warning: '#f59e0b', danger: '#ef4444'};
              let icons  = {info: 'bx-info-circle', success: 'bx-check-circle', warning: 'bx-error', danger: 'bx-x-circle'};
              let container = document.getElementById('realtime-toast-container');
              let toast = document.createElement('div');
              
              toast.style.cssText = 'background:#fff;border-radius:10px;box-shadow:0 8px 32px rgba(0,0,0,.15);padding:16px 20px;margin-bottom:12px;display:flex;align-items:flex-start;gap:12px;animation:slideIn .3s ease;border-left:4px solid '+colors[type]+';min-width:300px;position:relative;cursor:'+(url !== '#' ? 'pointer' : 'default')+';';
              
              if(url !== '#') {
                  toast.onclick = function(e) {
                      if(e.target.tagName !== 'BUTTON') window.location.href = url;
                  };
              }

              toast.innerHTML = `
                <i class="bx ${icons[type]}" style="font-size:24px;color:${colors[type]};margin-top:2px;"></i>
                <div style="flex:1;">
                  <strong style="display:block;margin-bottom:2px;font-size:14px;color:#333;">${title}</strong>
                  <span style="font-size:13px;color:#666;">${message}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;font-size:18px;cursor:pointer;color:#999;position:absolute;top:10px;right:10px;">&times;</button>
              `;
              container.appendChild(toast);
              
              // Auto hide logic: danger (error) doesn't auto hide
              if(type !== 'danger') {
                  let timeout = type === 'success' || type === 'info' ? 5000 : 8000;
                  setTimeout(() => { if(toast.parentElement) toast.remove(); }, timeout);
              }
          }
      };
    </script>
    <script>
      // Mark specific notification as read
      window.markNotificationAsRead = function(id) {
        fetch(`/notifications/${id}/read`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          }
        }).then(response => {
          if(response.ok) {
            const notifElement = document.getElementById(`notif-${id}`);
            if (notifElement) notifElement.remove();
            window.updateNotificationCount(-1);
          }
        });
      };

      // Mark all notifications as read
      window.markAllNotificationsAsRead = function() {
        fetch(`/notifications/read-all`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          }
        }).then(response => {
          if(response.ok) {
            const notifList = document.getElementById('notification-list');
            if (notifList) {
                notifList.innerHTML = '<li class="list-group-item text-center text-muted py-4" id="empty-notif">Tidak ada notifikasi baru.</li>';
            }
            window.updateNotificationCount('clear');
          }
        });
      };

      // Update badge count
      window.updateNotificationCount = function(change) {
        const badge = document.querySelector('.badge-notifications');
        if(!badge && change > 0) {
          // Create badge if it doesn't exist
          const bell = document.querySelector('.dropdown-notifications > a');
          const newBadge = document.createElement('span');
          newBadge.className = 'badge bg-danger rounded-pill badge-notifications';
          newBadge.innerText = change;
          bell.appendChild(newBadge);
        } else if (badge) {
          let currentCount = parseInt(badge.innerText);
          if(change === 'clear') {
            badge.remove();
          } else {
            currentCount += change;
            if(currentCount <= 0) {
              badge.remove();
            } else {
              badge.innerText = currentCount;
            }
          }
        }
      }

      // Toggle password visibility
      document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-password').forEach(button => {
          button.addEventListener('click', () => {
            const wrapper = button.closest('.position-relative') || button.closest('.input-wrapper') || button.parentElement;
            if (!wrapper) return;
            const input = wrapper.querySelector('input');
            const icon = button.querySelector('i');
            if (input && icon) {
              if (input.type === 'password') {
                input.type = 'text';
                if (icon.classList.contains('bx-show')) {
                  icon.classList.remove('bx-show');
                  icon.classList.add('bx-hide');
                } else if (icon.classList.contains('bi-eye')) {
                  icon.classList.remove('bi-eye');
                  icon.classList.add('bi-eye-slash');
                }
              } else {
                input.type = 'password';
                if (icon.classList.contains('bx-hide')) {
                  icon.classList.remove('bx-hide');
                  icon.classList.add('bx-show');
                } else if (icon.classList.contains('bi-eye-slash')) {
                  icon.classList.remove('bi-eye-slash');
                  icon.classList.add('bi-eye');
                }
              }
            }
          });
        });
      });
    </script>
    <style>
      @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
      .bx-tada { animation: bx-tada 1s ease infinite; }
      @keyframes bx-tada {
        0%, 100% { transform: rotate(0deg); }
        10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
        20%, 40%, 60%, 80% { transform: rotate(10deg); }
      }
    </style>

    @vite(['resources/js/app.js'])
    @yield('page-js')
  </body>
</html>
