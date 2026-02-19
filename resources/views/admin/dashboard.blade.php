@extends('layouts.admin')

@section('title', 'Admin Dashboard - E-Library')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_users'] }}</h3>
                        <p class="stat-label mb-1">Total Users</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +12% from last month
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card success">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_books'] }}</h3>
                        <p class="stat-label mb-1">Total Books</p>
                        <small class="text-muted">All books in library</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_downloads'] }}</h3>
                        <p class="stat-label mb-1">Total Downloads</p>
                        <small class="text-muted">All time downloads</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card info">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info text-white">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_reviews'] }}</h3>
                        <p class="stat-label mb-1">Total Reviews</p>
                        <small class="text-muted">User feedback</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @forelse ($recentReviews as $review)
                        <div class="activity-item">
                            <div class="activity-icon success">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">New review posted</div>
                                <div class="activity-description">{{ $review->user->name ?? 'Anonymous' }} reviewed "{{ $review->book->title ?? 'Unknown Book' }}"</div>
                                <div class="activity-time">{{ $review->create_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <p class="text-muted">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
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
                                    <small>Manage author accounts and permissions</small>
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
                                    <small>View system reports and analytics</small>
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
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book me-2"></i>
                        Recent Books
                    </h5>
                </div>
                <div class="card-body">
                    <div class="recent-books">
                        @forelse ($recentBooks as $book)
                        <div class="recent-book-item">
                            <div class="book-cover">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div class="book-info">
                                <h6 class="book-title">{{ $book->title }}</h6>
                                <p class="book-author">{{ $book->author_id ?? 'Unknown Author' }}</p>
                                <small class="book-date">{{ $book->create_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <p class="text-muted">No books found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Recent Users
                    </h5>
                </div>
                <div class="card-body">
                    <div class="recent-users">
                        @forelse ($recentUsers as $user)
                        <div class="recent-user-item">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <h6 class="user-name">{{ $user->name }}</h6>
                                <p class="user-email">{{ $user->email }}</p>
                                <small class="user-date">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
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
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">5</h4>
                                <p class="text-muted">Total Books</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">12</h4>
                                <p class="text-muted">Active Readers</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">3</h4>
                                <p class="text-muted">Pending Approvals</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">98%</h4>
                                <p class="text-muted">System Health</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
