<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan — PawPet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <style>
        body { font-family: 'Quicksand', sans-serif; background: #f1f5f9; }
    </style>
</head>
<body>
<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center" style="max-width:420px;">

        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
             style="width:80px;height:80px;background:rgba(245,158,11,.15);">
            <i class="bi bi-person-badge" style="font-size:2rem;color:#f59e0b;"></i>
        </div>

        <h4 class="fw-700 mb-1" style="color:#1e293b;">
            Selamat datang, {{ $karyawan->nama }}!
        </h4>
        <p class="text-muted mb-4" style="font-size:.875rem;">
            Dashboard karyawan PawPet. Fitur lengkap akan segera tersedia.
        </p>

        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3 text-start">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-person-circle" style="font-size:1.4rem;color:#64748b;"></i>
                <div>
                    <div class="fw-600" style="font-size:.9rem;">{{ $karyawan->nama }}</div>
                    <small class="text-muted">{{ $karyawan->email }}</small>
                </div>
                <span class="ms-auto badge"
                      style="background:rgba(245,158,11,.15);color:#d97706;font-size:.72rem;">
                    {{ ucfirst($karyawan->role) }}
                </span>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-person-gear me-1"></i>Edit Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm"
                        style="background:rgba(239,68,68,.1);color:#ef4444;border:none;">
                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                </button>
            </form>
        </div>

        <p class="text-muted mt-4 mb-0" style="font-size:.75rem;">
            <i class="bi bi-tools me-1"></i>
            Fitur kasir & manajemen karyawan sedang dalam pengembangan.
        </p>
    </div>
</div>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
