@extends('layouts.auth')

@section('title', 'Register - E-Library')

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

.password-requirements .requirement-item i {
    margin-right: 0.25rem;
    color: #64748b;
}

.password-requirements .requirement-item.valid i {
    color: #10b981;
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
                <p class="auth-subtitle">Join our community and start your digital reading</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <div class="alert-icon">✓</div>
                    {{ session('success') }}
                </div>
            @endif

            <form class="auth-form" action="{{ route('register.submit') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-id-card me-2 text-primary"></i>Full Name
                    </label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-user-circle text-muted"></i>
                        </div>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name') }}" 
                            placeholder="Enter your full name"
                            required
                            autofocus
                        >
                    </div>
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

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
                            placeholder="Create a strong password"
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
                    <div class="password-requirements">
                        <div class="requirement-item" id="length-req">
                            <i class="fas fa-check-circle text-muted"></i>
                            At least 8 characters
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-check-double me-2 text-primary"></i>Confirm Password
                    </label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-lock text-muted"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-control @error('password_confirmation') is-invalid @enderror" 
                            placeholder="Confirm your password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')" title="Toggle password visibility">
                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if(!session('show_hear_about_form'))
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        I agree to the <a href="#" class="link">Terms of Service</a> and <a href="#" class="link">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn btn--primary btn--block btn--large">
                    <i class="fas fa-user-plus"></i>
                    <span>Create Account</span>
                </button>
                @endif
            </form>

            @if(session('show_hear_about_form'))
            <!-- Step 2: How did you hear about us? -->
            <div class="auth-step-2">
                <div class="step-header">
                    <div class="step-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3>How did you hear about us?</h3>
                    <p class="step-subtitle">Help us improve our service by letting us know how you discovered E-Library</p>
                </div>

                <form class="auth-form" action="{{ route('register.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ session('registration_data.name') }}">
                    <input type="hidden" name="email" value="{{ session('registration_data.email') }}">
                    <input type="hidden" name="password" value="{{ session('registration_data.password') }}">
                    <input type="hidden" name="password_confirmation" value="{{ session('registration_data.password') }}">
                    <input type="hidden" name="terms" value="accepted">
                    
                    <div class="form-group">
                        <label for="how_hear_about_us" class="form-label">
                            <i class="fas fa-bullhorn me-2 text-primary"></i>How did you hear about us?
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-search-location text-muted"></i>
                            </div>
                            <select 
                                id="how_hear_about_us" 
                                name="how_hear_about_us" 
                                class="form-control @error('how_hear_about_us') is-invalid @enderror" 
                                required
                            >
                                <option value="">Please select an option</option>
                                <option value="friend">
                                    <i class="fas fa-user-friends me-1"></i>By Friend
                                </option>
                                <option value="social_media">
                                    <i class="fas fa-share-alt me-1"></i>Social Media
                                </option>
                                <option value="search">
                                    <i class="fas fa-search me-1"></i>Search Engine
                                </option>
                            </select>
                        </div>
                        @error('how_hear_about_us')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn--primary btn--block btn--large">
                        <i class="fas fa-check"></i>
                        <span>Complete Registration</span>
                    </button>
                </form>
            </div>
            @endif

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}" class="link">Sign in instead</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
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

// Password strength indicator
document.getElementById('password')?.addEventListener('input', function(e) {
    const password = e.target.value;
    const lengthReq = document.getElementById('length-req');
    
    if (password.length >= 8) {
        lengthReq.classList.add('valid');
    } else {
        lengthReq.classList.remove('valid');
    }
});
</script>
@endpush
