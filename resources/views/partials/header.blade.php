<header id="header" class="header fixed-top d-flex align-items-center" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 2px 20px rgba(0,0,0,0.05); height: 75px; transition: all 0.3s; z-index: 10000;">
  <div class="container d-flex align-items-center justify-content-between">

    <!-- Logo -->
    <a href="{{ route('home') }}" class="logo d-flex align-items-center" style="text-decoration: none;">
      <img src="{{ asset('assets/pawpet/logo/logo-paw.png') }}" alt="PawPet" style="max-height: 28px; border-radius: 6px; margin-right: 2px;">
      <img src="{{ asset('assets/pawpet/logo/text-Pawpet.png') }}" alt="PawPet Text" style="max-height: 28px;">
    </a>

    <!-- Desktop Navmenu (Visible on lg and larger) -->
    <nav class="d-none d-lg-flex align-items-center gap-4">
      <a href="#hero" class="nav-link-item" style="color: #2d2d2d; font-weight: 600; text-decoration: none; font-size: 0.95rem; font-family: 'Quicksand', sans-serif; transition: color 0.2s;">Home</a>
      <a href="#fitur" class="nav-link-item" style="color: #2d2d2d; font-weight: 600; text-decoration: none; font-size: 0.95rem; font-family: 'Quicksand', sans-serif; transition: color 0.2s;">Fitur</a>
      <a href="#faq" class="nav-link-item" style="color: #2d2d2d; font-weight: 600; text-decoration: none; font-size: 0.95rem; font-family: 'Quicksand', sans-serif; transition: color 0.2s;">FAQ</a>
      
      @guest
        <a href="{{ route('login') }}" class="nav-link-item" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); color: white; font-weight: 700; text-decoration: none; font-size: 0.95rem; font-family: 'Quicksand', sans-serif; padding: 10px 24px; border-radius: 50px; transition: all 0.3s; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3); display: inline-flex; align-items: center; gap: 6px;"><i class="bi bi-box-arrow-in-right"></i>Login</a>
      @endguest

      @auth
        @php
          $dashRoute = match(Auth::user()->role) {
            'admin'    => route('admin.dashboard'),
            'dokter'   => route('doctor.dashboard'),
            'karyawan' => route('karyawan.dashboard'),
            default    => route('home'),
          };
        @endphp
        <a href="{{ $dashRoute }}" class="nav-link-item" style="color: #2d2d2d; font-weight: 600; text-decoration: none; font-size: 0.95rem; font-family: 'Quicksand', sans-serif; transition: color 0.2s;">Dashboard</a>
      @endauth
    </nav>

    <!-- Mobile Hamburger Toggle Button (Visible on screens smaller than lg) -->
    <div class="d-flex d-lg-none align-items-center">
      <button class="btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavCollapse" aria-expanded="false" aria-controls="mobileNavCollapse" style="color: #f59e0b; font-size: 2rem;">
        <i class="bi bi-list"></i>
      </button>
    </div>

  </div>

  <!-- Mobile Collapsible Menu -->
  <div class="collapse position-absolute w-100 start-0 d-lg-none" id="mobileNavCollapse" style="top: 75px; background: rgba(255, 255, 255, 0.98); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-top: 1px solid rgba(0,0,0,0.05); z-index: 9999;">
    <div class="container py-4 d-flex flex-column gap-3">
      <a href="#hero" class="mobile-nav-link" onclick="bootstrap.Collapse.getInstance(document.getElementById('mobileNavCollapse')).hide();" style="color: #2d2d2d; font-weight: 600; font-size: 1.1rem; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid rgba(0,0,0,0.02); font-family: 'Quicksand', sans-serif;">Home</a>
      <a href="#fitur" class="mobile-nav-link" onclick="bootstrap.Collapse.getInstance(document.getElementById('mobileNavCollapse')).hide();" style="color: #2d2d2d; font-weight: 600; font-size: 1.1rem; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid rgba(0,0,0,0.02); font-family: 'Quicksand', sans-serif;">Fitur</a>
      <a href="#faq" class="mobile-nav-link" onclick="bootstrap.Collapse.getInstance(document.getElementById('mobileNavCollapse')).hide();" style="color: #2d2d2d; font-weight: 600; font-size: 1.1rem; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid rgba(0,0,0,0.02); font-family: 'Quicksand', sans-serif;">FAQ</a>
      
      @guest
        <a href="{{ route('login') }}" class="mobile-nav-link" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); color: white; font-weight: 700; font-size: 1.1rem; text-decoration: none; padding: 12px 16px; display: block; border-radius: 50px; font-family: 'Quicksand', sans-serif; text-align: center; margin-top: 8px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
      @endguest
      @auth
        @php
          $dashRouteMobile = match(Auth::user()->role) {
            'admin'    => route('admin.dashboard'),
            'dokter'   => route('doctor.dashboard'),
            'karyawan' => route('karyawan.dashboard'),
            default    => route('home'),
          };
        @endphp
        <a href="{{ $dashRouteMobile }}" class="mobile-nav-link" style="color: #2d2d2d; font-weight: 600; font-size: 1.1rem; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid rgba(0,0,0,0.02); font-family: 'Quicksand', sans-serif;">Dashboard</a>
      @endauth

      <a href="https://mega.nz/file/ao50SRhJ#3HGWwJIIOkfEfBdlVlOTM_MwoP1S-3G9MJ1qfDfnrUM" target="_blank" class="w-100 text-center btn btn-primary mt-2" style="background: #f59e0b; border: none; border-radius: 50px; padding: 12px; font-weight: 700; font-size: 1.05rem; font-family: 'Quicksand', sans-serif; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);">
        <i class="bi bi-android2 me-2"></i>Download APK
      </a>
    </div>
  </div>
</header>

<style>
  .nav-link-item:hover {
    color: #f59e0b !important;
  }
  
  .nav-link-item[href*="login"]:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4) !important;
  }
  
  .mobile-nav-link:hover {
    color: #f59e0b !important;
    padding-left: 5px !important;
    transition: all 0.2s;
  }
  
  .mobile-nav-link[href*="login"]:hover {
    transform: scale(1.02) !important;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4) !important;
    padding-left: 16px !important;
  }
</style>