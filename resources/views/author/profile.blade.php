@extends('layouts.author')

@section('title', 'My Profile - E-Library')

@section('page-title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="author-avatar-large mb-3 position-relative">
                        @if(auth()->user()->image_profile && !empty(auth()->user()->image_profile))
                            <img src="{{ Str::startsWith(auth()->user()->image_profile, ['http://', 'https://']) ? auth()->user()->image_profile : asset('storage/' . auth()->user()->image_profile) }}" alt="{{ auth()->user()->name }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); border-radius: 50%; display: none; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 3rem; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @else
                            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 3rem; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        
                        <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 m-2" onclick="document.getElementById('imageUpload').click()">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <span class="badge bg-success">Author</span>
                    
                    @if(auth()->user()->bio)
                        <div class="mt-3">
                            <p class="text-muted">{{ auth()->user()->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('author.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3">
                                <label for="image_profile" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="imageUpload" name="image_profile" accept="image/*">
                                <small class="form-text text-muted">Optional: Upload a profile image (JPG, PNG, GIF - Max 2MB)</small>
                            </div>
                            <div class="d-flex align-items-center">
                                @if(auth()->user()->image_profile && !empty(auth()->user()->image_profile))
                                    <img src="{{ Str::startsWith(auth()->user()->image_profile, ['http://', 'https://']) ? auth()->user()->image_profile : asset('storage/' . auth()->user()->image_profile) }}" alt="{{ auth()->user()->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" title="{{ auth()->user()->name }}">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" title="{{ auth()->user()->name }}">
                                        <span class="text-muted fw-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                @if(request()->get('debug'))
                                    <small class="d-block text-muted mt-1" style="font-size: 10px;">
                                        {{ auth()->user()->image_profile ? 'Has Image' : 'No Image' }}
                                    </small>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" {{ auth()->user()->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ auth()->user()->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4">{{ auth()->user()->bio ?? '' }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <a href="{{ route('author.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ auth()->user()->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ auth()->user()->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4">{{ auth()->user()->bio ?? '' }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('author.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.author-avatar-large {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 3rem;
    margin: 0 auto;
    box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);
    position: relative;
    overflow: hidden;
}

.author-avatar-large img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    margin: 0 auto;
    transition: opacity 0.3s ease;
}

.author-avatar-large .position-relative {
    position: relative;
}

.author-avatar-large .btn-sm {
    position: absolute;
    bottom: 0;
    right: 0;
    border-radius: 50%;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.author-avatar-large .btn-sm:hover {
    opacity: 1;
}
</style>
@endpush
@endsection
