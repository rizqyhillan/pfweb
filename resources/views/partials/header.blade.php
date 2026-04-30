<header id="header" class="header fixed-top">

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="logo d-flex align-items-center">
          <img src="{{ asset('assets/pawpet/logo/PawPet Logo New.jpg') }}" alt="PawPet" style="max-height: 40px; border-radius: 8px; margin-right: 10px;">
          <h1 class="sitename" style="font-weight: 700; color: var(--accent-color);">PawPet</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="{{ route('home') }}" class="active">Home</a></li>
            <li><a href="#shop">Shop</a></li>
            <li><a href="#booking">Booking</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        {{-- AUTH BUTTONS ──────────────────────────── --}}
        <div class="d-flex align-items-center gap-2 ms-3" style="flex-shrink:0;">

          @guest
            {{-- Belum login: tampilkan Login & Register --}}
            <a href="{{ route('login') }}"
               style="padding:.42rem 1rem;border:2px solid var(--accent-color);color:var(--accent-color);
                      border-radius:25px;font-size:.82rem;font-weight:700;text-decoration:none;
                      transition:all .2s;"
               onmouseover="this.style.background='var(--accent-color)';this.style.color='#fff';"
               onmouseout="this.style.background='transparent';this.style.color='var(--accent-color)';">
              <i class="bi bi-person me-1"></i>Login
            </a>
          @endguest

          @auth
            {{-- Sudah login: Dashboard sesuai role --}}
            @php
              $dashRoute = match(Auth::user()->role) {
                'admin'    => route('admin.dashboard'),
                'dokter'   => route('doctor.dashboard'),
                'karyawan' => route('karyawan.dashboard'),
                default    => route('home'),
              };
            @endphp

            <a href="{{ $dashRoute }}"
               style="padding:.42rem 1rem;border:2px solid var(--accent-color);color:var(--accent-color);
                      border-radius:25px;font-size:.82rem;font-weight:700;text-decoration:none;
                      transition:all .2s;"
               onmouseover="this.style.background='var(--accent-color)';this.style.color='#fff';"
               onmouseout="this.style.background='transparent';this.style.color='var(--accent-color)';">
              <i class="bi bi-grid me-1"></i>Dashboard
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
              @csrf
              <button type="submit"
                      style="padding:.42rem 1rem;background:rgba(239,68,68,.1);color:#ef4444;
                             border:2px solid rgba(239,68,68,.25);border-radius:25px;
                             font-size:.82rem;font-weight:700;cursor:pointer;transition:all .2s;"
                      onmouseover="this.style.background='rgba(239,68,68,.2)';"
                      onmouseout="this.style.background='rgba(239,68,68,.1)';">
                <i class="bi bi-box-arrow-right me-1"></i>Keluar
              </button>
            </form>
          @endauth

        </div>
        {{-- /AUTH BUTTONS ─────────────────────────── --}}

      </div>

    </div>

  </header>