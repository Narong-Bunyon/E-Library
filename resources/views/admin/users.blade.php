@extends('layouts.admin')

@section('title', 'Manage Users - E-Library')

@section('page-title', 'Manage Users')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Users Management
            </h4>
            <p class="text-muted mb-0">Manage user accounts, roles, and permissions</p>
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
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                        <button class="btn btn-outline-secondary" onclick="clearSearch()">
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
    <div class="card">
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
                                <span class="badge role-badge {{ $user->role }}">
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
                                <span class="badge status-badge {{ $user->status ?? 'active' }}">
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
                                    <div class="text-dark">{{ $user->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
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
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No users found</p>
                                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Create First User
                                </button>
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
        <div class="d-flex justify-content-between align-items-center mt-4">
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
                <h5 class="modal-title">Create New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role *</label>
                                <select class="form-select" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Administrator</option>
                                    <option value="author">Author</option>
                                    <option value="reader">Reader</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2" placeholder="Optional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create User
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
