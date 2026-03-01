@extends('layouts.author')

@section('title', 'Published Books - Author Dashboard')

@section('page-title', 'Published Books')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Published Books</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('author.books.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-2"></i>All Books
            </a>
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus me-2"></i>Add New Book
            </button>
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
                    <h5>{{ number_format($books->sum('views')) }}</h5>
                    <p>Total Views</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ number_format($books->sum('downloads')) }}</h5>
                    <p>Total Downloads</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ $books->avg('reviews.rating') ? number_format($books->avg('reviews.rating'), 1) : 'N/A' }}</h5>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Grid -->
    <div class="row">
        @forelse($books as $book)
        <div class="col-lg-6 mb-4">
            <div class="card h-100 book-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">{{ $book->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($book->description, 150) }}</p>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary">{{ $book->category->name ?? 'Uncategorized' }}</span>
                                <span class="badge bg-success">Published</span>
                                @if($book->isbn)
                                    <span class="badge bg-info">ISBN: {{ $book->isbn }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($book->cover_image)
                                    <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" 
                                         alt="{{ $book->title }}" class="img-fluid rounded mb-2" style="max-height: 120px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" style="height: 120px;">
                                        <i class="fas fa-book fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Row -->
                    <div class="row text-center mb-3">
                        <div class="col-3">
                            <small class="text-muted">Views</small>
                            <div class="fw-bold">{{ number_format($book->views ?? 0) }}</div>
                        </div>
                        <div class="col-3">
                            <small class="text-muted">Downloads</small>
                            <div class="fw-bold">{{ number_format($book->downloads ?? 0) }}</div>
                        </div>
                        <div class="col-3">
                            <small class="text-muted">Rating</small>
                            <div class="fw-bold">
                                @if($book->reviews->count() > 0)
                                    {{ number_format($book->reviews->avg('rating'), 1) }}
                                    <small class="text-muted">({{ $book->reviews->count() }})</small>
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="col-3">
                            <small class="text-muted">Reviews</small>
                            <div class="fw-bold">{{ $book->reviews->count() }}</div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewBookModal{{ $book->id }}">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBookModal{{ $book->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#reviewsModal{{ $book->id }}">
                            <i class="fas fa-star"></i> Reviews ({{ $book->reviews->count() }})
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="duplicateBook({{ $book->id }})">
                            <i class="fas fa-copy"></i> Duplicate
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- View Book Modal -->
        <div class="modal fade" id="viewBookModal{{ $book->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Book Details - {{ $book->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if($book->cover_image)
                                    <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" 
                                         alt="{{ $book->title }}" class="img-fluid rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-book fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $book->title }}</h4>
                                <p class="text-muted">{{ $book->description }}</p>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Category:</strong> {{ $book->category->name ?? 'Uncategorized' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>ISBN:</strong> {{ $book->isbn ?? 'N/A' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Pages:</strong> {{ $book->pages ?? 'N/A' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Language:</strong> {{ $book->language ?? 'N/A' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Published:</strong> {{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Status:</strong> <span class="badge bg-success">Published</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-primary">{{ number_format($book->views ?? 0) }} Views</span>
                                        <span class="badge bg-info">{{ number_format($book->downloads ?? 0) }} Downloads</span>
                                        @if($book->reviews->count() > 0)
                                            <span class="badge bg-warning">{{ number_format($book->reviews->avg('rating'), 1) }} ⭐</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('author.books.edit', $book->id) }}" class="btn btn-primary">Edit Book</a>
                    </div>
                </div>
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
        
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Published Books</h5>
                <p class="text-muted">You haven't published any books yet. Start by creating a new book!</p>
                <a href="{{ route('author.books.create') }}" class="btn btn-primary">
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

@include('author.books.partials.form-scripts')
@endsection
