@extends('layouts.author')

@section('title', 'My Books - E-Library')

@section('page-title', 'My Books')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Books</h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus me-2"></i>Add New Book
            </button>
        </div>
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
    
    <!-- Filter Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="{{ route('author.books.index') }}" class="btn {{ request()->is('author/books') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-list me-2"></i>All Books
                </a>
                <a href="{{ route('author.books.published') }}" class="btn {{ request()->is('author/books/published') ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="fas fa-check-circle me-2"></i>Published
                </a>
                <a href="{{ route('author.books.drafts') }}" class="btn {{ request()->is('author/books/drafts') ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-edit me-2"></i>Drafts
                </a>
            </div>
        </div>
    </div>
    
    <!-- Books Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Books</h5>
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
                            <th>Rating</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $book)
                        <tr>
                            <td>
                                @if($book->cover_image)
                                    <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" style="width: 50px; height: 60px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div style="width: 50px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: bold;">
                                        {{ strtoupper(substr($book->title, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $book->title }}</div>
                                <small class="text-muted">{{ \Str::limit($book->description, 60) }}</small>
                                @if($book->isbn)
                                    <div><small class="text-info">ISBN: {{ $book->isbn }}</small></div>
                                @endif
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
                            <td>
                                @if($book->reviews && $book->reviews->count() > 0)
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning me-1">{{ number_format($book->reviews->avg('rating'), 1) }}</span>
                                        <small class="text-muted">({{ $book->reviews->count() }})</small>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewBookModal{{ $book->id }}" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="openEditModal({{ $book->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#reviewsModal{{ $book->id }}" title="Reviews">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    @if($book->status === 0)
                                        <form action="{{ route('author.books.publish', $book->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Publish">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline-warning" onclick="confirmUnpublish({{ $book->id }}, '{{ $book->title }}')" title="Unpublish">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $book->id }}, '{{ $book->title }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- View Book Modal -->
                        <div class="modal fade" id="viewBookModal{{ $book->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Book Details - {{ $book->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                @if($book->cover_image)
                                                    <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" 
                                                         alt="{{ $book->title }}" class="img-fluid rounded">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                                        <i class="fas fa-book fa-3x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <h4>{{ $book->title }}</h4>
                                                <p class="text-muted">{{ $book->description }}</p>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <strong>Category:</strong> {{ $book->category->name ?? 'Uncategorized' }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>ISBN:</strong> {{ $book->isbn ?? 'N/A' }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Pages:</strong> {{ $book->pages ?? 'N/A' }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Language:</strong> {{ $book->language ?? 'N/A' }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Created:</strong> {{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Status:</strong> <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">{{ $book->status === 1 ? 'Published' : 'Draft' }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="badge bg-primary">{{ number_format($book->views ?? 0) }} Views</span>
                                                        <span class="badge bg-info">{{ number_format($book->downloads ?? 0) }} Downloads</span>
                                                        @if($book->reviews && $book->reviews->count() > 0)
                                                            <span class="badge bg-warning">{{ number_format($book->reviews->avg('rating'), 1) }} ⭐</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="openEditModal({{ $book->id }})" data-bs-dismiss="modal">Edit Book</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <!-- Reviews Modal -->
                        <div class="modal fade" id="reviewsModal{{ $book->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reviews for {{ $book->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($book->reviews && $book->reviews->count() > 0)
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center">
                                                    <h4>{{ number_format($book->reviews->avg('rating'), 1) }}</h4>
                                                    <div class="ms-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= round($book->reviews->avg('rating')) ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="ms-2 text-muted">({{ $book->reviews->count() }} reviews)</span>
                                                </div>
                                            </div>
                                            
                                            <div class="review-list">
                                                @foreach($book->reviews->sortByDesc('created_at')->take(10) as $review)
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div>
                                                                <strong>{{ $review->user->name }}</strong>
                                                                <div class="text-warning">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}</small>
                                                        </div>
                                                        <p class="mb-0">{{ $review->comment ?? 'No comment provided' }}</p>
                                                        
                                                        @if($review->status == 'pending')
                                                            <div class="mt-2">
                                                                <span class="badge bg-warning">Pending Approval</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No Reviews Yet</h5>
                                                <p class="text-muted">This book hasn't received any reviews yet.</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-center">
                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Books Found</h5>
                                    <p class="text-muted">You haven't created any books yet. Start by creating your first book!</p>
                                    <a href="{{ route('author.books.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create Your First Book
                                    </a>
                                </div>
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

<!-- Unpublish Confirmation Modal -->
<div class="modal fade" id="unpublishModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Unpublish Book
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-eye-slash fa-3x text-warning mb-3"></i>
                    <h5>Are you sure you want to unpublish this book?</h5>
                    <p class="text-muted">
                        <strong id="unpublishBookTitle"></strong>
                    </p>
                    <p class="text-muted small">
                        Unpublishing will make this book invisible to readers. You can publish it again later.
                    </p>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>What happens when you unpublish:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Book will no longer be visible to readers</li>
                        <li>Book will be moved to drafts</li>
                        <li>All existing data (reviews, downloads, views) will be preserved</li>
                        <li>You can publish it again at any time</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirmUnpublishBtn">
                    <i class="fas fa-eye-slash me-2"></i>Unpublish Book
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Book
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete this book?</h5>
                    <p class="text-muted">
                        <strong id="deleteBookTitle"></strong>
                    </p>
                    <p class="text-muted small">
                        This action cannot be undone. All book data will be permanently deleted.
                    </p>
                </div>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning: This will permanently delete:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Book content and metadata</li>
                        <li>All associated reviews and ratings</li>
                        <li>Download records and statistics</li>
                        <li>Reading progress data</li>
                        <li>Favorites and bookmarks</li>
                    </ul>
                </div>
                <div class="form-group">
                    <label for="deleteConfirmText" class="form-label">
                        Type <strong>DELETE</strong> to confirm:
                    </label>
                    <input type="text" class="form-control" id="deleteConfirmText" placeholder="Type DELETE to confirm">
                    <small class="text-muted">This extra step helps prevent accidental deletions</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash me-2"></i>Delete Book Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Book
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            @include('author.books.partials.edit-form')
        </div>
    </div>
</div>

<!-- Create Book Modal -->
<div class="modal fade" id="createBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Create New Book
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            @include('author.books.partials.create-form')
        </div>
    </div>
</div>

@include('author.books.partials.form-scripts')

<script>
// Additional functions for index-specific functionality
let currentBookId = null;

function confirmUnpublish(bookId, bookTitle) {
    currentBookId = bookId;
    document.getElementById('unpublishBookTitle').textContent = bookTitle;
    
    const modal = new bootstrap.Modal(document.getElementById('unpublishModal'));
    modal.show();
}

function confirmDelete(bookId, bookTitle) {
    currentBookId = bookId;
    document.getElementById('deleteBookTitle').textContent = bookTitle;
    document.getElementById('deleteConfirmText').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Handle delete confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteInput = document.getElementById('deleteConfirmText');
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    
    if (deleteInput && deleteBtn) {
        deleteInput.addEventListener('input', function(e) {
            deleteBtn.disabled = e.target.value !== 'DELETE';
        });
        
        deleteBtn.addEventListener('click', function() {
            if (currentBookId && deleteInput.value === 'DELETE') {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/author/books/${currentBookId}`;
                form.style.display = 'none';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});

// Handle unpublish confirmation
document.addEventListener('DOMContentLoaded', function() {
    const unpublishBtn = document.getElementById('confirmUnpublishBtn');
    
    if (unpublishBtn) {
        unpublishBtn.addEventListener('click', function() {
            if (currentBookId) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/author/books/${currentBookId}/unpublish`;
                form.style.display = 'none';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
</script>
@endsection
