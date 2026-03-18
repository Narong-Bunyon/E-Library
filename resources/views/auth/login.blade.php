@extends('layouts.auth')

@section('title', 'Login - E-Library')

@push('styles')
<style>
.form-label i {
    margin-right: 0.5rem;
    color: #6366f1;
    font-size: 0.9rem;
}

.input-group .input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-right: none;
    border-radius: 0.375rem 0 0 0.375rem;
}

.input-group .input-icon i {
    color: #64748b;
    font-size: 0.875rem;
}

.input-group .form-control {
    border-left: none;
    border-radius: 0 0.375rem 0.375rem 0;
}

.input-group .form-control:focus {
    border-left: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
}

.invalid-feedback i {
    margin-right: 0.25rem;
    color: #dc2626;
}
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-background">
        <div class="auth-pattern"></div>
        <div class="auth-glow"></div>
    </div>
    
    <div class="auth-content-centered">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <div class="brand__mark">E</div>
                    <span class="brand__text">E-Library</span>
                </div>
                <p class="auth-subtitle">Sign in to access your digital E-library.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <div class="alert-icon">✓</div>
                    {{ session('success') }}
                </div>
            @endif

            <form class="auth-form" action="{{ route('login.submit') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-at me-2 text-primary"></i>Email Address
                    </label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-envelope text-muted"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email address"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-key me-2 text-primary"></i>Password
                    </label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-shield-alt text-muted"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Enter your password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')" title="Toggle password visibility">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Remember me for 30 days
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn--primary btn--block btn--large">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In</span>
                </button>
            </form>

            <div class="auth-footer">
                <p>New to E-Library? <a href="{{ route('register') }}" class="link">Create your free account</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById('password-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        icon.style.color = '#3b82f6';
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        icon.style.color = '#64748b';
    }
}
</script>
@endpush
