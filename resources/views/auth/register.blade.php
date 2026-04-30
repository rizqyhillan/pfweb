<x-guest-layout>

@section('auth-title', 'Daftar')
@section('illust-title', 'Bergabung Bersama Kami!')
@section('illust-desc', 'Daftarkan diri Anda dan nikmati kemudahan merawat anabul tercinta 🐾')

<p class="auth-subtitle">Buat akun PawPet baru</p>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Nama -->
    <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <div class="input-wrapper">
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap">
            <i class="bi bi-person"></i>
        </div>
        @error('name')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrapper">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">
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
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
            <i class="bi bi-lock"></i>
        </div>
        @error('password')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <div class="input-wrapper">
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
            <i class="bi bi-shield-lock"></i>
        </div>
        @error('password_confirmation')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit -->
    <button type="submit" class="btn-pawpet">
        <i class="bi bi-person-plus"></i>
        Daftar Sekarang
    </button>
</form>

<div class="auth-footer">
    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
</div>

</x-guest-layout>
