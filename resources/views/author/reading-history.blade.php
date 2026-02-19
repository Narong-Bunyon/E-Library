@extends('layouts.author')

@section('title', 'Reading History - E-Library')

@section('page-title', 'Reading History')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Reading History</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportHistory()">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-outline-danger" onclick="clearHistory()">
                <i class="fas fa-trash me-2"></i>Clear History
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingHistory::where('user_id', auth()->user()->id)->count() }}</h5>
                    <p>Books Read</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingHistory::where('user_id', auth()->user()->id)->where('completed', true)->count() }}</h5>
                    <p>Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingHistory::where('user_id', auth()->user()->id)->where('completed', false)->count() }}</h5>
                    <p>In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingHistory::where('user_id', auth()->user()->id)->sum('reading_time') ?? 0 }}</h5>
                    <p>Total Hours</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reading History Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">My Reading History</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="in-progress">In Progress</option>
                        <option value="abandoned">Abandoned</option>
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
                            <th>Book</th>
                            <th>Author</th>
                            <th>Started</th>
                            <th>Last Read</th>
                            <th>Progress</th>
                            <th>Time Spent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readingHistory as $history)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($history->book->cover_image)
                                        <img src="{{ asset('storage/' . $history->book->cover_image) }}" alt="{{ $history->book->title }}" style="width: 30px; height: 40px; object-fit: cover; margin-right: 10px;">
                                    @else
                                        <div style="width: 30px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold; margin-right: 10px;">
                                            {{ strtoupper(substr($history->book->title, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $history->book->title }}</div>
                                        <small class="text-muted">{{ \Str::limit($history->book->description, 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $history->book->author->name }}</td>
                            <td>{{ $history->started_at->format('M d, Y') }}</td>
                            <td>{{ $history->last_read_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 100px; height: 8px;">
                                        <div class="progress-bar {{ $history->progress_percentage >= 75 ? 'bg-success' : ($history->progress_percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ $history->progress_percentage ?? 0 }}%"></div>
                                    </div>
                                    <span class="fw-semibold">{{ $history->progress_percentage ?? 0 }}%</span>
                                </div>
                            </td>
                            <td>{{ $history->reading_time ?? 0 }}h</td>
                            <td>
                                <span class="badge {{ $history->completed ? 'bg-success' : ($history->progress_percentage > 0 ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ $history->completed ? 'Completed' : ($history->progress_percentage > 0 ? 'In Progress' : 'Not Started') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="continueReading({{ $history->book_id }})">
                                        <i class="fas fa-book-open"></i>
                                    </button>
                                    <button class="btn btn-outline-info" onclick="viewDetails({{ $history->id }})">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="removeFromHistory({{ $history->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted">No reading history found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Reading Statistics -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Most Read Categories</h5>
                </div>
                <div class="card-body">
                    @forelse ($mostReadCategories as $category)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-layer-group text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $category->name }}</div>
                            <small class="text-muted">{{ $category->books_count }} books read</small>
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
                    <h5 class="card-title mb-0">Reading Activity</h5>
                </div>
                <div class="card-body">
                    @forelse ($recentActivity as $activity)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-book-reader text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $activity->book->title }}</div>
                            <small class="text-muted">{{ $activity->last_read_at->diffForHumans() }}</small>
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

<script>
function continueReading(bookId) {
    // Continue reading the book
    window.location.href = `/books/${bookId}`;
}

function viewDetails(historyId) {
    // View detailed reading history
    console.log('Viewing details for:', historyId);
}

function removeFromHistory(historyId) {
    if (confirm('Are you sure you want to remove this from your reading history?')) {
        // Remove from history
        console.log('Removing from history:', historyId);
    }
}

function exportHistory() {
    // Export reading history
    console.log('Exporting reading history');
}

function clearHistory() {
    if (confirm('Are you sure you want to clear your entire reading history? This action cannot be undone.')) {
        // Clear all history
        console.log('Clearing reading history');
    }
}
</script>
@endsection
