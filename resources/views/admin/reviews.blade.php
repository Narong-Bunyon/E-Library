@extends('layouts.admin')

@section('title', 'Reviews Management - E-Library')

@section('page-title', 'Reviews & Comments')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $totalReviews }}</h3>
                            <p class="stat-label">Total Reviews</p>
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
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ number_format($avgRating, 1) }}</h3>
                            <p class="stat-label">Avg Rating</p>
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
                            <h3 class="stat-value">{{ $pendingReviews }}</h3>
                            <p class="stat-label">Pending</p>
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
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $approvedReviews }}</h3>
                            <p class="stat-label">Approved</p>
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
                <i class="fas fa-comments me-2"></i>
                Reviews Management
            </h4>
            <p class="text-muted mb-0">Manage user reviews and comments</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReviewModal">
                <i class="fas fa-plus me-1"></i>
                Add Review
            </button>
            <button class="btn btn-outline-danger" onclick="deleteSelectedReviews()" id="bulkDeleteBtn" style="display: none;">
                <i class="fas fa-trash me-1"></i>
                Delete Selected
            </button>
            <button class="btn btn-outline-secondary" onclick="exportReviews()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-comments me-2"></i>
                All Reviews
                <span class="badge bg-primary ms-2">{{ $reviews->count() }}</span>
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-2" id="selectAll" onchange="toggleSelectAll()">
                    <small class="text-muted">Select All</small>
                </div>
                <div class="d-flex align-items-center">
                    <label class="form-label me-2 mb-0">Filter:</label>
                    <select class="form-select form-select-sm" id="statusFilter" onchange="filterReviews()">
                        <option value="">All Reviews</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Only</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved Only</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected Only</option>
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
                            <th>Review</th>
                            <th>Book</th>
                            <th>Reviewer</th>
                            <th>Rating</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input review-checkbox" value="{{ $review->id }}" onchange="updateBulkDeleteButton()">
                            </td>
                            <td>
                                <div class="review-content">
                                    <div class="review-rating mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="fas fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="review-text mb-1">{{ Str::limit($review->comment, 100) }}</p>
                                    <small class="review-meta text-muted">{{ $review->create_at?->diffForHumans() ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $review->book->title ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $review->book->author->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">{{ substr($review->user->name ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $review->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $review->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="rating-display">
                                    <span class="rating-value">{{ $review->rating }}.0</span>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="fas fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $review->create_at?->format('M d, Y') ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($review->status === 'approved') bg-success 
                                    @elseif($review->status === 'rejected') bg-danger 
                                    @else bg-warning @endif">
                                    {{ ucfirst($review->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($review->status === 'pending')
                                        <button class="btn btn-outline-success btn-sm" onclick="approveReview({{ $review->id }})" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="rejectReview({{ $review->id }})" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-primary btn-sm" onclick="editReview({{ $review->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="viewReview({{ $review->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteReview({{ $review->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No reviews found.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReviewModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Create First Review
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} reviews
                        </small>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $reviews->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Review Modal -->
<div class="modal fade" id="createReviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reviews.store') }}" method="POST" id="createReviewForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rating *</label>
                                <div class="rating-input">
                                    <div class="star-rating" id="createRating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="far fa-star" data-rating="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="ratingValue" value="5" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comment *</label>
                        <textarea class="form-control" name="comment" rows="4" required placeholder="Enter review comment"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" id="editReviewForm" onsubmit="updateReview(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editReviewId">
                <div class="modal-body">
                    <div class="row">
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rating *</label>
                                <div class="rating-input">
                                    <div class="star-rating" id="editRating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="far fa-star" data-rating="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="editRatingValue" value="5" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" id="editStatus" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comment *</label>
                        <textarea class="form-control" name="comment" id="editComment" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Review Modal -->
<div class="modal fade" id="viewReviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reviewDetails">
                <!-- Review details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="editReviewFromView()">Edit Review</button>
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

.review-content {
    max-width: 300px;
}

.review-rating {
    font-size: 12px;
}

.review-text {
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
}

.review-meta {
    font-size: 11px;
}

.book-cover-sm, .user-avatar-sm {
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

.rating-display {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rating-value {
    font-weight: bold;
    font-size: 14px;
}

.rating-stars {
    font-size: 12px;
}

.star-rating {
    display: flex;
    gap: 5px;
    font-size: 20px;
}

.star-rating i {
    cursor: pointer;
    transition: color 0.2s;
}

.star-rating i:hover,
.star-rating i.active {
    color: #ffc107 !important;
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
// Rating functionality
document.addEventListener('DOMContentLoaded', function() {
    // Create rating stars
    const createRating = document.getElementById('createRating');
    if (createRating) {
        const stars = createRating.querySelectorAll('i');
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                document.getElementById('ratingValue').value = rating;
                updateStars(createRating, rating);
            });
        });
        updateStars(createRating, 5);
    }

    // Edit rating stars
    const editRating = document.getElementById('editRating');
    if (editRating) {
        const stars = editRating.querySelectorAll('i');
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                document.getElementById('editRatingValue').value = rating;
                updateStars(editRating, rating);
            });
        });
    }
});

function updateStars(container, rating) {
    const stars = container.querySelectorAll('i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas', 'active');
        } else {
            star.classList.remove('fas', 'active');
            star.classList.add('far');
        }
    });
}

// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function toggleSelectAllTable() {
    const selectAll = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

// Bulk delete functionality
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.review-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (checkboxes.length > 0) {
        bulkDeleteBtn.style.display = 'inline-block';
    } else {
        bulkDeleteBtn.style.display = 'none';
    }
}

function deleteSelectedReviews() {
    const checkboxes = document.querySelectorAll('.review-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Please select at least one review to delete');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${ids.length} review${ids.length > 1 ? 's' : ''}? This action cannot be undone.`)) {
        fetch('/admin/reviews/bulk-delete', {
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
                location.reload();
            } else {
                alert('Error deleting reviews: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error deleting reviews: ' + error.message);
        });
    }
}

function deleteReview(id) {
    if (confirm('Are you sure you want to delete this review?')) {
        fetch(`/admin/reviews/${id}`, {
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
                alert('Error deleting review: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error deleting review: ' + error.message);
        });
    }
}

// CRUD operations
function editReview(id) {
    fetch(`/admin/reviews/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                // Populate edit form with review data
                document.getElementById('editReviewId').value = data.id;
                document.getElementById('editBookId').value = data.book_id;
                document.getElementById('editUserId').value = data.user_id;
                document.getElementById('editRating').value = data.rating;
                document.getElementById('editComment').value = data.comment || '';
                document.getElementById('editStatus').value = data.status || 'pending';
                
                // Show edit modal
                const modal = new bootstrap.Modal(document.getElementById('editReviewModal'));
                modal.show();
            } else {
                showNotification('Error fetching review data', 'error');
            }
        })
        .catch(error => {
            showNotification('Error fetching review data', 'error');
        });
}

function updateReview(event) {
    event.preventDefault();
    
    const form = document.getElementById('editReviewForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Review updated successfully', 'success');
            
            // Hide edit modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editReviewModal'));
            modal.hide();
            
            // Reload the page to show updated data
            location.reload();
        } else {
            showNotification('Error updating review: ' + data.error, 'error');
        }
    })
    .catch(error => {
        showNotification('Error updating review: ' + error.message, 'error');
    });
}

function viewReview(id) {
    fetch(`/admin/reviews/${id}`)
        .then(response => response.json())
        .then(data => {
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Book:</strong> ${data.book?.title || 'N/A'}<br>
                        <strong>User:</strong> ${data.user?.name || 'N/A'} (${data.user?.email || 'N/A'})<br>
                        <strong>Rating:</strong> ${generateStarHTML(data.rating)}<br>
                        <strong>Status:</strong> <span class="badge bg-${getStatusColor(data.status)}">${ucfirst(data.status || 'pending')}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong> ${data.create_at || 'N/A'}<br>
                        <strong>Review ID:</strong> ${data.id}<br>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Comment:</strong>
                    <p class="mt-2">${data.comment || 'No comment'}</p>
                </div>
            `;
            document.getElementById('reviewDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewReviewModal')).show();
        });
}

function editReviewFromView() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewReviewModal'));
    modal.hide();
    
    const id = document.getElementById('editReviewId').value;
    editReview(id);
}

function approveReview(id) {
    if (confirm('Are you sure you want to approve this review?')) {
        fetch(`/admin/reviews/${id}/approve`, {
            method: 'POST',
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
                alert('Error approving review: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error approving review: ' + error.message);
        });
    }
}

function rejectReview(id) {
    if (confirm('Are you sure you want to reject this review?')) {
        fetch(`/admin/reviews/${id}/reject`, {
            method: 'POST',
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
                alert('Error rejecting review: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error rejecting review: ' + error.message);
        });
    }
}

function deleteReview(id) {
    // Fetch review information first
    fetch(`/admin/reviews/${id}`)
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
                alert('Error deleting review: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error deleting review: ' + error.message);
        });
    }
}

function exportReviews() {
    window.location.href = '/admin/reviews/export';
}

function filterReviews() {
    const status = document.getElementById('statusFilter').value;
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

// Helper functions
function generateStarHTML(rating) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            html += '<i class="fas fa-star text-warning"></i>';
        } else {
            html += '<i class="fas fa-star text-muted"></i>';
        }
    }
    return html;
}

function getStatusColor(status) {
    switch(status) {
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        default: return 'warning';
    }
}

function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>
@endpush
@endsection
