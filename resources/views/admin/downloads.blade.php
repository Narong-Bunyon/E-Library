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
                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">A</div>
                                    <div>
                                        <div class="fw-semibold">Alice Reader</div>
                                        <small class="text-muted">alice@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Advanced Laravel Development</div>
                                        <small class="text-muted">by John Author</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary">PDF</span></td>
                            <td>15.2 MB</td>
                            <td>2 hours ago</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Re-download">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">B</div>
                                    <div>
                                        <div class="fw-semibold">Bob Reader</div>
                                        <small class="text-muted">bob@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Modern Web Design</div>
                                        <small class="text-muted">by Jane Designer</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-success">EPUB</span></td>
                            <td>8.7 MB</td>
                            <td>5 hours ago</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Re-download">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">C</div>
                                    <div>
                                        <div class="fw-semibold">Charlie Reader</div>
                                        <small class="text-muted">charlie@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Database Management</div>
                                        <small class="text-muted">by Mike Developer</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">MOBI</span></td>
                            <td>12.4 MB</td>
                            <td>1 day ago</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Re-download">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">D</div>
                                    <div>
                                        <div class="fw-semibold">Diana Reader</div>
                                        <small class="text-muted">diana@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-info"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">JavaScript Fundamentals</div>
                                        <small class="text-muted">by Sarah Developer</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-warning">ZIP</span></td>
                            <td>45.8 MB</td>
                            <td>2 days ago</td>
                            <td><span class="badge bg-warning">In Progress</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-success" title="Resume Download">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Cancel Download">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">E</div>
                                    <div>
                                        <div class="fw-semibold">Eva Reader</div>
                                        <small class="text-muted">eva@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">CSS Masterclass</div>
                                        <small class="text-muted">by Tom Designer</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary">PDF</span></td>
                            <td>22.1 MB</td>
                            <td>3 days ago</td>
                            <td><span class="badge bg-danger">Failed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Retry Download">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Clear Failed">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="form-check-input"></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">F</div>
                                    <div>
                                        <div class="fw-semibold">Frank Reader</div>
                                        <small class="text-muted">frank@elibrary.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-file-archive text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Complete Library Pack</div>
                                        <small class="text-muted">Multiple Books</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary">ZIP</span></td>
                            <td>156.7 MB</td>
                            <td>1 week ago</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="icon-btn text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-btn text-warning" title="Re-download">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
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
@endsection
