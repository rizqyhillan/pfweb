<div class="mb-3 col-12 mb-0">
    <div class="alert alert-warning">
        <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
        <p class="mb-0">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
    </div>
</div>

<button type="button" class="btn btn-danger deactivate-account" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
    {{ __('Delete Account') }}
</button>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">{{ __('Are you sure you want to delete your account?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>
                    
                    <div class="row">
                        <div class="col mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required />
                            @if ($errors->userDeletion->get('password'))
                                <div class="text-danger mt-1">
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
