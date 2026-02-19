@extends('layouts.author')

@section('title', 'Published Books - Author Dashboard')

@section('page-title', 'Published Books')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Published Books</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('author.books') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-2"></i>All Books
            </a>
            <a href="{{ route('author.books.drafts') }}" class="btn btn-outline-warning">
                <i class="fas fa-edit me-2"></i>Draft Books
            </a>
            <a href="{{ route('author.books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Book
            </a>
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
    
    <!-- Books Grid -->
    <div class="row">
        @forelse($books as $book)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 24px; font-weight: bold;">
                        {{ strtoupper(substr($book->title, 0, 1)) }}
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $book->title }}</h5>
                    <p class="card-text text-muted">{{ \Str::limit($book->description, 100) }}</p>
                    
                    <div class="mb-2">
                        <span class="badge bg-primary">{{ $book->category->name ?? 'Uncategorized' }}</span>
                        <span class="badge bg-success">Published</span>
                    </div>
                    
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <small class="text-muted">Views</small>
                            <div class="fw-bold">{{ number_format($book->views ?? 0) }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Downloads</small>
                            <div class="fw-bold">{{ number_format($book->downloads ?? 0) }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Rating</small>
                            <div class="fw-bold">{{ $book->averageRating() ? number_format($book->averageRating(), 1) : 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('books.analytics', $book->id) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-chart-line"></i> Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Published Books</h5>
                <p class="text-muted">You haven't published any books yet. Start by creating a new book!</p>
                <a href="{{ route('books.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Book
                </a>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($books->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
    @endif
</div>

<script>
function searchBooks() {
    const searchInput = document.querySelector('input[name="search"]');
    const categorySelect = document.querySelector('select[name="category"]');
    const sortSelect = document.querySelector('select[name="sort"]');
    
    const params = new URLSearchParams();
    if (searchInput.value) params.append('search', searchInput.value);
    if (categorySelect.value) params.append('category', categorySelect.value);
    if (sortSelect.value) params.append('sort', sortSelect.value);
    
    window.location.href = `{{ route('author.books.published') }}?${params.toString()}`;
}

// Auto-search on input change
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(searchBooks, 500);
        });
    }
    
    const categorySelect = document.querySelector('select[name="category"]');
    const sortSelect = document.querySelector('select[name="sort"]');
    if (categorySelect) categorySelect.addEventListener('change', searchBooks);
    if (sortSelect) sortSelect.addEventListener('change', searchBooks);
});
</script>
@endsection
