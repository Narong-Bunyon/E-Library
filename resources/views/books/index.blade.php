@extends('layouts.author')

@section('title', 'My Books - E-Library')

@section('page-title', 'My Books')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Books</h4>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Book
        </a>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Book::where('author_id', auth()->user()->id)->count() }}</h5>
                    <p>Total Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Book::where('author_id', auth()->user()->id)->where('status', 1)->count() }}</h5>
                    <p>Published</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Book::where('author_id', auth()->user()->id)->where('status', 0)->count() }}</h5>
                    <p>Drafts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ number_format(\App\Models\Book::where('author_id', auth()->user()->id)->sum('views') ?? 0) }}</h5>
                    <p>Total Views</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Books</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('books.published') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-check-circle me-1"></i>Published
                    </a>
                    <a href="{{ route('books.drafts') }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit me-1"></i>Drafts
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Downloads</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $book)
                        <tr>
                            <td>
                                @if($book->cover_image)
                                    <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" style="width: 40px; height: 50px; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                                        {{ strtoupper(substr($book->title, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $book->title }}</div>
                                <small class="text-muted">{{ \Str::limit($book->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $book->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $book->status === 1 ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ number_format($book->views ?? 0) }}</td>
                            <td>{{ number_format($book->downloads ?? 0) }}</td>
                            <td>{{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($book->status === 0)
                                        <form action="{{ route('books.publish', $book->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Publish">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted">No books found. <a href="{{ route('books.create') }}">Create your first book</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($books) && $books->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
