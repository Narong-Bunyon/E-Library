@extends('layouts.author')

@section('title', 'Reviews & Comments - E-Library')

@section('page-title', 'Reviews & Comments')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Reviews & Comments</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReviews()">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Review::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->count() }}</h5>
                    <p>Total Reviews</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Review::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->where('rating', '>=', 4)->count() }}</h5>
                    <p>Positive Reviews</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Review::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->where('rating', '<=', 2)->count() }}</h5>
                    <p>Negative Reviews</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Review::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->avg('rating') ? number_format(\App\Models\Review::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->avg('rating'), 1) : '0.0' }}</h5>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Reviews</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="ratingFilter">
                        <option value="">All Ratings</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                    <select class="form-select form-select-sm" id="bookFilter">
                        <option value="">All Books</option>
                        @foreach(\App\Models\Book::where('author_id', auth()->user()->id)->get() as $book)
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
                            <th>Book</th>
                            <th>Reviewer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($review->book->cover_image)
                                        <img src="{{ asset('storage/' . $review->book->cover_image) }}" alt="{{ $review->book->title }}" style="width: 30px; height: 40px; object-fit: cover; margin-right: 10px;">
                                    @else
                                        <div style="width: 30px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold; margin-right: 10px;">
                                            {{ strtoupper(substr($review->book->title, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $review->book->title }}</div>
                                        <small class="text-muted">{{ \Str::limit($review->book->description, 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <i class="fas fa-user-circle text-muted"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $review->user->name }}</div>
                                        <small class="text-muted">{{ $review->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $review->rating }}</span>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star fa-sm {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $review->comment ? \Str::limit($review->comment, 100) : 'No comment' }}</div>
                                @if($review->comment && strlen($review->comment) > 100)
                                    <button class="btn btn-link p-0 btn-sm" onclick="toggleComment({{ $review->id }})">Read more</button>
                                @endif
                            </td>
                            <td>{{ $review->created_at ? $review->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $review->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($review->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewReview({{ $review->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="approveReview({{ $review->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteReview({{ $review->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted">No reviews found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleComment(reviewId) {
    // Toggle full comment display
}

function viewReview(reviewId) {
    // View full review details
}

function approveReview(reviewId) {
    if (confirm('Are you sure you want to approve this review?')) {
        // Approve review logic
    }
}

function deleteReview(reviewId) {
    if (confirm('Are you sure you want to delete this review?')) {
        // Delete review logic
    }
}

function exportReviews() {
    // Export reviews logic
}
</script>
@endsection
