@extends('layouts.admin')

@section('title', 'Analytics & Statistics - E-Library')

@section('page-title', 'Analytics & Statistics')

@section('content')
<div class="container-fluid">
    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['total_users'] }}</h3>
                            <p class="stat-label">Total Users</p>
                            @if($stats['users_this_month'] > 0)
                                <small class="text-success">
                                    <i class="fas fa-arrow-up"></i> +{{ $stats['users_this_month'] }} this month
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['total_books'] }}</h3>
                            <p class="stat-label">Total Books</p>
                            <small class="text-muted">{{ $stats['published_books'] }} published, {{ $stats['draft_books'] }} drafts</small>
                            @if($stats['books_this_month'] > 0)
                                <small class="text-success d-block">
                                    <i class="fas fa-arrow-up"></i> +{{ $stats['books_this_month'] }} this month
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['total_downloads'] }}</h3>
                            <p class="stat-label">Total Downloads</p>
                            @if($stats['downloads_this_month'] > 0)
                                <small class="text-success">
                                    <i class="fas fa-arrow-up"></i> +{{ $stats['downloads_this_month'] }} this month
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['total_views'] }}</h3>
                            <p class="stat-label">Total Views</p>
                            <small class="text-muted">{{ $stats['completion_rate'] }}% completion rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['avg_rating'] }}</h3>
                            <p class="stat-label">Avg Rating</p>
                            <small class="text-muted">{{ $stats['total_reviews'] }} reviews</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['user_satisfaction'] }}%</h3>
                            <p class="stat-label">User Satisfaction</p>
                            <small class="text-muted">Based on 4-5 star ratings</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['avg_reading_time'] }}h</h3>
                            <p class="stat-label">Avg Reading Time</p>
                            <small class="text-muted">Per completed book</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-bookmark"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $stats['total_favorites'] }}</h3>
                            <p class="stat-label">Total Favorites</p>
                            <small class="text-muted">Books marked as favorite</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Reading & Download Trends (Last 30 Days)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="trendsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Category Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Book Categories
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Books & Recent Activity -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire me-2"></i>
                        Popular Books
                    </h5>
                </div>
                <div class="card-body">
                    <div class="popular-books">
                        @forelse ($popularBooks as $index => $book)
                        <div class="popular-book-item d-flex align-items-center mb-3">
                            <div class="book-rank">{{ $index + 1 }}</div>
                            <div class="book-cover me-3">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div class="book-info flex-grow-1">
                                <h6 class="book-title">{{ $book->title }}</h6>
                                <p class="book-stats text-muted mb-0">
                                    <i class="fas fa-download me-1"></i> {{ $book->downloads_count ?? 0 }} downloads
                                    @if($book->author)
                                        <span class="ms-2">by {{ $book->author->name }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="book-rating">
                                <i class="fas fa-star text-warning"></i> {{ $book->average_rating ?? 'N/A' }}
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No books found</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                        <div class="popular-book-item d-flex align-items-center mb-3">
                            <div class="book-rank">3</div>
                            <div class="book-cover me-3">
                                <i class="fas fa-book text-warning"></i>
                            </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Recent Activity
                    </h5>
                </div>
                @php
function getActivityColor($action) {
    $colors = [
        'login' => 'success',
        'download' => 'primary',
        'register' => 'info',
        'review' => 'warning',
        'favorite' => 'danger'
    ];
    return $colors[$action] ?? 'secondary';
}

function getActivityTitle($action) {
    $titles = [
        'login' => 'User Login',
        'download' => 'Book Downloaded',
        'register' => 'New User',
        'review' => 'Review Posted',
        'favorite' => 'Book Favorited'
    ];
    return $titles[$action] ?? 'Activity';
}

function getActivityDescription($activity) {
    switch($activity->action) {
        case 'download':
            return 'downloaded "' . ($activity->book->title ?? 'a book') . '"';
        case 'review':
            return 'reviewed "' . ($activity->book->title ?? 'a book') . '"';
        case 'favorite':
            return 'favorited "' . ($activity->book->title ?? 'a book') . '"';
        default:
            return $activity->description ?? 'performed an action';
    }
}
@endphp

                <div class="card-body">
                    <div class="activity-timeline">
                        @forelse ($recentActivity as $activity)
                        <div class="activity-item">
                            <div class="activity-dot bg-{{ getActivityColor($activity->action) }}"></div>
                            <div class="activity-content">
                                <h6 class="activity-title">{{ getActivityTitle($activity->action) }}</h6>
                                <p class="activity-description">
                                    {{ $activity->user->name ?? 'System' }} 
                                    {{ getActivityDescription($activity) }}
                                </p>
                                <small class="activity-time">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.stat-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.stat-card.success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    color: white;
}

.stat-card.warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: white;
}

.stat-card.info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.stat-card.secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 15px;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
    margin: 0;
}

.popular-book-item {
    padding: 10px;
    border-radius: 8px;
    background: #f8f9fa;
    transition: background 0.3s ease;
}

.popular-book-item:hover {
    background: #e9ecef;
}

.book-rank {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.book-cover {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.book-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.book-stats {
    font-size: 12px;
}

.book-rating {
    font-size: 12px;
    font-weight: bold;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    position: relative;
}

.activity-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 15px;
    margin-top: 5px;
    flex-shrink: 0;
}

.activity-content {
    flex-grow-1;
}

.activity-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.activity-description {
    font-size: 13px;
    color: #6c757d;
    margin-bottom: 2px;
}

.activity-time {
    font-size: 11px;
    color: #999;
}
</style>
@endpush

@push('scripts')
<script>
// Chart.js configurations
document.addEventListener('DOMContentLoaded', function() {
    // Trends Chart
    const trendsCtx = document.getElementById('trendsChart');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: @json($readingTrends->pluck('date')),
                datasets: [{
                    label: 'Reading Activity',
                    data: @json($readingTrends->pluck('count')),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Downloads',
                    data: @json($downloadTrends->pluck('count')),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryStats->pluck('name')),
                datasets: [{
                    data: @json($categoryStats->pluck('books_count')),
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#17a2b8', '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});

// Helper functions for activity display
function getActivityColor(action) {
    const colors = {
        'login': 'success',
        'download': 'primary',
        'register': 'info',
        'review': 'warning',
        'favorite': 'danger'
    };
    return colors[action] || 'secondary';
}

function getActivityTitle(action) {
    const titles = {
        'login': 'User Login',
        'download': 'Book Downloaded',
        'register': 'New User',
        'review': 'Review Posted',
        'favorite': 'Book Favorited'
    };
    return titles[action] || 'Activity';
}

function getActivityDescription(activity) {
    switch(activity.action) {
        case 'download':
            return `downloaded "${activity.book->title ?? 'a book'}"`;
        case 'review':
            return `reviewed "${activity.book->title ?? 'a book'}"`;
        case 'favorite':
            return `favorited "${activity.book->title ?? 'a book'}"`;
        default:
            return activity.description || 'performed an action';
    }
}
</script>
@endpush
@endsection
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-dot bg-info"></div>
                            <div class="activity-content">
                                <h6 class="activity-title">System update</h6>
                                <p class="activity-description">Security patches applied</p>
                                <small class="activity-time">2 days ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Engagement Metrics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        User Engagement Metrics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="engagement-metric">
                                <h4 class="metric-value">4.2</h4>
                                <p class="metric-label">Avg. Reading Time (hours)</p>
                                <div class="metric-progress">
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" style="width: 70%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="engagement-metric">
                                <h4 class="metric-value">68%</h4>
                                <p class="metric-label">Completion Rate</p>
                                <div class="metric-progress">
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 68%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="engagement-metric">
                                <h4 class="metric-value">3.8</h4>
                                <p class="metric-label">Avg. Rating</p>
                                <div class="metric-progress">
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 76%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="engagement-metric">
                                <h4 class="metric-value">92%</h4>
                                <p class="metric-label">User Satisfaction</p>
                                <div class="metric-progress">
                                    <div class="progress">
                                        <div class="progress-bar bg-info" style="width: 92%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
