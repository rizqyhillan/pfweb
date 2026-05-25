<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
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
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-smile"></i>
        <div class="text-truncate">Dashboard</div>
      </a>
    </li>

    <!-- Pasien Hewan -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Pasien Hewan</span></li>

    <li class="menu-item {{ request()->routeIs('admin.pets.*') ? 'active' : '' }}">
      <a href="{{ route('admin.pets.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bxs-dog"></i>
        <div class="text-truncate">Hewan Peliharaan</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.medical-records.*') ? 'active' : '' }}">
      <a href="{{ route('admin.medical-records.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div class="text-truncate">Rekam Medis</div>
      </a>
    </li>

    <!-- Layanan -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Booking</span></li>

    <li class="menu-item {{ (request()->routeIs('admin.groomings.*') && request()->query('jenis_layanan') === 'grooming') || (request()->routeIs('admin.package-types.*') && request()->query('section') === 'grooming') || request()->routeIs('admin.groomings.*') ? 'open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cut"></i>
        <div class="text-truncate">Grooming</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.package-types.*') && request()->query('section') === 'grooming' ? 'active' : '' }}">
          <a href="{{ route('admin.package-types.index', ['section' => 'grooming']) }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div class="text-truncate">Jenis Grooming</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.groomings.*') ? 'active' : '' }}">
          <a href="{{ route('admin.groomings.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-list-ul"></i>
            <div class="text-truncate">Data Grooming</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Penitipan (submenu: Paket Kamar + Data Boarding) -->
    <li class="menu-item {{ request()->routeIs('admin.boardings.*') || request()->routeIs('admin.rooms.*') || (request()->routeIs('admin.package-types.*') && request()->query('section') !== 'grooming') ? 'open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-hotel"></i>
        <div class="text-truncate">Penitipan</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.package-types.*') && request()->query('section') !== 'grooming' ? 'active' : '' }}">
          <a href="{{ route('admin.package-types.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div class="text-truncate">Jenis Paket</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
          <a href="{{ route('admin.rooms.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-door-open"></i>
            <div class="text-truncate">Kamar</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.boardings.*') ? 'active' : '' }}">
          <a href="{{ route('admin.boardings.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-list-ul"></i>
            <div class="text-truncate">Data Boarding</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Dokter (submenu: Jadwal Dokter + Layanan Dokter + Data Booking Dokter) -->
    <li class="menu-item {{ request()->routeIs('admin.doctor-schedules.*') || request()->routeIs('admin.doctor-services.*') || request()->routeIs('admin.doctor-bookings.*') || (request()->routeIs('admin.users.*') && request('role') === 'dokter') ? 'open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-plus-medical"></i>
        <div class="text-truncate">Dokter</div>
      </a>
    
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.users.*') && request('role') === 'dokter' ? 'active' : '' }}">
          <a href="{{ route('admin.users.role', ['role' => 'dokter']) }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div class="text-truncate">Data Dokter</div>
          </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.doctor-schedules.*') ? 'active' : '' }}">
          <a href="{{ route('admin.doctor-schedules.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-calendar"></i>
            <div class="text-truncate">Jadwal Dokter</div>
          </a>
        </li>
      
        <li class="menu-item {{ request()->routeIs('admin.doctor-services.*') ? 'active' : '' }}">
          <a href="{{ route('admin.doctor-services.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-briefcase-alt-2"></i>
            <div class="text-truncate">Layanan Dokter</div>
          </a>
        </li>
      
        <li class="menu-item {{ request()->routeIs('admin.doctor-bookings.*') ? 'active' : '' }}">
          <a href="{{ route('admin.doctor-bookings.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-list-ul"></i>
            <div class="text-truncate">Data Booking Dokter</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Shopping -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Shopping</span></li>
      
    <li class="menu-item {{ request()->routeIs('admin.shop-orders.*') ? 'active' : '' }}">
      <a href="{{ route('admin.shop-orders.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
        <div class="text-truncate">Pesanan Shopping</div>
      </a>
    </li>

    <!-- Produk & Stok -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Produk & Stok</span></li>

    <li class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
      <a href="{{ route('admin.products.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-package"></i>
        <div class="text-truncate">Produk</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
      <a href="{{ route('admin.suppliers.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-store"></i>
        <div class="text-truncate">Supplier</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.product-batches.*') ? 'active' : '' }}">
      <a href="{{ route('admin.product-batches.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-box"></i>
        <div class="text-truncate">Batch Produk</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.stock-cards.*') ? 'active' : '' }}">
      <a href="{{ route('admin.stock-cards.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-transfer"></i>
        <div class="text-truncate">Kartu Stok</div>
      </a>
    </li>

    <!-- Keuangan -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Keuangan</span></li>

    <li class="menu-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
      <a href="{{ route('admin.transactions.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-money"></i>
        <div class="text-truncate">Kasir / POS</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
      <a href="{{ route('admin.reports.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
        <div class="text-truncate">Laporan</div>
      </a>
    </li>

    <!-- Manajemen -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen</span></li>

    <li class="menu-item {{ request()->routeIs('admin.users.*') && request('role') !== 'dokter' ? 'active' : '' }}">
      <a href="{{ route('admin.users.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div class="text-truncate">Pengguna</div>
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