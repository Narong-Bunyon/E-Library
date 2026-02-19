@extends('layouts.user')

@section('title', 'My Favorites')
@section('page-title', 'My Favorites')

@section('content')
<div class="container-fluid p-0">
    <!-- Favorites Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">My Favorites</h2>
                    <p class="text-muted mb-0">Books you've marked as favorites for quick access.</p>
                </div>
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                        <i class="fas fa-heart me-1"></i>
                        0 Favorites
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div class="section-card">
        <div class="section-body text-center py-5">
            <i class="fas fa-heart fa-4x text-muted mb-4"></i>
            <h4 class="mb-3">No favorites yet</h4>
            <p class="text-muted mb-4">Start adding books to your favorites to see them here.</p>
            <a href="{{ route('user.browse') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>
                Browse Books
            </a>
        </div>
    </div>
</div>
@endsection
