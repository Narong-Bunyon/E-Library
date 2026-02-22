@extends('layouts.admin')

@section('title', 'Manage Users - E-Library')

@section('page-title', 'Manage Users')

@section('content')
<div class="users-container">
    <!-- Header Section -->
    <div class="users-header">
        <div>
            <h4>
                <i class="fas fa-users me-2"></i>
                Users Management
            </h4>
            <p>Manage user accounts, roles, and permissions</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus me-1"></i>
                Add New User
            </button>
            <button class="btn btn-outline-secondary" onclick="exportUsers()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\User::count() }}</div>
                    <div class="stats-label">Total Users</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\User::where('status', 'active')->count() }}</div>
                    <div class="stats-label">Active Users</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\User::where('role', 'admin')->count() }}</div>
                    <div class="stats-label">Administrators</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\User::whereDate('created_at', today())->count() }}</div>
                    <div class="stats-label">New Today</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                        <button class="btn" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" id="roleFilter" onchange="filterTable()">
                        <option value="">All Roles</option>
                        <option value="admin">Administrator</option>
                        <option value="author">Author</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter" onchange="filterTable()">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select class="form-select" id="sortBy" onchange="sortTable()">
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="role">Role</option>
                        <option value="created_at">Join Date</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-card" id="usersTableContainer">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover modern-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>User</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        @if($user->id !== auth()->user()->id)
                        <tr data-role="{{ $user->role }}" data-status="{{ $user->status ?? 'active' }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                            <td>
                                <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                        <small class="text-muted">ID: #{{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        <small>{{ $user->email }}</small>
                                    </div>
                                    @if($user->phone)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        <small>{{ $user->phone }}</small>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="role-badge {{ $user->role }}">
                                    @switch($user->role)
                                        @case('admin')
                                            <i class="fas fa-user-shield me-1"></i>
                                            Administrator
                                        @break
                                        @case('author')
                                            <i class="fas fa-user-edit me-1"></i>
                                            Author
                                        @break
                                        @default
                                            <i class="fas fa-user me-1"></i>
                                            User
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->status ?? 'active' }}">
                                    @switch($user->status ?? 'active')
                                        @case('active')
                                            <i class="fas fa-check-circle me-1"></i>
                                            Active
                                        @break
                                        @case('inactive')
                                            <i class="fas fa-pause-circle me-1"></i>
                                            Inactive
                                        @break
                                        @case('suspended')
                                            <i class="fas fa-ban me-1"></i>
                                            Suspended
                                        @break
                                        @default
                                            <i class="fas fa-check-circle me-1"></i>
                                            Active
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <div class="join-info">
                                    <div class="text-dark">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewUser({{ $user->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="editUser({{ $user->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="toggleUserStatus({{ $user->id }})" title="Toggle Status">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteUser({{ $user->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-4x mb-3"></i>
                                    <p class="mb-0">No users found</p>
                                    <button class="btn mt-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                        <i class="fas fa-plus me-1"></i>
                                        Create First User
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4" id="paginationContainer">
            <div class="pagination-info">
                <i class="fas fa-info-circle me-2"></i>
                Showing <span class="fw-semibold">{{ $users->firstItem() }}</span> to 
                <span class="fw-semibold">{{ $users->lastItem() }}</span> of 
                <span class="fw-semibold">{{ $users->total() }}</span> users
            </div>
            <div class="pagination-wrapper">
                {{ $users->links('pagination') }}
            </div>
        </div>
    @endif
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Create New User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                @csrf
                <div class="modal-body">
                    <!-- User Information Section -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-user me-2"></i>
                            User Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-signature me-1 text-muted"></i>
                                        Full Name *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" name="name" required 
                                               placeholder="Enter full name"
                                               pattern="[A-Za-z\s]{3,50}"
                                               title="Name should be 3-50 characters, letters only">
                                    </div>
                                    <div class="form-text">Enter the user's full name (3-50 characters)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        Email Address *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control" name="email" required 
                                               placeholder="user@example.com">
                                    </div>
                                    <div class="form-text">This will be used for login</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-shield-alt me-2"></i>
                            Security Settings
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-lock me-1 text-muted"></i>
                                        Password *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" name="password" required 
                                               id="password"
                                               placeholder="Enter strong password"
                                               minlength="8"
                                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                               title="Password must contain at least 8 characters, one uppercase, one lowercase, and one number">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-toggle"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Min 8 characters with uppercase, lowercase, and numbers</div>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar" id="password-strength" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-lock me-1 text-muted"></i>
                                        Confirm Password *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" name="password_confirmation" required 
                                               id="password_confirmation"
                                               placeholder="Confirm password"
                                               oninput="checkPasswordMatch()">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="password_confirmation-toggle"></i>
                                        </button>
                                    </div>
                                    <div class="form-text" id="password-match-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role & Contact Section -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-user-cog me-2"></i>
                            Role & Contact
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user-tag me-1 text-muted"></i>
                                        User Role *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-tag"></i>
                                        </span>
                                        <select class="form-select" name="role" required id="roleSelect">
                                            <option value="">Select Role</option>
                                            <option value="admin">
                                                Administrator
                                            </option>
                                            <option value="author">
                                                Author
                                            </option>
                                            <option value="user">
                                                User
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-text" id="role-description">Select the appropriate role for this user</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-phone me-1 text-muted"></i>
                                        Phone Number
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" class="form-control" name="phone" 
                                               placeholder="+1 (555) 123-4567"
                                               pattern="[+]?[0-9\s\-\(\)]+"
                                               title="Enter a valid phone number">
                                    </div>
                                    <div class="form-text">Optional: Enter contact number</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-3">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Additional Information
                        </h6>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <textarea class="form-control" name="address" rows="2" 
                                          placeholder="Enter address (optional)"
                                          maxlength="200"></textarea>
                            </div>
                            <div class="form-text">Optional: User's address (max 200 characters)</div>
                        </div>
                    </div>

                    <!-- Account Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-toggle-on me-1 text-muted"></i>
                                    Account Status
                                </label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" checked>
                                    <label class="form-check-label" for="statusSwitch">
                                        <span id="statusText">Active</span>
                                    </label>
                                </div>
                                <div class="form-text">New accounts are active by default</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-1 text-muted"></i>
                                    Email Verification
                                </label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="email_verified" id="emailVerifiedSwitch">
                                    <label class="form-check-label" for="emailVerifiedSwitch">
                                        Mark as Verified
                                    </label>
                                </div>
                                <div class="form-text">Skip email verification for this user</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="createUserBtn">
                        <i class="fas fa-save me-1"></i>
                        <span id="createBtnText">Create User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.update', ':id') }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" id="editName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" id="editEmail" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="editPassword" placeholder="Leave blank to keep current">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="editPasswordConfirmation" placeholder="Leave blank to keep current">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role *</label>
                                <select class="form-select" name="role" id="editRole" required>
                                    <option value="admin">Administrator</option>
                                    <option value="author">Author</option>
                                    <option value="reader">Reader</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="editPhone" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="editAddress" rows="2" placeholder="Optional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetails">
                <!-- User details will be loaded here -->
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
document.addEventListener('DOMContentLoaded', function() {
    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            loadUsers(url);
        }
    });
    
    // Handle filter changes
    document.getElementById('searchInput')?.addEventListener('input', debounce(function() {
        loadUsers(window.location.href);
    }, 500));
    
    document.getElementById('roleFilter')?.addEventListener('change', function() {
        loadUsers(window.location.href);
    });
    
    document.getElementById('statusFilter')?.addEventListener('change', function() {
        loadUsers(window.location.href);
    });
    
    document.getElementById('sortBy')?.addEventListener('change', function() {
        loadUsers(window.location.href);
    });
});

function loadUsers(url) {
    // Show loading state
    const tableContainer = document.getElementById('usersTableContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    
    if (tableContainer) {
        tableContainer.style.opacity = '0.5';
        tableContainer.style.pointerEvents = 'none';
    }
    
    if (paginationContainer) {
        paginationContainer.style.opacity = '0.5';
        paginationContainer.style.pointerEvents = 'none';
    }
    
    // Get current filter values
    const search = document.getElementById('searchInput')?.value || '';
    const role = document.getElementById('roleFilter')?.value || '';
    const status = document.getElementById('statusFilter')?.value || '';
    const sortBy = document.getElementById('sortBy')?.value || '';
    
    // Build URL with parameters
    const urlObj = new URL(url);
    if (search) urlObj.searchParams.set('search', search);
    if (role) urlObj.searchParams.set('role', role);
    if (status) urlObj.searchParams.set('status', status);
    if (sortBy) urlObj.searchParams.set('sort', sortBy);
    
    // Add AJAX parameter to indicate this is an AJAX request
    urlObj.searchParams.set('ajax', '1');
    
    fetch(urlObj.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Create a temporary DOM element to parse the response
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Extract the new table content
        const newTableContainer = tempDiv.querySelector('#usersTableContainer');
        const newPaginationContainer = tempDiv.querySelector('#paginationContainer');
        
        // Update the table
        if (newTableContainer && tableContainer) {
            tableContainer.innerHTML = newTableContainer.innerHTML;
            tableContainer.style.opacity = '1';
            tableContainer.style.pointerEvents = 'auto';
        }
        
        // Update pagination
        if (newPaginationContainer && paginationContainer) {
            paginationContainer.innerHTML = newPaginationContainer.innerHTML;
            paginationContainer.style.opacity = '1';
            paginationContainer.style.pointerEvents = 'auto';
        }
        
        // Update URL in browser without reload
        const newUrl = urlObj.toString().replace('&ajax=1', '').replace('?ajax=1', '');
        history.pushState({}, '', newUrl);
        
        // Reinitialize event listeners for new content
        reinitializeEventListeners();
    })
    .catch(error => {
        console.error('Error loading users:', error);
        // Reset loading state
        if (tableContainer) {
            tableContainer.style.opacity = '1';
            tableContainer.style.pointerEvents = 'auto';
        }
        if (paginationContainer) {
            paginationContainer.style.opacity = '1';
            paginationContainer.style.pointerEvents = 'auto';
        }
    });
}

function reinitializeEventListeners() {
    // Reinitialize any event listeners for the new content
    // This will be called after AJAX updates
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Enhanced Create User Functions
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = document.getElementById(fieldId + '-toggle');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('password-strength');
    let strength = 0;
    
    if (password.length >= 8) strength += 25;
    if (password.match(/[a-z]/)) strength += 25;
    if (password.match(/[A-Z]/)) strength += 25;
    if (password.match(/[0-9]/)) strength += 25;
    
    strengthBar.style.width = strength + '%';
    
    if (strength <= 25) {
        strengthBar.className = 'progress-bar bg-danger';
    } else if (strength <= 50) {
        strengthBar.className = 'progress-bar bg-warning';
    } else if (strength <= 75) {
        strengthBar.className = 'progress-bar bg-info';
    } else {
        strengthBar.className = 'progress-bar bg-success';
    }
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const feedback = document.getElementById('password-match-feedback');
    
    if (confirmPassword === '') {
        feedback.textContent = '';
        feedback.className = 'form-text';
        return;
    }
    
    if (password === confirmPassword) {
        feedback.textContent = '✓ Passwords match';
        feedback.className = 'form-text text-success';
    } else {
        feedback.textContent = '✗ Passwords do not match';
        feedback.className = 'form-text text-danger';
    }
}

function updateRoleDescription() {
    const roleSelect = document.getElementById('roleSelect');
    const roleDescription = document.getElementById('role-description');
    
    const descriptions = {
        'admin': 'Full system access including user management and settings',
        'author': 'Can create, edit, and manage their own books and content',
        'user': 'Standard user access with reading and basic functionality'
    };
    
    if (roleSelect.value && descriptions[roleSelect.value]) {
        roleDescription.textContent = descriptions[roleSelect.value];
        roleDescription.className = 'form-text text-primary';
    } else {
        roleDescription.textContent = 'Select the appropriate role for this user';
        roleDescription.className = 'form-text';
    }
}

function toggleStatus() {
    const statusSwitch = document.getElementById('statusSwitch');
    const statusText = document.getElementById('statusText');
    
    if (statusSwitch.checked) {
        statusText.textContent = 'Active';
        statusText.className = 'text-success';
    } else {
        statusText.textContent = 'Inactive';
        statusText.className = 'text-danger';
    }
}

// Form validation and submission
document.getElementById('createUserForm')?.addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent normal form submission
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const createBtn = document.getElementById('createUserBtn');
    const createBtnText = document.getElementById('createBtnText');
    
    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return false;
    }
    
    if (password.length < 8) {
        alert('Password must be at least 8 characters long!');
        return false;
    }
    
    // Show loading state
    createBtn.disabled = true;
    createBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating User...';
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit via AJAX
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showSuccessMessage('User created successfully!');
            
            // Redirect to users page
            setTimeout(() => {
                window.location.href = '/admin/users';
            }, 1500); // Wait 1.5 seconds to show notification
            
        } else {
            // Show errors
            if (data.errors) {
                let errorMessages = '';
                for (let field in data.errors) {
                    errorMessages += data.errors[field].join('\n') + '\n';
                }
                alert('Error: ' + errorMessages);
            } else {
                alert('Error creating user. Please try again.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating user. Please try again.');
    })
    .finally(() => {
        // Reset button
        createBtn.disabled = false;
        createBtn.innerHTML = '<i class="fas fa-save me-1"></i> <span id="createBtnText">Create User</span>';
    });
    
    return false;
});

function showSuccessMessage(message) {
    // Create success notification
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    notification.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Event listeners
document.getElementById('password')?.addEventListener('input', function() {
    checkPasswordStrength();
    checkPasswordMatch();
});

document.getElementById('roleSelect')?.addEventListener('change', updateRoleDescription);
document.getElementById('statusSwitch')?.addEventListener('change', toggleStatus);

// Initialize on modal show
document.getElementById('createUserModal')?.addEventListener('show.bs.modal', function() {
    // Reset form
    document.getElementById('createUserForm').reset();
    document.getElementById('password-strength').style.width = '0%';
    document.getElementById('password-match-feedback').textContent = '';
    document.getElementById('statusText').textContent = 'Active';
    document.getElementById('statusText').className = 'text-success';
    
    // Reset button
    const createBtn = document.getElementById('createUserBtn');
    createBtn.disabled = false;
    createBtn.innerHTML = '<i class="fas fa-save me-1"></i> <span id="createBtnText">Create User</span>';
});

// Original functions (keep your existing functions)
function filterTable() {
    loadUsers(window.location.href);
}

function sortTable() {
    loadUsers(window.location.href);
}

function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '';
        loadUsers(window.location.href);
    }
}

function viewUser(userId) {
    // Your existing viewUser function
    console.log('View user:', userId);
}

function editUser(userId) {
    // Your existing editUser function
    console.log('Edit user:', userId);
}

function toggleUserStatus(userId) {
    // Your existing toggleUserStatus function
    console.log('Toggle user status:', userId);
}

function deleteUser(userId) {
    // Your existing deleteUser function
    console.log('Delete user:', userId);
}

function exportUsers() {
    // Your existing exportUsers function
    console.log('Export users');
}
</script>
@endpush

@push('styles')
<style>
/* Statistics Cards - Clean Modern Design */
.stats-card {
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    transition: all 0.3s ease !important;
    height: 100% !important;
    display: flex !important;
    align-items: center !important;
    padding: 20px !important;
    position: relative !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: none !important;
    border: none !important;
}

.stats-icon {
    width: 50px !important;
    height: 50px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 20px !important;
    color: white !important;
    margin-right: 15px !important;
    flex-shrink: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-card:nth-child(1) .stats-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
}

.stats-card:nth-child(2) .stats-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    border: none !important;
}

.stats-card:nth-child(3) .stats-icon {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
    border: none !important;
}

.stats-card:nth-child(4) .stats-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    border: none !important;
}

.stats-info {
    flex: 1 !important;
    min-width: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-number {
    font-size: 28px !important;
    font-weight: 700 !important;
    color: #1a202c !important;
    line-height: 1 !important;
    margin-bottom: 4px !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-label {
    font-size: 13px !important;
    color: #64748b !important;
    font-weight: 500 !important;
    text-transform: capitalize !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Hide all old conflicting styles */
.stat-card-modern,
.stat-card-new,
.stat-card-body,
.stat-icon-wrapper,
.stat-content,
.stat-number,
.stat-title,
.modern-table {
    display: none !important;
    visibility: hidden !important;
    background-color: transparent !important;
}

/* User Avatar Styles */
.user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

/* Role Badges */
.role-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-badge.admin {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.role-badge.author {
    background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
    color: white;
}

.role-badge.user {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

/* Status Badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.status-badge.inactive {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.status-badge.suspended {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-card {
        padding: 16px !important;
    }
    
    .stats-icon {
        width: 44px !important;
        height: 44px !important;
        font-size: 18px !important;
        margin-right: 12px !important;
    }
    
    .stats-number {
        font-size: 24px !important;
    }
    
    .stats-label {
        font-size: 12px !important;
    }
}

@media (max-width: 576px) {
    .stats-card {
        padding: 14px !important;
    }
    
    .stats-icon {
        width: 40px !important;
        height: 40px !important;
        font-size: 16px !important;
        margin-right: 10px !important;
    }
    
    .stats-number {
        font-size: 20px !important;
    }
    
    .stats-label {
        font-size: 11px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const name = row.dataset.name || '';
        const email = row.dataset.email || '';
        const text = name + ' ' + email;
        row.style.display = text.includes(search) ? '' : 'none';
    });
});

// Filter functionality
document.getElementById('roleFilter').addEventListener('change', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);

function filterTable() {
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const rowRole = row.dataset.role || '';
        const rowStatus = row.dataset.status || '';
        
        const roleMatch = !role || rowRole === role;
        const statusMatch = !status || rowStatus === status;
        
        row.style.display = (roleMatch && statusMatch) ? '' : 'none';
    });
}

// Sort functionality
let sortOrder = {};

function sortTable(column) {
    const currentOrder = sortOrder[column] || 'asc';
    const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    sortOrder[column] = newOrder;
    
    const url = new URL(window.location);
    url.searchParams.set('sort', column);
    url.searchParams.set('order', newOrder);
    window.location.href = url.toString();
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// CRUD operations
function viewUser(id) {
    fetch(`/admin/users/${id}`)
        .then(response => response.json())
        .then(data => {
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>ID:</strong> ${data.id}<br>
                        <strong>Name:</strong> ${data.name}<br>
                        <strong>Email:</strong> ${data.email}<br>
                        <strong>Role:</strong> ${data.role}<br>
                        <strong>Joined:</strong> ${data.created_at}
                    </div>
                    <div class="col-md-6">
                        <strong>Phone:</strong> ${data.phone || 'Not provided'}<br>
                        <strong>Address:</strong> ${data.address || 'Not provided'}
                    </div>
                </div>
            `;
            document.getElementById('userDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        });
}

function editUser(id) {
    fetch(`/admin/users/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editUserId').value = data.id;
            document.getElementById('editName').value = data.name;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editRole').value = data.role;
            document.getElementById('editPhone').value = data.phone || '';
            document.getElementById('editAddress').value = data.address || '';
            
            const form = document.getElementById('editUserForm');
            form.action = form.action.replace(':id', id);
            
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        });
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch(`/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting user: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting user: ' + error.message);
        });
    }
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchInput').dispatchEvent(new Event('input'));
}

function exportUsers() {
    const url = new URL(window.location);
    url.searchParams.set('export', 'csv');
    window.location.href = url.toString();
}
</script>
@endpush
