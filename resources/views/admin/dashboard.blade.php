@extends('layouts.admin')

@section('title', 'Admin Dashboard - E-Library')

@section('page-title', 'Dashboard Overview')

@section('content')
<div class="dashboard-container">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card primary">
                <div class="stat-card-body">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_users'] ?? 0 }}</h3>
                        <p class="stat-label">Total Users</p>
                        <span class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +12% from last month
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card success">
                <div class="stat-card-body">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_books'] ?? 0 }}</h3>
                        <p class="stat-label">Total Books</p>
                        <span class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +8% from last month
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card warning">
                <div class="stat-card-body">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_downloads'] ?? 0 }}</h3>
                        <p class="stat-label">Total Downloads</p>
                        <span class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +15% from last month
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card info">
                <div class="stat-card-body">
                    <div class="stat-icon bg-info text-white">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_reviews'] ?? 0 }}</h3>
                        <p class="stat-label">Total Reviews</p>
                        <span class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +5% from last month
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @forelse ($recentReviews ?? [] as $review)
                        <div class="activity-item">
                            <div class="activity-icon success">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">New Review Posted</div>
                                <div class="activity-description">{{ $review->user->name ?? 'Anonymous' }} reviewed "{{ $review->book->title ?? 'Unknown Book' }}"</div>
                                <div class="activity-time">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() ?? 'Recently' }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Manage Users</h6>
                                    <small>Add, edit, or remove users</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('admin.authors.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Manage Authors</h6>
                                    <small>Manage author accounts</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('admin.settings') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Settings</h6>
                                    <small>Configure system settings</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('admin.books.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Manage Books</h6>
                                    <small>Add, edit, or remove books</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('admin.categories.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Categories</h6>
                                    <small>Manage book categories</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('admin.reports') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="action-content">
                                    <h6>Reports</h6>
                                    <small>View analytics and reports</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Books and Users -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book me-2"></i>
                        Recent Books
                    </h5>
                </div>
                <div class="card-body">
                    <div class="recent-books">
                        @forelse ($recentBooks ?? [] as $book)
                        <div class="recent-book-item">
                            <div class="book-cover">
                                @if($book->cover_image)
                                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                @else
                                    <i class="fas fa-book"></i>
                                @endif
                            </div>
                            <div class="book-info">
                                <h6 class="book-title">{{ $book->title }}</h6>
                                <p class="book-author">{{ $book->author->name ?? 'Unknown Author' }}</p>
                                <small class="book-date">{{ \Carbon\Carbon::parse($book->created_at)->diffForHumans() ?? 'Recently' }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No books found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Recent Users
                    </h5>
                </div>
                <div class="card-body">
                    <div class="recent-users">
                        @forelse ($recentUsers ?? [] as $user)
                        <div class="recent-user-item">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <h6 class="user-name">{{ $user->name }}</h6>
                                <p class="user-email">{{ $user->email }}</p>
                                <small class="user-date">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() ?? 'Recently' }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No users found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        System Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="system-overview">
                        <div class="system-stat">
                            <h4>{{ $stats['total_books'] ?? 0 }}</h4>
                            <p>Total Books</p>
                        </div>
                        <div class="system-stat">
                            <h4>{{ $stats['active_users'] ?? 0 }}</h4>
                            <p>Active Readers</p>
                        </div>
                        <div class="system-stat">
                            <h4>{{ $stats['pending_approvals'] ?? 0 }}</h4>
                            <p>Pending Approvals</p>
                        </div>
                        <div class="system-stat">
                            <h4>98%</h4>
                            <p>System Health</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
