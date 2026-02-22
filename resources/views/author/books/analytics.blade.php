@extends('layouts.author')

@section('title', 'Book Analytics - E-Library')

@section('page-title', 'Book Analytics')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ number_format(\App\Models\Book::where('author_id', auth()->user()->id)->sum('views') ?? 0) }}</h5>
                    <p>Total Views</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Download::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->count() }}</h5>
                    <p>Total Downloads</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Review::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->count() }}</h5>
                    <p>Total Reviews</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Favorite::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->count() }}</h5>
                    <p>Total Favorites</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Book Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Views</th>
                                    <th>Downloads</th>
                                    <th>Reviews</th>
                                    <th>Favorites</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($books as $book)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($book->cover_image)
                                                <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" style="width: 30px; height: 40px; object-fit: cover; margin-right: 10px;">
                                            @else
                                                <div style="width: 30px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold; margin-right: 10px;">
                                                    {{ strtoupper(substr($book->title, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $book->title }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($book->views ?? 0) }}</td>
                                    <td>{{ number_format($book->downloads ?? 0) }}</td>
                                    <td>{{ $book->reviews()->count() }}</td>
                                    <td>{{ $book->favorites()->count() }}</td>
                                    <td>
                                        @if($book->reviews()->count() > 0)
                                            <div class="d-flex align-items-center">
                                                <span class="me-1">{{ number_format($book->averageRating(), 1) }}</span>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star fa-sm {{ $i <= round($book->averageRating()) ? '' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No reviews</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $book->status === 1 ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted">No books found.</p>
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Performing Books</h5>
                </div>
                <div class="card-body">
                    @forelse ($topBooks as $book)
                    <div class="d-flex align-items-center mb-3">
                        @if($book->cover_image)
                            <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" style="width: 40px; height: 50px; object-fit: cover; margin-right: 10px;">
                        @else
                            <div style="width: 40px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; margin-right: 10px;">
                                {{ strtoupper(substr($book->title, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $book->title }}</div>
                            <small class="text-muted">{{ number_format($book->views ?? 0) }} views</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No data available.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    @forelse ($recentActivity as $activity)
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-2">
                            <i class="fas {{ $activity['icon'] }} text-primary"></i>
                        </div>
                        <div>
                            <div class="small">{{ $activity['text'] }}</div>
                            <small class="text-muted">{{ $activity['time'] }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No recent activity.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
