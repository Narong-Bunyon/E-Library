@extends('layouts.admin')

@section('title', 'Reading History - E-Library')

@section('page-title', 'Reading History')

@php
// Helper functions for display
$getProgressColor = function($percentage) {
    if ($percentage >= 80) return '#28a745';
    if ($percentage >= 60) return '#ffc107';
    if ($percentage >= 40) return '#fd7e14';
    return '#dc3545';
};

$getProgressBadgeClass = function($status) {
    switch($status) {
        case 'completed': return 'bg-success';
        case 'in_progress': return 'bg-primary';
        case 'not_started': return 'bg-secondary';
        case 'abandoned': return 'bg-danger';
        default: return 'bg-secondary';
    }
};

$getCategoryColor = function($categoryName) {
    $colors = [
        'Programming' => '#007bff',
        'Design' => '#28a745',
        'Database' => '#ffc107',
        'Web Development' => '#17a2b8',
        'Mobile' => '#6f42c1',
        'DevOps' => '#fd7e14',
        'Security' => '#dc3545',
        'AI/ML' => '#20c997',
    ];
    return $colors[$categoryName] ?? '#6c757d';
};
@endphp

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $totalSessions }}</h3>
                        <p class="stat-label mb-1">Total Sessions</p>
                        <small class="text-muted">All reading sessions</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card success">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $completedBooks }}</h3>
                        <p class="stat-label mb-1">Books Read</p>
                        <small class="text-muted">Completed books</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card warning">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $avgReadingTime }}</h3>
                        <p class="stat-label mb-1">Avg Reading Time</p>
                        <small class="text-muted">Per session</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card info">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $activeReaders }}</h3>
                        <p class="stat-label mb-1">Active Readers</p>
                        <small class="text-muted">Last 7 days</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-history me-2"></i>
                Reading History
            </h4>
            <p class="text-muted mb-0">Track user reading progress and completed books</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgressModal">
                <i class="fas fa-plus me-1"></i>
                Add Progress
            </button>
            <button class="btn btn-outline-secondary" onclick="exportReadingHistory()">
                <i class="fas fa-download me-1"></i>
                Export History
            </button>
        </div>
    </div>

    <!-- Reading History Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Reading Sessions
                <span class="badge bg-primary ms-2">{{ $history->count() }}</span>
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-2" id="selectAll" onchange="toggleSelectAll()">
                    <small class="text-muted">Select All</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" id="userFilter" onchange="filterReadingHistory()">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-select form-select-sm" id="bookFilter" onchange="filterReadingHistory()">
                        <option value="">All Books</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                    <select class="form-select form-select-sm" id="statusFilter" onchange="filterReadingHistory()">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="not_started">Not Started</option>
                        <option value="abandoned">Abandoned</option>
                    </select>
                    <select class="form-select form-select-sm" id="dateFilter" onchange="filterReadingHistory()">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
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
                            <th>Started</th>
                            <th>Last Updated</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reading-history-table">
                        @forelse ($history as $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input history-checkbox" value="{{ $item->id }}" onchange="updateBulkActions()">
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
                                <small class="text-muted">{{ $item->created_at?->diffForHumans() ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->updated_at?->diffForHumans() ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="progress-info">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="progress-text">{{ $item->progress_percentage ?? 0 }}% Complete</span>
                                        <span class="progress-pages">{{ $item->pages_read ?? 0 }} / {{ $item->book->pages ?? 0 }} pages</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $item->progress_percentage ?? 0 }}%; background-color: {{ $getProgressColor($item->progress_percentage ?? 0) }};"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $getProgressBadgeClass($item->status ?? 'not_started') }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status ?? 'not_started')) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info btn-sm" onclick="viewProgressDetails({{ $item->id }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" onclick="showAddToFavoritesModal({{ $item->user_id }}, {{ $item->book_id }}, '{{ $item->book_title ?? 'Unknown Book' }}', '{{ $item->user_name ?? 'Unknown User' }}')" title="Add to Favorites">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="showDeleteHistoryModal({{ $item->id }}, '{{ $item->book_title ?? 'Unknown Book' }}', '{{ $item->user_name ?? 'Unknown User' }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No reading history found.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgressModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Add First Reading Progress
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($history->hasPages())
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="pagination-info">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Showing <span id="showing-from">{{ $history->firstItem() }}</span> to <span id="showing-to">{{ $history->lastItem() }}</span> of <span id="total-entries">{{ $history->total() }}</span> entries
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pagination-wrapper d-flex justify-content-end" id="pagination-container">
                            {{ $history->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Progress Modal -->
<div class="modal fade" id="addProgressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Reading Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reading-history.add-progress') }}" method="POST" id="addProgressForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">User *</label>
                        <select class="form-select" name="user_id" required>
                            <option value="">Select a user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book *</label>
                        <select class="form-select" name="book_id" required>
                            <option value="">Select a book</option>
                            @foreach ($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Page</label>
                        <input type="number" class="form-control" name="current_page" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Pages</label>
                        <input type="number" class="form-control" name="total_pages" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Progress (%)</label>
                        <input type="number" class="form-control" name="progress_percentage" min="0" max="100" value="0">
                        <small class="text-muted">Leave empty to auto-calculate from pages</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="not_started">Not Started</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="abandoned">Abandoned</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Add Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Progress Details Modal -->
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
            </div>
        </div>
    </div>
</div>

<!-- Add to Favorites Confirmation Modal -->
<div class="modal fade" id="addToFavoritesModal" tabindex="-1" aria-labelledby="addToFavoritesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addToFavoritesModalLabel">
                    <i class="fas fa-heart text-danger me-2"></i>
                    Add to Favorites
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="mb-3">
                        <i class="fas fa-heart text-danger fa-3x"></i>
                    </div>
                    <h6 class="mb-3" id="addToFavoritesMessage">Are you sure? You want to add this to favorites?</h6>
                    <p class="text-muted mb-0" id="addToFavoritesDescription">This book will be added to the user's favorites list.</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmAddToFavoritesBtn">
                    <i class="fas fa-heart me-2"></i>Add to Favorites
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteHistoryModal" tabindex="-1" aria-labelledby="deleteHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteHistoryModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Delete History
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger fa-3x"></i>
                    </div>
                    <h6 class="mb-3" id="deleteHistoryMessage">Are you sure? You want to delete this reading history?</h6>
                    <p class="text-muted mb-0" id="deleteHistoryDescription">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteHistoryBtn">
                    <i class="fas fa-trash me-2"></i>Delete History
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

.progress-text {
    font-weight: 600;
    color: #495057;
}

.progress-pages {
    font-size: 12px;
    color: #6c757d;
}

.pagination-wrapper .pagination {
    margin-bottom: 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.pagination-wrapper .page-link {
    color: #6c757d;
    border: none;
    background: #fff;
    padding: 10px 16px;
    margin: 0 2px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-weight: 500;
    min-width: 44px;
    text-align: center;
}

.pagination-wrapper .page-link:hover {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    border: none;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    transform: translateY(-2px);
}

.pagination-wrapper .page-item.disabled .page-link {
    background: #f8f9fa;
    color: #adb5bd;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    border: none;
}

.pagination-info {
    padding: 10px 0;
    display: flex;
    align-items: center;
}

.pagination-info small {
    font-size: 0.875rem;
    font-weight: 500;
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination-wrapper .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .pagination-wrapper .page-link {
        padding: 8px 12px;
        font-size: 0.875rem;
        min-width: 36px;
        margin: 1px;
    }
    
    .pagination-info {
        text-align: center;
        margin-bottom: 15px;
    }
    
    .row.mt-4 > div {
        text-align: center !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.history-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkActions();
}

function toggleSelectAllTable() {
    const selectAll = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('.history-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.history-checkbox:checked');
    // Add bulk action buttons if needed
}

// CRUD operations
function viewProgressDetails(id) {
    fetch(`/admin/reading-history/${id}`)
        .then(response => response.json())
        .then(data => {
            if (!data) {
                showNotification('Error loading reading history details', 'error');
                return;
            }
            
            const categories = data.book && data.book.categories && data.book.categories.length > 0 
                ? data.book.categories.map(cat => cat.name).join(', ')
                : 'N/A';
                
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>User:</strong> ${data.user?.name || 'N/A'} (${data.user?.email || 'N/A'})<br>
                        <strong>Book:</strong> ${data.book?.title || 'N/A'}<br>
                        <strong>Author:</strong> ${data.book?.author?.name || 'N/A'}<br>
                        <strong>Categories:</strong> ${categories}<br>
                        <strong>Progress:</strong> ${data.progress_percentage || 0}%<br>
                        <strong>Pages Read:</strong> ${data.current_page || 0} / ${data.book?.pages || 0}<br>
                        <strong>Status:</strong> ${data.status || 'N/A'}<br>
                        <strong>Started:</strong> ${data.created_at ? new Date(data.created_at).toLocaleString() : 'N/A'}<br>
                        <strong>Last Updated:</strong> ${data.updated_at ? new Date(data.updated_at).toLocaleString() : 'N/A'}<br>
                        <strong>Completed:</strong> ${data.completed_at ? new Date(data.completed_at).toLocaleString() : 'Not completed'}
                    </div>
                    <div class="col-md-6">
                        <strong>Book Description:</strong><br>
                        <p>${data.book?.description || 'No description available'}</p>
                        <strong>Reading Notes:</strong><br>
                        <p>${data.notes || 'No notes available'}</p>
                        <strong>Progress Summary:</strong><br>
                        <div class="progress mb-2">
                            <div class="progress-bar ${data.progress_percentage >= 80 ? 'bg-success' : data.progress_percentage >= 50 ? 'bg-warning' : 'bg-danger'}" 
                                 style="width: ${data.progress_percentage || 0}%">
                                ${data.progress_percentage || 0}%
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('progressDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewProgressModal')).show();
        })
        .catch(error => {
            console.error('Error fetching reading history details:', error);
            showNotification('Error loading reading history details', 'error');
        });
}

function showAddToFavoritesModal(userId, bookId, bookTitle, userName) {
    // Show add to favorites confirmation modal with details
    const modal = new bootstrap.Modal(document.getElementById('addToFavoritesModal'));
    document.getElementById('addToFavoritesMessage').textContent = 
        `Are you sure? You want to add this to favorites?`;
    document.getElementById('addToFavoritesDescription').textContent = 
        `"${bookTitle}" will be added to ${userName}'s favorites list.`;
    
    // Set up confirm button
    document.getElementById('confirmAddToFavoritesBtn').onclick = function() {
        modal.hide();
        performAddToFavorites(userId, bookId);
    };
    
    modal.show();
}

function performAddToFavorites(userId, bookId) {
    fetch('/admin/reading-history/add-to-favorites', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user_id: userId,
            book_id: bookId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Book added to favorites successfully!', 'success');
        } else {
            showNotification(data.message || 'Error adding to favorites', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to favorites: ' + error.message, 'error');
    });
}

function showDeleteHistoryModal(id, bookTitle, userName) {
    // Show delete confirmation modal with details
    const modal = new bootstrap.Modal(document.getElementById('deleteHistoryModal'));
    document.getElementById('deleteHistoryMessage').textContent = 
        `Are you sure? You want to delete this reading history?`;
    document.getElementById('deleteHistoryDescription').textContent = 
        `This action cannot be undone. The reading history for "${bookTitle}" by ${userName} will be permanently deleted.`;
    
    // Set up confirm button
    document.getElementById('confirmDeleteHistoryBtn').onclick = function() {
        modal.hide();
        performDeleteHistory(id);
    };
    
    modal.show();
}

function performDeleteHistory(id) {
    fetch(`/admin/reading-history/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Reading history deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Error deleting reading history', 'error');
        }
    })
    .catch(error => {
        showNotification('Error deleting reading history', 'error');
    });
}

// Helper function for notifications
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function exportReadingHistory() {
    window.location.href = '/admin/reading-history/export';
}

function filterReadingHistory() {
    // Use AJAX to filter without page reload
    loadReadingHistoryPage(1);
}

// Handle form submission
document.getElementById('addProgressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Reading progress added successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('addProgressModal'));
            modal.hide();
            this.reset();
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            // Handle validation errors
            const errorMessages = Object.values(data.errors).flat().join(', ');
            showNotification('Error: ' + errorMessages, 'error');
        } else {
            showNotification(data.message || 'Error adding reading progress', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding reading progress: ' + error.message, 'error');
    });
});

// Auto-calculate progress percentage
document.addEventListener('DOMContentLoaded', function() {
    const currentPageInput = document.querySelector('input[name="current_page"]');
    const totalPagesInput = document.querySelector('input[name="total_pages"]');
    const progressInput = document.querySelector('input[name="progress_percentage"]');
    
    function calculateProgress() {
        const currentPage = parseInt(currentPageInput.value) || 0;
        const totalPages = parseInt(totalPagesInput.value) || 0;
        
        if (currentPage > 0 && totalPages > 0) {
            const percentage = Math.round((currentPage / totalPages) * 100);
            progressInput.value = Math.min(percentage, 100);
        }
    }
    
    if (currentPageInput && totalPagesInput && progressInput) {
        currentPageInput.addEventListener('input', calculateProgress);
        totalPagesInput.addEventListener('input', calculateProgress);
    }
});

// AJAX Pagination
function loadReadingHistoryPage(page) {
    const userId = document.getElementById('userFilter').value;
    const bookId = document.getElementById('bookFilter').value;
    const status = document.getElementById('statusFilter').value;
    const dateRange = document.getElementById('dateFilter').value;
    
    const params = new URLSearchParams();
    if (page) params.append('page', page);
    if (userId) params.append('user_id', userId);
    if (bookId) params.append('book_id', bookId);
    if (status) params.append('status', status);
    if (dateRange) params.append('date_range', dateRange);
    
    fetch(`/admin/reading-history?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update table body
            document.getElementById('reading-history-table').innerHTML = data.html;
            
            // Update pagination
            document.getElementById('pagination-container').innerHTML = data.pagination;
            
            // Update info
            document.getElementById('showing-from').textContent = data.from;
            document.getElementById('showing-to').textContent = data.to;
            document.getElementById('total-entries').textContent = data.total;
            
            // Re-attach pagination click handlers
            attachPaginationHandlers();
        } else {
            showNotification('Error loading page', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error loading page: ' + error.message, 'error');
    });
}

// Attach click handlers to pagination links
function attachPaginationHandlers() {
    const paginationLinks = document.querySelectorAll('.pagination-wrapper .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            if (href && !this.classList.contains('disabled')) {
                const url = new URL(href, window.location.origin);
                const page = url.searchParams.get('page');
                loadReadingHistoryPage(page);
            }
        });
    });
}

// Initialize pagination handlers on page load
document.addEventListener('DOMContentLoaded', function() {
    attachPaginationHandlers();
});
</script>
@endpush
@endsection
