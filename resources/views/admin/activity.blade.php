@extends('layouts.admin')

@section('title', 'Activity Log - E-Library')

@section('page-title', 'Activity Log')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Activity Log</h2>
            <p class="text-muted mb-0">Monitor system activities and user actions</p>
        </div>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="exportActivityLog()">
                <i class="fas fa-download me-1"></i> Export
            </button>
            <button class="btn btn-outline-danger" onclick="clearActivityLog()">
                <i class="fas fa-trash me-1"></i> Clear All
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-chart-line text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Activities</h6>
                            <h3 class="mb-0">{{ $totalActivities ?? 0 }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +{{ $activityGrowth ?? 0 }}% this week
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-sign-in-alt text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">User Logins</h6>
                            <h3 class="mb-0">{{ $userLogins ?? 0 }}</h3>
                            <small class="text-muted">Last 24 hours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Failed Attempts</h6>
                            <h3 class="mb-0">{{ $failedAttempts ?? 0 }}</h3>
                            <small class="text-muted">Security alerts</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-clock text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Avg Response</h6>
                            <h3 class="mb-0">{{ $avgResponseTime ?? '0.0s' }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-down"></i> -{{ $responseTimeImprovement ?? 0 }}% faster
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" id="dateRange" onchange="applyFilters()">
                        <option value="24h">Last 24 hours</option>
                        <option value="7d" selected>Last 7 days</option>
                        <option value="30d">Last 30 days</option>
                        <option value="3m">Last 3 months</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Activity Type</label>
                    <select class="form-select" id="activityType" onchange="applyFilters()">
                        <option value="">All Activities</option>
                        <option value="login">User Logins</option>
                        <option value="user">User Actions</option>
                        <option value="admin">Admin Actions</option>
                        <option value="system">System Events</option>
                        <option value="security">Security Events</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">User Search</label>
                    <input type="text" class="form-control" id="userSearch" placeholder="Search by user..." onkeyup="applyFilters()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter" onchange="applyFilters()">
                        <option value="">All Status</option>
                        <option value="success">Success</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                        <option value="info">Info</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="clearAllFilters()">
                        <i class="fas fa-eraser me-1"></i> Clear Filters
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="refreshTimeline()">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Recent Activities</h5>
        </div>
        <div class="card-body">
            @forelse($activities as $activity)
                <div class="activity-item border-bottom pb-3 mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="activity-icon bg-{{ getActivityColor($activity->status ?? 'success') }} bg-opacity-10 rounded-circle p-2">
                                <i class="fas fa-{{ getActivityIcon($activity->type) }} text-{{ getActivityColor($activity->status ?? 'success') }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $activity->title }}</h6>
                                    <p class="text-muted mb-2">{{ $activity->description }}</p>
                                    <div class="d-flex align-items-center text-muted small">
                                        <span class="me-3">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $activity->user_name ?? 'System' }}
                                        </span>
                                        @if($activity->ip_address)
                                        <span class="me-3">
                                            <i class="fas fa-globe me-1"></i>
                                            {{ $activity->ip_address }}
                                        </span>
                                        @endif
                                        <span>
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-{{ getActivityColor($activity->status ?? 'success') }}">
                                        {{ ucfirst($activity->status ?? 'success') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-history text-muted fs-1 mb-3"></i>
                    <h5>No Activities Found</h5>
                    <p class="text-muted">There are no activities to display for the selected filters.</p>
                    <button class="btn btn-primary" onclick="clearAllFilters()">
                        <i class="fas fa-filter me-1"></i> Clear Filters
                    </button>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if(isset($activities) && method_exists($activities, 'links'))
        <div class="card-footer bg-white border-top">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Activity Details Modal -->
<div class="modal fade" id="activityDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="activityDetailsContent">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Helper functions
function getActivityColor(status) {
    const colors = {
        'success': 'success',
        'warning': 'warning',
        'error': 'danger',
        'info': 'info'
    };
    return colors[status] || 'info';
}

function getActivityIcon(type) {
    const icons = {
        'login': 'sign-in-alt',
        'logout': 'sign-out-alt',
        'user': 'user',
        'admin': 'user-shield',
        'system': 'cog',
        'security': 'shield-alt',
        'content': 'book'
    };
    return icons[type] || 'info-circle';
}

// Filter functions
function applyFilters() {
    const dateRange = document.getElementById('dateRange').value;
    const activityType = document.getElementById('activityType').value;
    const userSearch = document.getElementById('userSearch').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const url = new URL(window.location);
    if (dateRange) url.searchParams.set('date_range', dateRange);
    if (activityType) url.searchParams.set('type', activityType);
    if (userSearch) url.searchParams.set('user', userSearch);
    if (statusFilter) url.searchParams.set('status', statusFilter);
    
    window.location.href = url.toString();
}

function clearAllFilters() {
    document.getElementById('dateRange').value = '7d';
    document.getElementById('activityType').value = '';
    document.getElementById('userSearch').value = '';
    document.getElementById('statusFilter').value = '';
    
    window.location.href = window.location.pathname;
}

function refreshTimeline() {
    window.location.reload();
}

// Activity management
function exportActivityLog() {
    const url = new URL(window.location);
    url.searchParams.set('export', 'csv');
    window.location.href = url.toString();
}

function clearActivityLog() {
    if (confirm('Are you sure you want to clear the entire activity log? This action cannot be undone.')) {
        fetch('/admin/activity/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Activity log cleared successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                alert('Error clearing activity log');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error clearing activity log');
        });
    }
}

function viewActivityDetails(activityId) {
    // Simple implementation - you can enhance this
    alert('Activity details for ID: ' + activityId);
}
</script>
@endpush

@php
// Helper functions for Blade template
function getActivityColor($status) {
    $colors = [
        'success' => 'success',
        'warning' => 'warning',
        'error' => 'danger',
        'info' => 'info'
    ];
    return $colors[$status] ?? 'info';
}

function getActivityIcon($type) {
    $icons = [
        'login' => 'sign-in-alt',
        'logout' => 'sign-out-alt',
        'user' => 'user',
        'admin' => 'user-shield',
        'system' => 'cog',
        'security' => 'shield-alt',
        'content' => 'book'
    ];
    return $icons[$type] ?? 'info-circle';
}
@endphp
