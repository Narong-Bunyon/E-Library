@extends('layouts.marketing')

@section('title', 'My Profile - E‑Library')

@section('content')
<div class="home-content">
<div class="container">
    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">My Profile</h2>
                    <p class="text-muted mb-0">Manage your personal information and reading preferences.</p>
                </div>
                <div>
                    <a href="{{ route('user.settings') }}" class="btn btn-outline-primary">
                        <i class="fas fa-cog me-2"></i>
                        Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-4">
            <div class="section-card">
                <div class="section-body text-center">
                    <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                        <i class="fas fa-user me-1"></i>
                        User
                    </div>
                    
                    <div class="mt-4">
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-camera me-2"></i>
                            Change Avatar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reading Stats -->
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">Reading Stats</h5>
                </div>
                <div class="section-body">
                    <div class="text-center mb-3">
                        <h2 class="mb-1">0</h2>
                        <small class="text-muted">Books Read</small>
                    </div>
                    <div class="text-center mb-3">
                        <h2 class="mb-1">0</h2>
                        <small class="text-muted">Pages Read</small>
                    </div>
                    <div class="text-center">
                        <h2 class="mb-1">0</h2>
                        <small class="text-muted">Day Streak</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="col-lg-8">
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">Profile Information</h5>
                </div>
                <div class="section-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" placeholder="Enter last name">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" rows="4" placeholder="Tell us about yourself..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Favorite Genres</label>
                            <input type="text" class="form-control" placeholder="e.g., Fiction, Mystery, Science Fiction">
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Save Changes
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">Account Settings</h5>
                </div>
                <div class="section-body">
                    <div class="mb-4">
                        <h6 class="mb-3">Change Password</h6>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-lock me-2"></i>
                                Update Password
                            </button>
                        </form>
                    </div>
                    
                    <hr>
                    
                    <div>
                        <h6 class="mb-3 text-danger">Danger Zone</h6>
                        <p class="text-muted mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                        <button class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
