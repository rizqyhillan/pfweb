<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('doctor.dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/pawpet/logo/PawPet Logo New.jpg') }}" alt="PawPet" style="height: 38px; width: auto;">
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">PawPet</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
      <a href="{{ route('doctor.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-smile"></i>
        <div class="text-truncate">Dashboard</div>
      </a>
    </li>

    <!-- Menu Dokter -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Menu Dokter</span></li>

    <li class="menu-item {{ request()->routeIs('doctor.patients') ? 'active' : '' }}">
      <a href="{{ route('doctor.patients') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bxs-dog"></i>
        <div class="text-truncate">Data Pasien</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('doctor.medical-records') ? 'active' : '' }}">
      <a href="{{ route('doctor.medical-records') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div class="text-truncate">Rekam Medis</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('doctor.schedule') ? 'active' : '' }}">
      <a href="{{ route('doctor.schedule') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-calendar"></i>
        <div class="text-truncate">Jadwal Saya</div>
      </a>
    </li>

    <!-- Lainnya -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Lainnya</span></li>
    <li class="menu-item">
      <a href="{{ route('home') }}" class="menu-link" target="_blank">
        <i class="menu-icon tf-icons bx bx-globe"></i>
        <div class="text-truncate">Lihat Website</div>
      </a>
    </li>
  </ul>
</aside>
