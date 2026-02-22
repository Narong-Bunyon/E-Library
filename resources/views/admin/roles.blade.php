@extends('layouts.admin')
@section('title', 'Roles & Permissions - E-Library')
@section('page-title', 'Roles & Permissions')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-roles.css') }}">
@endpush

@section('content')
<div class="roles-page">

    {{-- Header --}}
    <div class="roles-topbar">
        <div>
            <h1 class="roles-title">Roles &amp; Permissions</h1>
        </div>

        <!-- <div class="roles-total-users">
            <div class="num">{{ $totalUsers ?? 12 }}</div>
            <div class="label">TOTAL USERS</div>
        </div> -->
    </div>

    {{-- Action buttons --}}
    <div class="roles-actions">
        <button class="btn-ui btn-primary-ui" type="button" onclick="showCreateModal()">
            <i class="fas fa-plus"></i>
            Create New Role
        </button>
        <button class="btn-ui btn-outline-ui" type="button" onclick="exportRoles()">
            <i class="fas fa-download"></i>
            Export
        </button>
    </div>

    {{-- Statistics Cards --}}
    <div class="roles-cards">
        <div class="stat-card">
            <div class="stat-icon bg-indigo">
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <div class="stat-number">{{ $roles->count() ?? 4 }}</div>
                <div class="stat-title">TOTAL ROLES</div>
                <div class="stat-subtitle">System roles</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-red">
                <i class="fas fa-crown"></i>
            </div>
            <div>
                <div class="stat-number">{{ App\Models\User::where('role', 'admin')->count() }}</div>
                <div class="stat-title">ADMINS</div>
                <div class="stat-subtitle">Administrators</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-teal">
                <i class="fas fa-pen-fancy"></i>
            </div>
            <div>
                <div class="stat-number">{{ App\Models\User::where('role', 'author')->count() }}</div>
                <div class="stat-title">AUTHORS</div>
                <div class="stat-subtitle">Content creators</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-orange">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <div class="stat-number">{{ App\Models\User::where('role', 'user')->count() }}</div>
                <div class="stat-title">USERS</div>
                <div class="stat-subtitle">Regular users</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="roles-filter">
        <div class="filter-row">
            <div class="select-wrap">
                <select class="select-ui" id="role_filter" onchange="applyFilter()">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="author">Author</option>
                    <option value="user">User</option>
                </select>
                <i class="fas fa-chevron-down select-icon"></i>
            </div>
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="roles-table-card">
        <table class="roles-table">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Description</th>
                    <th class="col-center">Users</th>
                    <th class="col-center">Permissions</th>
                    <th class="col-center">Status</th>
                    <th class="col-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>
                        <div class="role-cell">
                            <div class="role-icon">
                                <i class="fas fa-{{ $role->name == 'admin' ? 'crown' : ($role->name == 'author' ? 'pen-fancy' : 'user') }}"></i>
                            </div>
                            <div class="role-text">
                                <div class="role-name">{{ ucfirst($role->name) }}</div>
                                <div class="role-tag">ID: #{{ str_pad($role->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="muted">
                        {{ $role->description ?? 'No description available for this role.' }}
                    </td>

                    <td class="col-center">
                        <div class="mini-metric">
                            <i class="fas fa-users"></i>
                            <span>{{ App\Models\User::where('role', $role->name)->count() }}</span>
                        </div>
                    </td>

                    <td class="col-center">
                        <div class="mini-metric">
                            <i class="fas fa-key"></i>
                            <span>{{ $role->permissions_count ?? 0 }}</span>
                        </div>
                    </td>

                    <td class="col-center">
                        <span class="pill pill-success">
                            <i class="fas fa-circle"></i>
                            {{ strtoupper($role->status ?? 'ACTIVE') }}
                        </span>
                    </td>

                    <td class="col-center">
                        <div class="actions">
                            <a class="btn-small btn-edit" href="#" onclick="editRole({{ $role->id }})">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            <a class="btn-small btn-review" href="#" onclick="viewRole({{ $role->id }})">
                                <i class="fas fa-search"></i> Review
                            </a>

                            @if($role->name != 'admin')
                            <form method="POST" action="{{ route('admin.roles.delete', $role->id) }}" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-small btn-delete" type="submit">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-users-slash" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        No roles found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if(isset($roles) && $roles instanceof \Illuminate\Pagination\LengthAwarePaginator && $roles->hasPages())
        <div class="pagination-wrapper">
            {{ $roles->links() }}
        </div>
        @endif

        <div class="table-footer">
            Showing {{ isset($roles) ? $roles->count() : 0 }} {{ isset($roles) && method_exists($roles, 'total') ? 'of ' . $roles->total() : '' }} roles
        </div>
    </div>
</div>

{{-- Create Role Modal --}}
<div id="createRoleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create New Role</h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="role_name">Role Name *</label>
                    <input type="text" id="role_name" name="name" required 
                           placeholder="e.g., moderator, editor, contributor">
                </div>
                
                <div class="form-group">
                    <label for="role_description">Description</label>
                    <textarea id="role_description" name="description" rows="3"
                              placeholder="Describe the role responsibilities..."></textarea>
                </div>

                <div class="form-group">
                    <label>Permissions</label>
                    <div class="permissions-grid">
                        <label class="permission-item">
                            <input type="checkbox" name="permissions[]" value="view_dashboard">
                            <span>View Dashboard</span>
                        </label>
                        <label class="permission-item">
                            <input type="checkbox" name="permissions[]" value="manage_users">
                            <span>Manage Users</span>
                        </label>
                        <label class="permission-item">
                            <input type="checkbox" name="permissions[]" value="create_content">
                            <span>Create Content</span>
                        </label>
                        <label class="permission-item">
                            <input type="checkbox" name="permissions[]" value="edit_content">
                            <span>Edit Content</span>
                        </label>
                        <label class="permission-item">
                            <input type="checkbox" name="permissions[]" value="delete_content">
                            <span>Delete Content</span>
                        </label>
                        <label class="permission-item">
                            <input type="checkbox" name="permissions[]" value="manage_roles">
                            <span>Manage Roles</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ui btn-outline-ui" onclick="closeCreateModal()">Cancel</button>
                <button type="submit" class="btn-ui btn-primary-ui">
                    <i class="fas fa-plus"></i> Create Role
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Role Modal --}}
<div id="editRoleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Role</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form method="POST" id="editRoleForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_role_name">Role Name *</label>
                    <input type="text" id="edit_role_name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_role_description">Description</label>
                    <textarea id="edit_role_description" name="description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Permissions</label>
                    <div class="permissions-grid" id="edit_permissions">
                        <!-- Permissions will be loaded dynamically -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ui btn-outline-ui" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-ui btn-primary-ui">
                    <i class="fas fa-save"></i> Update Role
                </button>
            </div>
        </form>
    </div>
</div>

{{-- View Role Modal --}}
<div id="viewRoleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Role Details</h3>
            <button class="modal-close" onclick="closeViewModal()">&times;</button>
        </div>
        <div class="modal-body" id="viewRoleContent">
            <!-- Role details will be loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-ui btn-outline-ui" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
:root{
  --bg: #f5f7ff;
  --card: #ffffff;
  --text: #0f172a;
  --muted: #64748b;
  --line: #e6e9ff;
  --primary: #2f6fed;
  --primary-dark: #2458c4;
  --shadow: 0 10px 25px rgba(15, 23, 42, .08);
  --shadow-soft: 0 8px 18px rgba(15, 23, 42, .06);
  --radius: 14px;
}

.roles-page{
  padding: 26px 26px 40px;
  background: radial-gradient(1000px 380px at 50% 0%, #f0f3ff 0%, var(--bg) 55%, #f6f8ff 100%);
  min-height: calc(100vh - 80px);
  color: var(--text);
}

.roles-topbar{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap: 16px;
  margin-bottom: 16px;
}

.roles-title{
  font-size: 34px;
  line-height: 1.15;
  margin: 0;
  font-weight: 800;
  letter-spacing: -0.3px;
}

.roles-total-users{
  text-align:right;
  padding-top: 4px;
}
.roles-total-users .num{
  font-size: 32px;
  font-weight: 900;
  line-height: 1;
}
.roles-total-users .label{
  font-size: 12px;
  font-weight: 800;
  letter-spacing: .6px;
  color: var(--muted);
  margin-top: 4px;
}

.roles-actions{
  display:flex;
  gap: 12px;
  margin: 6px 0 18px;
}

.btn-ui{
  display:inline-flex;
  align-items:center;
  gap: 10px;
  border-radius: 10px;
  padding: 10px 14px;
  font-weight: 700;
  font-size: 14px;
  border: 1px solid transparent;
  cursor: pointer;
  text-decoration: none;
  box-shadow: 0 6px 14px rgba(15, 23, 42, .06);
}

.btn-primary-ui{
  background: var(--primary);
  color: #fff;
}
.btn-primary-ui:hover{ background: var(--primary-dark); }

.btn-outline-ui{
  background: #f2f6ff;
  color: #1f2a44;
  border-color: #d7e4ff;
}
.btn-outline-ui:hover{
  background: #eaf1ff;
}

.roles-cards{
  display:grid;
  grid-template-columns: repeat(4, minmax(200px, 1fr));
  gap: 18px;
  margin: 0 0 18px;
}

.stat-card{
  background: var(--card);
  border: 1px solid #eef1ff;
  border-radius: var(--radius);
  box-shadow: var(--shadow-soft);
  padding: 18px 18px;
  display:flex;
  align-items:center;
  gap: 16px;
}

.stat-icon{
  width: 64px;
  height: 64px;
  border-radius: 16px;
  display:flex;
  align-items:center;
  justify-content:center;
  color: #0b1b3a;
  font-size: 22px;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,.35);
}

.bg-indigo{ background: #c8ccff; }
.bg-red{ background: #ffb2b2; }
.bg-teal{ background: #bdf3e2; }
.bg-orange{ background: #ffd2a1; }

.stat-number{
  font-size: 28px;
  font-weight: 900;
  line-height: 1;
}
.stat-title{
  margin-top: 6px;
  font-weight: 900;
  letter-spacing: .5px;
  font-size: 12px;
  color: #1f2a44;
}
.stat-subtitle{
  margin-top: 4px;
  color: var(--muted);
  font-size: 13px;
  font-weight: 600;
}

.roles-filter{
  margin: 6px 0 14px;
}

.filter-row{
  display:flex;
  flex-direction:column;
  gap: 10px;
  width: 340px;
}

.select-wrap{
  position:relative;
}
.select-ui{
  appearance:none;
  width:100%;
  border-radius: 10px;
  padding: 12px 40px 12px 14px;
  border: 1px solid #dfe6ff;
  background: #ffffff;
  font-weight: 700;
  color: #1f2a44;
  box-shadow: 0 6px 14px rgba(15, 23, 42, .05);
}
.select-icon{
  position:absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #5b6b8a;
  pointer-events:none;
  font-size: 12px;
}

.roles-table-card{
  background: var(--card);
  border: 1px solid #e8ecff;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 0;
  overflow:hidden;
}

.roles-table{
  width:100%;
  border-collapse: separate;
  border-spacing: 0;
}

.roles-table thead th{
  background: #f7f9ff;
  color: #334155;
  font-size: 13px;
  font-weight: 900;
  padding: 14px 16px;
  text-align:left;
  border-bottom: 1px solid #edf0ff;
  white-space: nowrap;
}

.th-sort i{
  margin-left: 8px;
  color: #94a3b8;
  font-size: 12px;
}

.roles-table tbody td{
  padding: 14px 16px;
  border-bottom: 1px solid #f0f3ff;
  vertical-align: middle;
}

.roles-table tbody tr:hover{
  background: #fbfcff;
}

.col-center{
  text-align:center;
}

.muted{
  color: var(--muted);
  font-weight: 600;
}

.role-cell{
  display:flex;
  align-items:center;
  gap: 12px;
}
.role-icon{
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: #f2f6ff;
  border: 1px solid #e4ebff;
  display:flex;
  align-items:center;
  justify-content:center;
  color: #1e3a8a;
  font-size: 18px;
}
.role-name{
  font-weight: 900;
  font-size: 16px;
}
.role-tag{
  margin-top: 2px;
  font-size: 12px;
  font-weight: 800;
  color: #6b7aa0;
}

.mini-metric{
  display:inline-flex;
  align-items:center;
  gap: 8px;
  font-weight: 800;
  color: #334155;
}
.mini-metric i{
  color: #1f2a44;
  opacity: .9;
}

.pill{
  display:inline-flex;
  align-items:center;
  gap: 8px;
  padding: 8px 14px;
  border-radius: 999px;
  font-weight: 900;
  font-size: 13px;
  letter-spacing: .3px;
  white-space: nowrap;
}
.pill-success{
  background: #1f9d7a;
  color: #fff;
  box-shadow: 0 10px 18px rgba(31, 157, 122, .18);
}

.actions{
  display:flex;
  align-items:center;
  justify-content:center;
  gap: 10px;
  flex-wrap: wrap;
}

.btn-small{
  border-radius: 10px;
  padding: 9px 12px;
  font-weight: 900;
  font-size: 13px;
  border: 1px solid transparent;
  cursor:pointer;
  display:inline-flex;
  align-items:center;
  gap: 8px;
  background:#fff;
}

.btn-edit{
  background:#2f6fed;
  color:#fff;
  box-shadow: 0 10px 16px rgba(47, 111, 237, .18);
}
.btn-edit:hover{ background: #2458c4; }

.btn-review{
  background:#f2f6ff;
  border-color:#d7e4ff;
  color:#1f2a44;
}
.btn-review:hover{ background:#eaf1ff; }

.btn-delete{
  background:#ef4444;
  color:#fff;
  box-shadow: 0 10px 16px rgba(239, 68, 68, .18);
}
.btn-delete:hover{ background:#dc2626; }

.inline{ display:inline; }

.table-footer{
  padding: 14px 16px;
  color: #475569;
  font-weight: 700;
  background: #fbfcff;
}

.pagination-wrapper{
  padding: 20px;
  display: flex;
  justify-content: center;
}

.pagination-wrapper .pagination{
  display: flex;
  gap: 8px;
  align-items: center;
}

.pagination-wrapper .pagination li{
  display: inline-block;
}

.pagination-wrapper .pagination a,
.pagination-wrapper .pagination span{
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 12px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 13px;
  text-decoration: none;
  border: 1px solid #e4ebff;
  background: #fff;
  color: #1f2a44;
}

.pagination-wrapper .pagination a:hover{
  background: #f2f6ff;
  border-color: #d7e4ff;
}

.pagination-wrapper .pagination span.active{
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}

/* Modal Styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 16px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e4ebff;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 800;
  color: #1e293b;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #64748b;
  padding: 4px;
  border-radius: 4px;
}

.modal-close:hover {
  background: #f1f5f9;
}

.modal-body {
  padding: 24px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 20px 24px;
  border-top: 1px solid #e4ebff;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 700;
  color: #374151;
  font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 500;
  color: #374151;
  background: white;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.permissions-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

.permission-item {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  padding: 8px;
  border-radius: 6px;
  transition: background 0.2s;
}

.permission-item:hover {
  background: #f8fafc;
}

.permission-item input[type="checkbox"] {
  width: auto;
  margin: 0;
}

.permission-item span {
  font-weight: 600;
  color: #374151;
  font-size: 0.9rem;
}

@media (max-width: 1200px){
  .roles-cards{ grid-template-columns: repeat(2, minmax(220px, 1fr)); }
}
@media (max-width: 640px){
  .roles-page{ padding: 18px; }
  .roles-actions{ flex-direction:column; align-items:flex-start; }
  .roles-cards{ grid-template-columns: 1fr; }
  .filter-row{ width: 100%; }
  .permissions-grid{ grid-template-columns: 1fr; }
  .modal-content{ width: 95%; margin: 20px; }
}


/* ============== */


.roles-page{
  padding: 26px 26px 40px;
  background: radial-gradient(1000px 380px at 50% 0%, #f0f3ff 0%, var(--bg) 55%, #f6f8ff 100%);
  min-height: calc(100vh - 80px);
  color: var(--text);
}

.roles-topbar{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap: 16px;
  margin-bottom: 16px;
}

.roles-title{
  font-size: 34px;
  line-height: 1.15;
  margin: 0;
  font-weight: 800;
  letter-spacing: -0.3px;
}

.roles-total-users{
  text-align:right;
  padding-top: 4px;
}
.roles-total-users .num{
  font-size: 32px;
  font-weight: 900;
  line-height: 1;
}
.roles-total-users .label{
  font-size: 12px;
  font-weight: 800;
  letter-spacing: .6px;
  color: var(--muted);
  margin-top: 4px;
}

.roles-actions{
  display:flex;
  gap: 12px;
  margin: 6px 0 18px;
}

.btn-ui{
  display:inline-flex;
  align-items:center;
  gap: 10px;
  border-radius: 10px;
  padding: 10px 14px;
  font-weight: 700;
  font-size: 14px;
  border: 1px solid transparent;
  cursor: pointer;
  text-decoration: none;
  box-shadow: 0 6px 14px rgba(15, 23, 42, .06);
}

.btn-primary-ui{
  background: var(--primary);
  color: #fff;
}
.btn-primary-ui:hover{ background: var(--primary-dark); }

.btn-outline-ui{
  background: #f2f6ff;
  color: #1f2a44;
  border-color: #d7e4ff;
}
.btn-outline-ui:hover{
  background: #eaf1ff;
}

.roles-cards{
  display:grid;
  grid-template-columns: repeat(4, minmax(200px, 1fr));
  gap: 18px;
  margin: 0 0 18px;
}

.stat-card{
  background: var(--card);
  border: 1px solid #eef1ff;
  border-radius: var(--radius);
  box-shadow: var(--shadow-soft);
  padding: 18px 18px;
  display:flex;
  align-items:center;
  gap: 16px;
}

.stat-icon{
  width: 64px;
  height: 64px;
  border-radius: 16px;
  display:flex;
  align-items:center;
  justify-content:center;
  color: #0b1b3a;
  font-size: 22px;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,.35);
}

.bg-indigo{ background: #c8ccff; }
.bg-red{ background: #ffb2b2; }
.bg-teal{ background: #bdf3e2; }
.bg-orange{ background: #ffd2a1; }

.stat-number{
  font-size: 28px;
  font-weight: 900;
  line-height: 1;
}
.stat-title{
  margin-top: 6px;
  font-weight: 900;
  letter-spacing: .5px;
  font-size: 12px;
  color: #1f2a44;
}
.stat-subtitle{
  margin-top: 4px;
  color: var(--muted);
  font-size: 13px;
  font-weight: 600;
}

.roles-filter{
  margin: 6px 0 14px;
}

.filter-row{
  display:flex;
  flex-direction:column;
  gap: 10px;
  width: 340px;
}

.select-wrap{
  position:relative;
}
.select-ui{
  appearance:none;
  width:100%;
  border-radius: 10px;
  padding: 12px 40px 12px 14px;
  border: 1px solid #dfe6ff;
  background: #ffffff;
  font-weight: 700;
  color: #1f2a44;
  box-shadow: 0 6px 14px rgba(15, 23, 42, .05);
}
.select-icon{
  position:absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #5b6b8a;
  pointer-events:none;
  font-size: 12px;
}

.roles-table-card{
  background: var(--card);
  border: 1px solid #e8ecff;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 0;
  overflow:hidden;
}

.roles-table{
  width:100%;
  border-collapse: separate;
  border-spacing: 0;
}

.roles-table thead th{
  background: #f7f9ff;
  color: #334155;
  font-size: 13px;
  font-weight: 900;
  padding: 14px 16px;
  text-align:left;
  border-bottom: 1px solid #edf0ff;
  white-space: nowrap;
}

.th-sort i{
  margin-left: 8px;
  color: #94a3b8;
  font-size: 12px;
}

.roles-table tbody td{
  padding: 14px 16px;
  border-bottom: 1px solid #f0f3ff;
  vertical-align: middle;
}

.roles-table tbody tr:hover{
  background: #fbfcff;
}

.col-center{
  text-align:center;
}

.muted{
  color: var(--muted);
  font-weight: 600;
}

.role-cell{
  display:flex;
  align-items:center;
  gap: 12px;
}
.role-icon{
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: #f2f6ff;
  border: 1px solid #e4ebff;
  display:flex;
  align-items:center;
  justify-content:center;
  color: #1e3a8a;
  font-size: 18px;
}
.role-name{
  font-weight: 900;
  font-size: 16px;
}
.role-tag{
  margin-top: 2px;
  font-size: 12px;
  font-weight: 800;
  color: #6b7aa0;
}

.mini-metric{
  display:inline-flex;
  align-items:center;
  gap: 8px;
  font-weight: 800;
  color: #334155;
}
.mini-metric i{
  color: #1f2a44;
  opacity: .9;
}

.pill{
  display:inline-flex;
  align-items:center;
  gap: 8px;
  padding: 8px 14px;
  border-radius: 999px;
  font-weight: 900;
  font-size: 13px;
  letter-spacing: .3px;
  white-space: nowrap;
}
.pill-success{
  background: #1f9d7a;
  color: #fff;
  box-shadow: 0 10px 18px rgba(31, 157, 122, .18);
}

.actions{
  display:flex;
  align-items:center;
  justify-content:center;
  gap: 10px;
  flex-wrap: wrap;
}

.btn-small{
  border-radius: 10px;
  padding: 9px 12px;
  font-weight: 900;
  font-size: 13px;
  border: 1px solid transparent;
  cursor:pointer;
  display:inline-flex;
  align-items:center;
  gap: 8px;
  background:#fff;
}

.btn-edit{
  background:#2f6fed;
  color:#fff;
  box-shadow: 0 10px 16px rgba(47, 111, 237, .18);
}
.btn-edit:hover{ background: #2458c4; }

.btn-review{
  background:#f2f6ff;
  border-color:#d7e4ff;
  color:#1f2a44;
}
.btn-review:hover{ background:#eaf1ff; }

.btn-delete{
  background:#ef4444;
  color:#fff;
  box-shadow: 0 10px 16px rgba(239, 68, 68, .18);
}
.btn-delete:hover{ background:#dc2626; }

.inline{ display:inline; }

.table-footer{
  padding: 14px 16px;
  color: #475569;
  font-weight: 700;
  background: #fbfcff;
}

@media (max-width: 1200px){
  .roles-cards{ grid-template-columns: repeat(2, minmax(220px, 1fr)); }
}
@media (max-width: 640px){
  .roles-page{ padding: 18px; }
  .roles-actions{ flex-direction:column; align-items:flex-start; }
  .roles-cards{ grid-template-columns: 1fr; }
  .filter-row{ width: 100%; }
}

</style>
<script>
function showCreateModal() {
    document.getElementById('createRoleModal').style.display = 'flex';
}

function closeCreateModal() {
    document.getElementById('createRoleModal').style.display = 'none';
}

function editRole(id) {
    // Load role data for editing
    fetch(`/admin/roles/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_role_name').value = data.name;
            document.getElementById('edit_role_description').value = data.description || '';
            
            // Load permissions
            const permissionsContainer = document.getElementById('edit_permissions');
            permissionsContainer.innerHTML = `
                <label class="permission-item">
                    <input type="checkbox" name="permissions[]" value="view_dashboard" ${data.permissions?.includes('view_dashboard') ? 'checked' : ''}>
                    <span>View Dashboard</span>
                </label>
                <label class="permission-item">
                    <input type="checkbox" name="permissions[]" value="manage_users" ${data.permissions?.includes('manage_users') ? 'checked' : ''}>
                    <span>Manage Users</span>
                </label>
                <label class="permission-item">
                    <input type="checkbox" name="permissions[]" value="create_content" ${data.permissions?.includes('create_content') ? 'checked' : ''}>
                    <span>Create Content</span>
                </label>
                <label class="permission-item">
                    <input type="checkbox" name="permissions[]" value="edit_content" ${data.permissions?.includes('edit_content') ? 'checked' : ''}>
                    <span>Edit Content</span>
                </label>
                <label class="permission-item">
                    <input type="checkbox" name="permissions[]" value="delete_content" ${data.permissions?.includes('delete_content') ? 'checked' : ''}>
                    <span>Delete Content</span>
                </label>
                <label class="permission-item">
                    <input type="checkbox" name="permissions[]" value="manage_roles" ${data.permissions?.includes('manage_roles') ? 'checked' : ''}>
                    <span>Manage Roles</span>
                </label>
            `;
            
            // Set form action
            document.getElementById('editRoleForm').action = `/admin/roles/${id}`;
            
            // Show modal
            document.getElementById('editRoleModal').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error loading role data:', error);
            // Fallback: show basic edit modal
            document.getElementById('edit_role_name').value = '';
            document.getElementById('edit_role_description').value = '';
            document.getElementById('editRoleForm').action = `/admin/roles/${id}`;
            document.getElementById('editRoleModal').style.display = 'flex';
        });
}

function closeEditModal() {
    document.getElementById('editRoleModal').style.display = 'none';
}

function viewRole(id) {
    // Load role details for viewing
    fetch(`/admin/roles/${id}`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('viewRoleContent');
            content.innerHTML = `
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div class="role-icon" style="width: 60px; height: 60px; font-size: 24px;">
                        <i class="fas fa-${data.name == 'admin' ? 'crown' : (data.name == 'author' ? 'pen-fancy' : 'user')}"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e293b;">${data.name}</h4>
                        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.9rem;">ID: #${String(data.id).padStart(4, '0')}</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h5 style="margin: 0 0 8px 0; font-weight: 700; color: #374151;">Description</h5>
                    <p style="margin: 0; color: #64748b; line-height: 1.6;">${data.description || 'No description available.'}</p>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <h5 style="margin: 0 0 8px 0; font-weight: 700; color: #374151;">Users</h5>
                        <div class="mini-metric">
                            <i class="fas fa-users"></i>
                            <span>${data.user_count || 0}</span>
                        </div>
                    </div>
                    <div>
                        <h5 style="margin: 0 0 8px 0; font-weight: 700; color: #374151;">Permissions</h5>
                        <div class="mini-metric">
                            <i class="fas fa-key"></i>
                            <span>${data.permissions_count || 0}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h5 style="margin: 0 0 8px 0; font-weight: 700; color: #374151;">Status</h5>
                    <span class="pill pill-success">
                        <i class="fas fa-circle"></i>
                        ${data.status || 'ACTIVE'}
                    </span>
                </div>
            `;
            
            document.getElementById('viewRoleModal').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error loading role details:', error);
            // Fallback: show basic view modal
            const content = document.getElementById('viewRoleContent');
            content.innerHTML = `
                <div style="text-align: center; padding: 20px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #f59e0b; margin-bottom: 1rem; display: block;"></i>
                    <p style="color: #64748b;">Unable to load role details. Please try again.</p>
                </div>
            `;
            document.getElementById('viewRoleModal').style.display = 'flex';
        });
}

function closeViewModal() {
    document.getElementById('viewRoleModal').style.display = 'none';
}

function applyFilter() {
    const filter = document.getElementById('role_filter').value;
    const url = new URL(window.location);
    if (filter) {
        url.searchParams.set('filter', filter);
    } else {
        url.searchParams.delete('filter');
    }
    window.location.href = url.toString();
}

function exportRoles() {
    window.location.href = '/admin/roles/export';
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>
@endpush