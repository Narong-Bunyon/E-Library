@extends('layouts.author')

@section('title', 'My Profile - E-Library')

@section('page-title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="author-avatar-large mb-3">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
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
                    <form method="POST" action="{{ route('author.profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
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
}
</style>
@endpush
@endsection
