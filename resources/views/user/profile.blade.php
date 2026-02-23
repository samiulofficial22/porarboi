@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card border-0 rounded-5 shadow-lg overflow-hidden">
            <div class="card-header bg-primary py-4 border-0">
                <h4 class="mb-0 text-white fw-bold"><i class="fas fa-user-edit me-2"></i>Account Settings</h4>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-5">
                        <!-- Profile Image Section -->
                        <div class="col-lg-4 text-center">
                            <h6 class="fw-bold text-muted text-uppercase small mb-4 tracking-wider">Profile Picture</h6>
                            <div class="position-relative d-inline-block mb-4">
                                @php
                                    $avatarUrl = $user->avatar ? (str_contains($user->avatar, 'http') ? $user->avatar : Storage::url($user->avatar)) : null;
                                @endphp
                                
                                <div class="profile-preview-wrap shadow">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" id="avatar-preview" class="rounded-circle" width="150" height="150" style="object-fit: cover; border: 4px solid var(--primary-color);">
                                    @else
                                        <div id="avatar-placeholder" class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; border: 4px solid var(--primary-color);">
                                            <i class="fas fa-user fa-4x text-primary opacity-25"></i>
                                        </div>
                                        <img src="" id="avatar-preview" class="rounded-circle d-none" width="150" height="150" style="object-fit: cover; border: 4px solid var(--primary-color);">
                                    @endif
                                    
                                    <label for="avatar-input" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 mb-1 me-1 shadow" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*" onchange="previewImage(this)">
                                </div>
                            </div>
                            <p class="text-muted small">Update your photo for better recognition. Max 2MB.</p>
                        </div>

                        <!-- Basic Info Section -->
                        <div class="col-lg-8 border-start border-secondary border-opacity-10">
                            <h6 class="fw-bold text-muted text-uppercase small mb-4 tracking-wider">Personal Information</h6>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Full Name</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-user text-muted small"></i></span>
                                    <input type="text" name="name" class="form-control bg-transparent border-start-0 ps-0" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Email Address</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent border-end-0 opacity-50"><i class="fas fa-envelope text-muted small"></i></span>
                                    <input type="email" class="form-control bg-transparent border-start-0 ps-0 opacity-50" value="{{ $user->email }}" disabled>
                                </div>
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Email cannot be changed.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Phone Number</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-phone text-muted small"></i></span>
                                    <input type="text" name="phone" class="form-control bg-transparent border-start-0 ps-0" value="{{ old('phone', $user->phone) }}" required>
                                </div>
                            </div>

                            <hr class="my-5 opacity-10">

                            <h6 class="fw-bold text-muted text-uppercase small mb-4 tracking-wider">Security</h6>
                            <p class="text-muted small mb-4">Leave password fields blank if you don't want to change it.</p>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">New Password</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-lock text-muted small"></i></span>
                                        <input type="password" name="password" class="form-control bg-transparent border-start-0 ps-0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Confirm New Password</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-shield-alt text-muted small"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control bg-transparent border-start-0 ps-0">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold">
                                    <i class="fas fa-save me-2"></i>Apply Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if(placeholder) placeholder.classList.add('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.profile-preview-wrap {
    position: relative;
    border-radius: 50%;
    overflow: visible;
}
.tracking-wider { letter-spacing: 0.1em; }
.card {
    background-color: var(--card-bg);
}
.input-group-text {
    border-color: var(--border-color);
}
.form-control {
    border-color: var(--border-color);
    color: var(--text-color) !important;
}
.form-control:focus {
    box-shadow: none;
    border-color: var(--primary-color);
}
</style>
@endsection
