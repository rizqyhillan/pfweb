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

    <!-- Pasien & Hewan -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Pasien & Hewan</span></li>

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

    <!-- Dokter & Layanan -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Dokter & Layanan</span></li>

    <li class="menu-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
      <a href="{{ route('admin.services.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-first-aid"></i>
        <div class="text-truncate">Layanan</div>
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

    <!-- Penitipan -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Penitipan Hewan</span></li>

    <li class="menu-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
      <a href="{{ route('admin.rooms.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-door-open"></i>
        <div class="text-truncate">Kamar</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.boardings.*') ? 'active' : '' }}">
      <a href="{{ route('admin.boardings.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-hotel"></i>
        <div class="text-truncate">Penitipan</div>
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

    <!-- Manajemen -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen</span></li>

    <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
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
