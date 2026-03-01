@extends('layouts.author')

@section('title', 'Published Books - Author Dashboard')

@section('page-title', 'Published Books')

@section('content')
<div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Published Books</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('author.books.drafts') }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>Draft Books
                        </a>
                    </div>
                </div>
    <!-- Create Book Modal -->
    <div class="modal fade" id="createBookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Create New Book
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                @include('author.books.partials.create-form')
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ $books->total() }}</h5>
                    <p>Published Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ $books->sum('views') }}</h5>
                    <p>Total Views</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ $books->sum('downloads') }}</h5>
                    <p>Total Downloads</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ $books->avg(function($book) { return $book->reviews->avg('rating') ?: 0; }) ? number_format($books->avg(function($book) { return $book->reviews->avg('rating') ?: 0; }), 1) : '0.0' }}</h5>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('author.books.published') }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search books..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                                <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Most Downloaded</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                        <a href="{{ route('author.books.published') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Books Table -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header">
                    <h5><i class="fas fa-book me-2"></i>Published Books</h5>
                    <div class="header-actions">
                        <span class="badge bg-success">{{ $books->total() }} Published</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="published-books-table-container">
                        <table class="published-books-table">
                            <thead>
                                <tr>
                                    <th width="80">Cover</th>
                                    <th>Title & Info</th>
                                    <th>Category</th>
                                    <th>Published</th>
                                    <th>Statistics</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($books as $book)
                                <tr class="published-book-row">
                                    <td class="cover-cell">
                                        <div class="book-cover-small">
                                            @if($book->cover_image)
                                                <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                            @else
                                                <div class="cover-placeholder">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="info-cell">
                                        <div class="book-details">
                                            <h6 class="book-title">{{ $book->title }}</h6>
                                            <div class="book-meta-row">
                                                <span class="badge bg-primary">{{ $book->category->name ?? 'Uncategorized' }}</span>
                                                <span class="badge bg-success">Published</span>
                                            </div>
                                            <p class="book-description">{{ \Str::limit($book->description, 120) }}</p>
                                            <div class="book-excerpt">
                                                <small class="text-muted">{{ $book->excerpt ? \Str::limit($book->excerpt, 80) : 'No excerpt available' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="category-cell">
                                        <div class="category-info">
                                            <i class="fas fa-folder"></i>
                                            <span>{{ $book->category->name ?? 'Uncategorized' }}</span>
                                        </div>
                                    </td>
                                    <td class="date-cell">
                                        <div class="date-info">
                                            <i class="fas fa-calendar"></i>
                                            <span>{{ $book->published_date ? \Carbon\Carbon::parse($book->published_date)->format('M d, Y') : 'Not set' }}</span>
                                        </div>
                                    </td>
                                    <td class="stats-cell">
                                        <div class="stats-grid">
                                            <div class="stat-item">
                                                <i class="fas fa-eye text-primary"></i>
                                                <span>{{ number_format($book->views ?? 0) }}</span>
                                                <small>Views</small>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-download text-success"></i>
                                                <span>{{ number_format($book->downloads ?? 0) }}</span>
                                                <small>Downloads</small>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-star text-warning"></i>
                                                <span>{{ $book->averageRating() ? number_format($book->averageRating(), 1) : 'N/A' }}</span>
                                                <small>Rating</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="actions-cell">
                                        <div class="table-actions">
                                            <a href="{{ route('author.books.show', $book->id) }}" class="table-btn view" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="table-btn edit" title="Edit Book" onclick="openEditModal({{ $book->id }}, '{{ $book->title }}')">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('author.books.unpublish', $book->id) }}" method="POST" class="action-form unpublish-form">
                                                @csrf
                                                <button type="submit" class="table-btn unpublish" title="Unpublish Book">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="table-btn delete" onclick="confirmDelete({{ $book->id }}, '{{ $book->title }}')" title="Delete Book">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <h5>No Published Books</h5>
                                <p>You haven't published any books yet. Start by creating your first book!</p>
                            </div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Book Modal -->
    <div class="modal fade" id="editBookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Book
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                @include('author.books.partials.edit-form')
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($books->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
    @endif
</div>

<script>
@include('author.books.partials.form-scripts')
@endsection

@push('styles')
<style>
/* Published Books Table Styles */
.published-books-table-container {
    overflow-x: auto;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    background: white;
}

.published-books-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.published-books-table th {
    background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
    color: white;
    font-weight: 700;
    padding: 16px 20px;
    text-align: left;
    border: none;
    white-space: nowrap;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.published-books-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f3f4;
    vertical-align: middle;
    transition: all 0.2s ease;
}

.published-book-row:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(74,85,104,0.1);
}

.published-book-row:hover td {
    border-bottom-color: #dee2e6;
}

.cover-cell {
    width: 90px;
    text-align: center;
    padding: 12px 20px;
}

.book-cover-small {
    width: 70px;
    height: 90px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.book-cover-small:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}

.book-cover-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.info-cell {
    min-width: 300px;
    padding: 12px 20px;
}

.book-details {
    text-align: left;
}

.book-title {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.book-meta-row {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.book-description {
    font-size: 13px;
    color: #6c757d;
    margin: 0 0 12px 0;
    line-height: 1.5;
}

.book-excerpt {
    margin-top: 8px;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #4a90e2;
}

.category-cell {
    width: 140px;
    padding: 12px 20px;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.date-cell {
    width: 120px;
    padding: 12px 20px;
}

.date-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.stats-cell {
    width: 160px;
    padding: 12px 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.stat-item:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stat-item i {
    font-size: 16px;
    margin-bottom: 4px;
}

.stat-item span {
    font-size: 14px;
    font-weight: 700;
    color: #2c3e50;
}

.stat-item small {
    font-size: 11px;
    color: #6c757d;
    margin-top: 2px;
}

.actions-cell {
    width: 180px;
    padding: 12px 20px;
}

.table-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.action-form {
    display: inline-block;
}

.table-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}

.table-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.1);
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.table-btn:hover::before {
    transform: translateY(0);
}

.table-btn.view {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(0,123,255,0.2);
}

.table-btn.view:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.table-btn.edit {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #212529;
    box-shadow: 0 2px 8px rgba(255,193,7,0.2);
}

.table-btn.edit:hover {
    background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,193,7,0.3);
}

.table-btn.unpublish {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(108,117,125,0.2);
}

.table-btn.unpublish:hover {
    background: linear-gradient(135deg, #5a6268 0%, #4d5549 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108,117,125,0.3);
}

.table-btn.delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(220,53,69,0.2);
}

.table-btn.delete:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220,53,69,0.3);
}

/* Enhanced Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    color: #6c757d;
    margin-bottom: 20px;
}

.empty-state h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6c757d;
    font-size: 16px;
    max-width: 400px;
    margin: 0 auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .published-books-table {
        font-size: 12px;
    }
    
    .published-books-table th,
    .published-books-table td {
        padding: 12px 16px;
    }
    
    .info-cell {
        min-width: 200px;
    }
    
    .category-cell,
    .date-cell,
    .stats-cell,
    .actions-cell {
        width: auto;
    }
    
    .book-title {
        font-size: 14px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .table-actions {
        flex-wrap: wrap;
        gap: 6px;
    }
    
    .table-btn {
        width: 32px;
        height: 32px;
    }
}
</style>
@endpush
