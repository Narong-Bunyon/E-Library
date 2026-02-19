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
                    <h5>0.0</h5>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Grid -->
    <div class="row">
        @forelse($books as $book)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $book->title }}</h5>
                    <p class="card-text">{{ Str::limit($book->description, 100) }}</p>
                    
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
                            <div class="fw-bold">N/A</div>
                        </div>
                    </div>
                    
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('author.books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('author.books.edit', $book->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
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
@endsection
