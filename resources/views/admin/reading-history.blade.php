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
                    <tbody>
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
                                    <button class="btn btn-outline-warning btn-sm" onclick="addToFavorites({{ $item->user_id }}, {{ $item->book_id }})" title="Add to Favorites">
                                        <i class="fas fa-heart"></i>
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
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Showing {{ $history->firstItem() }} to {{ $history->lastItem() }} of {{ $history->total() }} entries
                        </small>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $history->links('pagination::bootstrap-4') }}
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
                        <label class="form-label">Progress (%)</label>
                        <input type="number" class="form-control" name="progress_percentage" min="0" max="100" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pages Read</label>
                        <input type="number" class="form-control" name="pages_read" min="0" value="0">
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
            const categories = data.progress.book.categories && data.progress.book.categories.length > 0 
                ? data.progress.book.categories.map(cat => cat.name).join(', ')
                : 'N/A';
                
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>User:</strong> ${data.progress.user?.name || 'N/A'} (${data.progress.user?.email || 'N/A'})<br>
                        <strong>Book:</strong> ${data.progress.book?.title || 'N/A'}<br>
                        <strong>Author:</strong> ${data.progress.book?.author?.name || 'N/A'}<br>
                        <strong>Categories:</strong> ${categories}<br>
                        <strong>Progress:</strong> ${data.progress.progress_percentage || 0}%<br>
                        <strong>Pages Read:</strong> ${data.progress.pages_read || 0} / ${data.progress.book?.pages || 0}<br>
                        <strong>Status:</strong> ${data.progress.status || 'N/A'}<br>
                        <strong>Started:</strong> ${data.progress.created_at || 'N/A'}<br>
                        <strong>Last Updated:</strong> ${data.progress.updated_at || 'N/A'}
                    </div>
                    <div class="col-md-6">
                        <strong>Book Description:</strong><br>
                        <p>${data.progress.book?.description || 'No description available'}</p>
                        <strong>Recent Reading Sessions:</strong><br>
                        ${data.reading_sessions && data.reading_sessions.length > 0 ? 
                            data.reading_sessions.map(session => `Session: ${session.create_at} - ${session.status}`).join('<br>') : 
                            'No recent sessions found'
                        }
                    </div>
                </div>
            `;
            document.getElementById('progressDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewProgressModal')).show();
        });
}

function addToFavorites(userId, bookId) {
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
            alert('Book added to favorites successfully!');
        } else {
            alert(data.message || 'Error adding to favorites');
        }
    })
    .catch(error => {
        alert('Error adding to favorites: ' + error.message);
    });
}

function exportReadingHistory() {
    window.location.href = '/admin/reading-history/export';
}

function filterReadingHistory() {
    const userId = document.getElementById('userFilter').value;
    const bookId = document.getElementById('bookFilter').value;
    const status = document.getElementById('statusFilter').value;
    const dateRange = document.getElementById('dateFilter').value;
    
    const url = new URL(window.location);
    
    if (userId) url.searchParams.set('user_id', userId);
    else url.searchParams.delete('user_id');
    
    if (bookId) url.searchParams.set('book_id', bookId);
    else url.searchParams.delete('book_id');
    
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    
    if (dateRange) url.searchParams.set('date_range', dateRange);
    else url.searchParams.delete('date_range');
    
    window.location.href = url.toString();
}

// Handle form submission
document.getElementById('addProgressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('addProgressModal'));
            modal.hide();
            location.reload();
        } else {
            alert(data.message || 'Error adding reading progress');
        }
    })
    .catch(error => {
        alert('Error adding reading progress: ' + error.message);
    });
});
</script>
@endpush
@endsection
