@extends('layouts.author')

@section('title', 'Author Dashboard - E-Library')

@section('page-title', 'Author Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="welcome-text">
                        <h4>Welcome back, {{ auth()->user()->name }}!</h4>
                        <p class="text-muted">Here's what's happening with your books today</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="modern-stats-card primary">
                <div class="stats-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ $stats['total_books'] ?? 0 }}</div>
                    <div class="stats-label">My Books</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="modern-stats-card success">
                <div class="stats-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ number_format($stats['total_views'] ?? 0) }}</div>
                    <div class="stats-label">Total Views</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="modern-stats-card warning">
                <div class="stats-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ number_format($stats['total_downloads'] ?? 0) }}</div>
                    <div class="stats-label">Downloads</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="modern-stats-card info">
                <div class="stats-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ number_format($stats['total_reviews'] ?? 0) }}</div>
                    <div class="stats-label">Reviews</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    <div class="action-grid">
                        <a href="{{ route('author.books.create') }}" class="modern-action-btn primary">
                            <i class="fas fa-plus"></i>
                            <span>New Book</span>
                        </a>
                        
                        <a href="{{ route('author.books.index') }}" class="modern-action-btn secondary">
                            <i class="fas fa-list"></i>
                            <span>My Books</span>
                        </a>
                        
                        <a href="{{ route('author.books.published') }}" class="modern-action-btn success">
                            <i class="fas fa-check-circle"></i>
                            <span>Published</span>
                        </a>
                        
                        <a href="{{ route('author.books.drafts') }}" class="modern-action-btn warning">
                            <i class="fas fa-edit"></i>
                            <span>Drafts</span>
                        </a>
                        
                        <a href="{{ route('author.analytics') }}" class="modern-action-btn info">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Books -->
    <div class="row">
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="card-header">
                    <h5><i class="fas fa-clock me-2"></i>Recent Books</h5>
                    <a href="{{ route('author.books.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentBooks) && $recentBooks->count() > 0)
                        <div class="recent-books-grid">
                            @foreach($recentBooks->take(5) as $book)
                            <div class="recent-book-item">
                                <div class="book-cover">
                                    @if($book->cover_image)
                                        <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                    @else
                                        <div class="placeholder-cover">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="book-info">
                                    <h6>{{ $book->title }}</h6>
                                    <div class="book-meta">
                                        <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $book->status === 1 ? 'Published' : 'Draft' }}
                                        </span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}</small>
                                    </div>
                                    <div class="book-stats">
                                        <span><i class="fas fa-eye"></i> {{ number_format($book->views ?? 0) }}</span>
                                        <span><i class="fas fa-download"></i> {{ number_format($book->downloads ?? 0) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h6>No books yet</h6>
                            <p class="text-muted">Start by creating your first book!</p>
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">You haven't published any books yet.</p>
                            <a href="{{ route('author.books.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Create Your First Book
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Author Profile -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Author Profile</h5>
                </div>
                <div class="card-body text-center">
                    <div class="author-profile-avatar mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="avatar-placeholder rounded-circle" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: bold; margin: 0 auto;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                    <p class="text-muted small">{{ auth()->user()->bio ?? 'No bio available' }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-primary">Author</span>
                        <span class="badge {{ auth()->user()->status === 'active' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst(auth()->user()->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentActivity) && $recentActivity->count() > 0)
                        <div class="activity-timeline">
                            @foreach($recentActivity as $activity)
                            <div class="activity-item mb-3">
                                <div class="activity-icon">
                                    <i class="fas {{ $activity['icon'] }}"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">{{ $activity['text'] }}</div>
                                    <small class="text-muted">{{ $activity['time'] }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-2x text-muted mb-2"></i>
                            <p class="text-muted small">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Statistics Cards - Clean Modern Design */
.stats-card {
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    transition: all 0.3s ease !important;
    height: 100% !important;
    display: flex !important;
    align-items: center !important;
    padding: 20px !important;
    position: relative !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: none !important;
    border: none !important;
}

.stats-icon {
    width: 50px !important;
    height: 50px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 20px !important;
    color: white !important;
    margin-right: 15px !important;
    flex-shrink: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-card:nth-child(1) .stats-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
}

.stats-card:nth-child(2) .stats-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    border: none !important;
}

.stats-card:nth-child(3) .stats-icon {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
    border: none !important;
}

.stats-card:nth-child(4) .stats-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    border: none !important;
}

.stats-info {
    flex: 1 !important;
    min-width: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-number {
    font-size: 28px !important;
    font-weight: 700 !important;
    color: #1a202c !important;
    line-height: 1 !important;
    margin-bottom: 4px !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-label {
    font-size: 13px !important;
    color: #64748b !important;
    font-weight: 500 !important;
    text-transform: capitalize !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Author Profile Styles */
.author-profile-avatar img,
.avatar-placeholder {
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Activity Timeline */
.activity-timeline {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-text {
    font-size: 13px;
    margin-bottom: 2px;
    line-height: 1.4;
}

/* Book Cover Styles */
.book-cover img,
.default-cover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-card {
        padding: 16px !important;
    }
    
    .stats-icon {
        width: 44px !important;
        height: 44px !important;
        font-size: 18px !important;
        margin-right: 12px !important;
    }
    
    .stats-number {
        font-size: 24px !important;
    }
    
    .stats-label {
        font-size: 12px !important;
    }
}

@media (max-width: 576px) {
    .stats-card {
        padding: 14px !important;
    }
    
    .stats-icon {
        width: 40px !important;
        height: 40px !important;
        font-size: 16px !important;
        margin-right: 10px !important;
    }
    
    .stats-number {
        font-size: 20px !important;
    }
    
    .stats-label {
        font-size: 11px !important;
    }
}
</style>
@endpush
                    <div class="stat-content">
                        <h3 class="stat-value">{{ number_format($stats['total_downloads']) }}</h3>
                        <p class="stat-label mb-1">Downloads</p>
                        <small class="text-muted">All time downloads</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card info">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info text-white">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value">{{ $stats['total_reviews'] }}</h3>
                        <p class="stat-label mb-1">Reviews</p>
                        <small class="text-muted">{{ $stats['total_favorites'] }} favorites</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100 action-btn" data-bs-toggle="modal" data-bs-target="#createBookModal">
                                <i class="fas fa-plus me-2"></i>
                                Create New Book
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('author.books.index') }}" class="btn btn-outline-primary w-100 action-btn">
                                <i class="fas fa-list me-2"></i>
                                Manage Books
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('author.reviews') }}" class="btn btn-outline-success w-100 action-btn">
                                <i class="fas fa-comments me-2"></i>
                                View Reviews
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('author.analytics') }}" class="btn btn-outline-info w-100 action-btn">
                                <i class="fas fa-chart-bar me-2"></i>
                                Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Books and Popular Content -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book me-2"></i>
                        My Books
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="filterBooks('all')">All ({{ $stats['total_books'] }})</button>
                        <button class="btn btn-outline-success" onclick="filterBooks('published')">Published ({{ $stats['published_books'] }})</button>
                        <button class="btn btn-outline-warning" onclick="filterBooks('draft')">Drafts ({{ $stats['draft_books'] }})</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="booksTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th>Downloads</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentBooks as $book)
                                <tr data-status="{{ $book->status == 1 ? 'published' : 'draft' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="book-cover-sm me-3">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $book->title }}</h6>
                                                <small class="text-muted">ID: #{{ $book->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($book->status == 1)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $book->readingProgress()->count() ?? 0 }}</td>
                                    <td>{{ $book->downloads()->where('status', 'completed')->count() ?? 0 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-1">{{ $book->averageRating() }}</span>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editBook({{ $book->id }})" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="viewBook({{ $book->id }})" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBook({{ $book->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No books found. Create your first book!</p>
                                        <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#createBookModal">
                                            <i class="fas fa-plus me-1"></i>
                                            Create Book
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Popular Books -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire me-2"></i>
                        Popular Books
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($popularBooks as $book)
                    <div class="popular-book-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="popular-book-cover me-3">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $book->title }}</h6>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-eye me-1"></i>
                                    <span>{{ $book->reading_progress_count ?? 0 }} views</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-star me-1"></i>
                                    <span>{{ $book->averageRating() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No popular books yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comments me-2"></i>
                        Recent Reviews
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($recentReviews as $review)
                    <div class="review-item mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0">{{ $review->book->title ?? 'Unknown Book' }}</h6>
                            <div class="d-flex align-items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} fa-sm"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-muted small mb-1">{{ $review->user->name ?? 'Anonymous' }}</p>
                        <p class="small">{{ Str::limit($review->comment, 100) }}</p>
                        <small class="text-muted">{{ $review->create_at->diffForHumans() }}</small>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No reviews yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Performance Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $stats['total_readers'] }}</h4>
                                <p class="text-muted">Total Readers</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $stats['total_favorites'] }}</h4>
                                <p class="text-muted">Total Favorites</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $stats['published_books'] }}</h4>
                                <p class="text-muted">Published Books</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $stats['draft_books'] }}</h4>
                                <p class="text-muted">Draft Books</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Book Modal -->
<div class="modal fade" id="createBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('author.books.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Book Title *</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Author ID *</label>
                                <input type="number" class="form-control" name="author_id" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select" name="status" required>
                                    <option value="1">Published</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Access Level *</label>
                                <select class="form-select" name="access_level" required>
                                    <option value="0">Public</option>
                                    <option value="1">Registered Users</option>
                                    <option value="2">Premium</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">File Path *</label>
                                <input type="text" class="form-control" name="file_path" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cover Image</label>
                        <input type="text" class="form-control" name="cover_image" placeholder="Optional">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterBooks(status) {
    const rows = document.querySelectorAll('#booksTable tbody tr');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function editBook(id) {
    window.location.href = `/admin/books/${id}/edit`;
}

function viewBook(id) {
    window.location.href = `/admin/books/${id}`;
}

function deleteBook(id) {
    if (confirm('Are you sure you want to delete this book?')) {
        fetch(`/admin/books/${id}`, {
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
                alert('Error deleting book');
            }
        });
    }
}
</script>

<style>
.action-btn {
    padding: 0.75rem 1rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.book-cover-sm {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.popular-book-cover {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.popular-book-item, .review-item {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}

.popular-book-item:last-child, .review-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
</style>
