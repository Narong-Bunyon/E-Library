@extends('layouts.author')

@section('title', 'Reading Progress - E-Library')

@section('page-title', 'Reading Progress')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Reading Progress</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportProgress()">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingProgress::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->distinct('user_id')->count() }}</h5>
                    <p>Total Readers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingProgress::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->where('progress_percentage', '>=', 100)->count() }}</h5>
                    <p>Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingProgress::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->where('progress_percentage', '>=', 50)->where('progress_percentage', '<', 100)->count() }}</h5>
                    <p>In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\ReadingProgress::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->avg('progress_percentage') ? number_format(\App\Models\ReadingProgress::whereHas('book', function($query) { $query->where('author_id', auth()->user()->id); })->avg('progress_percentage'), 1) : '0' }}%</h5>
                    <p>Avg Progress</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reading Progress Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Reader Progress</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="progressFilter">
                        <option value="">All Progress</option>
                        <option value="completed">Completed</option>
                        <option value="in-progress">In Progress</option>
                        <option value="just-started">Just Started</option>
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
                            <th>Progress</th>
                            <th>Current Page</th>
                            <th>Total Pages</th>
                            <th>Started</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readingProgress as $progress)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <i class="fas fa-user-circle text-muted"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $progress->user->name }}</div>
                                        <small class="text-muted">{{ $progress->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($progress->book->cover_image)
                                        <img src="{{ asset('storage/' . $progress->book->cover_image) }}" alt="{{ $progress->book->title }}" style="width: 30px; height: 40px; object-fit: cover; margin-right: 10px;">
                                    @else
                                        <div style="width: 30px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold; margin-right: 10px;">
                                            {{ strtoupper(substr($progress->book->title, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $progress->book->title }}</div>
                                        <small class="text-muted">{{ \Str::limit($progress->book->description, 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 100px; height: 8px;">
                                        <div class="progress-bar {{ $progress->progress_percentage >= 75 ? 'bg-success' : ($progress->progress_percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ $progress->progress_percentage }}%"></div>
                                    </div>
                                    <span class="fw-semibold">{{ $progress->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td>{{ $progress->current_page ?? 'N/A' }}</td>
                            <td>{{ $progress->book->pages ?? 'N/A' }}</td>
                            <td>{{ $progress->created_at->format('M d, Y') }}</td>
                            <td>{{ $progress->updated_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge {{ $progress->progress_percentage >= 100 ? 'bg-success' : ($progress->progress_percentage >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $progress->progress_percentage >= 100 ? 'Completed' : ($progress->progress_percentage >= 50 ? 'In Progress' : 'Just Started') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewProgress({{ $progress->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-info" onclick="sendMessage({{ $progress->user_id }}, {{ $progress->book_id }})">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted">No reading progress found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function viewProgress(progressId) {
    // View full progress details
}

function sendMessage(userId, bookId) {
    // Send message to reader
}

function exportProgress() {
    // Export reading progress data
}
</script>
@endsection
