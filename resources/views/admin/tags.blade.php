@extends('layouts.admin')

@section('title', 'Tags Management - E-Library')

@section('page-title', 'Tags Management')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $totalTags }}</h3>
                            <p class="stat-label">Total Tags</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $taggedBooks }}</h3>
                            <p class="stat-label">Tagged Books</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $popularTags }}</h3>
                            <p class="stat-label">Popular Tags</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $newThisMonth }}</h3>
                            <p class="stat-label">New This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tags Management -->
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-tags me-2"></i>
                Tags Management
            </h4>
            <p class="text-muted mb-0">Manage book tags and categorization</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                <i class="fas fa-plus me-1"></i>
                Add New Tag
            </button>
            <button class="btn btn-outline-danger" onclick="deleteSelectedTags()" id="bulkDeleteBtn" style="display: none;">
                <i class="fas fa-trash me-1"></i>
                Delete Selected
            </button>
            <button class="btn btn-outline-secondary" onclick="exportTags()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-tags me-2"></i>
                All Tags
                <span class="badge bg-primary ms-2">{{ $tags->count() }}</span>
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-2" id="selectAll" onchange="toggleSelectAll()">
                    <small class="text-muted">Select All</small>
                </div>
                <div class="d-flex align-items-center">
                    <label class="form-label me-2 mb-0">View:</label>
                    <select class="form-select form-select-sm" id="viewMode" onchange="changeViewMode()">
                        <option value="grid">Grid</option>
                        <option value="table">Table</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse ($tags as $tag)
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <div class="tag-card">
                        <div class="tag-header">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input tag-checkbox" value="{{ $tag->id }}" onchange="updateBulkDeleteButton()">
                                </div>
                                <div class="tag-color" style="background-color: {{ $tag->color ?? '#6c757d' }};"></div>
                            </div>
                            <h6 class="tag-name">{{ $tag->name }}</h6>
                            <span class="tag-count">{{ $tag->books_count ?? 0 }} books</span>
                            @if($tag->description)
                                <small class="text-muted d-block mt-1">{{ Str::limit($tag->description, 50) }}</small>
                            @endif
                        </div>
                        <div class="tag-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editTag({{ $tag->id }})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="viewTag({{ $tag->id }})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTag({{ $tag->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No tags found. Create your first tag!</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                        <i class="fas fa-plus me-1"></i>
                        Create First Tag
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Table View (Hidden by default) -->
            <div id="tableView" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="selectAllTable" onchange="toggleSelectAllTable()">
                                </th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Color</th>
                                <th>Books Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tags as $tag)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input tag-checkbox-table" value="{{ $tag->id }}" onchange="updateBulkDeleteButton()">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="tag-color-sm me-2" style="background-color: {{ $tag->color ?? '#6c757d' }};"></div>
                                        <strong>{{ $tag->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-truncate d-block" style="max-width: 200px;">
                                        {{ $tag->description ?? 'No description' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}; color: white;">
                                        {{ $tag->color ?? 'Default' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $tag->books_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm" onclick="editTag({{ $tag->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewTag({{ $tag->id }})" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteTag({{ $tag->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No tags found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Tags Cloud -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-cloud me-2"></i>
                Tag Cloud
            </h5>
        </div>
        <div class="card-body">
            <div class="tag-cloud">
                @foreach ($tags->sortByDesc('books_count')->take(15) as $tag)
                    @php
                        $size = 'small';
                        if ($tag->books_count > 10) $size = 'large';
                        elseif ($tag->books_count > 5) $size = 'medium';
                    @endphp
                    <span class="tag-cloud-item {{ $size }}" style="background-color: {{ $tag->color ?? '#6c757d' }}; color: white;">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Create Tag Modal -->
<div class="modal fade" id="createTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tags.store') }}" method="POST" id="createTagForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tag Name *</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter tag name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Enter tag description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#007bff" id="color_primary" checked>
                                <label class="form-check-label" for="color_primary">
                                    <span class="color-preview" style="background-color: #007bff;"></span>
                                    Primary
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#28a745" id="color_success">
                                <label class="form-check-label" for="color_success">
                                    <span class="color-preview" style="background-color: #28a745;"></span>
                                    Success
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#ffc107" id="color_warning">
                                <label class="form-check-label" for="color_warning">
                                    <span class="color-preview" style="background-color: #ffc107;"></span>
                                    Warning
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#dc3545" id="color_danger">
                                <label class="form-check-label" for="color_danger">
                                    <span class="color-preview" style="background-color: #dc3545;"></span>
                                    Danger
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#6f42c1" id="color_purple">
                                <label class="form-check-label" for="color_purple">
                                    <span class="color-preview" style="background-color: #6f42c1;"></span>
                                    Purple
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger fa-3x"></i>
                    </div>
                    <h6 class="mb-3" id="deleteMessage">Are you sure? You want to delete this?</h6>
                    <p class="text-muted mb-0" id="deleteDescription">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Tag Modal -->
<div class="modal fade" id="editTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" id="editTagForm" onsubmit="updateTag(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editTagId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tag Name *</label>
                        <input type="text" class="form-control" name="name" id="editTagName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="editTagDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#007bff" id="edit_color_primary">
                                <label class="form-check-label" for="edit_color_primary">
                                    <span class="color-preview" style="background-color: #007bff;"></span>
                                    Primary
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#28a745" id="edit_color_success">
                                <label class="form-check-label" for="edit_color_success">
                                    <span class="color-preview" style="background-color: #28a745;"></span>
                                    Success
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#ffc107" id="edit_color_warning">
                                <label class="form-check-label" for="edit_color_warning">
                                    <span class="color-preview" style="background-color: #ffc107;"></span>
                                    Warning
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#dc3545" id="edit_color_danger">
                                <label class="form-check-label" for="edit_color_danger">
                                    <span class="color-preview" style="background-color: #dc3545;"></span>
                                    Danger
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="#6f42c1" id="edit_color_purple">
                                <label class="form-check-label" for="edit_color_purple">
                                    <span class="color-preview" style="background-color: #6f42c1;"></span>
                                    Purple
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Tag Modal -->
<div class="modal fade" id="viewTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tag Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="tagDetails">
                <!-- Tag details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="editTagFromView()">Edit Tag</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.stat-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.stat-card.success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    color: white;
}

.stat-card.warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: white;
}

.stat-card.info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 15px;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
    margin: 0;
}

.tag-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    height: 100%;
    transition: all 0.3s ease;
    cursor: pointer;
}

.tag-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.tag-header {
    margin-bottom: 10px;
}

.tag-name {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.tag-count {
    font-size: 12px;
    color: #6c757d;
}

.tag-color {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tag-color-sm {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tag-actions {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.tag-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.tag-cloud-item {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.tag-cloud-item:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.tag-cloud-item.small {
    font-size: 12px;
    padding: 6px 12px;
}

.tag-cloud-item.medium {
    font-size: 14px;
    padding: 8px 16px;
}

.tag-cloud-item.large {
    font-size: 16px;
    padding: 10px 20px;
    font-weight: bold;
}

.color-preview {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 5px;
    border: 1px solid #dee2e6;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-title {
    margin: 0;
}

.btn-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
}

.btn-close:hover {
    opacity: 0.7;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

/* Notification animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Modal enhancements */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 1px solid #f0f0f0;
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #f0f0f0;
    padding: 1.5rem;
}
</style>
@endpush

@push('scripts')
<script>
// View mode switching
function changeViewMode() {
    const viewMode = document.getElementById('viewMode').value;
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    
    if (viewMode === 'table') {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
    } else {
        gridView.style.display = 'block';
        tableView.style.display = 'none';
    }
}

// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.tag-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function toggleSelectAllTable() {
    const selectAll = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('.tag-checkbox-table');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

// Bulk delete functionality
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.tag-checkbox:checked, .tag-checkbox-table:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (checkboxes.length > 0) {
        bulkDeleteBtn.style.display = 'inline-block';
    } else {
        bulkDeleteBtn.style.display = 'none';
    }
}

function deleteSelectedTags() {
    const checkboxes = document.querySelectorAll('.tag-checkbox:checked, .tag-checkbox-table:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        showNotification('Please select at least one tag to delete', 'warning');
        return;
    }
    
    // Fetch tag names for better confirmation message
    fetch('/admin/tags/bulk-info', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(tags => {
        // Show delete confirmation modal with tag names
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const tagNames = tags.map(tag => tag.name).join(', ');
        
        if (tags.length === 1) {
            document.getElementById('deleteMessage').textContent = 
                `Are you sure? You want to delete ${tagNames}?`;
            document.getElementById('deleteDescription').textContent = 
                `This action cannot be undone. The tag ${tagNames} will be permanently deleted.`;
        } else {
            document.getElementById('deleteMessage').textContent = 
                `Are you sure? You want to delete these ${tags.length} tags?`;
            document.getElementById('deleteDescription').textContent = 
                `This action cannot be undone. The following tags will be permanently deleted: ${tagNames}`;
        }
        
        // Set up confirm button
        document.getElementById('confirmDeleteBtn').onclick = function() {
            deleteModal.hide();
            performBulkDelete(ids);
        };
        
        deleteModal.show();
    })
    .catch(error => {
        showNotification('Error fetching tag information', 'error');
    });
}

function performBulkDelete(ids) {
    fetch('/admin/tags/bulk-delete', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${ids.length} tag${ids.length > 1 ? 's' : ''} deleted successfully`, 'success');
            location.reload();
        } else {
            showNotification('Error deleting tags: ' + data.error, 'error');
        }
    })
    .catch(error => {
        showNotification('Error deleting tags: ' + error.message, 'error');
    });
}

// CRUD operations
function editTag(id) {
    fetch(`/admin/tags/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editTagId').value = data.id;
            document.getElementById('editTagName').value = data.name;
            document.getElementById('editTagDescription').value = data.description || '';
            
            // Set color radio button
            const color = data.color || '#007bff';
            const colorRadio = document.querySelector(`input[name="color"][value="${color}"]`);
            if (colorRadio) {
                colorRadio.checked = true;
            }
            
            const form = document.getElementById('editTagForm');
            form.action = `/admin/tags/${id}`;
            
            new bootstrap.Modal(document.getElementById('editTagModal')).show();
        });
}

function updateTag(event) {
    event.preventDefault();
    
    const form = document.getElementById('editTagForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editTagModal'));
            modal.hide();
            location.reload();
        } else if (data && data.errors) {
            console.error('Validation errors:', data.errors);
            alert('Please fix the errors in the form.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the tag.');
    });
}

function viewTag(id) {
    fetch(`/admin/tags/${id}`)
        .then(response => response.json())
        .then(data => {
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Name:</strong> ${data.name}<br>
                        <strong>Description:</strong> ${data.description || 'No description'}<br>
                        <strong>Color:</strong> <span class="badge" style="background-color: ${data.color || '#6c757d'}; color: white;">${data.color || 'Default'}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Books Count:</strong> ${data.books_count || 0}<br>
                        <strong>Status:</strong> <span class="badge bg-success">Active</span>
                    </div>
                </div>
            `;
            document.getElementById('tagDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewTagModal')).show();
        })
        .catch(error => {
            showNotification('Error fetching tag details', 'error');
        });
}

function editTagFromView() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewTagModal'));
    modal.hide();
    
    const id = document.getElementById('editTagId').value;
    editTag(id);
}

function deleteTag(id) {
    // Fetch tag information first
    fetch(`/admin/tags/${id}`)
        .then(response => response.json())
        .then(tag => {
            // Show delete confirmation modal with tag name
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            document.getElementById('deleteMessage').textContent = 
                `Are you sure? You want to delete ${tag.name}?`;
            document.getElementById('deleteDescription').textContent = 
                `This action cannot be undone. The tag ${tag.name} will be permanently deleted.`;
            
            // Set up confirm button
            document.getElementById('confirmDeleteBtn').onclick = function() {
                deleteModal.hide();
                performSingleDelete(id);
            };
            
            deleteModal.show();
        })
        .catch(error => {
            showNotification('Error fetching tag information', 'error');
        });
}

function performSingleDelete(id) {
    fetch(`/admin/tags/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Tag deleted successfully', 'success');
            location.reload();
        } else {
            showNotification('Error deleting tag: ' + data.error, 'error');
        }
    })
    .catch(error => {
        showNotification('Error deleting tag: ' + error.message, 'error');
    });
}

function exportTags() {
    window.location.href = '/admin/tags/export';
}

// Notification system
function showNotification(message, type = 'info') {
    // Create notification container if it doesn't exist
    let notificationContainer = document.getElementById('notificationContainer');
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notificationContainer';
        notificationContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 350px;
        `;
        document.body.appendChild(notificationContainer);
    }
    
    // Create notification element
    const notification = document.createElement('div');
    const notificationId = 'notification-' + Date.now();
    notification.id = notificationId;
    
    // Set notification styles based on type
    const bgColors = {
        success: 'bg-success',
        error: 'bg-danger',
        warning: 'bg-warning',
        info: 'bg-info'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    notification.className = `alert ${bgColors[type]} alert-dismissible fade show mb-2`;
    notification.style.cssText = `
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 8px;
        animation: slideInRight 0.3s ease-out;
    `;
    
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas ${icons[type]} me-2"></i>
            <div class="flex-grow-1">${message}</div>
            <button type="button" class="btn-close ms-2" onclick="closeNotification('${notificationId}')"></button>
        </div>
    `;
    
    // Add to container
    notificationContainer.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        closeNotification(notificationId);
    }, 5000);
}

function closeNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}
</script>
@endpush
@endsection
