<x-guest-layout>

@section('auth-title', 'Login')
@section('illust-title', 'Selamat Datang!')
@section('illust-desc', 'Masuk ke akun Anda dan kelola perawatan terbaik untuk anabul kesayangan 🐾')

<p class="auth-subtitle">Masuk ke akun PawPet Anda</p>

<!-- Session Status -->
@if (session('status'))
    <div class="session-status">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email Address -->
    <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrapper">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
            <i class="bi bi-envelope"></i>
        </div>
        @error('email')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password">Password</label>
        <div class="input-wrapper">
            <input id="password" class="has-toggle" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            <i class="bi bi-lock"></i>
            <button type="button" class="toggle-password" tabindex="-1" aria-label="Toggle Password Visibility">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Remember & Forgot -->
    <div class="remember-row">
        <label class="remember-check">
            <input id="remember_me" type="checkbox" name="remember">
            <span>Ingat saya</span>
        </label>
        @if (Route::has('password.request'))
            <a class="forgot-link" href="{{ route('password.request') }}">Lupa password?</a>
        @endif
    </div>

    <!-- Submit -->
    <button type="submit" class="btn-pawpet">
        <i class="bi bi-box-arrow-in-right"></i>
        Masuk
    </button>
</form>


</x-guest-layout>
