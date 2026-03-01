@extends('layouts.author')

@section('title', 'My Favorites - E-Library')

@section('page-title', 'My Favorites')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Favorites</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportFavorites()">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-outline-danger" onclick="clearFavorites()">
                <i class="fas fa-trash me-2"></i>Clear All
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Favorite::where('user_id', auth()->user()->id)->count() }}</h5>
                    <p>Total Favorites</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Favorite::where('user_id', auth()->user()->id)->whereHas('book', function($query) { $query->where('status', 1); })->count() }}</h5>
                    <p>Published Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Favorite::where('user_id', auth()->user()->id)->whereDate('created_at', '>=', now()->subDays(30))->count() }}</h5>
                    <p>Last 30 Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Category::whereHas('books.favorites', function($query) { $query->where('user_id', auth()->user()->id); })->distinct()->count() }}</h5>
                    <p>Categories</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Favorites Grid -->
    <div class="row" id="favoritesGrid">
        @forelse ($favorites as $favorite)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                @if($favorite->book->cover_image)
                    <img src="{{ asset('storage/' . $favorite->book->cover_image) }}" class="card-img-top" alt="{{ $favorite->book->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 3rem; font-weight: bold;">
                        {{ strtoupper(substr($favorite->book->title, 0, 1)) }}
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $favorite->book->title }}</h5>
                    <p class="card-text text-muted">{{ \Str::limit($favorite->book->description, 80) }}</p>
                    <div class="mb-2">
                        <small class="text-muted">By {{ $favorite->book->author->name }}</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $favorite->book->category->name ?? 'Uncategorized' }}</span>
                        <span class="badge {{ $favorite->book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                            {{ $favorite->book->status === 1 ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <div class="mb-2">
                        @if($favorite->book->reviews()->count() > 0)
                            <div class="d-flex align-items-center">
                                <span class="me-1">{{ number_format($favorite->book->averageRating(), 1) }}</span>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star fa-sm {{ $i <= round($favorite->book->averageRating()) ? '' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">({{ $favorite->book->reviews()->count() }})</small>
                            </div>
                        @else
                            <small class="text-muted">No reviews</small>
                        @endif
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-heart me-1"></i>Favorited {{ $favorite->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($favorite->book->views ?? 0) }}
                                <i class="fas fa-download ms-2 me-1"></i>{{ number_format($favorite->book->downloads ?? 0) }}
                            </small>
                        </div>
                        <div class="btn-group w-100">
                            <a href="{{ route('books.show', $favorite->book->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($favorite->book->file_path)
                                <a href="{{ asset('storage/' . $favorite->book->file_path) }}" class="btn btn-outline-success btn-sm" target="_blank">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @endif
                            <button class="btn btn-outline-danger btn-sm" onclick="removeFromFavorites({{ $favorite->id }})">
                                <i class="fas fa-heart-broken"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No favorite books found.</p>
            <a href="{{ route('browse') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Browse Library
            </a>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($favorites->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $favorites->links() }}
    </div>
    @endif
</div>

<script>
function removeFromFavorites(favoriteId) {
    if (confirm('Are you sure you want to remove this book from your favorites?')) {
        fetch(`/favorites/${favoriteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error removing from favorites');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing from favorites');
        });
    }
}

function exportFavorites() {
    window.location.href = '/favorites/export';
}

function clearFavorites() {
    if (confirm('Are you sure you want to clear all your favorites? This action cannot be undone.')) {
        fetch('/favorites/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error clearing favorites');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error clearing favorites');
        });
    }
}
</script>
@endsection
