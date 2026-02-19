@extends('layouts.author')

@section('title', 'Downloads - E-Library')

@section('page-title', 'Downloads')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Downloads</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportDownloads()">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
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
                    <h5>{{ \App\Models\Download::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->distinct('user_id')->count() }}</h5>
                    <p>Unique Downloaders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Download::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->whereDate('created_at', '>=', now()->subDays(30))->count() }}</h5>
                    <p>Last 30 Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Book::where('author_id', auth()->user()->id)->withCount('downloads')->orderBy('downloads_count', 'desc')->first()->downloads_count ?? 0 }}</h5>
                    <p>Most Downloaded</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Downloads Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Download History</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                        <option value="pending">Pending</option>
                    </select>
                    <select class="form-select form-select-sm" id="bookFilter">
                        <option value="">All Books</option>
                        @foreach(\App\Models\Book::where('author_id', auth()->user()->id)->get() as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                    <select class="form-select form-select-sm" id="dateFilter">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Downloader</th>
                            <th>Book</th>
                            <th>Download Date</th>
                            <th>File Size</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($downloads as $download)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <i class="fas fa-user-circle text-muted"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $download->user->name ?? 'Guest' }}</div>
                                        <small class="text-muted">{{ $download->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($download->book->cover_image)
                                        <img src="{{ asset('storage/' . $download->book->cover_image) }}" alt="{{ $download->book->title }}" style="width: 30px; height: 40px; object-fit: cover; margin-right: 10px;">
                                    @else
                                        <div style="width: 30px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold; margin-right: 10px;">
                                            {{ strtoupper(substr($download->book->title, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $download->book->title }}</div>
                                        <small class="text-muted">{{ \Str::limit($download->book->description, 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $download->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $download->file_size ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $download->status === 'completed' ? 'bg-success' : ($download->status === 'failed' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($download->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>{{ $download->ip_address ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewDownloader({{ $download->user_id ?? 0 }})">
                                        <i class="fas fa-user"></i>
                                    </button>
                                    <button class="btn btn-outline-info" onclick="viewBook({{ $download->book_id }})">
                                        <i class="fas fa-book"></i>
                                    </button>
                                    @if($download->user_id)
                                        <button class="btn btn-outline-success" onclick="sendMessage({{ $download->user_id }}, {{ $download->book_id }})">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted">No downloads found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Download Statistics -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Most Downloaded Books</h5>
                </div>
                <div class="card-body">
                    @forelse ($mostDownloadedBooks as $book)
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
                            <small class="text-muted">{{ $book->downloads_count }} downloads</small>
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
                    <h5 class="card-title mb-0">Recent Downloads</h5>
                </div>
                <div class="card-body">
                    @forelse ($recentDownloads as $download)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-download text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $download->user->name ?? 'Guest' }} downloaded {{ $download->book->title }}</div>
                            <small class="text-muted">{{ $download->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No recent downloads.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewDownloader(userId) {
    // View downloader profile
}

function viewBook(bookId) {
    // View book details
}

function sendMessage(userId, bookId) {
    // Send message to downloader
}

function exportDownloads() {
    // Export download data
}
</script>
@endsection
