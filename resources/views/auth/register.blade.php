@extends('layouts.auth')

@section('title', 'Register - E-Library')

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
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
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
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
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
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Account Type</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <select 
                            id="role" 
                            name="role" 
                            class="form-control @error('role') is-invalid @enderror" 
                            required
                        >
                            <option value="">Select your account type</option>
                            <option value="user">Reader - Can read books</option>
                            <option value="author">Author - Can write and manage books</option>
                            <option value="admin">Administrator - Full system access</option>
                        </select>
                    </div>
                    @error('role')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Create a strong password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="password-requirements">
                        <div class="requirement-item" id="length-req">
                            <i class="fas fa-check-circle"></i>
                            At least 8 characters
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-control @error('password_confirmation') is-invalid @enderror" 
                            placeholder="Confirm your password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
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
                    <input type="hidden" name="role" value="{{ session('registration_data.role') }}">
                    <input type="hidden" name="terms" value="accepted">
                    
                    <div class="form-group">
                        <label for="how_hear_about_us" class="form-label">Please select an option</label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <select 
                                id="how_hear_about_us" 
                                name="how_hear_about_us" 
                                class="form-control @error('how_hear_about_us') is-invalid @enderror" 
                                required
                            >
                                <option value="">Please select an option</option>
                                <option value="friend">By Friend</option>
                                <option value="social_media">Social Media</option>
                                <option value="search">Search Engine</option>
                            </select>
                        </div>
                        @error('how_hear_about_us')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
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
