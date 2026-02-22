@extends('layouts.author')

@section('title', 'Book Details - E-Library')

@section('page-title', 'Book Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $book->title }}</h5>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this book?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($book->cover_image)
                                <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid rounded">
                            @else
                                <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                    {{ strtoupper(substr($book->title, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $book->title }}</h4>
                            <p class="text-muted mb-3">{{ $book->description }}</p>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Author:</strong> {{ $book->author->name }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Category:</strong> {{ $book->category->name ?? 'Uncategorized' }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>ISBN:</strong> {{ $book->isbn ?? 'N/A' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Pages:</strong> {{ $book->pages ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Language:</strong> {{ $book->language ?? 'N/A' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Published:</strong> {{ $book->published_date ? \Carbon\Carbon::parse($book->published_date)->format('M d, Y') : 'Not set' }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Access Level:</strong>
                                    <span class="badge bg-info">{{ ucfirst($book->access_level ?? 'public') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Status:</strong>
                                    <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $book->status === 1 ? 'Published' : 'Draft' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($book->excerpt)
                                <div class="mb-3">
                                    <strong>Excerpt:</strong>
                                    <p class="text-muted">{{ $book->excerpt }}</p>
                                </div>
                            @endif
                            
                            <div class="d-flex gap-2">
                                @if($book->file_path)
                                    <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-download me-2"></i>Download Book
                                    </a>
                                @endif
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Book
                                </a>
                                @if($book->status === 0)
                                    <form action="{{ route('books.publish', $book->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>Publish Book
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Book Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Views</span>
                        <span class="badge bg-primary">{{ number_format($book->views ?? 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Downloads</span>
                        <span class="badge bg-success">{{ number_format($book->downloads ?? 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Reviews</span>
                        <span class="badge bg-info">{{ $book->reviews()->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Favorites</span>
                        <span class="badge bg-warning">{{ $book->favorites()->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Average Rating</span>
                        <span class="badge bg-secondary">
                            @if($book->reviews()->count() > 0)
                                {{ number_format($book->averageRating(), 1) }} ⭐
                            @else
                                No Rating
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Reviews</h5>
                </div>
                <div class="card-body">
                    @forelse($book->reviews()->latest()->take(5)->get() as $review)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $review->user->name }}</div>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star fa-sm {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <p class="text-muted text-center">No reviews yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
