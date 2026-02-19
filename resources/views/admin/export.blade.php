@extends('layouts.admin')

@section('title', 'Data Export - E-Library')

@section('page-title', 'Data Export')

@section('content')
<div class="container-fluid">
    <!-- Export Options -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-file-export me-2"></i>
                Data Export Center
            </h5>
        </div>
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Export Type</label>
                    <select class="form-select" id="exportType">
                        <option value="">Select Export Type</option>
                        <option value="users">Users Data</option>
                        <option value="books">Books Data</option>
                        <option value="categories">Categories Data</option>
                        <option value="reviews">Reviews Data</option>
                        <option value="downloads">Download History</option>
                        <option value="reading-progress">Reading Progress</option>
                        <option value="favorites">Favorites Data</option>
                        <option value="activity">Activity Log</option>
                        <option value="reports">Generated Reports</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" id="dateRange">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Format</label>
                    <select class="form-select" id="exportFormat">
                        <option value="csv">CSV (Comma Separated)</option>
                        <option value="excel">Excel Spreadsheet</option>
                        <option value="json">JSON Data</option>
                        <option value="xml">XML Format</option>
                        <option value="pdf">PDF Report</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Include Fields</label>
                    <div class="field-selection">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeId" checked>
                            <label class="form-check-label" for="includeId">ID Fields</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeDates" checked>
                            <label class="form-check-label" for="includeDates">Date Fields</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeStatus" checked>
                            <label class="form-check-label" for="includeStatus">Status Fields</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeRelations" checked>
                            <label class="form-check-label" for="includeRelations">Related Data</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Custom Date Range</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="startDate" placeholder="Start Date">
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="endDate" placeholder="End Date">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i>
                            Export Data
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="previewExport()">
                            <i class="fas fa-eye me-1"></i>
                            Preview Export
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="scheduleExport()">
                            <i class="fas fa-clock me-1"></i>
                            Schedule Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export History -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>
                Recent Exports
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Export Type</th>
                            <th>Records</th>
                            <th>Format</th>
                            <th>Generated By</th>
                            <th>Date</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="export-icon bg-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="export-name">Users Data</h6>
                                        <small class="text-muted">Complete user export</small>
                                    </div>
                                </div>
                            </td>
                            <td>1,247</td>
                            <td><span class="badge bg-success">Excel</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Admin User</div>
                                        <small class="text-muted">admin@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>2 hours ago</td>
                            <td>2.3 MB</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="export-icon bg-success">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="export-name">Books Data</h6>
                                        <small class="text-muted">All books and metadata</small>
                                    </div>
                                </div>
                            </td>
                            <td>156</td>
                            <td><span class="badge bg-primary">CSV</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Admin User</div>
                                        <small class="text-muted">admin@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>1 day ago</td>
                            <td>856 KB</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="export-icon bg-info">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="export-name">Download History</h6>
                                        <small class="text-muted">User download logs</small>
                                    </div>
                                </div>
                            </td>
                            <td>8,942</td>
                            <td><span class="badge bg-warning">JSON</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Admin User</div>
                                        <small class="text-muted">admin@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>3 days ago</td>
                            <td>1.2 MB</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="export-icon bg-warning">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="export-name">Activity Log</h6>
                                        <small class="text-muted">System activity logs</small>
                                    </div>
                                </div>
                            </td>
                            <td>3,456</td>
                            <td><span class="badge bg-secondary">PDF</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Admin User</div>
                                        <small class="text-muted">admin@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>1 week ago</td>
                            <td>3.4 MB</td>
                            <td><span class="badge bg-warning">Processing</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Retry">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Cancel">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Export Statistics -->
    <div class="row mt-4">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Export Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="export-stats">
                        <div class="stat-item">
                            <div class="stat-value">24</div>
                            <div class="stat-label">Total Exports</div>
                            <div class="stat-period">This month</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">156</div>
                            <div class="stat-label">Files Downloaded</div>
                            <div class="stat-period">All time</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">89</div>
                            <div class="stat-label">Active Users</div>
                            <div class="stat-period">Using exports</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">2.3s</div>
                            <div class="stat-label">Avg Export Time</div>
                            <div class="stat-period">Per operation</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-hdd me-2"></i>
                        Storage Usage
                    </h5>
                </div>
                <div class="card-body">
                    <div class="storage-info">
                        <div class="storage-item">
                            <div class="storage-label">Export Files</div>
                            <div class="storage-value">456 MB</div>
                        </div>
                        <div class="storage-item">
                            <div class="storage-label">Temporary Files</div>
                            <div class="storage-value">89 MB</div>
                        </div>
                        <div class="storage-item">
                            <div class="storage-label">Available Space</div>
                            <div class="storage-value">2.1 GB</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Processing Queue
                    </h5>
                </div>
                <div class="card-body">
                    <div class="queue-info">
                        <div class="queue-item">
                            <div class="queue-status success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="queue-details">
                                <div class="queue-title">No pending exports</div>
                                <small class="queue-time">Last checked: 2 min ago</small>
                            </div>
                        </div>
                        <div class="queue-item">
                            <div class="queue-status idle">
                                <i class="fas fa-pause-circle"></i>
                            </div>
                            <div class="queue-details">
                                <div class="queue-title">Export system idle</div>
                                <small class="queue-time">Ready for new exports</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewExport() {
    const exportType = document.getElementById('exportType').value;
    const dateRange = document.getElementById('dateRange').value;
    const format = document.getElementById('exportFormat').value;
    
    if (!exportType) {
        alert('Please select an export type');
        return;
    }
    
    // Show preview modal or notification
    alert(`Preview: Exporting ${exportType} data for ${dateRange} in ${format} format`);
}

function scheduleExport() {
    const exportType = document.getElementById('exportType').value;
    const dateRange = document.getElementById('dateRange').value;
    
    if (!exportType) {
        alert('Please select an export type');
        return;
    }
    
    // Show scheduling modal or notification
    alert(`Schedule: ${exportType} export for ${dateRange} will be scheduled`);
}
</script>
@endsection
