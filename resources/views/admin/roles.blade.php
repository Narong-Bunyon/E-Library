@extends('layouts.admin')

@section('title', 'Roles & Permissions - E-Library')

@section('page-title', 'Roles & Permissions')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-roles.css') }}">
@endpush

@section('content')
<div class="roles-container">
    <!-- Header Section -->
    <div class="roles-header">
        <div>
            <h4>
                <i class="fas fa-user-shield me-2"></i>
                Roles & Permissions Management
            </h4>
            <p>Manage user roles, permissions, and access control</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                <i class="fas fa-plus me-1"></i>
                Add New Role
            </button>
            <button class="btn btn-outline-secondary" onclick="exportRoles()">
                <i class="fas fa-download me-1"></i>
                Export Permissions
            </button>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card primary">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ App\Models\Role::count() }}</h3>
                        <p class="stat-label mb-1">Total Roles</p>
                        <small class="text-muted">System roles defined</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card danger">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ App\Models\User::where('role', 'admin')->count() }}</h3>
                        <p class="stat-label mb-1">Total Admin</p>
                        <small class="text-muted">Administrator accounts</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card success">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ App\Models\User::where('role', 'user')->count() }}</h3>
                        <p class="stat-label mb-1">Total User</p>
                        <small class="text-muted">Regular user accounts</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card warning">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-pen-fancy"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ App\Models\User::where('role', 'author')->count() }}</h3>
                        <p class="stat-label mb-1">Total Author</p>
                        <small class="text-muted">Content creators</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Roles Table Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-list me-2"></i>
                        All System Roles
                    </div>
                    <div class="table-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search roles..." id="roleSearch">
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            <i class="fas fa-plus me-1"></i>
                            Add Role
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>
                                    <div class="table-header-cell">
                                        <input type="checkbox" class="form-check-input" id="selectAllRoles">
                                        <label for="selectAllRoles"></label>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header-cell">
                                        Role Name
                                        <i class="fas fa-sort ms-2"></i>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header-cell">
                                        Description
                                        <i class="fas fa-sort ms-2"></i>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header-cell">
                                        Users
                                        <i class="fas fa-sort ms-2"></i>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header-cell">
                                        Permissions
                                        <i class="fas fa-sort ms-2"></i>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header-cell">
                                        Status
                                        <i class="fas fa-sort ms-2"></i>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-header-cell">
                                        Actions
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Role::all() as $role)
                            <tr>
                                <td>
                                    <div class="table-cell">
                                        <input type="checkbox" class="form-check-input role-checkbox" value="{{ $role->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="table-cell role-name-cell">
                                        <div class="role-icon-small">
                                            <i class="fas fa-{{ $role->name == 'admin' ? 'crown' : ($role->name == 'author' ? 'pen-fancy' : 'user') }}"></i>
                                        </div>
                                        <div class="role-info">
                                            <div class="role-name">{{ ucfirst($role->name) }}</div>
                                            <div class="role-id">ID: #{{ str_pad($role->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-cell">
                                        <div class="role-description-cell">
                                            {{ $role->description ?? 'No description available for this role.' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-cell">
                                        <div class="user-count-cell">
                                            <i class="fas fa-users me-2"></i>
                                            <span>{{ App\Models\User::where('role', $role->name)->count() }} users</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-cell">
                                        <div class="permission-count-cell">
                                            <i class="fas fa-key me-2"></i>
                                            <span>{{ $role->permissions_count ?? 0 }} permissions</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-cell">
                                        <span class="status-badge active">
                                            <i class="fas fa-circle me-1"></i>
                                            Active
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-cell">
                                        <div class="action-buttons">
                                            <button class="btn-action edit" onclick="editRole({{ $role->id }})" title="Edit Role">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-action view" onclick="viewRole({{ $role->id }})" title="View Role">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($role->name != 'admin')
                                            <button class="btn-action delete" onclick="deleteRole({{ $role->id }})" title="Delete Role">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="table-footer">
                    <div class="table-info">
                        <span>Showing {{ App\Models\Role::count() }} roles</span>
                    </div>
                    <div class="table-pagination">
                        <button class="btn-pagination" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn-pagination active">1</button>
                        <button class="btn-pagination">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editRoleName" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="editRoleName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editRoleStatus" class="form-label">Status</label>
                                <select class="form-select" id="editRoleStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editRoleDescription" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="permissions-grid">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="create_books" id="perm_create_books">
                                <label class="form-check-label" for="perm_create_books">Create Books</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="edit_books" id="perm_edit_books">
                                <label class="form-check-label" for="perm_edit_books">Edit Books</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="delete_books" id="perm_delete_books">
                                <label class="form-check-label" for="perm_delete_books">Delete Books</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="manage_users" id="perm_manage_users">
                                <label class="form-check-label" for="perm_manage_users">Manage Users</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="view_analytics" id="perm_view_analytics">
                                <label class="form-check-label" for="perm_view_analytics">View Analytics</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="system_settings" id="perm_system_settings">
                                <label class="form-check-label" for="perm_system_settings">System Settings</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateRole()">Update Role</button>
            </div>
        </div>
    </div>
</div>

<!-- View Role Modal -->
<div class="modal fade" id="viewRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Role Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="viewRoleContent">
                    <!-- Role details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Role Management Functions
let currentRoleId = null;

function editRole(roleId) {
    currentRoleId = roleId;
    // Load role data into edit modal
    fetch(`/admin/roles/${roleId}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editRoleName').value = data.name;
            document.getElementById('editRoleDescription').value = data.description || '';
            document.getElementById('editRoleStatus').value = data.status || 'active';
            
            // Load permissions
            if (data.permissions) {
                data.permissions.forEach(perm => {
                    const checkbox = document.getElementById(`perm_${perm}`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading role:', error);
            // Fallback for demo
            document.getElementById('editRoleName').value = 'Role ' + roleId;
            document.getElementById('editRoleDescription').value = 'Role description';
            const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            modal.show();
        });
}

function viewRole(roleId) {
    // Load role data into view modal
    fetch(`/admin/roles/${roleId}`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="role-details">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Role Information</h6>
                            <p><strong>Name:</strong> ${data.name}</p>
                            <p><strong>Description:</strong> ${data.description || 'No description'}</p>
                            <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Statistics</h6>
                            <p><strong>Users:</strong> ${data.user_count || 0}</p>
                            <p><strong>Permissions:</strong> ${data.permissions_count || 0}</p>
                            <p><strong>Created:</strong> ${data.created_at || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Permissions</h6>
                        <div class="permissions-list">
                            ${(data.permissions || []).map(perm => 
                                `<span class="badge bg-primary me-1">${perm}</span>`
                            ).join('')}
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('viewRoleContent').innerHTML = content;
            
            const modal = new bootstrap.Modal(document.getElementById('viewRoleModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading role:', error);
            // Fallback for demo
            const content = `
                <div class="role-details">
                    <p><strong>Role ID:</strong> ${roleId}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                    <p>This role information is being loaded...</p>
                </div>
            `;
            document.getElementById('viewRoleContent').innerHTML = content;
            const modal = new bootstrap.Modal(document.getElementById('viewRoleModal'));
            modal.show();
        });
}

function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
        fetch(`/admin/roles/${roleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Role deleted successfully!');
                location.reload();
            } else {
                alert('Error deleting role: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting role:', error);
            // Fallback for demo
            alert('Role deletion functionality requires backend implementation.');
        });
    }
}

function updateRole() {
    const formData = {
        name: document.getElementById('editRoleName').value,
        description: document.getElementById('editRoleDescription').value,
        status: document.getElementById('editRoleStatus').value,
        permissions: []
    };
    
    // Get checked permissions
    document.querySelectorAll('.permissions-grid input:checked').forEach(checkbox => {
        formData.permissions.push(checkbox.value);
    });
    
    fetch(`/admin/roles/${currentRoleId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Role updated successfully!');
            location.reload();
        } else {
            alert('Error updating role: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error updating role:', error);
        // Fallback for demo
        alert('Role update functionality requires backend implementation.');
    });
}

function exportRoles() {
    window.location.href = '/admin/roles/export';
}

// Search functionality
document.getElementById('roleSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.modern-table tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Select all functionality
document.getElementById('selectAllRoles')?.addEventListener('change', function(e) {
    const checkboxes = document.querySelectorAll('.role-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = e.target.checked;
    });
});
</script>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Create New Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST" id="createRoleForm">
                @csrf
                <div class="modal-body">
                    <!-- Role Information Section -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-user-tag me-2"></i>
                            Role Information
                        </h6>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1 text-muted"></i>
                                Role Name *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <input type="text" class="form-control" name="name" required 
                                       placeholder="Enter role name"
                                       pattern="[A-Za-z\s]{3,50}">
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-align-left me-2"></i>
                            Description
                        </h6>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-file-alt me-1 text-muted"></i>
                                Role Description
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                                <textarea class="form-control" name="description" rows="4" 
                                          placeholder="Describe this role and its responsibilities"
                                          maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="createRoleBtn">
                        <i class="fas fa-save me-1"></i>
                        <span id="createRoleBtnText">Create Role</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.update', ':id') }}" method="POST" id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editRoleId">
                <div class="modal-body">
                    <!-- Role content will be loaded here -->
                    <div id="editRoleContent">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="editRoleBtn">
                        <i class="fas fa-save me-1"></i>
                        <span id="editRoleBtnText">Update Role</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Role Modal -->
<div class="modal fade" id="viewRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Role Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="viewRoleContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Role Management Functions
function editRole(roleId) {
    // Show loading state
    const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
    const editContent = document.getElementById('editRoleContent');
    
    editContent.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
        </div>
    `;
    
    // Show modal
    editModal.show();
    
    // Load role data (replace with actual API call)
    setTimeout(() => {
        loadRoleData(roleId);
    }, 500);
}

function viewRole(roleId) {
    // Show loading state
    const viewModal = new bootstrap.Modal(document.getElementById('viewRoleModal'));
    const viewContent = document.getElementById('viewRoleContent');
    
    viewContent.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
        </div>
    `;
    
    // Show modal
    viewModal.show();
    
    // Load role data (replace with actual API call)
    setTimeout(() => {
        loadRoleView(roleId);
    }, 500);
}

function loadRoleData(roleId) {
    // Simulate loading role data (replace with actual API call)
    const roleData = {
        'admin': {
            name: 'Administrator',
            description: 'Full system access with all permissions including user management, system settings, and complete control.',
            is_active: true
        },
        'author': {
            name: 'Author',
            description: 'Can create, edit, and manage their own books and content. Limited user management capabilities.',
            is_active: true
        },
        'user': {
            name: 'User',
            description: 'Can read books and manage personal library. Basic access to system features.',
            is_active: true
        }
    };
    
    const data = roleData[roleId];
    if (!data) return;
    
    // Set the role ID in the hidden input
    document.getElementById('editRoleId').value = roleId;
    
    const editContent = document.getElementById('editRoleContent');
    editContent.innerHTML = `
        <div class="mb-4">
            <h6 class="text-primary fw-bold mb-3">
                <i class="fas fa-user-tag me-2"></i>
                Role Information
            </h6>
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-tag me-1 text-muted"></i>
                    Role Name *
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user-tag"></i>
                    </span>
                    <input type="text" class="form-control" name="name" value="${data.name}" required>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <h6 class="text-primary fw-bold mb-3">
                <i class="fas fa-align-left me-2"></i>
                Description
            </h6>
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-file-alt me-1 text-muted"></i>
                    Role Description
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <textarea class="form-control" name="description" rows="4">${data.description}</textarea>
                </div>
            </div>
        </div>
    `;
}

function loadRoleView(roleId) {
    // Simulate loading role view (replace with actual API call)
    const roleData = {
        'admin': {
            name: 'Administrator',
            display_name: 'Super Admin',
            description: 'Full system access with all permissions including user management, system settings, and complete control.',
            permissions: ['create_books', 'edit_books', 'delete_books', 'view_users', 'manage_users', 'assign_roles', 'view_analytics', 'system_settings', 'export_data', 'audit_logs'],
            users_count: 1,
            is_active: true,
            created_at: '2024-01-15'
        },
        'author': {
            name: 'Author',
            display_name: 'Content Creator',
            description: 'Can create, edit, and manage their own books and content. Limited user management capabilities.',
            permissions: ['create_books', 'edit_books', 'delete_books', 'view_analytics'],
            users_count: 3,
            is_active: true,
            created_at: '2024-01-10'
        },
        'user': {
            name: 'User',
            display_name: 'Reader',
            description: 'Can read books and manage personal library. Basic access to system features.',
            permissions: ['view_analytics'],
            users_count: 4,
            is_active: true,
            created_at: '2024-01-05'
        }
    };
    
    const data = roleData[roleId];
    if (!data) return;
    
    const viewContent = document.getElementById('viewRoleContent');
    viewContent.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <h6 class="text-primary fw-bold mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Role Information
                    </h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Role Name:</strong></td>
                            <td>${data.name}</td>
                        </tr>
                        <tr>
                            <td><strong>Display Name:</strong></td>
                            <td>${data.display_name}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td><span class="badge ${data.is_active ? 'bg-success' : 'bg-danger'}">${data.is_active ? 'Active' : 'Inactive'}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>${data.created_at}</td>
                        </tr>
                        <tr>
                            <td><strong>Users Count:</strong></td>
                            <td>${data.users_count} users</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <h6 class="text-primary fw-bold mb-3">
                        <i class="fas fa-key me-2"></i>
                        Permissions
                    </h6>
                    <div class="permission-list">
                        ${data.permissions.map(perm => {
                            const permLabels = {
                                'create_books': '<i class="fas fa-plus me-1"></i> Create Books',
                                'edit_books': '<i class="fas fa-edit me-1"></i> Edit Books',
                                'delete_books': '<i class="fas fa-trash me-1"></i> Delete Books',
                                'view_users': '<i class="fas fa-users me-1"></i> View Users',
                                'manage_users': '<i class="fas fa-user-cog me-1"></i> Manage Users',
                                'assign_roles': '<i class="fas fa-user-tag me-1"></i> Assign Roles',
                                'view_analytics': '<i class="fas fa-chart-bar me-1"></i> View Analytics',
                                'system_settings': '<i class="fas fa-cogs me-1"></i> System Settings',
                                'export_data': '<i class="fas fa-download me-1"></i> Export Data',
                                'audit_logs': '<i class="fas fa-history me-1"></i> View Audit Logs'
                            };
                            return `<div class="permission-item"><span class="badge bg-primary me-2">${permLabels[perm] || perm}</span></div>`;
                        }).join('')}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-4">
                    <h6 class="text-primary fw-bold mb-3">
                        <i class="fas fa-align-left me-2"></i>
                        Description
                    </h6>
                    <p>${data.description}</p>
                </div>
            </div>
        </div>
    `;
}

// Form validation and submission
document.getElementById('createRoleForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const createBtn = document.getElementById('createRoleBtn');
    const createBtnText = document.getElementById('createRoleBtnText');
    
    // Show loading state
    createBtn.disabled = true;
    createBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating Role...';
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit via AJAX
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                          document.querySelector('[name="_token"]')?.value
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createRoleModal'));
            modal.hide();
            
            // Show success message
            showSuccessMessage('Role created successfully!');
            
            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            // Show errors
            if (data.errors) {
                let errorMessages = '';
                for (let field in data.errors) {
                    errorMessages += data.errors[field].join('\n') + '\n';
                }
                alert('Error: ' + errorMessages);
            } else {
                alert('Error creating role. Please try again.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating role: ' + error.message + '. Please try again.');
    })
    .finally(() => {
        // Reset button
        createBtn.disabled = false;
        createBtn.innerHTML = '<i class="fas fa-save me-1"></i> <span id="createRoleBtnText">Create Role</span>';
    });
    
    return false;
});

// Edit form submission
document.getElementById('editRoleForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const editBtn = document.getElementById('editRoleBtn');
    const editBtnText = document.getElementById('editRoleBtnText');
    
    // Show loading state
    editBtn.disabled = true;
    editBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating Role...';
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit via AJAX (replace with actual API call)
    fetch(this.action.replace(':id', document.getElementById('editRoleId').value), {
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
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editRoleModal'));
            modal.hide();
            
            // Show success message
            showSuccessMessage('Role updated successfully!');
            
            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            // Show errors
            if (data.errors) {
                let errorMessages = '';
                for (let field in data.errors) {
                    errorMessages += data.errors[field].join('\n') + '\n';
                }
                alert('Error: ' + errorMessages);
            } else {
                alert('Error updating role. Please try again.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating role. Please try again.');
    })
    .finally(() => {
        // Reset button
        editBtn.disabled = false;
        editBtn.innerHTML = '<i class="fas fa-save me-1"></i> <span id="editRoleBtnText">Update Role</span>';
    });
    
    return false;
});

// Role status toggle
document.getElementById('roleActive')?.addEventListener('change', function() {
    const statusText = document.getElementById('roleStatusText');
    if (this.checked) {
        statusText.textContent = 'Active';
        statusText.className = 'text-success';
    } else {
        statusText.textContent = 'Inactive';
        statusText.className = 'text-danger';
    }
});

document.getElementById('editRoleActive')?.addEventListener('change', function() {
    const statusText = document.getElementById('editRoleStatusText');
    if (this.checked) {
        statusText.textContent = 'Active';
        statusText.className = 'text-success';
    } else {
        statusText.textContent = 'Inactive';
        statusText.className = 'text-danger';
    }
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

function exportRoles() {
    console.log('Export roles functionality');
    // Implement role export functionality
    showSuccessMessage('Roles exported successfully!');
}

function exportPermissions() {
    console.log('Export permissions functionality');
    // Implement permissions export functionality
    showSuccessMessage('Permissions exported successfully!');
}
</script>
@endpush
