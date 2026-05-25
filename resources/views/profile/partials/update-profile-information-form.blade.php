<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="mt-2" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <!-- Avatar Preview & Upload -->
    <div class="d-flex align-items-center gap-4 mb-4">
        <img src="{{ $user->foto_url }}" alt="user-avatar" class="d-block rounded-3 border" height="100" width="100" id="uploadedAvatar" style="object-fit: cover;" />
        <div class="button-wrapper">
            <label for="upload" class="btn btn-primary me-2 mb-2" tabindex="0">
                <span class="d-none d-sm-block"><i class="bx bx-upload me-1"></i> Unggah Foto Baru</span>
                <i class="bx bx-upload d-block d-sm-none"></i>
                <input type="file" id="upload" name="foto" class="account-file-input" hidden accept="image/png, image/jpeg, image/jpg" onchange="previewImage(this);" />
            </label>
            <div class="text-muted" style="font-size: 0.8rem;">Diizinkan JPG, JPEG, atau PNG. Maksimal 2MB.</div>
            @if ($errors->get('foto'))
                <div class="text-danger mt-1">
                    {{ $errors->first('foto') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="mb-3 col-md-6">
            <label for="nama" class="form-label">{{ __('Nama') }}</label>
            <input class="form-control" type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required autofocus autocomplete="name" />
            @if ($errors->get('nama'))
                <div class="text-danger mt-1">
                    {{ $errors->first('nama') }}
                </div>
            @endif
        </div>

        <div class="mb-3 col-md-6">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @if ($errors->get('email'))
                <div class="text-danger mt-1">
                    {{ $errors->first('email') }}
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm mt-2 text-warning">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="mt-2">
        <button type="submit" class="btn btn-primary me-2">{{ __('Save Changes') }}</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success" id="profile-updated-msg"><i class="bx bx-check-circle"></i> {{ __('Saved.') }}</span>
            <script>
                setTimeout(() => {
                    const msg = document.getElementById('profile-updated-msg');
                    if (msg) {
                        msg.style.display = 'none';
                    }
                }, 2000);
            </script>
        @endif
    </div>
</form>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadedAvatar').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
