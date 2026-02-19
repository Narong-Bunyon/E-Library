@extends('layouts.admin')

@section('title', 'Reports - E-Library')

@section('page-title', 'Reports & Analytics')

@section('content')
<div class="container-fluid">
    <!-- Report Generation Options -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-bar me-2"></i>
                Generate Report
            </h5>
        </div>
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Report Type</label>
                    <select class="form-select">
                        <option>Select Report Type</option>
                        <option>User Activity Report</option>
                        <option>Book Performance Report</option>
                        <option>Download Statistics Report</option>
                        <option>Reading Progress Report</option>
                        <option>Revenue Report</option>
                        <option>System Usage Report</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date Range</label>
                    <select class="form-select">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>Last 3 Months</option>
                        <option>Last 6 Months</option>
                        <option>Last Year</option>
                        <option>Custom Range</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Format</label>
                    <select class="form-select">
                        <option>PDF Report</option>
                        <option>Excel Spreadsheet</option>
                        <option>CSV Data</option>
                        <option>HTML Report</option>
                        <option>Printable Format</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-export me-1"></i>
                            Generate Report
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-clock me-1"></i>
                            Schedule Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">24</h3>
                        <p class="stat-label mb-1">Reports Generated</p>
                        <small class="text-muted">This month</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card success">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">156</h3>
                        <p class="stat-label mb-1">Reports Downloaded</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +18% this month
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">89</h3>
                        <p class="stat-label mb-1">Active Users</p>
                        <small class="text-muted">Using reports</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card info">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info text-white">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">2.3s</h3>
                        <p class="stat-label mb-1">Avg Generation Time</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-down"></i> -15% faster
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>
                Recent Reports
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
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Admin User</div>
                                        <small class="text-muted">admin@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>2 hours ago</td>
                            <td><span class="badge bg-success">PDF</span></td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Share">
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
                                        <h6 class="report-name">Book Performance Report</h6>
                                        <small class="text-muted">Most popular books analysis</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-success">Performance</span></td>
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
                            <td><span class="badge bg-warning">Excel</span></td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Share">
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
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Admin User</div>
                                        <small class="text-muted">admin@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>3 days ago</td>
                            <td><span class="badge bg-success">PDF</span></td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Share">
                                        <i class="fas fa-share"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="report-icon bg-warning">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="report-name">Revenue Report</h6>
                                        <small class="text-muted">Monthly earnings analysis</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-warning">Financial</span></td>
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
                            <td><span class="badge bg-warning">Excel</span></td>
                            <td><span class="badge bg-warning">Processing</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
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
                                    <div class="report-icon bg-secondary">
                                        <i class="fas fa-server"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="report-name">System Usage Report</h6>
                                        <small class="text-muted">Server performance metrics</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary">System</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Admin User</div>
                                        <small class="text-muted">admin@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>2 weeks ago</td>
                            <td><span class="badge bg-success">HTML</span></td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Share">
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

    <!-- Scheduled Reports -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-clock me-2"></i>
                Scheduled Reports
            </h5>
        </div>
        <div class="card-body">
            <div class="scheduled-reports">
                <div class="scheduled-report-item">
                    <div class="report-info">
                        <h6 class="report-title">Weekly User Activity</h6>
                        <p class="report-description">Every Monday at 9:00 AM</p>
                        <div class="report-meta">
                            <span class="badge bg-success">Active</span>
                            <small class="text-muted">Next: 2 days</small>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-warning">Pause</button>
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </div>
                </div>

                <div class="scheduled-report-item">
                    <div class="report-info">
                        <h6 class="report-title">Monthly Performance</h6>
                        <p class="report-description">1st of every month at 8:00 AM</p>
                        <div class="report-meta">
                            <span class="badge bg-success">Active</span>
                            <small class="text-muted">Next: 15 days</small>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-warning">Pause</button>
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </div>
                </div>

                <div class="scheduled-report-item">
                    <div class="report-info">
                        <h6 class="report-title">Quarterly Revenue</h6>
                        <p class="report-description">Every quarter start at 10:00 AM</p>
                        <div class="report-meta">
                            <span class="badge bg-warning">Paused</span>
                            <small class="text-muted">Next: 45 days</small>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-success">Resume</button>
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
