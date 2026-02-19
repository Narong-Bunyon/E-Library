@extends('layouts.admin')

@section('title', 'Books Management - E-Library')

@section('page-title', 'Books Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-book me-2"></i>
                Books Management
            </h4>
            <p class="text-muted mb-0">Manage your library books and publications</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBookModal">
                <i class="fas fa-plus me-1"></i>
                Add New Book
            </button>
            <button class="btn btn-outline-danger" onclick="deleteSelectedBooks()" id="bulkDeleteBtn" style="display: none;">
                <i class="fas fa-trash me-1"></i>
                Delete Selected
            </button>
            <button class="btn btn-outline-secondary" onclick="exportBooks()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $books->total() }}</h3>
                            <p class="stat-label">Total Books</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $publishedBooks }}</h3>
                            <p class="stat-label">Published</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $draftBooks }}</h3>
                            <p class="stat-label">Draft</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $totalDownloads }}</h3>
                            <p class="stat-label">Downloads</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search Books</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by title, author...">
                        <button class="btn btn-outline-secondary" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select class="form-select" id="sortBy">
                        <option value="title">Title</option>
                        <option value="created_at">Date Created</option>
                        <option value="downloads">Downloads</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-book me-2"></i>
                All Books
                <span class="badge bg-primary ms-2">{{ $books->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <input type="checkbox" class="form-check-input me-2" id="selectAll" onchange="toggleSelectAll()">
                <small class="text-muted">Select All</small>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAllHeader" onchange="toggleSelectAllHeader()">
                            </th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
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
                                <input type="checkbox" class="form-check-input book-checkbox" value="{{ $book->id }}" onchange="updateBulkDeleteButton()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover me-3">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $book->title }}</div>
                                        <small class="text-muted">{{ $book->pages ?? 0 }} pages</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $book->author->name ?? 'Unknown' }}</td>
                            <td><span class="badge bg-info">{{ $book->categories->first()->name ?? 'N/A' }}</span></td>
                            <td><span class="badge bg-{{ $book->status == 1 ? 'success' : 'warning' }}">{{ $book->status == 1 ? 'Published' : 'Draft' }}</span></td>
                            <td>{{ $book->totalDownloads() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    {{ number_format($book->averageRating(), 1) }}
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $book->create_at?->diffForHumans() ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editBook({{ $book->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="viewBook({{ $book->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteBook({{ $book->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No books found. Create your first book!</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBookModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Create First Book
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($books->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $books->firstItem() }} to {{ $books->lastItem() }} of {{ $books->total() }} books
                    </div>
                    <div>
                        {{ $books->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Book Modal -->
<div class="modal fade" id="createBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.books.store') }}" method="POST" id="createBookForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" class="form-control" name="title" required placeholder="Enter book title">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Author *</label>
                            <select class="form-select" name="author_id" required>
                                <option value="">Select Author</option>
                                @foreach ($authors ?? [] as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea class="form-control" name="description" rows="3" required placeholder="Enter book description"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Category *</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select class="form-select" name="status" required>
                                <option value="0">Draft</option>
                                <option value="1">Published</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pages *</label>
                            <input type="number" class="form-control" name="pages" min="1" required placeholder="Number of pages">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Language *</label>
                            <input type="text" class="form-control" name="language" value="English" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn" placeholder="ISBN number">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Published Date</label>
                            <input type="date" class="form-control" name="published_date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" id="editBookForm" onsubmit="updateBook(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editBookId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" class="form-control" name="title" id="editBookTitle" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Author *</label>
                            <select class="form-select" name="author_id" id="editBookAuthor" required>
                                <option value="">Select Author</option>
                                @foreach ($authors ?? [] as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea class="form-control" name="description" id="editBookDescription" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Category *</label>
                            <select class="form-select" name="category_id" id="editBookCategory" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select class="form-select" name="status" id="editBookStatus" required>
                                <option value="0">Draft</option>
                                <option value="1">Published</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pages *</label>
                            <input type="number" class="form-control" name="pages" id="editBookPages" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Language *</label>
                            <input type="text" class="form-control" name="language" id="editBookLanguage" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn" id="editBookIsbn">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Published Date</label>
                            <input type="date" class="form-control" name="published_date" id="editBookPublishedDate">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Book Modal -->
<div class="modal fade" id="viewBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookDetails">
                <!-- Book details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="editBookFromView()">Edit Book</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.stat-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card.success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.stat-card.warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
}

.stat-card.info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 15px;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
    margin: 0;
}

.book-cover {
    width: 40px;
    height: 50px;
    border-radius: 5px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-title {
    margin: 0;
}

.btn-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
}

.btn-close:hover {
    opacity: 0.7;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const title = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
        const author = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();
        
        if (title?.includes(search) || author?.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', applyFilters);
document.getElementById('categoryFilter').addEventListener('change', applyFilters);

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const category = document.getElementById('categoryFilter').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let show = true;
        
        if (status && !row.textContent.includes(status === 'published' ? 'Published' : 'Draft')) {
            show = false;
        }
        
        if (category) {
            const categoryCell = row.querySelector('td:nth-child(4)')?.textContent;
            if (!categoryCell?.includes(category)) {
                show = false;
            }
        }
        
        row.style.display = show ? '' : 'none';
    });
}

// Sort functionality
document.getElementById('sortBy').addEventListener('change', function() {
    const sortBy = this.value;
    const url = new URL(window.location);
    url.searchParams.set('sort', sortBy);
    window.location.href = url.toString();
});

// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.book-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function toggleSelectAllHeader() {
    const selectAll = document.getElementById('selectAllHeader');
    const checkboxes = document.querySelectorAll('.book-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

// Bulk delete functionality
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.book-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (checkboxes.length > 0) {
        bulkDeleteBtn.style.display = 'inline-block';
    } else {
        bulkDeleteBtn.style.display = 'none';
    }
}

function deleteSelectedBooks() {
    const checkboxes = document.querySelectorAll('.book-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Please select at least one book to delete');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${ids.length} book${ids.length > 1 ? 's' : ''}? This action cannot be undone.`)) {
        fetch('/admin/books/bulk-delete', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting books: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting books: ' + error.message);
        });
    }
}

// CRUD operations
function editBook(id) {
    fetch(`/admin/books/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editBookId').value = data.id;
            document.getElementById('editBookTitle').value = data.title;
            document.getElementById('editBookAuthor').value = data.author_id;
            document.getElementById('editBookDescription').value = data.description;
            document.getElementById('editBookCategory').value = data.category_id;
            document.getElementById('editBookStatus').value = data.status;
            document.getElementById('editBookPages').value = data.pages;
            document.getElementById('editBookLanguage').value = data.language;
            document.getElementById('editBookIsbn').value = data.isbn;
            document.getElementById('editBookPublishedDate').value = data.published_date;
            
            const form = document.getElementById('editBookForm');
            form.action = `/admin/books/${id}`;
            
            new bootstrap.Modal(document.getElementById('editBookModal')).show();
        });
}

function updateBook(event) {
    event.preventDefault();
    
    const form = document.getElementById('editBookForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editBookModal'));
            modal.hide();
            location.reload();
        } else if (data && data.errors) {
            console.error('Validation errors:', data.errors);
            alert('Please fix the errors in the form.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the book.');
    });
}

function viewBook(id) {
    fetch(`/admin/books/${id}`)
        .then(response => response.json())
        .then(data => {
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>ID:</strong> ${data.id}<br>
                        <strong>Title:</strong> ${data.title}<br>
                        <strong>Author:</strong> ${data.author?.name || 'N/A'}<br>
                        <strong>Category:</strong> ${data.category?.name || 'N/A'}<br>
                        <strong>Status:</strong> <span class="badge bg-${data.status == 1 ? 'success' : 'warning'}">${data.status == 1 ? 'Published' : 'Draft'}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Pages:</strong> ${data.pages || 'N/A'}<br>
                        <strong>Language:</strong> ${data.language || 'N/A'}<br>
                        <strong>ISBN:</strong> ${data.isbn || 'N/A'}<br>
                        <strong>Published:</strong> ${data.published_date || 'N/A'}<br>
                        <strong>Created:</strong> ${data.create_at || 'N/A'}
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Description:</strong><br>
                    <p>${data.description || 'No description'}</p>
                </div>
            `;
            document.getElementById('bookDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewBookModal')).show();
        });
}

function editBookFromView() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewBookModal'));
    modal.hide();
    
    const id = document.getElementById('editBookId').value;
    editBook(id);
}

function deleteBook(id) {
    if (confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
        fetch(`/admin/books/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting book: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting book: ' + error.message);
        });
    }
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchInput').dispatchEvent(new Event('input'));
}

function exportBooks() {
    const url = new URL(window.location);
    url.searchParams.set('export', 'csv');
    window.location.href = url.toString();
}
</script>
@endpush
@endsection
