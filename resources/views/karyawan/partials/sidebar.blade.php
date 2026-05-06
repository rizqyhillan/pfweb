<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('karyawan.dashboard') }}" class="app-brand-link">
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
    <li class="menu-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
      <a href="{{ route('karyawan.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-smile"></i>
        <div class="text-truncate">Dashboard</div>
      </a>
    </li>

    <!-- Kasir & Transaksi -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Layanan & Kasir</span></li>

    <li class="menu-item {{ request()->routeIs('karyawan.transactions*') ? 'active' : '' }}">
      <a href="{{ route('karyawan.transactions') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-money"></i>
        <div class="text-truncate">Kasir / POS</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('karyawan.boardings*') ? 'active' : '' }}">
      <a href="{{ route('karyawan.boardings.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-heart"></i>
        <div class="text-truncate">Penitipan Hewan</div>
      </a>
    </li>

    <!-- Produk & Informasi -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Produk & Layanan</span></li>

    <li class="menu-item {{ request()->routeIs('karyawan.products') ? 'active' : '' }}">
      <a href="{{ route('karyawan.products') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-package"></i>
        <div class="text-truncate">Produk</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('karyawan.services') ? 'active' : '' }}">
      <a href="{{ route('karyawan.services') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-first-aid"></i>
        <div class="text-truncate">Layanan</div>
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
