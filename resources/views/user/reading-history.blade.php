@extends('layouts.user')

@section('title', 'Reading History')
@section('page-title', 'Reading History')

@section('content')
<div class="container-fluid p-0">
    <!-- Reading History Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Reading History</h2>
                    <p class="text-muted mb-0">Track your reading journey and progress over time.</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>
                        Export History
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading Stats -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Books Completed</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Pages Read</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Hours Read</div>
        </div>
    </div>

    <!-- Empty State -->
    <div class="section-card">
        <div class="section-body text-center py-5">
            <i class="fas fa-history fa-4x text-muted mb-4"></i>
            <h4 class="mb-3">No reading history yet</h4>
            <p class="text-muted mb-4">Start reading books to build your reading history.</p>
            <a href="{{ route('user.browse') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>
                Browse Books
            </a>
        </div>
    </div>
</div>
@endsection
