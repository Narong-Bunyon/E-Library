@extends('layouts.author')

@section('title', 'Browse Library - E-Library')

@section('page-title', 'Browse Library')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Browse Library</h4>
        <div class="d-flex gap-2">
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" placeholder="Search books..." id="searchInput">
                <button class="btn btn-outline-primary" onclick="searchBooks()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Book::count() }}</h5>
                    <p>Total Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Book::where('status', 1)->count() }}</h5>
                    <p>Published Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Category::count() }}</h5>
                    <p>Categories</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\User::count() }}</h5>
                    <p>Total Users</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Author</label>
                    <select class="form-select" id="authorFilter">
                        <option value="">All Authors</option>
                        @foreach(\App\Models\User::whereHas('books')->get() as $author)
                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Language</label>
                    <select class="form-select" id="languageFilter">
                        <option value="">All Languages</option>
                        <option value="en">English</option>
                        <option value="kh">Khmer</option>
                        <option value="fr">French</option>
                        <option value="zh">Chinese</option>
                        <option value="es">Spanish</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select class="form-select" id="sortBy">
                        <option value="latest">Latest</option>
                        <option value="popular">Most Popular</option>
                        <option value="rating">Highest Rated</option>
                        <option value="downloads">Most Downloaded</option>
                        <option value="title">Title A-Z</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Grid -->
    <div class="row" id="booksGrid">
        @forelse ($books as $book)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                @if($book->cover_image)
                    <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 3rem; font-weight: bold;">
                        {{ strtoupper(substr($book->title, 0, 1)) }}
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $book->title }}</h5>
                    <p class="card-text text-muted">{{ \Str::limit($book->description, 80) }}</p>
                    <div class="mb-2">
                        <small class="text-muted">By {{ $book->author->name }}</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $book->category->name ?? 'Uncategorized' }}</span>
                        <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                            {{ $book->status === 1 ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <div class="mb-2">
                        @if($book->reviews()->count() > 0)
                            <div class="d-flex align-items-center">
                                <span class="me-1">{{ number_format($book->averageRating(), 1) }}</span>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star fa-sm {{ $i <= round($book->averageRating()) ? '' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">({{ $book->reviews()->count() }})</small>
                            </div>
                        @else
                            <small class="text-muted">No reviews</small>
                        @endif
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($book->views ?? 0) }}
                                <i class="fas fa-download ms-2 me-1"></i>{{ number_format($book->downloads ?? 0) }}
                            </small>
                        </div>
                        <div class="btn-group w-100">
                            <a href="{{ route('author.books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($book->file_path)
                                <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-outline-success btn-sm" target="_blank">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @endif
                            <button class="btn btn-outline-danger btn-sm" onclick="addToFavorites({{ $book->id }})">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No books found.</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if(isset($books) && $books->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $books->links() }}
        </div>
    @endif
</div>

<script>
function searchBooks() {
    const searchTerm = document.getElementById('searchInput').value;
    // Implement search functionality
    console.log('Searching for:', searchTerm);
}

function addToFavorites(bookId) {
    // Add book to favorites
    console.log('Adding to favorites:', bookId);
}

// Filter change handlers
document.getElementById('categoryFilter')?.addEventListener('change', filterBooks);
document.getElementById('authorFilter')?.addEventListener('change', filterBooks);
document.getElementById('languageFilter')?.addEventListener('change', filterBooks);
document.getElementById('sortBy')?.addEventListener('change', filterBooks);

function filterBooks() {
    const category = document.getElementById('categoryFilter')?.value || '';
    const author = document.getElementById('authorFilter')?.value || '';
    const language = document.getElementById('languageFilter')?.value || '';
    const sortBy = document.getElementById('sortBy')?.value || 'latest';
    
    // Implement filter functionality
    console.log('Filtering books:', { category, author, language, sortBy });
}
</script>
@endsection
