@extends('layouts.author')

@section('title', 'Reading History - E-Library')

@section('page-title', 'Reading History')

@push('styles')
<link href="{{ asset('css/reading-history.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Reading History</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReadingHistory()">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-primary" onclick="showAddReadingModal()">
                <i class="fas fa-plus me-2"></i>Add Reading Entry
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ $readingStats['total_books'] ?? 0 }}</h5>
                    <p>Total Books Read</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ $readingStats['completed_books'] ?? 0 }}</h5>
                    <p>Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ $readingStats['currently_reading'] ?? 0 }}</h5>
                    <p>Currently Reading</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ $readingStats['avg_pages'] ?? 0 }}</h5>
                    <p>Avg Pages/Book</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reading History Table -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-history me-2"></i>Reading History</h5>
            <div class="card-actions">
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="statusFilter" onchange="filterReadingHistory()">
                        <option value="">All Status</option>
                        <option value="reading">Currently Reading</option>
                        <option value="completed">Completed</option>
                        <option value="paused">Paused</option>
                        <option value="abandoned">Abandoned</option>
                    </select>
                    <div class="input-group" style="width: 250px;">
                        <input type="text" class="form-control form-control-sm search-input-custom" placeholder="Search books..." id="searchInput">
                        <button class="btn btn-outline-secondary" onclick="searchReadingHistory()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($readingHistory && $readingHistory->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover reading-history-table">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-book me-2"></i>Book Details</th>
                                <th><i class="fas fa-chart-line me-2"></i>Status</th>
                                <th><i class="fas fa-tasks me-2"></i>Progress</th>
                                <th><i class="fas fa-file-alt me-2"></i>Pages</th>
                                <th><i class="fas fa-calendar me-2"></i>Started</th>
                                <th><i class="fas fa-check-circle me-2"></i>Completed</th>
                                <th><i class="fas fa-sticky-note me-2"></i>Notes</th>
                                <th><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($readingHistory as $history)
                                <tr class="reading-history-row">
                                    <td>
                                        <div class="book-cell" style="gap: 0.5rem !important; padding: 0.3rem !important;">
                                            <div class="book-cover-container">
                                                @if($history->book && $history->book->cover_image)
                                                    <img src="{{ Str::startsWith($history->book->cover_image, ['http://', 'https://']) ? $history->book->cover_image : asset('storage/' . $history->book->cover_image) }}" 
                                                         alt="{{ $history->book->title }}" 
                                                         style="width: 35px !important; height: 50px !important; max-width: 35px !important; max-height: 50px !important; min-width: 35px !important; min-height: 50px !important; object-fit: cover !important; border-radius: 4px !important; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;">
                                                @else
                                                    <div class="book-placeholder-enhanced"
                                                         style="width: 32px !important; height: 45px !important; max-width: 32px !important; max-height: 45px !important; min-width: 32px !important; min-height: 45px !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; border-radius: 4px !important; display: flex !important; align-items: center !important; justify-content: center !important; color: white !important; font-size: 1rem !important; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="book-details" style="font-size: 1em !important; line-height: 1.1 !important;">
                                                <div class="book-title" style="font-size: 1rem !important; margin-bottom: 0.08rem !important;">{{ $history->book->title ?? 'Unknown Book' }}</div>
                                                <div class="book-author" style="font-size: 1rem !important; margin-bottom: 0.08rem !important;">
                                                    <i class="fas fa-user-edit me-1"></i>
                                                    {{ $history->book->author->name ?? 'Unknown Author' }}
                                                </div>
                                                @if($history->book->category)
                                                    <div class="book-category" style="font-size: 1rem !important; padding: 0.08rem 0.25rem !important; border-radius: 6px !important; display: inline-block !important;">
                                                        <i class="fas fa-folder me-1"></i>
                                                        {{ $history->book->category->name ?? 'Uncategorized' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge-enhanced {{ getStatusColor($history->status ?? 'reading') }}">
                                            <i class="fas {{ getStatusIcon($history->status ?? 'reading') }} me-1"></i>
                                            {{ ucfirst($history->status ?? 'reading') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress-cell">
                                            <div class="progress-enhanced">
                                                <div class="progress-bar-enhanced" style="width: {{ $history->progress_percentage ?? 0 }}%"></div>
                                            </div>
                                            <div class="progress-text">
                                                <strong>{{ $history->progress_percentage ?? 0 }}%</strong>
                                                <small class="text-muted d-block">{{ getProgressLabel($history->progress_percentage ?? 0) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pages-cell">
                                            <div class="pages-read">
                                                <strong>{{ $history->pages_read ?? 0 }}</strong>
                                                <small class="text-muted">read</small>
                                            </div>
                                            <div class="pages-total">
                                                <small class="text-muted">of {{ $history->total_pages ?? '?' }} total</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            @if($history->started_at)
                                                <div class="date-main">{{ \Carbon\Carbon::parse($history->started_at)->format('M d') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($history->started_at)->format('Y') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            @if($history->completed_at)
                                                <div class="date-main">{{ \Carbon\Carbon::parse($history->completed_at)->format('M d') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($history->completed_at)->format('Y') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="notes-cell">
                                            @if($history->notes)
                                                <div class="notes-preview" title="{{ $history->notes }}">
                                                    {{ Str::limit($history->notes, 50) }}
                                                </div>
                                            @else
                                                <span class="text-muted">No notes</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="actions-cell">
                                            <div class="btn-group-vertical" role="group">
                                                <button class="btn btn-outline-primary btn-sm action-btn" onclick="viewReadingHistory({{ $history->id }})" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm action-btn" onclick="editReadingHistory({{ $history->id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm action-btn" onclick="deleteReadingHistory({{ $history->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state-enhanced">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h5>No Reading History Found</h5>
                    <p class="text-muted">You haven't added any reading history entries yet. Start tracking your reading progress!</p>
                    <button class="btn btn-primary btn-lg" onclick="showAddReadingModal()">
                        <i class="fas fa-plus me-2"></i>Add Your First Reading Entry
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Pagination -->
    @if($readingHistory && $readingHistory->hasPages())
        <div class="d-flex justify-content-center mt-3 mb-3">
            <div style="display: flex; justify-content: center; align-items: center; padding: 0.5rem; background: #ffffff; border-radius: 6px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); margin-top: 0.5rem;">
                <div style="margin: 0; display: flex; gap: 0.25rem;" class="pagination">
                    {!! $readingHistory->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Add/Edit Reading History Modal -->
<div class="modal fade" id="readingHistoryModal" tabindex="-1">
    <div class="modal-dialog reading-modal">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-history me-2"></i>
                    <span id="readingModalTitle">Add Reading Entry</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="readingHistoryForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="book_id" class="form-label">Book *</label>
                                <select class="form-select" id="book_id" name="book_id" required>
                                    <option value="">Select a book</option>
                                    @if($userBooks && $userBooks->isNotEmpty())
                                        @foreach($userBooks as $book)
                                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>No books available</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="reading">Reading</option>
                                    <option value="completed">Completed</option>
                                    <option value="paused">Paused</option>
                                    <option value="abandoned">Abandoned</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="started_at" class="form-label">Started Date</label>
                                <input type="datetime-local" class="form-control" id="started_at" name="started_at">
                            </div>
                            <div class="mb-3">
                                <label for="completed_at" class="form-label">Completed Date</label>
                                <input type="datetime-local" class="form-control" id="completed_at" name="completed_at">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="progress_percentage" class="form-label">Progress (%)</label>
                                <input type="number" class="form-control" id="progress_percentage" name="progress_percentage" min="0" max="100" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pages_read" class="form-label">Pages Read</label>
                                <input type="number" class="form-control" id="pages_read" name="pages_read" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="total_pages" class="form-label">Total Pages</label>
                                <input type="number" class="form-control" id="total_pages" name="total_pages" min="0" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add your reading notes here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Reading Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteReadingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Delete Reading Entry
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. The reading history entry will be permanently deleted.
                </div>
                <p>Are you sure you want to delete this reading entry?</p>
                <div class="mb-3">
                    <label class="form-label">Type "DELETE" to confirm:</label>
                    <input type="text" class="form-control" id="deleteConfirmText" placeholder="DELETE">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash me-2"></i>Delete Entry
                </button>
            </div>
        </div>
    </div>
</div>

@php
function getStatusColor($status) {
    switch($status) {
        case 'reading':
            return 'bg-primary';
        case 'completed':
            return 'bg-success';
        case 'paused':
            return 'bg-warning';
        case 'abandoned':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

function getStatusIcon($status) {
    switch($status) {
        case 'reading':
            return 'fa-book-reader';
        case 'completed':
            return 'fa-check-circle';
        case 'paused':
            return 'fa-pause-circle';
        case 'abandoned':
            return 'fa-times-circle';
        default:
            return 'fa-question-circle';
    }
}

function getProgressLabel($percentage) {
    if ($percentage == 0) return 'Not started';
    if ($percentage < 25) return 'Just beginning';
    if ($percentage < 50) return 'Making progress';
    if ($percentage < 75) return 'Halfway there';
    if ($percentage < 100) return 'Almost done';
    return 'Completed';
}
@endphp


<script>
let currentReadingId = null;

function showAddReadingModal() {
    currentReadingId = null;
    document.getElementById('readingModalTitle').textContent = 'Add Reading Entry';
    document.getElementById('readingHistoryForm').reset();
    
    const modal = new bootstrap.Modal(document.getElementById('readingHistoryModal'));
    modal.show();
}

function viewReadingHistory(id) {
    // Fetch reading history details and show in modal
    fetch(`/author/reading-history/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateReadingModal(data.reading_history);
                document.getElementById('readingModalTitle').textContent = 'Reading Entry Details';
                // Make form read-only for viewing
                const form = document.getElementById('readingHistoryForm');
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => input.disabled = true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading reading history details');
        });
}

function editReadingHistory(id) {
    // Fetch reading history details and populate form for editing
    fetch(`/author/reading-history/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateReadingModal(data.reading_history);
                document.getElementById('readingModalTitle').textContent = 'Edit Reading Entry';
                // Enable form for editing
                const form = document.getElementById('readingHistoryForm');
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => input.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading reading history for editing');
        });
}

function populateReadingModal(reading) {
    document.getElementById('book_id').value = reading.book_id || '';
    document.getElementById('status').value = reading.status || 'reading';
    document.getElementById('started_at').value = reading.started_at ? new Date(reading.started_at).toISOString().slice(0, 16) : '';
    document.getElementById('completed_at').value = reading.completed_at ? new Date(reading.completed_at).toISOString().slice(0, 16) : '';
    document.getElementById('progress_percentage').value = reading.progress_percentage || 0;
    document.getElementById('pages_read').value = reading.pages_read || 0;
    document.getElementById('total_pages').value = reading.total_pages || 0;
    document.getElementById('notes').value = reading.notes || '';
}

function deleteReadingHistory(id) {
    currentReadingId = id;
    document.getElementById('deleteConfirmText').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteReadingModal'));
    modal.show();
}

function filterReadingHistory() {
    const status = document.getElementById('statusFilter').value;
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

function searchReadingHistory() {
    const searchTerm = document.getElementById('searchInput').value;
    const url = new URL(window.location);
    if (searchTerm) {
        url.searchParams.set('search', searchTerm);
    } else {
        url.searchParams.delete('search');
    }
    window.location.href = url.toString();
}

function exportReadingHistory() {
    window.location.href = '/author/reading-history/export';
}

// Handle delete confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteInput = document.getElementById('deleteConfirmText');
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    
    if (deleteInput && deleteBtn) {
        deleteInput.addEventListener('input', function(e) {
            deleteBtn.disabled = e.target.value !== 'DELETE';
        });
        
        deleteBtn.addEventListener('click', function() {
            if (currentReadingId && deleteInput.value === 'DELETE') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/author/reading-history/${currentReadingId}`;
                form.style.display = 'none';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Handle form submission
    const readingForm = document.getElementById('readingHistoryForm');
    if (readingForm) {
        readingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(readingForm);
            const url = currentReadingId ? 
                `/author/reading-history/${currentReadingId}` : 
                '/author/reading-history';
            
            const method = currentReadingId ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(currentReadingId ? 'Reading entry updated successfully!' : 'Reading entry created successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Error saving reading entry');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving reading entry');
            });
        });
    }
});
</script>
@endsection
