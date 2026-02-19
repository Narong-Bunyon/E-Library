@extends('layouts.author')

@use App\Models\Favorite

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
                    <h5>{{ \App\Models\Favorite::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->count() }}</h5>
                    <p>Total Favorites</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Favorite::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->distinct('user_id')->count() }}</h5>
                    <p>Unique Readers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Favorite::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->whereDate(Favorite::CREATED_AT, '>=', now()->subDays(30))->count() }}</h5>
                    <p>Last 30 Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Book::where('author_id', auth()->user()->id)->withCount('favorites')->orderBy('favorites_count', 'desc')->first()->favorites_count ?? 0 }}</h5>
                    <p>Most Favorited</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Favorites Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Book Favorites</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="dateFilter">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
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
                            <th>Reader</th>
                            <th>Book</th>
                            <th>Favorite Date</th>
                            <th>Book Views</th>
                            <th>Book Downloads</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($favorites as $favorite)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <i class="fas fa-user-circle text-muted"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $favorite->user->name }}</div>
                                        <small class="text-muted">{{ $favorite->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($favorite->book->cover_image)
                                        <img src="{{ asset('storage/' . $favorite->book->cover_image) }}" alt="{{ $favorite->book->title }}" style="width: 30px; height: 40px; object-fit: cover; margin-right: 10px;">
                                    @else
                                        <div style="width: 30px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold; margin-right: 10px;">
                                            {{ strtoupper(substr($favorite->book->title, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $favorite->book->title }}</div>
                                        <small class="text-muted">{{ \Str::limit($favorite->book->description, 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $favorite->created_at ? $favorite->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ number_format($favorite->book->views ?? 0) }}</td>
                            <td>{{ number_format($favorite->book->downloads ?? 0) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewReader({{ $favorite->user_id }})">
                                        <i class="fas fa-user"></i>
                                    </button>
                                    <button class="btn btn-outline-info" onclick="viewBook({{ $favorite->book_id }})">
                                        <i class="fas fa-book"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="sendMessage({{ $favorite->user_id }}, {{ $favorite->book_id }})">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted">No favorites found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Most Favorited Books -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Most Favorited Books</h5>
                </div>
                <div class="card-body">
                    @forelse ($mostFavoritedBooks as $book)
                    <div class="d-flex align-items-center mb-3">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" style="width: 40px; height: 50px; object-fit: cover; margin-right: 10px;">
                        @else
                            <div style="width: 40px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; margin-right: 10px;">
                                {{ strtoupper(substr($book->title, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $book->title }}</div>
                            <small class="text-muted">{{ $book->favorites_count }} favorites</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No data available.</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Favorites</h5>
                </div>
                <div class="card-body">
                    @forelse ($recentFavorites as $favorite)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-heart text-danger"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $favorite->user->name }} favorited {{ $favorite->book->title }}</div>
                            <small class="text-muted">{{ $favorite->created_at ? $favorite->created_at->diffForHumans() : 'N/A' }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No recent favorites.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewReader(userId) {
    // View reader profile
}

function viewBook(bookId) {
    // View book details
}

function sendMessage(userId, bookId) {
    // Send message to reader
}

function exportFavorites() {
    // Export favorites data
}
</script>
@endsection
