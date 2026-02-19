@extends('layouts.admin')

@section('title', 'Activity Log - E-Library')

@section('page-title', 'Activity Log')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">1,247</h3>
                        <p class="stat-label mb-1">Total Activities</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +18% this week
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
                        <h3 class="stat-value">89</h3>
                        <p class="stat-label mb-1">User Logins</p>
                        <small class="text-muted">Last 24 hours</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">12</h3>
                        <p class="stat-label mb-1">Failed Attempts</p>
                        <small class="text-muted">Security alerts</small>
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
                        <p class="stat-label mb-1">Avg Response Time</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-down"></i> -15% faster
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select">
                        <option>Last 24 hours</option>
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 3 months</option>
                        <option>Custom range</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Activity Type</label>
                    <select class="form-select">
                        <option>All Activities</option>
                        <option>User Actions</option>
                        <option>System Events</option>
                        <option>Security Events</option>
                        <option>Admin Actions</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control" placeholder="Search by user...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            Filter
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-download me-1"></i>
                            Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>
                Recent Activities
            </h5>
        </div>
        <div class="card-body">
            <div class="activity-timeline">
                <!-- Today -->
                <div class="timeline-group">
                    <div class="timeline-date">Today</div>
                    
                    <div class="activity-item">
                        <div class="activity-dot bg-success"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">User Login</h6>
                                <small class="activity-time">2 hours ago</small>
                            </div>
                            <p class="activity-description">Alice Reader logged in from IP 192.168.1.100</p>
                            <div class="activity-meta">
                                <span class="badge bg-success">Success</span>
                                <span class="text-muted">User: alice@elibrary.com</span>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot bg-primary"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">Book Published</h6>
                                <small class="activity-time">5 hours ago</small>
                            </div>
                            <p class="activity-description">John Author published "Advanced Laravel Development"</p>
                            <div class="activity-meta">
                                <span class="badge bg-primary">Content</span>
                                <span class="text-muted">Author: john@elibrary.com</span>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot bg-warning"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">Settings Updated</h6>
                                <small class="activity-time">8 hours ago</small>
                            </div>
                            <p class="activity-description">Admin User updated system settings</p>
                            <div class="activity-meta">
                                <span class="badge bg-warning">Admin</span>
                                <span class="text-muted">User: admin@elibrary.com</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yesterday -->
                <div class="timeline-group">
                    <div class="timeline-date">Yesterday</div>
                    
                    <div class="activity-item">
                        <div class="activity-dot bg-danger"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">Failed Login Attempt</h6>
                                <small class="activity-time">1 day ago</small>
                            </div>
                            <p class="activity-description">Failed login attempt for admin@elibrary.com from IP 192.168.1.200</p>
                            <div class="activity-meta">
                                <span class="badge bg-danger">Security</span>
                                <span class="text-muted">Reason: Invalid password</span>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot bg-info"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">Book Downloaded</h6>
                                <small class="activity-time">1 day ago</small>
                            </div>
                            <p class="activity-description">Bob Reader downloaded "Modern Web Design"</p>
                            <div class="activity-meta">
                                <span class="badge bg-info">User Action</span>
                                <span class="text-muted">User: bob@elibrary.com</span>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot bg-secondary"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">System Backup</h6>
                                <small class="activity-time">1 day ago</small>
                            </div>
                            <p class="activity-description">Automatic system backup completed successfully</p>
                            <div class="activity-meta">
                                <span class="badge bg-secondary">System</span>
                                <span class="text-muted">Size: 2.3GB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2 Days Ago -->
                <div class="timeline-group">
                    <div class="timeline-date">2 Days Ago</div>
                    
                    <div class="activity-item">
                        <div class="activity-dot bg-success"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">User Registration</h6>
                                <small class="activity-time">2 days ago</small>
                            </div>
                            <p class="activity-description">New user Charlie Reader registered</p>
                            <div class="activity-meta">
                                <span class="badge bg-success">User</span>
                                <span class="text-muted">Email: charlie@elibrary.com</span>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot bg-primary"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <h6 class="activity-title">Role Modified</h6>
                                <small class="activity-time">2 days ago</small>
                            </div>
                            <p class="activity-description">Bob Reader's role changed from Reader to Author</p>
                            <div class="activity-meta">
                                <span class="badge bg-primary">Admin Action</span>
                                <span class="text-muted">Modified by: admin@elibrary.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
