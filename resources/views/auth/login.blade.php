@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="auth-card card shadow-lg border-0 rounded-5">
            <div class="card-body p-5">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="auth-icon-wrap mx-auto mb-3">
                        <i class="fas fa-book-open fa-2x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Welcome Back</h3>
                    <p class="text-muted small">Sign in to continue your reading journey</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger rounded-4 border-0 mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Google Login -->
                <div class="d-grid mb-4">
                    <a href="{{ route('login.google') }}" class="btn btn-gmail btn-lg d-flex align-items-center justify-content-center gap-2 rounded-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                        Login with Google
                    </a>
                </div>

                <!-- Separator -->
                <div class="position-relative mb-4 login-separator">
                    <hr>
                    <span class="position-absolute top-50 start-50 translate-middle small">Or login with credentials</span>
                </div>

                <!-- Login Form -->
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email or Phone</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text auth-input-icon"><i class="fas fa-user-circle text-muted small"></i></span>
                            <input type="text" name="login" value="{{ old('login') }}"
                                class="form-control auth-input @error('login') is-invalid @enderror"
                                required placeholder="Email or Phone number">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Password</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text auth-input-icon"><i class="fas fa-lock text-muted small"></i></span>
                            <input type="password" name="password"
                                class="form-control auth-input @error('password') is-invalid @enderror"
                                required placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 shadow-sm fw-bold">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted small">Don't have an account?
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .auth-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color) !important;
        transition: all 0.3s ease;
    }

    .auth-icon-wrap {
        width: 64px;
        height: 64px;
        background: rgba(99, 102, 241, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(99, 102, 241, 0.2);
    }

    .btn-gmail {
        background-color: var(--card-bg);
        color: var(--text-color);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        border-radius: 12px !important;
    }

    .btn-gmail:hover {
        background-color: var(--input-bg);
        border-color: var(--primary-color);
        color: var(--text-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .login-separator span {
        background-color: var(--card-bg) !important;
        color: var(--text-color) !important;
        padding: 0 15px;
        font-size: 0.8rem;
    }

    .login-separator hr {
        border-color: var(--border-color) !important;
        opacity: 0.5;
    }

    /* Input group styling */
    .auth-input-icon {
        background-color: var(--input-bg);
        border-color: var(--input-border, var(--border-color));
        border-right: none;
        color: var(--text-color);
        padding-left: 16px;
        padding-right: 14px;
    }

    .auth-input {
        background-color: var(--input-bg) !important;
        border-color: var(--input-border, var(--border-color)) !important;
        color: var(--text-color) !important;
        border-left: none !important;
    }

    .auth-input:focus {
        box-shadow: none !important;
        border-color: var(--primary-color) !important;
    }

    .input-group:focus-within .auth-input-icon {
        border-color: var(--primary-color) !important;
    }

    .auth-input::placeholder {
        color: #94a3b8 !important;
        opacity: 0.7;
    }

    .form-label {
        color: var(--text-color);
    }

    /* Light mode tweaks */
    [data-bs-theme="light"] .auth-card {
        box-shadow: 0 20px 60px rgba(0,0,0,0.08) !important;
    }

    [data-bs-theme="light"] .auth-input-icon {
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }

    [data-bs-theme="light"] .auth-input {
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #1e293b !important;
    }

    [data-bs-theme="light"] .auth-input:focus {
        background-color: #ffffff !important;
        border-color: #6366f1 !important;
    }

    [data-bs-theme="light"] .input-group:focus-within .auth-input-icon {
        background-color: #ffffff;
        border-color: #6366f1 !important;
    }
</style>
@endsection
