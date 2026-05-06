@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Profile</h4>

<div class="row">
    <div class="col-md-12">
        <!-- Update Profile Information -->
        <div class="card mb-4">
            <h5 class="card-header">Profile Information</h5>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="card mb-4">
            <h5 class="card-header">Update Password</h5>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
