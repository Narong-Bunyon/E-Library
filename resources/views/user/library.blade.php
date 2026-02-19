@extends('layouts.user')

@section('title', 'My Library')
@section('page-title', 'My Library')

@section('content')
<div class="container-fluid p-0">
    <!-- Library Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">My Library</h2>
                    <p class="text-muted mb-0">Your personal collection of books and reading materials.</p>
                </div>
                <div>
                    <a href="{{ route('user.browse') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add Books
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Stats -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Total Books</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Currently Reading</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>

    <!-- Empty State -->
    <div class="section-card">
        <div class="section-body text-center py-5">
            <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
            <h4 class="mb-3">Your library is empty</h4>
            <p class="text-muted mb-4">Start building your collection by browsing and adding books to your library.</p>
            <a href="{{ route('user.browse') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>
                Browse Books
            </a>
        </div>
    </div>
</div>
@endsection
