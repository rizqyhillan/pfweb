<header id="header" class="header fixed-top">

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="logo d-flex align-items-center">
          <img src="{{ asset('assets/pawpet/logo/PawPet Logo New.jpg') }}" alt="PawPet" style="max-height: 40px; border-radius: 8px; margin-right: 10px;">
          <h1 class="sitename" style="font-weight: 700; color: var(--accent-color);">PawPet</h1>
        </a>

        <div class="dropdown">
          <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 32px; color: var(--accent-color); border: none; background: transparent;">
            <i class="bi bi-list"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; padding: 10px 0; min-width: 220px;">
            <li><a class="dropdown-item py-2 fw-semibold" href="#hero">Home</a></li>
            <li><a class="dropdown-item py-2 fw-semibold" href="#services">Layanan Terpadu</a></li>
            <li><a class="dropdown-item py-2 fw-semibold" href="#shop">Shop</a></li>
            <li><a class="dropdown-item py-2 fw-semibold" href="#contact">Contact</a></li>
            <li><hr class="dropdown-divider"></li>
            @guest
              <li><a class="dropdown-item py-2 fw-bold text-primary" href="{{ route('login') }}"><i class="bi bi-person me-2"></i>Login</a></li>
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
              <li><a class="dropdown-item py-2 fw-bold text-primary" href="{{ $dashRoute }}"><i class="bi bi-grid me-2"></i>Dashboard</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                  @csrf
                  <button type="submit" class="dropdown-item py-2 fw-bold text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                </form>
              </li>
            @endauth
          </ul>
        </div>

      </div>

    </div>

  </header>