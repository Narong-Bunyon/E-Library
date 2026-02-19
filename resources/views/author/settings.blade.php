@extends('layouts.author')

@section('title', 'Settings - E-Library')

@section('page-title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('author.profile.update') }}">
                        @csrf
                        <div class="mb-4">
                            <h6 class="text-uppercase mb-3">Change Password</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-uppercase mb-3">Email Preferences</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                <label class="form-check-label" for="email_notifications">
                                    Receive email notifications for new reviews
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="email_updates" name="email_updates" checked>
                                <label class="form-check-label" for="email_updates">
                                    Receive email updates about your books
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="email_newsletter" name="email_newsletter">
                                <label class="form-check-label" for="email_newsletter">
                                    Subscribe to newsletter
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-uppercase mb-3">Privacy Settings</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="profile_public" name="profile_public" checked>
                                <label class="form-check-label" for="profile_public">
                                    Make profile public
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="show_email" name="show_email">
                                <label class="form-check-label" for="show_email">
                                    Show email address on profile
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                            <a href="{{ route('author.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Books</span>
                        <span class="badge bg-primary">{{ \App\Models\Book::where('author_id', auth()->user()->id)->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Published Books</span>
                        <span class="badge bg-success">{{ \App\Models\Book::where('author_id', auth()->user()->id)->where('status', 1)->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Draft Books</span>
                        <span class="badge bg-warning">{{ \App\Models\Book::where('author_id', auth()->user()->id)->where('status', 0)->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Views</span>
                        <span class="badge bg-info">{{ number_format(\App\Models\Book::where('author_id', auth()->user()->id)->sum('views') ?? 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Downloads</span>
                        <span class="badge bg-secondary">{{ \App\Models\Download::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->count() }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Book
                        </a>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-book me-2"></i>Manage Books
                        </a>
                        <a href="{{ route('author.profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
