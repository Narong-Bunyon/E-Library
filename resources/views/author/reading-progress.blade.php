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
    
    <!-- View Progress Modal -->
    <div class="modal fade" id="viewProgressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reading Progress Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="progressDetails">
                    <!-- Progress details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewProgress(progressId) {
    fetch(`/author/reading-progress/${progressId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const progress = data.progress;
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Reader Information</h6>
                        <p><strong>Name:</strong> ${progress.user?.name || 'N/A'}</p>
                        <p><strong>Email:</strong> ${progress.user?.email || 'N/A'}</p>
                        <p><strong>Phone:</strong> ${progress.user?.phone || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Book Information</h6>
                        <p><strong>Title:</strong> ${progress.book?.title || 'N/A'}</p>
                        <p><strong>Author:</strong> ${progress.book?.author?.name || 'N/A'}</p>
                        <p><strong>Pages:</strong> ${progress.book?.pages || 'N/A'}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Progress Details</h6>
                        <p><strong>Current Page:</strong> ${progress.current_page || 'N/A'}</p>
                        <p><strong>Total Pages:</strong> ${progress.book?.pages || 'N/A'}</p>
                        <p><strong>Progress:</strong> ${progress.progress_percentage || 0}%</p>
                        <p><strong>Status:</strong> <span class="badge ${progress.progress_percentage >= 100 ? 'bg-success' : (progress.progress_percentage >= 50 ? 'bg-warning' : 'bg-danger')}">${progress.progress_percentage >= 100 ? 'Completed' : (progress.progress_percentage >= 50 ? 'In Progress' : 'Just Started')}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Timeline</h6>
                        <p><strong>Started:</strong> ${progress.created_at ? new Date(progress.created_at).toLocaleDateString() : 'N/A'}</p>
                        <p><strong>Last Updated:</strong> ${progress.updated_at ? new Date(progress.updated_at).toLocaleDateString() : 'N/A'}</p>
                        <p><strong>Completed:</strong> ${progress.completed_at ? new Date(progress.completed_at).toLocaleDateString() : 'Not completed'}</p>
                    </div>
                </div>
            `;
            document.getElementById('progressDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewProgressModal')).show();
        } else {
            alert('Error loading progress details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading progress details: ' + error.message);
    });
}

function sendMessage(userId, bookId) {
    const message = prompt('Enter your message to the reader:');
    if (message && message.trim()) {
        // Here you would implement an actual messaging system
        alert(`Message sent to user ${userId}: ${message}`);
    }
}

function exportProgress() {
    const filter = document.getElementById('progressFilter').value;
    const bookFilter = document.getElementById('bookFilter').value;
    
    let url = '/author/reading-progress/export';
    const params = new URLSearchParams();
    
    if (filter) params.append('filter', filter);
    if (bookFilter) params.append('book', bookFilter);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    window.location.href = url;
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const progressFilter = document.getElementById('progressFilter');
    const bookFilter = document.getElementById('bookFilter');
    
    if (progressFilter) {
        progressFilter.addEventListener('change', function() {
            const url = new URL(window.location);
            if (this.value) {
                url.searchParams.set('filter', this.value);
            } else {
                url.searchParams.delete('filter');
            }
            window.location.href = url.toString();
        });
    }
    
    if (bookFilter) {
        bookFilter.addEventListener('change', function() {
            const url = new URL(window.location);
            if (this.value) {
                url.searchParams.set('book', this.value);
            } else {
                url.searchParams.delete('book');
            }
            window.location.href = url.toString();
        });
    }
});
</script>
@endpush
@endsection
