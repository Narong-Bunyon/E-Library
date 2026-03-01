@extends('layouts.admin')

@section('title', 'Reports - E-Library')

@section('page-title', 'System Reports')

@section('content')
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">System Reports</h2>
            <p class="text-muted mb-0">Comprehensive overview of all e-library metrics and activities</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="generateNewReport()">
                <i class="fas fa-plus me-1"></i> Generate Report
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="refreshReports()">
                <i class="fas fa-sync-alt me-1"></i> Refresh Data
            </button>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +{{ $stats['books_this_month'] ?? 0 }} this month
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
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +{{ $stats['reviews_this_month'] ?? 0 }} this month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Generated Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Type</th>
                                    <th>Generated By</th>
                                    <th>Date</th>
                                    <th>Format</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="report-icon bg-primary">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="report-name">User Activity Report</h6>
                                                <small class="text-muted">Monthly user engagement</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">Analytics</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">{{ substr(auth()->user()->name, 0, 1) }}</div>
                                            <div>
                                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                                <small class="text-muted">{{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ now()->format('M d, Y') }}</td>
                                    <td><span class="badge bg-success">PDF</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <div class="table-actions">
                                            <button class="icon-btn text-info" title="View Details" onclick="viewReport(1)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="icon-btn text-success" title="Download" onclick="downloadReport(1)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="icon-btn text-warning" title="Share" onclick="shareReport(1)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="report-icon bg-success">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="report-name">Book Statistics Report</h6>
                                                <small class="text-muted">Popular books and categories</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Statistics</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">{{ substr(auth()->user()->name, 0, 1) }}</div>
                                            <div>
                                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                                <small class="text-muted">{{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ now()->subDays(1)->format('M d, Y') }}</td>
                                    <td><span class="badge bg-warning">Excel</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <div class="table-actions">
                                            <button class="icon-btn text-info" title="View Details" onclick="viewReport(2)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="icon-btn text-success" title="Download" onclick="downloadReport(2)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="icon-btn text-warning" title="Share" onclick="shareReport(2)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="report-icon bg-info">
                                                <i class="fas fa-download"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="report-name">Download Statistics Report</h6>
                                                <small class="text-muted">Weekly download trends</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Statistics</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">{{ substr(auth()->user()->name, 0, 1) }}</div>
                                            <div>
                                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                                <small class="text-muted">{{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ now()->subDays(3)->format('M d, Y') }}</td>
                                    <td><span class="badge bg-success">PDF</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <div class="table-actions">
                                            <button class="icon-btn text-info" title="View Details" onclick="viewReport(3)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="icon-btn text-success" title="Download" onclick="downloadReport(3)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="icon-btn text-warning" title="Share" onclick="shareReport(3)">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Recent System Activity
                    </h5>
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
                            <p class="text-muted">No recent activity found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Popular Books
                    </h5>
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
                            <div class="badge bg-success mb-1">{{ $book->downloads_count ?? 0 }} downloads</div>
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
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
    transition: transform 0.2s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.report-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.report-name {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.user-avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 12px;
}

.table-actions {
    display: flex;
    gap: 4px;
}

.icon-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.icon-btn:hover {
    background: rgba(0, 0, 0, 0.05);
}

.icon-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.activity-item {
    position: relative;
}

.activity-indicator {
    position: relative;
}

.activity-dot {
    position: absolute;
    top: 5px;
    left: 0;
}

.activity-card {
    margin-left: 20px;
    border-left: 2px solid #e9ecef;
    padding-left: 15px;
}

.book-rank {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush

@push('scripts')
<script>
// Generate New Report
function generateNewReport() {
    const reportType = prompt('Select report type:\n1. User Activity\n2. Book Statistics\n3. Download Statistics\n4. Review Analysis');
    if (reportType) {
        window.location.href = `/admin/reports/generate?type=${reportType}`;
    }
}

// View Report Details
function viewReport(id) {
    // Direct navigation instead of AJAX
    window.location.href = `/admin/reports/${id}/view`;
}

// Download Report
function downloadReport(id) {
    // Direct navigation for download
    window.location.href = `/admin/reports/${id}/download`;
}

// Share Report
function shareReport(id) {
    // Direct navigation instead of AJAX
    window.location.href = `/admin/reports/${id}/share`;
}

// Loading state functions
function showLoading() {
    const loader = document.createElement('div');
    loader.id = 'globalLoader';
    loader.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50';
    loader.style.zIndex = '9999';
    loader.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-white mt-2 mb-0">Processing...</p>
        </div>
    `;
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.getElementById('globalLoader');
    if (loader) {
        loader.remove();
    }
}

// Notification function (if not already defined)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Refresh Reports
function refreshReports() {
    window.location.reload();
}
</script>
@endpush
