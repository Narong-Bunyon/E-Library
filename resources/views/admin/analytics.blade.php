@extends('layouts.admin')

@section('title', 'Analytics & Statistics - E-Library')

@section('page-title', 'Analytics & Statistics')

@section('content')
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Analytics Dashboard</h2>
            <p class="text-muted mb-0">Monitor your e-library performance and user engagement</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="exportAnalytics()">
                <i class="fas fa-download me-1"></i> Export Report
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="refreshAnalytics()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 mb-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="fas fa-users fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-1 fw-bold">{{ $stats['total_users'] ?? 0 }}</h3>
                            <p class="text-muted mb-1">Total Users</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +{{ $stats['users_this_month'] ?? 0 }} this month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="fas fa-book fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-1 fw-bold">{{ $stats['total_books'] ?? 0 }}</h3>
                            <p class="text-muted mb-1">Total Books</p>
                            <small class="text-muted">{{ $stats['published_books'] ?? 0 }} published</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                            <i class="fas fa-download fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-1 fw-bold">{{ $stats['total_downloads'] ?? 0 }}</h3>
                            <p class="text-muted mb-1">Total Downloads</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +{{ $stats['downloads_this_month'] ?? 0 }} this month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 mb-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info rounded-3 p-3">
                            <i class="fas fa-star fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-1 fw-bold">{{ $stats['total_reviews'] ?? 0 }}</h3>
                            <p class="text-muted mb-1">Total Reviews</p>
                            <small class="text-muted">{{ $stats['avg_rating'] ?? '0.0' }} avg rating</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">User Activity Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Content Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="contentChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Books & Recent Activity -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Popular Books</h5>
                </div>
                <div class="card-body">
                    @forelse ($popularBooks ?? [] as $book)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="book-rank bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $book->title }}</h6>
                            <p class="text-muted mb-0 small">{{ $book->author_name ?? 'Unknown Author' }}</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-success mb-1">{{ $book->downloads ?? 0 }} downloads</div>
                            <div class="text-muted small">{{ $book->rating ?? '0.0' }} ⭐</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-book text-muted fa-2x mb-2"></i>
                        <p class="text-muted">No books available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        @forelse ($recentActivity ?? [] as $activity)
                        <div class="activity-item d-flex mb-3">
                            <div class="activity-indicator me-3">
                                <div class="activity-dot bg-{{ 
                                    $activity->action === 'login' ? 'success' : 
                                    ($activity->action === 'download' ? 'primary' : 
                                    ($activity->action === 'register' ? 'info' : 
                                    ($activity->action === 'review' ? 'warning' : 'secondary')))
                                }} rounded-circle" style="width: 12px; height: 12px;"></div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-card bg-light rounded p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0 fw-semibold">{{
                                            $activity->action === 'login' ? 'User Login' : 
                                            ($activity->action === 'download' ? 'Book Downloaded' : 
                                            ($activity->action === 'register' ? 'New User' : 
                                            ($activity->action === 'review' ? 'Review Posted' : 'Activity')))
                                        }}</h6>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-muted small">
                                        <strong>{{ $activity->user_name ?? 'System' }}</strong>
                                        @switch($activity->action)
                                            @case('download')
                                                downloaded "{{ $activity->book_title ?? 'a book' }}"
                                                @break
                                            @case('review')
                                                reviewed "{{ $activity->book_title ?? 'a book' }}"
                                                @break
                                            @default
                                                {{ $activity->description ?? 'performed an action' }}
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-clock text-muted fa-2x mb-2"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Engagement Metrics -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">User Engagement Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="metric-card text-center">
                                <div class="metric-value text-primary fw-bold mb-2">4.2</div>
                                <div class="metric-label text-muted mb-2">Avg. Reading Time (hours)</div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="metric-card text-center">
                                <div class="metric-value text-success fw-bold mb-2">68%</div>
                                <div class="metric-label text-muted mb-2">Completion Rate</div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: 68%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="metric-card text-center">
                                <div class="metric-value text-warning fw-bold mb-2">3.8</div>
                                <div class="metric-label text-muted mb-2">Avg. Rating</div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: 76%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="metric-card text-center">
                                <div class="metric-value text-info fw-bold mb-2">92%</div>
                                <div class="metric-label text-muted mb-2">User Satisfaction</div>
                                <div class="progress" style="height: 6px;">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Activity Chart
const activityCtx = document.getElementById('activityChart').getContext('2d');
new Chart(activityCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'User Activity',
            data: [65, 78, 90, 81, 96, 105],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4
        }, {
            label: 'Downloads',
            data: [28, 48, 40, 59, 76, 87],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

// Content Distribution Chart
const contentCtx = document.getElementById('contentChart').getContext('2d');
new Chart(contentCtx, {
    type: 'doughnut',
    data: {
        labels: ['Fiction', 'Non-Fiction', 'Educational', 'Technical', 'Other'],
        datasets: [{
            data: [30, 25, 20, 15, 10],
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Export Analytics
function exportAnalytics() {
    window.location.href = '/admin/analytics/export';
}

// Refresh Analytics
function refreshAnalytics() {
    window.location.reload();
}
</script>
@endpush
