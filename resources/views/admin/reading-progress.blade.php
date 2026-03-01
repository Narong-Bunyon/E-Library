@extends('layouts.admin')

@section('title', 'Reading Progress - E-Library')

@section('page-title', 'Reading Progress Management')

@php
// Helper functions for progress display - defined at the top for global access
$getProgressColor = function($percentage) {
    if ($percentage >= 80) return '#28a745';
    if ($percentage >= 60) return '#ffc107';
    if ($percentage >= 40) return '#fd7e14';
    return '#dc3545';
};

$getStatusBadgeClass = function($status) {
    switch($status) {
        case 'completed': return 'bg-success';
        case 'in_progress': return 'bg-primary';
        case 'not_started': return 'bg-secondary';
        case 'abandoned': return 'bg-danger';
        default: return 'bg-secondary';
    }
};
@endphp

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $activeReaders }}</h3>
                            <p class="stat-label">Active Readers</p>
                            @if($progressThisMonth > 0)
                                <small class="text-success">
                                    <i class="fas fa-arrow-up"></i> +{{ $progressThisMonth }} this month
                                </small>
                            @endif
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
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $completionRate }}%</h3>
                            <p class="stat-label">Completion Rate</p>
                            <small class="text-muted">{{ $completedBooks }} completed</small>
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
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ round($avgReadingTime, 1) }}h</h3>
                            <p class="stat-label">Avg Reading Time</p>
                            <small class="text-muted">Per completed book</small>
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
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $inProgressBooks }}</h3>
                            <p class="stat-label">In Progress</p>
                            <small class="text-muted">Currently being read</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-book-reader me-2"></i>
                Reading Progress Management
            </h4>
            <p class="text-muted mb-0">Track and manage user reading progress</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProgressModal">
                <i class="fas fa-plus me-1"></i>
                Add Progress
            </button>
            <button class="btn btn-outline-danger" onclick="deleteSelectedProgress()" id="bulkDeleteBtn" style="display: none;">
                <i class="fas fa-trash me-1"></i>
                Delete Selected
            </button>
            <button class="btn btn-outline-secondary" onclick="exportProgress()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Reading Progress Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                All Progress Entries
                <span class="badge bg-primary ms-2">{{ $progress->count() }}</span>
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-2" id="selectAll" onchange="toggleSelectAll()">
                    <small class="text-muted">Select All</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" id="statusFilter" onchange="filterProgress()">
                        <option value="">All Status</option>
                        <option value="not_started">Not Started</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="abandoned">Abandoned</option>
                    </select>
                    <select class="form-select form-select-sm" id="userFilter" onchange="filterProgress()">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-select form-select-sm" id="bookFilter" onchange="filterProgress()">
                        <option value="">All Books</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAllTable" onchange="toggleSelectAllTable()">
                            </th>
                            <th>User</th>
                            <th>Book</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th>Last Read</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($progress as $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input progress-checkbox" value="{{ $item->id }}" onchange="updateBulkDeleteButton()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">{{ substr($item->user->name ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $item->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $item->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $item->book->title ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $item->book->author->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="progress-info">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="fw-bold">{{ $item->current_page }}/{{ $item->total_pages }}</span>
                                        <span class="ms-2 badge bg-info">{{ $item->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $item->progress_percentage }}%; background-color: {{ $getProgressColor($item->progress_percentage) }};">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $getStatusBadgeClass($item->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->started_at?->format('M d, Y') ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->last_read_at?->diffForHumans() ?? 'Never' }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editProgress({{ $item->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="viewProgress({{ $item->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteProgress({{ $item->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-book-reader fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No reading progress found.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProgressModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Add First Progress Entry
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($progress->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Showing {{ $progress->firstItem() }} to {{ $progress->lastItem() }} of {{ $progress->total() }} entries
                        </small>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $progress->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Side Stats -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Most Active Readers
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($activeReadersList as $reader)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $reader->user->name }}</strong>
                            <small class="text-muted d-block">{{ $reader->progress_count }} books</small>
                        </div>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar" style="width: {{ min(($reader->progress_count / $activeReadersList->first()->progress_count) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">No active readers found</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire me-2"></i>
                        Popular Books Being Read
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($popularBooks as $book)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $book->book->title }}</strong>
                            <small class="text-muted d-block">{{ $book->reader_count }} readers</small>
                        </div>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar" style="width: {{ min(($book->reader_count / $popularBooks->first()->reader_count) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">No books being read</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Progress Modal -->
<div class="modal fade" id="createProgressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Reading Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reading-progress.store') }}" method="POST" id="createProgressForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">User *</label>
                                <select class="form-select" name="user_id" required>
                                    <option value="">Select a user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Book *</label>
                                <select class="form-select" name="book_id" required>
                                    <option value="">Select a book</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Current Page *</label>
                                <input type="number" class="form-control" name="current_page" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Total Pages *</label>
                                <input type="number" class="form-control" name="total_pages" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Progress %</label>
                                <input type="number" class="form-control" name="progress_percentage" min="0" max="100" placeholder="Auto-calculated">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" required>
                                    <option value="not_started">Not Started</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="abandoned">Abandoned</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Add Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Progress Modal -->
<div class="modal fade" id="editProgressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Reading Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" id="editProgressForm" onsubmit="updateProgress(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editProgressId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">User *</label>
                                <select class="form-select" name="user_id" id="editUserId" required>
                                    <option value="">Select a user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Book *</label>
                                <select class="form-select" name="book_id" id="editBookId" required>
                                    <option value="">Select a book</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Current Page *</label>
                                <input type="number" class="form-control" name="current_page" id="editCurrentPage" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Total Pages *</label>
                                <input type="number" class="form-control" name="total_pages" id="editTotalPages" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Progress %</label>
                                <input type="number" class="form-control" name="progress_percentage" id="editProgressPercentage" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" id="editStatus" required>
                                    <option value="not_started">Not Started</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="abandoned">Abandoned</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Progress Modal -->
<div class="modal fade" id="viewProgressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reading Progress Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="progressDetails">
                <!-- Progress details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="editProgressFromView()">Edit Progress</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProgressModal" tabindex="-1" aria-labelledby="deleteProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteProgressModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Delete Progress
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger fa-3x"></i>
                    </div>
                    <h6 class="mb-3" id="deleteProgressMessage">Are you sure? You want to delete this progress entry?</h6>
                    <p class="text-muted mb-0" id="deleteProgressDescription">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteProgressBtn">
                    <i class="fas fa-trash me-2"></i>Delete Progress
                </button>
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

.user-avatar-sm, .book-cover-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    color: #6c757d;
}

.progress-info {
    min-width: 150px;
}

.pagination-wrapper .pagination {
    margin-bottom: 0;
}

.pagination-wrapper .page-link {
    color: #007bff;
    border-color: #dee2e6;
    padding: 0.5rem 0.75rem;
}

.pagination-wrapper .page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination-wrapper .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.pagination-wrapper .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.progress-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function toggleSelectAllTable() {
    const selectAll = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('.progress-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

// Bulk delete functionality
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.progress-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (checkboxes.length > 0) {
        bulkDeleteBtn.style.display = 'inline-block';
    } else {
        bulkDeleteBtn.style.display = 'none';
    }
}

function deleteSelectedProgress() {
    const checkboxes = document.querySelectorAll('.progress-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        showNotification('Please select at least one progress entry to delete', 'warning');
        return;
    }
    
    // Show delete confirmation modal with progress count
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteProgressModal'));
    document.getElementById('deleteProgressMessage').textContent = 
        `Are you sure? You want to delete these ${ids.length} progress entr${ids.length > 1 ? 'ies' : 'y'}?`;
    document.getElementById('deleteProgressDescription').textContent = 
        `This action cannot be undone. ${ids.length} progress entr${ids.length > 1 ? 'ies' : 'y'} will be permanently deleted.`;
    
    // Set up confirm button
    document.getElementById('confirmDeleteProgressBtn').onclick = function() {
        deleteModal.hide();
        performBulkDeleteProgress(ids);
    };
    
    deleteModal.show();
}

function performBulkDeleteProgress(ids) {
    fetch('/admin/reading-progress/bulk-delete', {
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
            showNotification(`${ids.length} progress entr${ids.length > 1 ? 'ies' : 'y'} deleted successfully`, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Error deleting progress entries', 'error');
        }
    })
    .catch(error => {
        showNotification('Error deleting progress entries', 'error');
    });
}

// CRUD operations
function editProgress(id) {
    fetch(`/admin/reading-progress/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editProgressId').value = data.id;
            document.getElementById('editUserId').value = data.user_id;
            document.getElementById('editBookId').value = data.book_id;
            document.getElementById('editCurrentPage').value = data.current_page;
            document.getElementById('editTotalPages').value = data.total_pages;
            document.getElementById('editProgressPercentage').value = data.progress_percentage;
            document.getElementById('editStatus').value = data.status;
            
            const form = document.getElementById('editProgressForm');
            form.action = `/admin/reading-progress/${id}`;
            
            new bootstrap.Modal(document.getElementById('editProgressModal')).show();
        });
}

function updateProgress(event) {
    event.preventDefault();
    
    const form = document.getElementById('editProgressForm');
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('editProgressModal'));
            modal.hide();
            location.reload();
        } else if (data && data.errors) {
            console.error('Validation errors:', data.errors);
            alert('Please fix the errors in the form.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the progress.');
    });
}

function viewProgress(id) {
    fetch(`/admin/reading-progress/${id}`)
        .then(response => response.json())
        .then(data => {
            // Calculate progress color in JavaScript
            let progressColor;
            if (data.progress_percentage >= 80) progressColor = '#28a745';
            else if (data.progress_percentage >= 60) progressColor = '#ffc107';
            else if (data.progress_percentage >= 40) progressColor = '#fd7e14';
            else progressColor = '#dc3545';
            
            // Calculate status badge class in JavaScript
            let statusBadgeClass;
            switch(data.status) {
                case 'completed': statusBadgeClass = 'bg-success'; break;
                case 'in_progress': statusBadgeClass = 'bg-primary'; break;
                case 'not_started': statusBadgeClass = 'bg-secondary'; break;
                case 'abandoned': statusBadgeClass = 'bg-danger'; break;
                default: statusBadgeClass = 'bg-secondary';
            }
            
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>User:</strong> ${data.user?.name || 'N/A'} (${data.user?.email || 'N/A'})<br>
                        <strong>Book:</strong> ${data.book?.title || 'N/A'}<br>
                        <strong>Progress:</strong> ${data.current_page}/${data.total_pages} (${data.progress_percentage}%)<br>
                        <strong>Status:</strong> <span class="badge ${statusBadgeClass}">${ucfirst(data.status?.replace('_', ' ') || 'N/A')}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Started:</strong> ${data.started_at || 'N/A'}<br>
                        <strong>Last Read:</strong> ${data.last_read_at || 'N/A'}<br>
                        <strong>Completed:</strong> ${data.completed_at || 'N/A'}<br>
                        <strong>Created:</strong> ${data.created_at || 'N/A'}<br>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Progress Bar:</strong>
                    <div class="progress mt-2">
                        <div class="progress-bar" style="width: ${data.progress_percentage}%; background-color: ${progressColor};">
                            ${data.progress_percentage}%
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('progressDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewProgressModal')).show();
        });
}

function editProgressFromView() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewProgressModal'));
    modal.hide();
    
    const id = document.getElementById('editProgressId').value;
    editProgress(id);
}

function deleteProgress(id) {
    // Fetch progress information first
    fetch(`/admin/reading-progress/${id}`)
        .then(response => response.json())
        .then(progress => {
            // Show delete confirmation modal with progress details
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteProgressModal'));
            document.getElementById('deleteProgressMessage').textContent = 
                `Are you sure? You want to delete this progress entry?`;
            document.getElementById('deleteProgressDescription').textContent = 
                `This action cannot be undone. The progress for ${progress.book_title || 'Unknown Book'} by ${progress.user_name || 'Unknown User'} will be permanently deleted.`;
            
            // Set up confirm button
            document.getElementById('confirmDeleteProgressBtn').onclick = function() {
                deleteModal.hide();
                performDeleteProgress(id);
            };
            
            deleteModal.show();
        })
        .catch(error => {
            showNotification('Error fetching progress information', 'error');
        });
}

function performDeleteProgress(id) {
    fetch(`/admin/reading-progress/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Progress deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Error deleting progress', 'error');
        }
    })
    .catch(error => {
        showNotification('Error deleting progress', 'error');
    });
}

function exportProgress() {
    window.location.href = '/admin/reading-progress/export';
}

function filterProgress() {
    const status = document.getElementById('statusFilter').value;
    const userId = document.getElementById('userFilter').value;
    const bookId = document.getElementById('bookFilter').value;
    
    const url = new URL(window.location);
    if (status) url.searchParams.set('status', status);
    if (userId) url.searchParams.set('user_id', userId);
    if (bookId) url.searchParams.set('book_id', bookId);
    
    window.location.href = url.toString();
}

// Helper functions (only JavaScript-specific functions)
function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Auto-calculate progress percentage
document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('createProgressForm');
    const editForm = document.getElementById('editProgressForm');
    
    function calculateProgress(form) {
        const currentPage = form.querySelector('input[name="current_page"]');
        const totalPages = form.querySelector('input[name="total_pages"]');
        const progressPercentage = form.querySelector('input[name="progress_percentage"]');
        
        if (currentPage.value && totalPages.value) {
            const percentage = Math.round((currentPage.value / totalPages.value) * 100);
            progressPercentage.value = Math.min(percentage, 100);
        }
    }
    
    createForm?.addEventListener('input', function(e) {
        if (e.target.name === 'current_page' || e.target.name === 'total_pages') {
            calculateProgress(createForm);
        }
    });
    
    editForm?.addEventListener('input', function(e) {
        if (e.target.name === 'current_page' || e.target.name === 'total_pages') {
            calculateProgress(editForm);
        }
    });
});
</script>
@endpush
@endsection
