<form method="post" action="{{ route('password.update') }}" class="mt-2">
    @csrf
    @method('put')

    <div class="row">
        <div class="mb-3 col-md-12">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input class="form-control" type="password" id="update_password_current_password" name="current_password" autocomplete="current-password" />
            @if ($errors->updatePassword->get('current_password'))
                <div class="text-danger mt-1">
                    {{ $errors->updatePassword->first('current_password') }}
                </div>
            @endif
        </div>

        <div class="mb-3 col-md-6">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <input class="form-control" type="password" id="update_password_password" name="password" autocomplete="new-password" />
            @if ($errors->updatePassword->get('password'))
                <div class="text-danger mt-1">
                    {{ $errors->updatePassword->first('password') }}
                </div>
            @endif
        </div>

        <div class="mb-3 col-md-6">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input class="form-control" type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" />
            @if ($errors->updatePassword->get('password_confirmation'))
                <div class="text-danger mt-1">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </div>
            @endif
        </div>
    </div>

    <div class="mt-2">
        <button type="submit" class="btn btn-primary me-2">{{ __('Update Password') }}</button>

        @if (session('status') === 'password-updated')
            <span class="text-success" id="password-updated-msg"><i class="bx bx-check-circle"></i> {{ __('Saved.') }}</span>
            <script>
                setTimeout(() => {
                    const msg = document.getElementById('password-updated-msg');
                    if (msg) {
                        msg.style.display = 'none';
                    }
                }, 2000);
            </script>
        @endif
    </div>
</form>
