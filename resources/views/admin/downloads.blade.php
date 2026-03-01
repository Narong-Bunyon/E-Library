@extends('layouts.admin')

@section('title', 'Downloads Management - E-Library')

@section('page-title', 'Downloads Management')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">8,942</h3>
                        <p class="stat-label mb-1">Total Downloads</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +24% this month
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card success">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">234</h3>
                        <p class="stat-label mb-1">Unique Users</p>
                        <small class="text-muted">Downloaded this month</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">156</h3>
                        <p class="stat-label mb-1">Books Downloaded</p>
                        <small class="text-muted">Unique titles</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card info">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info text-white">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">2.3GB</h3>
                        <p class="stat-label mb-1">Bandwidth Used</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +18% this month
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Downloads Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-download me-2"></i>
                Recent Downloads
            </h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm">
                    <option>All Downloads</option>
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>Last 3 Months</option>
                </select>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-file-export me-1"></i>
                    Export Report
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input">
                            </th>
                            <th>User</th>
                            <th>Book</th>
                            <th>File Type</th>
                            <th>File Size</th>
                            <th>Download Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($downloads as $download)
                        <tr>
                            <td><input type="checkbox" class="form-check-input" value="{{ $download->id }}"></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">{{ substr($download->user->name ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $download->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $download->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $download->book->title ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $download->book->author->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary">{{ strtoupper($download->file_type ?? 'PDF') }}</span></td>
                            <td>{{ $download->file_size ?? '0 MB' }}</td>
                            <td>{{ $download->created_at ? \Carbon\Carbon::parse($download->created_at)->diffForHumans() : 'N/A' }}</td>
                            <td><span class="badge bg-{{ $download->status == 'completed' ? 'success' : ($download->status == 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($download->status ?? 'Unknown') }}</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details" onclick="viewDownloadDetails({{ $download->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Re-download" onclick="reDownload({{ $download->id }})">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete" onclick="deleteDownload({{ $download->id }}, '{{ $download->book->title ?? 'Unknown Book' }}', '{{ $download->user->name ?? 'Unknown User' }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No downloads found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Download Statistics -->
    <div class="row mt-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        File Type Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="file-type-stats">
                        <div class="file-type-item">
                            <div class="file-type-icon bg-primary">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="file-type-info">
                                <h6 class="file-type-name">PDF</h6>
                                <div class="file-type-details">
                                    <span class="count">342</span> downloads
                                    <span class="percentage">45%</span>
                                </div>
                            </div>
                        </div>

                        <div class="file-type-item">
                            <div class="file-type-icon bg-success">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="file-type-info">
                                <h6 class="file-type-name">EPUB</h6>
                                <div class="file-type-details">
                                    <span class="count">287</span> downloads
                                    <span class="percentage">38%</span>
                                </div>
                            </div>
                        </div>

                        <div class="file-type-item">
                            <div class="file-type-icon bg-info">
                                <i class="fas fa-file-archive"></i>
                            </div>
                            <div class="file-type-info">
                                <h6 class="file-type-name">MOBI</h6>
                                <div class="file-type-details">
                                    <span class="count">89</span> downloads
                                    <span class="percentage">12%</span>
                                </div>
                            </div>
                        </div>

                        <div class="file-type-item">
                            <div class="file-type-icon bg-warning">
                                <i class="fas fa-file-word"></i>
                            </div>
                            <div class="file-type-info">
                                <h6 class="file-type-name">Other</h6>
                                <div class="file-type-details">
                                    <span class="count">45</span> downloads
                                    <span class="percentage">5%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Bandwidth Usage
                    </h5>
                </div>
                <div class="card-body">
                    <div class="bandwidth-info">
                        <div class="bandwidth-item">
                            <div class="bandwidth-label">This Month</div>
                            <div class="bandwidth-value">2.3 GB</div>
                            <div class="bandwidth-bar">
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 65%"></div>
                                </div>
                                <span class="bandwidth-limit">3.5 GB limit</span>
                            </div>
                        </div>
                        <div class="bandwidth-item">
                            <div class="bandwidth-label">Last Month</div>
                            <div class="bandwidth-value">1.9 GB</div>
                            <div class="bandwidth-bar">
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 54%"></div>
                                </div>
                                <span class="bandwidth-limit">3.5 GB limit</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Download Details Modal -->
<div class="modal fade" id="viewDownloadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye text-info me-2"></i>
                    Download Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="downloadDetails">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Download Modal -->
<div class="modal fade" id="deleteDownloadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Delete Download
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <i class="fas fa-trash text-danger fa-3x mb-3"></i>
                    <h6 id="deleteDownloadMessage">Are you sure? You want to delete this download?</h6>
                    <p id="deleteDownloadDescription">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button class="btn btn-danger" id="confirmDeleteDownloadBtn">
                    <i class="fas fa-trash me-2"></i>Delete Download
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// View download details
function viewDownloadDetails(id) {
    fetch(`/admin/downloads/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>User:</strong> ${data.download.user?.name || 'N/A'} (${data.download.user?.email || 'N/A'})<br>
                            <strong>Book:</strong> ${data.download.book?.title || 'N/A'}<br>
                            <strong>Author:</strong> ${data.download.book?.author?.name || 'N/A'}<br>
                            <strong>File Type:</strong> ${data.download.file_type || 'N/A'}<br>
                            <strong>File Size:</strong> ${data.download.file_size || 'N/A'}<br>
                            <strong>Status:</strong> ${data.download.status || 'N/A'}<br>
                            <strong>Download Date:</strong> ${data.download.created_at ? new Date(data.download.created_at).toLocaleString() : 'N/A'}<br>
                            <strong>IP Address:</strong> ${data.download.ip_address || 'N/A'}
                        </div>
                        <div class="col-md-6">
                            <strong>User Agent:</strong><br>
                            <p class="text-muted small">${data.download.user_agent || 'N/A'}</p>
                            <strong>Download Path:</strong><br>
                            <p class="text-muted small">${data.download.file_path || 'N/A'}</p>
                            <strong>Reference:</strong><br>
                            <p class="text-muted small">${data.download.reference_id || 'N/A'}</p>
                        </div>
                    </div>
                `;
                document.getElementById('downloadDetails').innerHTML = details;
                new bootstrap.Modal(document.getElementById('viewDownloadModal')).show();
            } else {
                showNotification('Error loading download details', 'error');
            }
        })
        .catch(error => {
            console.error('Error fetching download details:', error);
            showNotification('Error loading download details', 'error');
        });
}

// Re-download function
function reDownload(id) {
    if (confirm('Are you sure you want to re-download this file?')) {
        fetch(`/admin/downloads/${id}/re-download`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Download started successfully', 'success');
                // Optionally trigger file download
                if (data.download_url) {
                    window.location.href = data.download_url;
                }
            } else {
                showNotification(data.message || 'Error starting download', 'error');
            }
        })
        .catch(error => {
            console.error('Error starting download:', error);
            showNotification('Error starting download', 'error');
        });
    }
}

// Delete download with modal
function deleteDownload(id, bookTitle, userName) {
    const modal = new bootstrap.Modal(document.getElementById('deleteDownloadModal'));
    document.getElementById('deleteDownloadMessage').textContent = 
        `Are you sure? You want to delete this download?`;
    document.getElementById('deleteDownloadDescription').textContent = 
        `The download for "${bookTitle}" by ${userName} will be permanently deleted. This action cannot be undone.`;
    
    document.getElementById('confirmDeleteDownloadBtn').onclick = function() {
        modal.hide();
        performDeleteDownload(id);
    };
    
    modal.show();
}

// Perform the actual delete
function performDeleteDownload(id) {
    fetch(`/admin/downloads/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Download deleted successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Error deleting download', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting download:', error);
        showNotification('Error deleting download', 'error');
    });
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
</script>
@endpush

@endsection
