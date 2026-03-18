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
            <button class="btn btn-outline-secondary" onclick="exportBooks()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-gradient text-white rounded-3 p-3 me-3">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value mb-0">{{ $books->total() }}</h3>
                            <p class="stat-label text-muted mb-0">Total Books</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-gradient text-white rounded-3 p-3 me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value mb-0">{{ $publishedBooks }}</h3>
                            <p class="stat-label text-muted mb-0">Published</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-gradient text-white rounded-3 p-3 me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value mb-0">{{ $draftBooks }}</h3>
                            <p class="stat-label text-muted mb-0">Draft</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-gradient text-white rounded-3 p-3 me-3">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value mb-0">{{ $totalDownloads }}</h3>
                            <p class="stat-label text-muted mb-0">Downloads</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search Books</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Search by title, author...">
                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="1">Published</option>
                        <option value="0">Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sort By</label>
                    <select class="form-select" id="sortBy">
                        <option value="created_at">Date Created</option>
                        <option value="title">Title</option>
                        <option value="downloads">Downloads</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th style="width: 80px;">Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Downloads</th>
                            <th>Rating</th>
                            <th>Created</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    @include('admin.books._table')
                </table>
            </div>
            @include('admin.books._pagination')
        </div>
    </div>
</div>

<style>
/* Enhanced Book Table Styles */
.book-cover-container {
    width: 60px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.book-cover-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.book-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.book-cover-img:hover {
    transform: scale(1.05);
}

.book-cover-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.book-info {
    min-width: 200px;
}

.book-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e50;
    line-height: 1.3;
    margin-bottom: 0.25rem;
}

.book-title:hover {
    color: #007bff;
}

.author-name {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.category-badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 20px;
    font-weight: 500;
}

.status-badge {
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
    text-transform: uppercase;
    font-weight: 600;
    border-radius: 20px;
    letter-spacing: 0.5px;
}

.download-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.rating-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.stars {
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.rating-number {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.75rem;
}

.empty-state {
    padding: 3rem 2rem;
}

.btn-group .btn {
    padding: 0.35rem 0.6rem;
    font-size: 0.8rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

/* Table hover effects */
.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Modal enhancements */
.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .book-cover-container {
        width: 50px;
        height: 70px;
    }
    
    .book-title {
        font-size: 0.8rem;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 0.25rem;
    }
}

/* Loading spinner */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Alert enhancements */
.alert {
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
}

.alert-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}
</style>

<!-- Create Book Modal -->
<div class="modal fade" id="createBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createBookForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" class="form-control" id="isbn" name="isbn">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pages" class="form-label">Pages</label>
                                        <input type="number" class="form-control" id="pages" name="pages" min="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="book_file" class="form-label">Book File</label>
                                <input type="file" class="form-control" id="book_file" name="book_file" accept=".pdf,.epub,.mobi">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="author_id" class="form-label">Author *</label>
                                <select class="form-select" id="author_id" name="author_id" required>
                                    <option value="">Select Author</option>
                                    @if(isset($authors))
                                        @foreach ($authors as $author)
                                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @if(isset($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Book</button>
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
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Book
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBookForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_book_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description *</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="4" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_isbn" class="form-label">ISBN</label>
                                        <input type="text" class="form-control" id="edit_isbn" name="isbn">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_pages" class="form-label">Pages</label>
                                        <input type="number" class="form-control" id="edit_pages" name="pages" min="1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_language" class="form-label">Language</label>
                                        <input type="text" class="form-control" id="edit_language" name="language">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_published_date" class="form-label">Published Date</label>
                                        <input type="date" class="form-control" id="edit_published_date" name="published_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_cover_image" class="form-label">Cover Image</label>
                                <input type="file" class="form-control" id="edit_cover_image" name="cover_image" accept="image/*">
                                <img id="editCoverPreview" class="img-fluid mt-2 rounded" style="max-height: 200px; display: none;">
                            </div>
                            <div class="mb-3">
                                <label for="edit_file_path" class="form-label">Book File</label>
                                <input type="file" class="form-control" id="edit_file_path" name="file_path" accept=".pdf,.epub,.mobi">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_author_id" class="form-label">Author *</label>
                                <select class="form-select" id="edit_author_id" name="author_id" required>
                                    <option value="">Select Author</option>
                                    @if(isset($authors))
                                        @foreach ($authors as $author)
                                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_category_id" class="form-label">Category *</label>
                                <select class="form-select" id="edit_category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @if(isset($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="0">Draft</option>
                                    <option value="1">Published</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_access_level" class="form-label">Access Level</label>
                                <select class="form-select" id="edit_access_level" name="access_level">
                                    <option value="0">Public</option>
                                    <option value="1">Private</option>
                                    <option value="2">Premium</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_views" class="form-label">Views</label>
                                <input type="number" class="form-control" id="edit_views" name="views" min="0" readonly>
                            </div>
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

<!-- Review Book Modal -->
<div class="modal fade" id="reviewBookModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Book Review
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reviewBookContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Book Modal -->
<div class="modal fade" id="deleteBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All book data including cover image, file, and related records will be permanently deleted.
                </div>
                <div id="deleteBookInfo">
                    <!-- Book info will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteBook()">
                    <i class="fas fa-trash me-1"></i>
                    Delete Book
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced modal functions
function editBook(id) {
    console.log('Edit book ID:', id);
    
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('editBookModal'));
    const modalBody = document.querySelector('#editBookModal .modal-body');
    const originalContent = modalBody.innerHTML;
    
    modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3">Loading book data...</p></div>';
    modal.show();
    
    // Load book data via AJAX
    fetch('/admin/books/' + id + '/edit')
        .then(response => {
            console.log('Raw response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Book data loaded:', data);
            
            // Restore original content and populate form
            modalBody.innerHTML = originalContent;
            
            // Populate edit form with safe access
            document.getElementById('edit_book_id').value = data.id || '';
            document.getElementById('edit_title').value = data.title || '';
            document.getElementById('edit_description').value = data.description || '';
            document.getElementById('edit_isbn').value = data.isbn || '';
            document.getElementById('edit_pages').value = data.pages || '';
            document.getElementById('edit_language').value = data.language || '';
            document.getElementById('edit_published_date').value = data.published_date || '';
            document.getElementById('edit_status').value = data.status || 0;
            document.getElementById('edit_author_id').value = data.author_id || '';
            document.getElementById('edit_category_id').value = data.category_id || '';
            document.getElementById('edit_access_level').value = data.access_level || '';
            document.getElementById('edit_views').value = data.views || '';
            
            // Show cover image if exists
            const coverPreview = document.getElementById('editCoverPreview');
            if (data.cover_image) {
                const imagePath = data.cover_image.startsWith('http') ? data.cover_image : `/storage/${data.cover_image}`;
                coverPreview.src = imagePath;
                coverPreview.style.display = 'block';
            } else {
                coverPreview.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading book:', error);
            modalBody.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error loading book data. Please try again.</div>';
        });
}

function viewBook(id) {
    console.log('View book ID:', id);
    
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('reviewBookModal'));
    const reviewContent = document.getElementById('reviewBookContent');
    
    reviewContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3">Loading book details...</p></div>';
    modal.show();
    
    // Load book data via AJAX
    fetch('/admin/books/' + id)
        .then(response => {
            console.log('Raw response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Book data loaded:', data);
            
            // Create enhanced review content
            const content = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            ${data.cover_image ? 
                                `<img src="${data.cover_image.startsWith('http') ? data.cover_image : '/storage/' + data.cover_image}" class="img-fluid rounded shadow mb-3" alt="${data.title}" style="max-height: 400px; width: 100%; object-fit: cover;">` : 
                                `<div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 400px;">
                                    <i class="fas fa-book fa-4x text-muted"></i>
                                </div>`
                            }
                            <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
                                <span class="badge ${data.status == 1 ? 'bg-success' : 'bg-warning'}">
                                    <i class="fas ${data.status == 1 ? 'fa-check-circle' : 'fa-clock'} me-1"></i>
                                    ${data.status == 1 ? 'Published' : 'Draft'}
                                </span>
                                <span class="badge bg-primary">
                                    <i class="fas fa-file-alt me-1"></i>
                                    ${data.pages || 0} pages
                                </span>
                                <span class="badge bg-info">
                                    <i class="fas fa-eye me-1"></i>
                                    ${data.views || 0} views
                                </span>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-download me-1"></i>
                                    ${data.downloads || 0} downloads
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3">${data.title || 'Untitled'}</h4>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Basic Information</h6>
                                        <p class="mb-2">
                                            <strong>Author:</strong><br>
                                            <span class="text-muted">${data.author && data.author.name ? data.author.name : 'N/A'}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Category:</strong><br>
                                            <span class="text-muted">${data.categories && data.categories.length > 0 ? data.categories[0].name : 'N/A'}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>ISBN:</strong><br>
                                            <span class="text-muted">${data.isbn || 'N/A'}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Language:</strong><br>
                                            <span class="text-muted">${data.language || 'N/A'}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Publication Details</h6>
                                        <p class="mb-2">
                                            <strong>Published Date:</strong><br>
                                            <span class="text-muted">${data.published_date || 'N/A'}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Access Level:</strong><br>
                                            <span class="badge bg-info">${data.access_level || 'Public'}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Created:</strong><br>
                                            <span class="text-muted">${data.created_at ? new Date(data.created_at).toLocaleDateString() : 'N/A'}</span>
                                        </p>
                                        <p class="mb-0">
                                            <strong>Last Updated:</strong><br>
                                            <span class="text-muted">${data.updated_at ? new Date(data.updated_at).toLocaleDateString() : 'N/A'}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">Description</h6>
                                <p class="text-muted">${data.description || 'No description available'}</p>
                            </div>
                        </div>
                        
                        ${data.file_path ? 
                            `<div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">File Information</h6>
                                    <p class="mb-3">
                                        <strong>File Path:</strong><br>
                                        <code class="text-muted">${data.file_path}</code>
                                    </p>
                                    <a href="/storage/${data.file_path}" class="btn btn-primary btn-sm" download>
                                        <i class="fas fa-download me-1"></i>
                                        Download Book
                                    </a>
                                </div>
                            </div>` : ''
                        }
                    </div>
                </div>
            `;
            
            reviewContent.innerHTML = content;
        })
        .catch(error => {
            console.error('Error loading book:', error);
            reviewContent.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error loading book data. Please try again.</div>';
        });
}

function deleteBook(id) {
    console.log('Delete book ID:', id);
    
    // Load book data for confirmation
    fetch('/admin/books/' + id)
        .then(response => response.json())
        .then(data => {
            console.log('Book data for delete:', data);
            
            // Create simple confirmation content with only book name
            const bookInfo = `
                <div class="text-center">
                    <h5 class="mb-3">Are you sure you want to delete this book?</h5>
                    <h4 class="text-danger mb-0">${data.title || 'Untitled'}</h4>
                </div>
            `;
            
            document.getElementById('deleteBookInfo').innerHTML = bookInfo;
            
            // Store book ID for deletion
            window.deleteBookId = id;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('deleteBookModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading book:', error);
            alert('Error loading book data. Please try again.');
        });
}

function confirmDeleteBook() {
    const bookId = window.deleteBookId;
    if (!bookId) return;
    
    // Show loading state
    const deleteBtn = document.querySelector('#deleteBookModal .btn-danger');
    const originalText = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Deleting...';
    deleteBtn.disabled = true;
    
    // Send delete request
    fetch('/admin/books/' + bookId, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteBookModal'));
            modal.hide();
            
            // Show success message
            showToast('Book deleted successfully!', 'success');
            
            // Reload page after delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Delete failed');
        }
    })
    .catch(error => {
        console.error('Error deleting book:', error);
        showToast('Error deleting book: ' + error.message, 'error');
        
        // Reset button
        deleteBtn.innerHTML = originalText;
        deleteBtn.disabled = false;
    });
}

// Enhanced edit form submission
document.addEventListener('DOMContentLoaded', function() {
    // Edit book form handler
    const editForm = document.getElementById('editBookForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = editForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Updating...';
            submitBtn.disabled = true;
            
            const formData = new FormData(editForm);
            const bookId = document.getElementById('edit_book_id').value;
            
            fetch('/admin/books/' + bookId, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editBookModal'));
                    modal.hide();
                    
                    // Show success message
                    showToast('Book updated successfully!', 'success');
                    
                    // Reload page after delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            })
            .catch(error => {
                console.error('Error updating book:', error);
                showToast('Error updating book: ' + error.message, 'error');
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Create book form handler
    const createForm = document.getElementById('createBookForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = createForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Creating...';
            submitBtn.disabled = true;
            
            const formData = new FormData(createForm);
            
            fetch('/admin/books', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createBookModal'));
                    modal.hide();
                    
                    // Show success message
                    showToast('Book created successfully!', 'success');
                    
                    // Reload page after delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Creation failed');
                }
            })
            .catch(error => {
                console.error('Error creating book:', error);
                showToast('Error creating book: ' + error.message, 'error');
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Toast notification helper
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.innerHTML = toastHtml;
    document.body.appendChild(toastContainer);
    
    const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
    toast.show();
    
    // Remove toast container after hiding
    toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', () => {
        toastContainer.remove();
    });
}

// Simple pagination that works
function loadPage(url) {
    console.log('Loading page:', url);
    
    // Show loading
    const tableBody = document.querySelector('table tbody');
    tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><br><small class="text-muted mt-2">Loading books...</small></td></tr>';
    
    // Use fetch API (more reliable than jQuery for this)
    fetch(url)
        .then(response => response.text())
        .then(html => {
            console.log('Page loaded successfully');
            
            // Create a temporary element to parse the HTML
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            // Find the new table content
            const newTableBody = temp.querySelector('table tbody');
            if (newTableBody) {
                tableBody.innerHTML = newTableBody.innerHTML;
            }
            
            // Find and update pagination
            const newPagination = temp.querySelector('.d-flex.justify-content-between.align-items-center.mt-4');
            if (newPagination) {
                const currentPagination = document.querySelector('.d-flex.justify-content-between.align-items-center.mt-4');
                if (currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                    // Re-attach event listeners
                    attachPaginationListeners();
                }
            }
        })
        .catch(error => {
            console.error('Error loading page:', error);
            tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error loading data. Please try again.</td></tr>';
        });
}

// Attach event listeners to pagination buttons
function attachPaginationListeners() {
    console.log('Attaching pagination listeners');
    
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (prevBtn) {
        prevBtn.onclick = function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            if (url) {
                loadPage(url);
            }
        };
    }
    
    if (nextBtn) {
        nextBtn.onclick = function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            if (url) {
                loadPage(url);
            }
        };
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing pagination');
    attachPaginationListeners();
});

$(document).ready(function() {
    console.log('jQuery ready');
    
    // Edit book form submission
    $('#editBookForm').submit(function(e) {
        e.preventDefault();
        const bookId = $('#edit_book_id').val();
        const formData = new FormData(this);
        
        console.log('Submitting edit form for book ID:', bookId);
        
        $.ajax({
            url: '/admin/books/' + bookId,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                console.log('Book updated successfully:', response);
                $('#editBookModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                console.error('Error updating book:', xhr);
                alert('Error updating book: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });
    
    // Delete confirmation button
    $('#confirmDeleteBtn').click(function() {
        if (window.deleteBookId) {
            console.log('Confirming delete for book ID:', window.deleteBookId);
            
            $.ajax({
                url: '/admin/books/' + window.deleteBookId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Book deleted successfully:', response);
                    $('#deleteBookModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    console.error('Error deleting book:', xhr);
                    alert('Error deleting book: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        }
    });
    
    // Cover image preview for edit form
    $('#edit_cover_image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#editCoverPreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Create book form submission
    $('#createBookForm').submit(function(e) {
        e.preventDefault();
        console.log('Create form submitted');
        alert('Create book form submitted!');
    });
});

function toggleSelectAll() {
    const selectAll = $('#selectAll').prop('checked');
    $('.book-checkbox').prop('checked', selectAll);
    updateBulkDeleteButton();
}

function updateBulkDeleteButton() {
    const checkedBoxes = $('.book-checkbox:checked').length;
    $('#bulkDeleteBtn').toggle(checkedBoxes > 0);
}

function exportBooks() {
    window.location.href = '/admin/books/export';
}
</script>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBookForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_book_id" name="book_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_id" class="form-label">ID</label>
                        <input type="text" class="form-control" id="edit_id" name="id" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_color" class="form-label">Color</label>
                        <input type="color" class="form-control" id="edit_color" name="color" value="#007bff">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Review Book Modal -->
<div class="modal fade" id="reviewBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reviewBookContent">
                <!-- Book details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete this book?</h5>
                    <p class="text-muted">This action cannot be undone. All data associated with this book will be permanently removed.</p>
                </div>
                <div id="deleteBookInfo" class="alert alert-warning">
                    <!-- Book info will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>
                    Delete Book
                </button>
            </div>

/**
 * Perform AJAX search with all filters
 * Filters books dynamically without page reload
 */
function performSearch() {
    // Get current filter values
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const category = document.getElementById('categoryFilter').value;
    const sort = document.getElementById('sortBy').value;
    
    // Show loading state
    showTableLoading();
    
    // Build AJAX URL with parameters
    let url = '/admin/books?';
    const params = [];
    
    // Add parameters only if they have values
    if (search) params.push(`search=${encodeURIComponent(search)}`);
    if (status) params.push(`status=${status}`);
    if (category) params.push(`category_id=${category}`);
    if (sort) params.push(`sort=${sort}`);
    
    url += params.join('&');
    
    // Perform AJAX request
    fetch(url)
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response and update table
            updateTableContent(html);
        })
        .catch(error => {
            console.error('Search error:', error);
            showToast('Error searching books', 'error');
        });
}

/**
 * Clear all search filters
 * Resets search input and all filter dropdowns
 */
function clearSearch() {
    document.getElementById('searchInput').value = '';
    window.location.href = '/admin/books';
}

/**
 * Initialize event listeners when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Search input - trigger on Enter key and real-time search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        // Real-time search with debounce
        let searchTimeout;
        searchInput.addEventListener('keyup', function(event) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 500); // Simple search functionality
        });
        
        // Also trigger on Enter
        searchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const searchValue = searchInput.value.trim();
                if (searchValue) {
                    window.location.href = '/admin/books?search=' + encodeURIComponent(searchValue);
                } else {
                    window.location.href = '/admin/books';
                }
            }
        });
    }
    
    // Status filter - trigger on change
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', performSearch);
    }
    
    // Category filter - trigger on change
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', performSearch);
    }
    
    // Sort filter - trigger on change
    const sortBy = document.getElementById('sortBy');
    if (sortBy) {
        sortBy.addEventListener('change', performSearch);
    }
    
});

/**
 * Show loading state in table
 */
function showTableLoading() {
    const tableBody = document.querySelector('table tbody');
    if (tableBody) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Searching books...</p>
                </td>
            </tr>
        `;
    }
}

/**
 * Update table content with new HTML
 */
function updateTableContent(html) {
    try {
        const data = JSON.parse(html);
        
        if (data.success) {
            // Update table body
            const currentTableBody = document.querySelector('table tbody');
            if (currentTableBody && data.table) {
                currentTableBody.innerHTML = data.table;
            }
            
            // Update pagination
            const currentPagination = document.querySelector('.d-flex.justify-content-between.align-items-center.mt-4');
            if (currentPagination && data.pagination) {
                currentPagination.innerHTML = data.pagination;
                
                // Re-attach pagination event listeners
                attachPaginationListeners();
            }
            
            // Show success message
            showToast('Books filtered successfully', 'success');
        } else {
            showToast('Error filtering books', 'error');
        }
    } catch (error) {
        console.error('Error parsing response:', error);
        showToast('Error processing response', 'error');
    }
}

/**
 * Re-attach pagination event listeners after content update
 */
function attachPaginationListeners() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (prevBtn) {
        prevBtn.onclick = function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            if (url) loadPage(url);
        };
    }
    
    if (nextBtn) {
        nextBtn.onclick = function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            if (url) loadPage(url);
        };
    }
}

/**
 * Load page with AJAX (for pagination)
 */
function loadPage(url) {
    showTableLoading();
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            updateTableContent(html);
        })
        .catch(error => {
            console.error('Pagination error:', error);
            showToast('Error loading page', 'error');
        });
}
</script>
@endsection
