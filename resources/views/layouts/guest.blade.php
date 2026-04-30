<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PawPet') }} — @yield('auth-title', 'Welcome')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --paw-amber: #f59e0b;
            --paw-amber-light: #fbbf24;
            --paw-amber-dark: #d97706;
            --paw-cream: #fffaf0;
            --paw-cream-dark: #fef3e2;
            --paw-brown: #78350f;
            --paw-brown-light: #92400e;
            --paw-text: #451a03;
            --paw-text-light: #78350f;
            --paw-white: #ffffff;
            --paw-error: #dc2626;
            --paw-shadow: 0 20px 60px rgba(245, 158, 11, 0.15);
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background: var(--paw-cream);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* ── Animated Background ── */
        .auth-bg {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, var(--paw-cream) 0%, var(--paw-cream-dark) 50%, #fff7ed 100%);
            overflow: hidden;
        }
        .auth-bg::before {
            content: '';
            position: absolute; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%);
            top: -200px; right: -100px;
            animation: floatBlob 15s ease-in-out infinite;
        }
        .auth-bg::after {
            content: '';
            position: absolute; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(251,191,36,0.06) 0%, transparent 70%);
            bottom: -150px; left: -100px;
            animation: floatBlob 18s ease-in-out infinite reverse;
        }
        @keyframes floatBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -30px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* ── Floating Paw Prints ── */
        .floating-paws {
            position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
        }
        .paw-print {
            position: absolute;
            opacity: 0;
            animation: floatPaw linear infinite;
        }
        .paw-print svg { width: 100%; height: 100%; }

        @keyframes floatPaw {
            0% { opacity: 0; transform: translateY(100vh) rotate(0deg) scale(0.5); }
            10% { opacity: 0.07; }
            90% { opacity: 0.07; }
            100% { opacity: 0; transform: translateY(-100px) rotate(360deg) scale(1); }
        }

        /* ── Main Container ── */
        .auth-wrapper {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-container {
            display: flex;
            width: 100%; max-width: 960px;
            background: var(--paw-white);
            border-radius: 24px;
            box-shadow: var(--paw-shadow);
            overflow: hidden;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Left Panel (Illustration) ── */
        .auth-illustration {
            flex: 0 0 420px;
            background: linear-gradient(160deg, rgba(251, 191, 36, 0.85) 0%, rgba(245, 158, 11, 0.85) 100%);
            padding: 3rem 2.5rem;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            position: relative; overflow: hidden;
        }
        .auth-illustration::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        }

        .illust-content {
            position: relative; z-index: 2;
            text-align: center; color: white;
        }

        .pet-illustration {
            margin: 0 auto 2rem;
            animation: petBounce 3s ease-in-out infinite;
        }
        .illust-logo {
            width: 240px; height: auto;
            object-fit: contain;
            filter: drop-shadow(0 6px 25px rgba(255,255,255,0.6));
        }
        @keyframes petBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .illust-content h2 {
            font-family: 'Quicksand', sans-serif;
            font-size: 1.75rem; font-weight: 700;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .illust-content p {
            font-size: 0.95rem; opacity: 0.9;
            line-height: 1.6; font-weight: 500;
        }

        /* Decorative floating elements */
        .deco-heart, .deco-star, .deco-bone {
            position: absolute; z-index: 2; opacity: 0.3;
        }
        .deco-heart { top: 15%; right: 10%; animation: floatDeco 4s ease-in-out infinite; }
        .deco-star { bottom: 20%; left: 8%; animation: floatDeco 5s ease-in-out infinite 1s; }
        .deco-bone { top: 60%; right: 15%; animation: floatDeco 6s ease-in-out infinite 0.5s; }

        @keyframes floatDeco {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(10deg); }
        }

        /* ── Right Panel (Form) ── */
        .auth-form-panel {
            flex: 1;
            padding: 3rem;
            display: flex; flex-direction: column;
            justify-content: center;
        }

        /* (brand removed from right panel) */

        .auth-subtitle {
            color: var(--paw-text-light);
            font-size: 0.9rem; margin-bottom: 2rem;
            font-weight: 500;
        }

        /* Form styles */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            font-size: 0.85rem; font-weight: 600;
            color: var(--paw-text-light);
            margin-bottom: 0.4rem;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #d1a054; font-size: 1.1rem;
            transition: color 0.3s;
        }
        .input-wrapper input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.75rem;
            border: 2px solid #f3e8d5;
            border-radius: 12px;
            font-family: 'Quicksand', sans-serif;
            font-size: 0.95rem; font-weight: 500;
            color: var(--paw-text);
            background: #fffcf7;
            transition: all 0.3s ease;
            outline: none;
        }
        .input-wrapper input::placeholder { color: #c4a882; font-weight: 400; }
        .input-wrapper input:focus {
            border-color: var(--paw-amber);
            background: var(--paw-white);
            box-shadow: 0 0 0 4px rgba(245,158,11,0.1);
        }
        .input-wrapper input:focus + i,
        .input-wrapper input:focus ~ i { color: var(--paw-amber); }

        /* Error */
        .field-error {
            font-size: 0.8rem; color: var(--paw-error);
            margin-top: 0.3rem; font-weight: 500;
        }
        .field-error ul { list-style: none; padding: 0; }

        /* Checkbox */
        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;
        }
        .remember-check {
            display: flex; align-items: center; gap: 0.5rem;
            cursor: pointer; font-size: 0.85rem;
            color: var(--paw-text-light); font-weight: 500;
        }
        .remember-check input[type="checkbox"] {
            width: 18px; height: 18px;
            accent-color: var(--paw-amber);
            border-radius: 4px; cursor: pointer;
        }
        .forgot-link {
            font-size: 0.85rem; color: var(--paw-amber-dark);
            text-decoration: none; font-weight: 600;
            transition: color 0.3s;
        }
        .forgot-link:hover { color: var(--paw-amber); }

        /* Button */
        .btn-pawpet {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, var(--paw-amber), var(--paw-amber-dark));
            color: white;
            border: none; border-radius: 12px;
            font-family: 'Quicksand', sans-serif;
            font-size: 1rem; font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245,158,11,0.3);
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            position: relative; overflow: hidden;
        }
        .btn-pawpet::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.15));
            opacity: 0; transition: opacity 0.3s;
        }
        .btn-pawpet:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245,158,11,0.4);
        }
        .btn-pawpet:hover::before { opacity: 1; }
        .btn-pawpet:active { transform: translateY(0); }

        /* Footer link */
        .auth-footer {
            text-align: center; margin-top: 1.5rem;
            font-size: 0.9rem; color: var(--paw-text-light);
            font-weight: 500;
        }
        .auth-footer a {
            color: var(--paw-amber-dark);
            text-decoration: none; font-weight: 700;
            transition: color 0.3s;
        }
        .auth-footer a:hover { color: var(--paw-amber); }

        /* Session status */
        .session-status {
            background: #ecfdf5; color: #065f46;
            padding: 0.75rem 1rem; border-radius: 10px;
            margin-bottom: 1rem; font-size: 0.85rem; font-weight: 600;
            border: 1px solid #a7f3d0;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .auth-illustration { display: none; }
            .auth-container { max-width: 460px; border-radius: 20px; }
            .auth-form-panel { padding: 2rem 1.5rem; }
        }

        /* ── Tiny paw SVG for floating ── */
        .paw-svg-tpl { display: none; }
    </style>
</head>
<body>
    <!-- Background -->
    <div class="auth-bg"></div>

    <!-- Floating Paw Prints -->
    <div class="floating-paws" id="floatingPaws"></div>

    <!-- Main -->
    <div class="auth-wrapper">
        <div class="auth-container">
            <!-- Left Illustration Panel -->
            <div class="auth-illustration">
                <!-- Floating decorations -->
                <svg class="deco-heart" width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <svg class="deco-star" width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <svg class="deco-bone" width="36" height="20" viewBox="0 0 36 20" fill="white"><path d="M8 4a4 4 0 0 0-4 4 4 4 0 0 0 0 4 4 4 0 0 0 4 4h2v-2h12v2h2a4 4 0 0 0 4-4 4 4 0 0 0 0-4 4 4 0 0 0-4-4h-2v2H10V4H8z"/></svg>

                <div class="illust-content">
                    <div class="pet-illustration">
                        <img src="{{ asset('assets/pawpet/logo/PawPet Logo New.jpg') }}" alt="PawPet Logo" class="illust-logo">
                    </div>
                    <h2>@yield('illust-title', 'Selamat Datang!')</h2>
                    <p>@yield('illust-desc', 'Masuk ke akun Anda dan kelola perawatan terbaik untuk anabul kesayangan 🐾')</p>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="auth-form-panel">

                {{ $slot }}
            </div>
        </div>
    </div>

    <script>
        // Generate floating paw prints
        (function() {
            const container = document.getElementById('floatingPaws');
            const pawSvg = `<svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><g fill="#f59e0b"><ellipse cx="20" cy="28" rx="10" ry="8"/><ellipse cx="10" cy="16" rx="5" ry="6" transform="rotate(-10,10,16)"/><ellipse cx="20" cy="12" rx="5" ry="6"/><ellipse cx="30" cy="16" rx="5" ry="6" transform="rotate(10,30,16)"/></g></svg>`;
            for (let i = 0; i < 8; i++) {
                const paw = document.createElement('div');
                paw.className = 'paw-print';
                paw.innerHTML = pawSvg;
                paw.style.left = Math.random() * 100 + '%';
                paw.style.width = (20 + Math.random() * 25) + 'px';
                paw.style.animationDuration = (15 + Math.random() * 20) + 's';
                paw.style.animationDelay = (Math.random() * 15) + 's';
                container.appendChild(paw);
            }
        })();
    </script>
</body>
</html>
