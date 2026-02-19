@extends('layouts.admin')

@section('title', 'Roles & Permissions - E-Library')

@section('page-title', 'Roles & Permissions')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">3</h3>
                        <p class="stat-label mb-1">Total Roles</p>
                        <small class="text-muted">System roles</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card success">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">24</h3>
                        <p class="stat-label mb-1">Permissions</p>
                        <small class="text-muted">System permissions</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">12</h3>
                        <p class="stat-label mb-1">Role Assignments</p>
                        <small class="text-muted">Custom assignments</small>
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
                        <h3 class="stat-value">5</h3>
                        <p class="stat-label mb-1">Recent Changes</p>
                        <small class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> This week
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-shield me-2"></i>
                        System Roles
                    </h5>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        Add Role
                    </button>
                </div>
                <div class="card-body">
                    <div class="role-list">
                        <div class="role-item">
                            <div class="role-info">
                                <div class="role-header">
                                    <h6 class="role-name">Administrator</h6>
                                    <span class="badge bg-danger">Super Admin</span>
                                </div>
                                <p class="role-description">Full system access with all permissions</p>
                                <div class="role-stats">
                                    <span class="stat-item">
                                        <i class="fas fa-users me-1"></i> 1 user
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-key me-1"></i> 24 permissions
                                    </span>
                                </div>
                            </div>
                            <div class="role-actions">
                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                <button class="btn btn-sm btn-outline-secondary">View</button>
                            </div>
                        </div>

                        <div class="role-item">
                            <div class="role-info">
                                <div class="role-header">
                                    <h6 class="role-name">Author</h6>
                                    <span class="badge bg-warning">Content Creator</span>
                                </div>
                                <p class="role-description">Can create, edit, and manage own books</p>
                                <div class="role-stats">
                                    <span class="stat-item">
                                        <i class="fas fa-users me-1"></i> 3 users
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-key me-1"></i> 12 permissions
                                    </span>
                                </div>
                            </div>
                            <div class="role-actions">
                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                <button class="btn btn-sm btn-outline-secondary">View</button>
                            </div>
                        </div>

                        <div class="role-item">
                            <div class="role-info">
                                <div class="role-header">
                                    <h6 class="role-name">Reader</h6>
                                    <span class="badge bg-success">User</span>
                                </div>
                                <p class="role-description">Can read books and manage personal library</p>
                                <div class="role-stats">
                                    <span class="stat-item">
                                        <i class="fas fa-users me-1"></i> 4 users
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-key me-1"></i> 8 permissions
                                    </span>
                                </div>
                            </div>
                            <div class="role-actions">
                                <button class="btn btn-sm btn-outline-primary">Edit</button>
                                <button class="btn btn-sm btn-outline-secondary">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i>
                        Permissions Matrix
                    </h5>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>
                </div>
                <div class="card-body">
                    <div class="permissions-matrix">
                        <div class="matrix-header">
                            <div class="permission-category">Permission</div>
                            <div class="role-col">Admin</div>
                            <div class="role-col">Author</div>
                            <div class="role-col">Reader</div>
                        </div>

                        <div class="matrix-row">
                            <div class="permission-name">Create Books</div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                        </div>

                        <div class="matrix-row">
                            <div class="permission-name">Edit Books</div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                        </div>

                        <div class="matrix-row">
                            <div class="permission-name">Delete Books</div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                        </div>

                        <div class="matrix-row">
                            <div class="permission-name">Manage Users</div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                        </div>

                        <div class="matrix-row">
                            <div class="permission-name">View Analytics</div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-minus text-warning"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                        </div>

                        <div class="matrix-row">
                            <div class="permission-name">System Settings</div>
                            <div class="permission-cell">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                            <div class="permission-cell">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Permission Changes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Recent Permission Changes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Role</th>
                                    <th>Changed By</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">A</div>
                                            Alice Reader
                                        </div>
                                    </td>
                                    <td>Role assigned</td>
                                    <td><span class="badge bg-success">Reader</span></td>
                                    <td>Admin User</td>
                                    <td>2 hours ago</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">B</div>
                                            Bob Reader
                                        </div>
                                    </td>
                                    <td>Permission granted</td>
                                    <td><span class="badge bg-warning">Author</span></td>
                                    <td>Admin User</td>
                                    <td>1 day ago</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">J</div>
                                            John Author
                                        </div>
                                    </td>
                                    <td>Permission modified</td>
                                    <td><span class="badge bg-warning">Author</span></td>
                                    <td>Admin User</td>
                                    <td>3 days ago</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
