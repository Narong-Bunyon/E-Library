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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>
                    Create New Review
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reviews.store') }}" method="POST" id="createReviewForm" onsubmit="storeReview(event)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Book Selection with Preview -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-book me-1"></i>
                                    Book *
                                </label>
                                <select class="form-select" name="book_id" id="bookSelect" required onchange="updateBookInfo()">
                                    <option value="">Select a book</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}" 
                                                data-title="{{ $book->title }}"
                                                data-author="{{ $book->author->name ?? 'Unknown' }}"
                                                data-description="{{ Str::limit($book->description, 150) }}">
                                            {{ $book->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Book Preview Card -->
                            <div id="bookPreview" class="card border-0 bg-light" style="display: none;">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2" id="previewTitle"></h6>
                                    <p class="card-text small text-muted mb-2" id="previewAuthor"></p>
                                    <p class="card-text small mb-0" id="previewDescription"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Selection -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>
                                    Reviewer *
                                </label>
                                <select class="form-select" name="user_id" required>
                                    <option value="">Select a user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Rating Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-star me-1"></i>
                                    Rating *
                                </label>
                                <div class="rating-input">
                                    <div class="star-rating mb-2" id="createRating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="far fa-star" data-rating="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <div class="rating-feedback">
                                        <small class="text-muted" id="ratingFeedback">Click to rate</small>
                                    </div>
                                    <input type="hidden" name="rating" id="ratingValue" value="5" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-flag me-1"></i>
                                    Status *
                                </label>
                                <select class="form-select" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved" selected>Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Comment Templates
                                </label>
                                <div class="d-flex gap-2 flex-wrap" id="commentTemplates">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectBookSpecificComment()">
                                        <i class="fas fa-magic me-1"></i>
                                        Smart Suggestion
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateRandomComment()">
                                        <i class="fas fa-dice me-1"></i>
                                        Random
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comment Section with Enhanced Features -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-comment me-1"></i>
                            Review Comment *
                        </label>
                        <div class="comment-section">
                            <textarea class="form-control" name="comment" id="reviewComment" rows="5" required 
                                      placeholder="Write a thoughtful review comment..." 
                                      oninput="updateCharCount()"></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <span id="charCount">0</span> characters
                                </small>
                                <div class="comment-quality" id="commentQuality">
                                    <small class="text-muted">Quality: </small>
                                    <span class="badge bg-secondary">Analyzing...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book-Specific Comment Suggestions -->
                    <div id="bookSpecificSuggestions" class="mb-3" style="display: none;">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sparkles me-1"></i>
                            Suggested Comments for This Book
                        </label>
                        <div class="suggestion-cards" id="suggestionCards">
                            <!-- Suggestions will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-outline-primary me-2" onclick="previewReview()">
                        <i class="fas fa-eye me-1"></i>
                        Preview
                    </button>
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

/* Enhanced Review Modal Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.suggestion-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.suggestion-card:hover {
    background-color: #f8f9fa;
    border-color: #007bff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.15);
}

.suggestion-card .badge {
    font-size: 0.75rem;
}

.comment-section textarea {
    resize: vertical;
    min-height: 120px;
}

.comment-quality .badge {
    font-size: 0.75rem;
}

.book-preview {
    transition: all 0.3s ease;
}

.rating-feedback {
    text-align: center;
    margin-top: 5px;
}

.star-rating i:hover {
    transform: scale(1.1);
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

/* Review preview styles */
.review-preview {
    font-size: 0.9rem;
}

.review-preview .border {
    border: 1px solid #dee2e6 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
    }
    
    .suggestion-card {
        font-size: 0.85rem;
    }
    
    .comment-section textarea {
        min-height: 100px;
    }
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
    
    // Update rating feedback
    const feedback = document.getElementById('ratingFeedback');
    if (feedback) {
        const feedbackTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
        feedback.textContent = feedbackTexts[rating] || 'Click to rate';
    }
}

// Enhanced review creation functions
function updateBookInfo() {
    const bookSelect = document.getElementById('bookSelect');
    const bookPreview = document.getElementById('bookPreview');
    const previewTitle = document.getElementById('previewTitle');
    const previewAuthor = document.getElementById('previewAuthor');
    const previewDescription = document.getElementById('previewDescription');
    const suggestions = document.getElementById('bookSpecificSuggestions');
    
    if (bookSelect.value && bookSelect.options[bookSelect.selectedIndex]) {
        const selectedOption = bookSelect.options[bookSelect.selectedIndex];
        const title = selectedOption.dataset.title;
        const author = selectedOption.dataset.author;
        const description = selectedOption.dataset.description;
        
        // Show book preview
        previewTitle.textContent = title;
        previewAuthor.textContent = `by ${author}`;
        previewDescription.textContent = description;
        bookPreview.style.display = 'block';
        
        // Load book-specific suggestions
        loadBookSpecificSuggestions(title);
        suggestions.style.display = 'block';
    } else {
        bookPreview.style.display = 'none';
        suggestions.style.display = 'none';
    }
}

function loadBookSpecificSuggestions(bookTitle) {
    const suggestionCards = document.getElementById('suggestionCards');
    
    // Generic comments for all books
    const genericComments = [
        "This book absolutely captivated me from the first page. The author's writing style is both elegant and accessible, making complex themes feel personal and relatable.",
        "A masterpiece of modern literature. The character development is exceptional, and the plot twists kept me guessing throughout.",
        "The world-building in this book is absolutely phenomenal. The attention to detail and the rich, immersive setting make the story come alive.",
        "This book changed my perspective on so many things. The way the author handles difficult topics with grace and insight is remarkable.",
        "A solid read with engaging characters and a well-crafted plot. Thoroughly enjoyable and perfect for a weekend escape."
    ];
    
    // Create suggestion cards
    suggestionCards.innerHTML = genericComments.map((comment, index) => `
        <div class="suggestion-card mb-2 p-3 border rounded cursor-pointer hover-bg-light" onclick="useSuggestion(${index})">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge bg-primary">Suggestion ${index + 1}</span>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="useSuggestion(${index}); return false;">
                    <i class="fas fa-plus"></i> Use
                </button>
            </div>
            <p class="mb-0 small">${comment}</p>
        </div>
    `).join('');
}

function useSuggestion(index) {
    const suggestionCards = document.getElementById('suggestionCards');
    const cards = suggestionCards.querySelectorAll('.suggestion-card');
    const comment = cards[index].querySelector('p').textContent;
    
    document.getElementById('reviewComment').value = comment;
    updateCharCount();
    updateCommentQuality();
}

function selectBookSpecificComment() {
    const bookSelect = document.getElementById('bookSelect');
    if (!bookSelect.value) {
        showNotification('Please select a book first', 'warning');
        return;
    }
    
    const suggestionCards = document.getElementById('suggestionCards');
    const cards = suggestionCards.querySelectorAll('.suggestion-card');
    
    if (cards.length > 0) {
        const randomIndex = Math.floor(Math.random() * cards.length);
        useSuggestion(randomIndex);
    } else {
        generateRandomComment();
    }
}

function generateRandomComment() {
    const randomComments = [
        "This book absolutely captivated me from the first page. The author's writing style is both elegant and accessible.",
        "A masterpiece of modern literature. The character development is exceptional, and the plot twists kept me guessing.",
        "The world-building in this book is absolutely phenomenal. The attention to detail makes the story come alive.",
        "This book changed my perspective on so many things. The way the author handles difficult topics with grace and insight is remarkable.",
        "A solid read with engaging characters and a well-crafted plot. Thoroughly enjoyable and perfect for a weekend escape."
    ];
    
    const randomComment = randomComments[Math.floor(Math.random() * randomComments.length)];
    document.getElementById('reviewComment').value = randomComment;
    updateCharCount();
    updateCommentQuality();
}

function updateCharCount() {
    const textarea = document.getElementById('reviewComment');
    const charCount = document.getElementById('charCount');
    if (charCount) {
        charCount.textContent = textarea.value.length;
    }
    updateCommentQuality();
}

function updateCommentQuality() {
    const textarea = document.getElementById('reviewComment');
    const qualityDiv = document.getElementById('commentQuality');
    if (!qualityDiv) return;
    
    const text = textarea.value.trim();
    
    let quality = 'Poor';
    let badgeClass = 'bg-danger';
    
    if (text.length === 0) {
        quality = 'Analyzing...';
        badgeClass = 'bg-secondary';
    } else if (text.length < 20) {
        quality = 'Too Short';
        badgeClass = 'bg-warning';
    } else if (text.length < 50) {
        quality = 'Fair';
        badgeClass = 'bg-info';
    } else if (text.length < 100) {
        quality = 'Good';
        badgeClass = 'bg-primary';
    } else {
        quality = 'Excellent';
        badgeClass = 'bg-success';
    }
    
    qualityDiv.innerHTML = `<small class="text-muted">Quality: </small><span class="badge ${badgeClass}">${quality}</span>`;
}

function previewReview() {
    const bookSelect = document.getElementById('bookSelect');
    const userSelect = document.querySelector('select[name="user_id"]');
    const rating = document.getElementById('ratingValue').value;
    const comment = document.getElementById('reviewComment').value;
    const status = document.querySelector('select[name="status"]').value;
    
    if (!bookSelect.value || !userSelect.value || !comment.trim()) {
        showNotification('Please fill in all required fields first', 'warning');
        return;
    }
    
    const bookTitle = bookSelect.options[bookSelect.selectedIndex].text;
    const userName = userSelect.options[userSelect.selectedIndex].text;
    
    const previewContent = `
        <div class="review-preview">
            <h6>Review Preview</h6>
            <div class="border rounded p-3 bg-light">
                <div class="mb-2">
                    <strong>Book:</strong> ${bookTitle}<br>
                    <strong>Reviewer:</strong> ${userName}<br>
                    <strong>Rating:</strong> ${'★'.repeat(rating)}${'☆'.repeat(5-rating)} (${rating}/5)<br>
                    <strong>Status:</strong> <span class="badge bg-${status === 'approved' ? 'success' : status === 'rejected' ? 'danger' : 'warning'}">${status}</span>
                </div>
                <div>
                    <strong>Comment:</strong><br>
                    <p class="mb-0">${comment}</p>
                </div>
            </div>
        </div>
    `;
    
    // Create a simple modal for preview
    const previewModal = document.createElement('div');
    previewModal.className = 'modal fade';
    previewModal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ${previewContent}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(previewModal);
    const modal = new bootstrap.Modal(previewModal);
    modal.show();
    
    previewModal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(previewModal);
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
        showNotification('Please select at least one review to delete', 'warning');
        return;
    }
    
    showDeleteConfirmation('multiple', ids.length, () => {
        fetch('/admin/reviews/bulk-delete', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Reviews deleted successfully', 'success');
                location.reload();
            } else {
                showNotification('Error deleting reviews: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            showNotification('Error deleting reviews: ' + error.message, 'error');
        });
    });
}

// CRUD operations
function editReview(id) {
    fetch(`/admin/reviews/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.success) {
            // Populate edit form with review data
            document.getElementById('editReviewId').value = data.id;
            document.getElementById('editBookId').value = data.book_id;
            document.getElementById('editUserId').value = data.user_id;
            document.getElementById('editRatingValue').value = data.rating;
            document.getElementById('editComment').value = data.comment || '';
            document.getElementById('editStatus').value = data.status || 'pending';
            
            // Update rating stars
            const editRating = document.getElementById('editRating');
            if (editRating) {
                updateStars(editRating, data.rating);
            }
            
            // Set form action
            const form = document.getElementById('editReviewForm');
            form.action = `/admin/reviews/${id}`;
            
            // Show edit modal
            const modal = new bootstrap.Modal(document.getElementById('editReviewModal'));
            modal.show();
        } else {
            showNotification('Error fetching review data', 'error');
        }
    })
    .catch(error => {
        showNotification('Error fetching review data: ' + error.message, 'error');
    });
}

function updateReview(event) {
    event.preventDefault();
    
    const form = document.getElementById('editReviewForm');
    const formData = new FormData(form);
    
    // Add _method for PUT
    formData.append('_method', 'PUT');
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            showNotification('Review updated successfully', 'success');
            
            // Hide edit modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editReviewModal'));
            modal.hide();
            
            // Reload the page to show updated data
            location.reload();
        } else {
            showNotification('Error updating review: ' + (data.error || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        showNotification('Error updating review: ' + error.message, 'error');
    });
}

function viewReview(id) {
    fetch(`/admin/reviews/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
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
                    <strong>Created:</strong> ${data.create_at ? new Date(data.create_at).toLocaleDateString() : 'N/A'}<br>
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
    })
    .catch(error => {
        showNotification('Error fetching review details: ' + error.message, 'error');
    });
}

function editReviewFromView() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewReviewModal'));
    modal.hide();
    
    const id = document.getElementById('editReviewId').value;
    if (id) {
        editReview(id);
    }
}

function approveReview(id) {
    showActionConfirmation('approve', () => {
        fetch(`/admin/reviews/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Review approved successfully', 'success');
                location.reload();
            } else {
                showNotification('Error approving review: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            showNotification('Error approving review: ' + error.message, 'error');
        });
    });
}

function rejectReview(id) {
    showActionConfirmation('reject', () => {
        fetch(`/admin/reviews/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Review rejected successfully', 'success');
                location.reload();
            } else {
                showNotification('Error rejecting review: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            showNotification('Error rejecting review: ' + error.message, 'error');
        });
    });
}

function deleteReview(id) {
    showDeleteConfirmation('single', 1, () => {
        fetch(`/admin/reviews/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Review deleted successfully', 'success');
                location.reload();
            } else {
                showNotification('Error deleting review: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            showNotification('Error deleting review: ' + error.message, 'error');
        });
    });
}

function exportReviews() {
    window.location.href = '/admin/reviews/export';
}

function storeReview(event) {
    event.preventDefault();
    
    const form = document.getElementById('createReviewForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            showNotification('Review created successfully', 'success');
            
            // Hide create modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createReviewModal'));
            modal.hide();
            
            // Reset form
            form.reset();
            
            // Reload the page to show updated data
            location.reload();
        } else {
            showNotification('Error creating review: ' + (data.error || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        showNotification('Error creating review: ' + error.message, 'error');
    });
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

// Confirmation modals
function showDeleteConfirmation(type, count, onConfirm) {
    const message = type === 'multiple' 
        ? `Are you sure you want to delete ${count} review${count > 1 ? 's' : ''}? This action cannot be undone.`
        : 'Are you sure you want to delete this review? This action cannot be undone.';
    
    const modalHtml = `
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Confirm Deletion
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>${message}</div>
                        </div>
                        <p class="mb-0"><strong>This action is permanent and cannot be undone.</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-1"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if present
    const existingModal = document.getElementById('deleteConfirmationModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
    
    // Set confirmation callback
    window.confirmDelete = function() {
        modal.hide();
        onConfirm();
        // Clean up modal after hiding
        setTimeout(() => {
            const modalElement = document.getElementById('deleteConfirmationModal');
            if (modalElement) {
                modalElement.remove();
            }
        }, 300);
    };
}

function showActionConfirmation(action, onConfirm) {
    const actionText = action === 'approve' ? 'approve' : 'reject';
    const actionIcon = action === 'approve' ? 'check' : 'times';
    const actionColor = action === 'approve' ? 'success' : 'warning';
    
    const modalHtml = `
        <div class="modal fade" id="actionConfirmationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-${actionColor} text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-${actionIcon} me-2"></i>
                            Confirm ${ucfirst(actionText)}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to ${actionText} this review?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-${actionColor}" onclick="confirmAction()">
                            <i class="fas fa-${actionIcon} me-1"></i>
                            ${ucfirst(actionText)}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if present
    const existingModal = document.getElementById('actionConfirmationModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('actionConfirmationModal'));
    modal.show();
    
    // Set confirmation callback
    window.confirmAction = function() {
        modal.hide();
        onConfirm();
        // Clean up modal after hiding
        setTimeout(() => {
            const modalElement = document.getElementById('actionConfirmationModal');
            if (modalElement) {
                modalElement.remove();
            }
        }, 300);
    };
}

// Notification system
function showNotification(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show`;
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
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
