@extends('layouts.admin')

@section('title', 'Edit User - E-Library')

@section('page-title', 'Edit User')

@section('content')
<div class="edit-user">
    <div class="edit-header">
        <div class="header-left">
            <h2 class="section-title">Edit User</h2>
            <p class="section-description">Update user information and role</p>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.users') }}" class="btn btn--secondary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12,19 5,12 12,5"></polyline>
                </svg>
                Back to Users
            </a>
        </div>
    </div>

    <div class="edit-content">
        <div class="user-profile">
            <div class="profile-avatar">
                <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="avatar-info">
                    <h3>{{ $user->name }}</h3>
                    <p>{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="edit-form">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">User Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Reader</option>
                            <option value="author" {{ $user->role === 'author' ? 'selected' : '' }}>Author</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Account Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="role-info">
                    <h4>Role Permissions</h4>
                    <div class="permissions-list">
                        <div class="permission-item">
                            <div class="permission-icon">👤</div>
                            <div class="permission-content">
                                <strong>Reader:</strong>
                                <p>Can read books and manage personal account</p>
                            </div>
                        </div>
                        <div class="permission-item">
                            <div class="permission-icon">✍️</div>
                            <div class="permission-content">
                                <strong>Author:</strong>
                                <p>Can create and manage books, plus all reader permissions</p>
                            </div>
                        </div>
                        <div class="permission-item">
                            <div class="permission-icon">👑</div>
                            <div class="permission-content">
                                <strong>Administrator:</strong>
                                <p>Full system access, user management, and all permissions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17,21 17,13 7,13 7,21"></polyline>
                            <polyline points="7,3 7,8 15,8"></polyline>
                        </svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn btn--secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
