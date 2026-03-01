@extends('layouts.author')

@section('title', 'Draft Books - Author Dashboard')

@section('page-title', 'Draft Books')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="welcome-text">
                        <h4>Draft Books</h4>
                        <p class="text-muted">Manage your unpublished books and prepare them for publishing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body">
                    <div class="action-grid">
                        <a href="{{ route('author.books.published') }}" class="modern-action-btn success">
                            <i class="fas fa-check-circle"></i>
                            <span>Published</span>
                        </a>
                        
                        <!-- <button type="button" class="modern-action-btn primary" onclick="openCreateModal()">
                            <i class="fas fa-plus"></i>
                            <span>New Book</span>
                        </button> -->
                        
                        <a href="{{ route('author.analytics') }}" class="modern-action-btn info">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="stats-grid">
                <div class="modern-stats-card warning">
                    <div class="stats-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-number">{{ $books->total() }}</div>
                        <div class="stats-label">Draft Books</div>
                    </div>
                </div>
                
                <div class="modern-stats-card info">
                    <div class="stats-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-number">{{ number_format($books->sum('views') ?? 0) }}</div>
                        <div class="stats-label">Total Views</div>
                    </div>
                </div>
                
                <div class="modern-stats-card secondary">
                    <div class="stats-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-number">{{ number_format($books->sum('downloads') ?? 0) }}</div>
                        <div class="stats-label">Total Downloads</div>
                    </div>
                </div>
                
                <div class="modern-stats-card primary">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-number">{{ $books->count() > 0 ? \Carbon\Carbon::parse($books->first()->created_at)->diffForHumans() : 'N/A' }}</div>
                        <div class="stats-label">Last Updated</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="card-header">
                    <h5><i class="fas fa-lightbulb me-2"></i>Publishing Tips</h5>
                </div>
                <div class="card-body">
                    <div class="tips-list">
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Review content for errors</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Add cover image</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Set proper category</span>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Write compelling description</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Grid -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header">
                    <h5><i class="fas fa-file-alt me-2"></i>Your Draft Books</h5>
                    <div class="header-actions">
                        <span class="badge bg-warning">{{ $books->total() }} Drafts</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="drafts-table-container">
                        <table class="drafts-table">
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Created</th>
                                    <th>Stats</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($books as $book)
                                <tr class="draft-row">
                                    <td class="cover-cell">
                                        @if($book->cover_image)
                                            <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="table-cover">
                                        @else
                                            <div class="table-placeholder-cover">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="title-cell">
                                        <div class="title-content">
                                            <h6>{{ $book->title }}</h6>
                                            <span class="badge bg-warning">Draft</span>
                                        </div>
                                        <p class="table-description">{{ Str::limit($book->description ?? 'No description', 80) }}</p>
                                    </td>
                                    <td class="category-cell">
                                        <span class="category-tag">
                                            <i class="fas fa-folder"></i>
                                            {{ $book->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="date-cell">
                                        <div class="date-info">
                                            <i class="fas fa-calendar"></i>
                                            <span>{{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="stats-cell">
                                        <div class="mini-stats">
                                            <div class="mini-stat">
                                                <i class="fas fa-eye"></i>
                                                <span>{{ number_format($book->views ?? 0) }}</span>
                                            </div>
                                            <div class="mini-stat">
                                                <i class="fas fa-download"></i>
                                                <span>{{ number_format($book->downloads ?? 0) }}</span>
                                            </div>
                                            <div class="mini-stat">
                                                <i class="fas fa-file-alt"></i>
                                                <span>{{ $book->pages ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="actions-cell">
                                        <div class="table-actions">
                                            <button type="button" class="table-btn view" title="View" onclick="openViewModal({{ $book->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="table-btn edit" title="Edit" onclick="openEditModal({{ $book->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('author.books.publish', $book->id) }}" method="POST" class="publish-form">
                                                @csrf
                                                <button type="submit" class="table-btn publish" title="Publish">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="table-btn delete" title="Delete" onclick="openDeleteModal({{ $book->id }}, '{{ $book->title }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h5>No Draft Books</h5>
                                <p>You don't have any draft books. All your books are published!</p>
                                <div class="empty-actions">
                                    <a href="{{ route('author.books.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create New Book
                                    </a>
                                    <a href="{{ route('author.books.published') }}" class="btn btn-outline-success">
                                        <i class="fas fa-check-circle me-2"></i>View Published Books
                                    </a>
                                </div>
                            </div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Book Modal -->
    <div class="modal fade" id="viewBookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Book Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div id="modalBookCover" class="modal-book-cover">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <span id="modalBookBadge" class="badge bg-warning mt-2">Draft</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 id="modalBookTitle" class="mb-3">Book Title</h4>
                            <p id="modalBookDescription" class="text-muted mb-3">Book description will appear here...</p>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>ISBN:</strong>
                                    <p id="modalBookIsbn" class="text-muted">-</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Category:</strong>
                                    <p id="modalBookCategory" class="text-muted">-</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Pages:</strong>
                                    <p id="modalBookPages" class="text-muted">-</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Language:</strong>
                                    <p id="modalBookLanguage" class="text-muted">-</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Created:</strong>
                                    <p id="modalBookCreated" class="text-muted">-</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Status:</strong>
                                    <p id="modalBookStatus" class="text-muted">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div class="d-flex gap-4">
                                    <div class="text-center">
                                        <i class="fas fa-eye text-primary"></i>
                                        <div id="modalBookViews" class="fw-bold">0</div>
                                        <small class="text-muted">Views</small>
                                    </div>
                                    <div class="text-center">
                                        <i class="fas fa-download text-success"></i>
                                        <div id="modalBookDownloads" class="fw-bold">0</div>
                                        <small class="text-muted">Downloads</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editFromModal()">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    @if($book->file_path)
                                    <a id="modalDownloadLink" href="#" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="fas fa-trash me-2"></i>Delete Book Permanently
                    </button>
                </div>
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
    
    <!-- Enhanced Pagination -->
    @if($books->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="pagination-section">
                <!-- Pagination Info -->
                <div class="pagination-info">
                    <span class="info-text">
                        Showing {{ $books->firstItem() }} to {{ $books->lastItem() }} of 
                        <span class="total-count">{{ $books->total() }}</span> draft books
                    </span>
                    
                    <!-- Per Page Selector -->
                    <div class="per-page-selector">
                        <label for="perPage" class="per-page-label">Show:</label>
                        <select id="perPage" class="per-page-select" onchange="changePerPage()">
                            <option value="5" {{ request()->get('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request()->get('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request()->get('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request()->get('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request()->get('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="per-page-text">per page</span>
                    </div>
                </div>
                
                <!-- Pagination Links -->
                <div class="pagination-wrapper">
                    @if($books->onFirstPage())
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-left"></i>
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}" class="pagination-link">
                            <i class="fas fa-chevron-left"></i>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif
                    
                    <!-- Page Numbers -->
                    {{ $books->links() }}
                    
                    @if($books->onLastPage())
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @else
                        <a href="{{ $books->nextPageUrl() }}" class="pagination-link">
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @endif
                </div>
                
                <!-- Quick Navigation -->
                <div class="quick-nav">
                    <span class="quick-nav-label">Go to page:</span>
                    <input type="number" 
                           id="pageInput" 
                           class="page-input" 
                           min="1" 
                           max="{{ $books->lastPage() }}" 
                           value="{{ $books->currentPage() }}"
                           onkeypress="handlePageInput(event)">
                    <button onclick="goToPage()" class="go-btn">Go</button>
                    <span class="total-pages">of {{ $books->lastPage() }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@include('author.books.partials.form-scripts')

<script>
// Additional functions for drafts-specific functionality
let currentBookData = null;
let currentDeleteBookId = null;

function openDeleteModal(bookId, bookTitle) {
    currentDeleteBookId = bookId;
    document.getElementById('deleteBookTitle').textContent = bookTitle;
    document.getElementById('deleteConfirmText').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function confirmDelete(bookId) {
    // Legacy function for backward compatibility
    openDeleteModal(bookId, 'this book');
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
            if (currentDeleteBookId && deleteInput.value === 'DELETE') {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/author/books/${currentDeleteBookId}`;
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

// Modal Functions
function openViewModal(bookId) {
    fetch(`/author/books/${bookId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentBookData = data.book;
                populateModal(data.book);
                const modal = new bootstrap.Modal(document.getElementById('viewBookModal'));
                modal.show();
            } else {
                window.bookFormHandler.showAlert('error', 'Error loading book details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.bookFormHandler.showAlert('error', 'Error loading book details');
        });
}

function populateModal(book) {
    // Update modal content with book data
    document.getElementById('modalBookTitle').textContent = book.title || 'Untitled';
    document.getElementById('modalBookDescription').textContent = book.description || 'No description available';
    document.getElementById('modalBookIsbn').textContent = book.isbn || 'Not specified';
    document.getElementById('modalBookCategory').textContent = book.category?.name || 'Uncategorized';
    document.getElementById('modalBookPages').textContent = book.pages || 'Not specified';
    document.getElementById('modalBookLanguage').textContent = book.language || 'Not specified';
    document.getElementById('modalBookCreated').textContent = book.created_at ? new Date(book.created_at).toLocaleDateString() : 'Unknown';
    document.getElementById('modalBookStatus').textContent = book.status === 1 ? 'Published' : 'Draft';
    document.getElementById('modalBookViews').textContent = book.views || 0;
    document.getElementById('modalBookDownloads').textContent = book.downloads || 0;
    
    // Update cover image
    const coverDiv = document.getElementById('modalBookCover');
    if (book.cover_image) {
        const imageUrl = book.cover_image.startsWith('http') ? book.cover_image : `/storage/${book.cover_image}`;
        coverDiv.innerHTML = `<img src="${imageUrl}" alt="${book.title}" class="img-fluid rounded">`;
    } else {
        coverDiv.innerHTML = '<i class="fas fa-file-alt fa-3x text-muted"></i>';
    }
    
    // Update badge
    const badge = document.getElementById('modalBookBadge');
    badge.className = book.status === 1 ? 'badge bg-success mt-2' : 'badge bg-warning mt-2';
    badge.textContent = book.status === 1 ? 'Published' : 'Draft';
    
    // Update download link
    const downloadLink = document.getElementById('modalDownloadLink');
    if (book.file_path) {
        downloadLink.href = `/storage/${book.file_path}`;
        downloadLink.style.display = 'inline-block';
    } else {
        downloadLink.style.display = 'none';
    }
}

function editFromModal() {
    if (currentBookData) {
        // Close view modal and open edit modal
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewBookModal'));
        viewModal.hide();
        
        // Open edit modal with current book data
        openEditModal(currentBookData.id);
    }
}

// Enhanced Pagination Functions
function changePerPage() {
    const perPage = document.getElementById('perPage').value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

function handlePageInput(event) {
    if (event.key === 'Enter') {
        goToPage();
    }
}

function goToPage() {
    const pageInput = document.getElementById('pageInput');
    const page = parseInt(pageInput.value);
    const maxPage = parseInt(pageInput.getAttribute('max'));
    
    if (page >= 1 && page <= maxPage) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('page', page);
        window.location.href = currentUrl.toString();
    } else {
        alert(`Please enter a page number between 1 and ${maxPage}`);
        pageInput.value = '{{ $books->currentPage() }}';
    }
}

// Auto-refresh pagination info
function updatePaginationInfo() {
    // This can be called after AJAX operations to update the display
    const info = document.querySelector('.info-text');
    if (info) {
        // You can update this dynamically if needed
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + Left Arrow for previous page
    if ((event.ctrlKey || event.metaKey) && event.key === 'ArrowLeft') {
        event.preventDefault();
        const prevLink = document.querySelector('.pagination-wrapper a[href*="page="]');
        if (prevLink) prevLink.click();
    }
    
    // Ctrl/Cmd + Right Arrow for next page
    if ((event.ctrlKey || event.metaKey) && event.key === 'ArrowRight') {
        event.preventDefault();
        const nextLink = document.querySelector('.pagination-wrapper a[href*="page="]:last-child');
        if (nextLink) nextLink.click();
    }
});
</script>

<style>
.welcome-card {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
}

.welcome-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.welcome-icon {
    font-size: 3rem;
    opacity: 0.8;
}

.welcome-text h4 {
    margin: 0;
    font-weight: 600;
}

.welcome-text p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.modern-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    overflow: hidden;
}

.modern-card .card-header {
    background: #f8f9fa;
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modern-card .card-body {
    padding: 1.5rem;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.modern-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
    text-align: center;
}

.modern-action-btn.primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.modern-action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,123,255,0.4);
}

.modern-action-btn.secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    color: white;
}

.modern-action-btn.secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108,117,125,0.4);
}

.modern-action-btn.success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    color: white;
}

.modern-action-btn.success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40,167,69,0.4);
}

.modern-action-btn.warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #212529;
}

.modern-action-btn.warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255,193,7,0.4);
}

.modern-action-btn.info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.modern-action-btn.info:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(23,162,184,0.4);
}

.modern-action-btn i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.modern-action-btn span {
    font-size: 0.875rem;
    font-weight: 500;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.modern-stats-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.modern-stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.modern-stats-card.warning { 
    border-left-color: #ffc107; 
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
}
.modern-stats-card.info { 
    border-left-color: #17a2b8; 
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
}
.modern-stats-card.secondary { 
    border-left-color: #6c757d; 
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}
.modern-stats-card.primary { 
    border-left-color: #007bff; 
    background: linear-gradient(135deg, #f8f9ff 0%, #e9ecef 100%);
}

.stats-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #6c757d;
}

.modern-stats-card.warning .stats-icon { color: #ffc107; }
.modern-stats-card.info .stats-icon { color: #17a2b8; }
.modern-stats-card.secondary .stats-icon { color: #6c757d; }
.modern-stats-card.primary .stats-icon { color: #007bff; }

.stats-info {
    flex: 1;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.stats-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    margin-top: 0.25rem;
}

.tips-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #28a745;
}

.tip-item i {
    color: #28a745;
    font-size: 1rem;
}

.drafts-table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.drafts-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.drafts-table thead {
    background: #f8f9fa;
}

.drafts-table th {
    padding: 0.75rem;
    text-align: left;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    font-size: 0.8rem;
}

.draft-row {
    border-bottom: 1px solid #f1f3f5;
    transition: background-color 0.2s ease;
}

.draft-row:hover {
    background-color: #f8f9fa;
}

.draft-row:last-child {
    border-bottom: none;
}

.drafts-table td {
    padding: 0.75rem;
    vertical-align: middle;
}

.cover-cell {
    width: 60px;
    text-align: center;
}

.table-cover {
    width: 40px;
    height: 50px;
    border-radius: 4px;
    object-fit: cover;
    border: 1px solid #e9ecef;
}

.table-placeholder-cover {
    width: 40px;
    height: 50px;
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    border-radius: 4px;
    font-size: 0.8rem;
}

.title-cell {
    min-width: 200px;
}

.title-content {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.title-content h6 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.85rem;
    line-height: 1.2;
    flex: 1;
}

.table-description {
    color: #6c757d;
    line-height: 1.3;
    margin: 0;
    font-size: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.category-cell {
    width: 120px;
}

.category-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: #e9ecef;
    border-radius: 12px;
    font-size: 0.7rem;
    color: #495057;
}

.category-tag i {
    color: #ffc107;
    font-size: 0.6rem;
}

.date-cell {
    width: 100px;
}

.date-info {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #6c757d;
}

.date-info i {
    color: #ffc107;
    font-size: 0.6rem;
}

.stats-cell {
    width: 120px;
}

.mini-stats {
    display: flex;
    gap: 0.5rem;
}

.mini-stat {
    display: flex;
    align-items: center;
    gap: 0.125rem;
    font-size: 0.7rem;
    color: #6c757d;
}

.mini-stat i {
    color: #6c757d;
    font-size: 0.6rem;
}

.actions-cell {
    width: 180px;
}

.table-actions {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
}

.table-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 28px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.7rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.table-btn.view {
    background: #007bff;
    color: white;
}

.table-btn.view:hover {
    background: #0056b3;
}

.table-btn.edit {
    background: #ffc107;
    color: #212529;
}

.table-btn.edit:hover {
    background: #e0a800;
}

.table-btn.publish {
    background: #28a745;
    color: white;
}

.table-btn.publish:hover {
    background: #1e7e34;
}

.table-btn.delete {
    background: #dc3545;
    color: white;
}

.table-btn.delete:hover {
    background: #c82333;
}

.publish-form {
    display: contents;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: #ffc107;
    margin-bottom: 1.5rem;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 2rem;
}

.empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination-section {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.pagination-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f1f3f5;
}

.info-text {
    color: #6c757d;
    font-size: 0.9rem;
}

.total-count {
    font-weight: 600;
    color: #2c3e50;
}

.per-page-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.per-page-label {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

.per-page-select {
    padding: 0.375rem 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background: white;
    color: #495057;
    font-size: 0.85rem;
    cursor: pointer;
    transition: border-color 0.2s ease;
}

.per-page-select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.per-page-text {
    color: #6c757d;
    font-size: 0.85rem;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin: 1rem 0;
}

.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    text-decoration: none;
    color: #495057;
    background: white;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.pagination-link:hover {
    background: #007bff;
    border-color: #007bff;
    color: white;
    transform: translateY(-1px);
}

.pagination-link.disabled {
    background: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination-link.disabled:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
    transform: none;
}

.quick-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f3f5;
}

.quick-nav-label {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

.page-input {
    width: 60px;
    padding: 0.375rem 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    text-align: center;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.page-input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.go-btn {
    padding: 0.375rem 0.75rem;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.go-btn:hover {
    background: #0056b3;
}

.total-pages {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Modal Styles */
.modal-book-cover {
    width: 200px;
    height: 250px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    margin: 0 auto;
}

.modal-book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.modal-book-cover i {
    font-size: 3rem;
    color: #6c757d;
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.modal-body .row {
    margin-bottom: 1rem;
}

.modal-body .text-center {
    margin-bottom: 1rem;
}

.modal-body h4 {
    color: #2c3e50;
    font-weight: 600;
}

.modal-body strong {
    color: #495057;
    font-weight: 600;
}

.modal-body p {
    margin-bottom: 0.5rem;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    background: #f8f9fa;
}

/* Edit Modal Styles */
.modal-header.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
}

.modal-header.bg-warning .btn-close {
    filter: invert(1);
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255,193,7,0.25);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    border: none;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
}

/* Delete Modal Styles */
.modal-header.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.modal-header.bg-danger .btn-close {
    filter: invert(1);
}

.modal-body .alert-danger {
    border-left: 4px solid #dc3545;
}

.modal-body .fa-trash {
    color: #dc3545;
}

.modal-body .fa-exclamation-triangle {
    color: #dc3545;
}

#deleteConfirmText:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,0.25);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
}

.btn-danger:hover:not(:disabled) {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
}

.btn-danger:disabled {
    background: #6c757d;
    opacity: 0.6;
    cursor: not-allowed;
}

/* Create Modal Styles */
.modal-header.bg-primary .btn-close-white {
    filter: invert(1);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

@media (max-width: 768px) {
    .welcome-content {
        flex-direction: column;
        text-align: center;
    }
    
    .action-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .drafts-table-container {
        overflow-x: auto;
    }
    
    .drafts-table {
        min-width: 700px;
    }
    
    .drafts-table th {
        padding: 0.5rem;
        font-size: 0.75rem;
    }
    
    .drafts-table td {
        padding: 0.5rem;
    }
    
    .title-cell {
        min-width: 150px;
    }
    
    .category-cell {
        width: 100px;
    }
    
    .date-cell {
        width: 80px;
    }
    
    .stats-cell {
        width: 100px;
    }
    
    .actions-cell {
        width: 140px;
    }
    
    .table-btn {
        width: 28px;
        height: 24px;
        font-size: 0.6rem;
    }
    
    .table-cover {
        width: 30px;
        height: 40px;
    }
    
    .table-placeholder-cover {
        width: 30px;
        height: 40px;
        font-size: 0.7rem;
    }
    
    .pagination-section {
        padding: 1rem;
    }
    
    .pagination-info {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .info-text {
        text-align: center;
        font-size: 0.8rem;
    }
    
    .per-page-selector {
        justify-content: center;
    }
    
    .pagination-wrapper {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .pagination-link {
        min-width: 36px;
        height: 36px;
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .quick-nav {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .empty-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .drafts-table {
        min-width: 600px;
    }
    
    .drafts-table th {
        padding: 0.375rem;
        font-size: 0.7rem;
    }
    
    .drafts-table td {
        padding: 0.375rem;
    }
    
    .title-content h6 {
        font-size: 0.75rem;
    }
    
    .table-description {
        font-size: 0.65rem;
        -webkit-line-clamp: 1;
    }
    
    .category-tag {
        font-size: 0.6rem;
        padding: 0.125rem 0.375rem;
    }
    
    .date-info {
        font-size: 0.65rem;
    }
    
    .mini-stat {
        font-size: 0.6rem;
    }
    
    .table-btn {
        width: 24px;
        height: 20px;
        font-size: 0.55rem;
    }
    
    .pagination-section {
        padding: 0.75rem;
    }
    
    .pagination-link {
        min-width: 32px;
        height: 32px;
        padding: 0.25rem 0.375rem;
        font-size: 0.75rem;
    }
    
    .page-input {
        width: 50px;
        font-size: 0.8rem;
    }
    
    .go-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 360px) {
    .drafts-table {
        min-width: 500px;
    }
    
    .title-cell {
        min-width: 120px;
    }
    
    .category-cell {
        width: 80px;
    }
    
    .date-cell {
        width: 70px;
    }
    
    .stats-cell {
        width: 80px;
    }
    
    .actions-cell {
        width: 120px;
    }
    
    .pagination-link {
        min-width: 28px;
        height: 28px;
        padding: 0.25rem 0.375rem;
        font-size: 0.7rem;
    }
}
</style>
@endsection
