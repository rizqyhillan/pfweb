<x-guest-layout>

@section('auth-title', 'Lupa Password')
@section('illust-title', 'Jangan Khawatir!')
@section('illust-desc', 'Kami akan membantu Anda mendapatkan kembali akses ke akun PawPet Anda 🔑')

<p class="auth-subtitle">Masukkan email Anda untuk reset password</p>

<!-- Session Status -->
@if (session('status'))
    <div class="session-status">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrapper">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            <i class="bi bi-envelope"></i>
        </div>
        @error('email')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit -->
    <button type="submit" class="btn-pawpet">
        <i class="bi bi-send"></i>
        Kirim Link Reset
    </button>
</form>

<div class="auth-footer">
    Ingat password? <a href="{{ route('login') }}">Kembali ke Login</a>
</div>

</x-guest-layout>
