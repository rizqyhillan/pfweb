<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PawPet Dokter</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

    <style>
        :root {
            --primary:   #f59e0b;
            --primary-d: #d97706;
            --sidebar-w: 240px;
            --sidebar-bg:#1e293b;
            --sidebar-tx:#94a3b8;
            --sidebar-active:#f59e0b;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Quicksand', sans-serif;
            background: #f1f5f9;
            margin: 0;
        }

        /* ── SIDEBAR ─────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand h5 {
            color: #fff; margin: 0; font-weight: 700; font-size: 1.1rem;
        }
        .sidebar-brand small { color: var(--sidebar-tx); font-size: .75rem; }

        .sidebar-nav { padding: .75rem 0; flex: 1; }
        .nav-label {
            padding: .5rem 1.25rem;
            font-size: .65rem; font-weight: 700; letter-spacing: .08em;
            color: #475569; text-transform: uppercase;
        }
        .sidebar-nav a {
            display: flex; align-items: center; gap: .65rem;
            padding: .65rem 1.25rem;
            color: var(--sidebar-tx);
            text-decoration: none; font-size: .875rem; font-weight: 500;
            border-radius: 0;
            transition: background .15s, color .15s;
        }
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(245,158,11,.12);
            color: var(--sidebar-active);
        }
        .sidebar-nav a i { font-size: 1rem; width: 1.25rem; text-align: center; }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-footer .doctor-name { color: #fff; font-size: .85rem; font-weight: 600; }
        .sidebar-footer small { color: var(--sidebar-tx); font-size: .72rem; }

        /* ── MAIN CONTENT ─────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        .topbar {
            background: #fff;
            padding: .85rem 1.75rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-weight: 700; color: #1e293b; font-size: 1.05rem; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .topbar-right .role-badge {
            background: rgba(245,158,11,.15);
            color: var(--primary-d);
            font-size: .72rem; font-weight: 700;
            padding: .25rem .65rem;
            border-radius: 20px;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .page-content { padding: 1.75rem; flex: 1; }

        /* ── CARDS ──────────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #e2e8f0;
            transition: box-shadow .2s;
        }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: .85rem;
        }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; color: #1e293b; }
        .stat-card .stat-label { font-size: .8rem; color: #64748b; font-weight: 600; }

        .card-section {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .card-section-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-section-header h6 {
            margin: 0; font-weight: 700; color: #1e293b; font-size: .9rem;
        }

        /* Flash alerts */
        .flash-area { padding: 0 1.75rem; }

        /* Badge pill */
        .badge-status {
            font-size: .7rem; font-weight: 700;
            padding: .28rem .65rem; border-radius: 20px;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrap { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h5><i class="bi bi-heart-pulse me-2" style="color:var(--primary)"></i>PawPet</h5>
            <small>Portal Dokter</small>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('doctor.dashboard') }}"
               class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('doctor.patients') }}"
               class="{{ request()->routeIs('doctor.patients') ? 'active' : '' }}">
                <i class="bi bi-paw"></i> Data Pasien
            </a>
            <a href="{{ route('doctor.medical-records') }}"
               class="{{ request()->routeIs('doctor.medical-records') ? 'active' : '' }}">
                <i class="bi bi-file-medical"></i> Rekam Medis
            </a>
            <a href="{{ route('doctor.schedule') }}"
               class="{{ request()->routeIs('doctor.schedule') ? 'active' : '' }}">
                <i class="bi bi-calendar-week"></i> Jadwal Saya
            </a>

            <div class="nav-label mt-2">Akun</div>
            <a href="{{ route('profile.edit') }}">
                <i class="bi bi-person-circle"></i> Profil
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:34px;height:34px;background:rgba(245,158,11,.2);">
                    <i class="bi bi-person-fill" style="color:var(--primary);font-size:.9rem;"></i>
                </div>
                <div>
                    <div class="doctor-name">{{ Auth::user()->nama }}</div>
                    <small>Dokter Hewan</small>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-sm w-100"
                        style="background:rgba(239,68,68,.1);color:#ef4444;font-size:.75rem;border:none;">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="main-wrap">
        <header class="topbar">
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
            <div class="topbar-right">
                <span class="role-badge"><i class="bi bi-capsule me-1"></i>Dokter</span>
                <span style="color:#64748b;font-size:.85rem;">{{ now()->locale('id')->translatedFormat('l, d M Y') }}</span>
            </div>
        </header>

        <div class="page-content">
            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible mb-3" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
