@extends('layouts.admin')

@section('title', 'Authors Management - E-Library')

@section('page-title', 'Authors Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-user-pen me-2"></i>
                Authors Management
            </h4>
            <p class="text-muted mb-0">Manage authors and their published books</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAuthorModal">
                <i class="fas fa-plus me-1"></i>
                Add New Author
            </button>
            <button class="btn btn-outline-secondary" onclick="exportAuthors()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-user-pen"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\User::where('role', 'author')->count() }}</div>
                    <div class="stats-label">Total Authors</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\Book::count() }}</div>
                    <div class="stats-label">Total Books</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\User::where('role', 'author')->where('status', 'active')->count() }}</div>
                    <div class="stats-label">Active Authors</div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-number">{{ App\Models\Review::count() }}</div>
                    <div class="stats-label">Total Reviews</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search authors...">
                        <button class="btn btn-outline-secondary" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter" onchange="filterTable()">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Books Range</label>
                    <select class="form-select" id="booksFilter" onchange="filterTable()">
                        <option value="">All Authors</option>
                        <option value="0">No Books</option>
                        <option value="1-5">1-5 Books</option>
                        <option value="6-10">6-10 Books</option>
                        <option value="10+">10+ Books</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select class="form-select" id="sortBy" onchange="sortTable()">
                        <option value="name">Name</option>
                        <option value="books">Books Count</option>
                        <option value="created_at">Join Date</option>
                        <option value="status">Status</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Authors Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="authorsTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Author</th>
                            <th>Email</th>
                            <th>Bio</th>
                            <th>Books</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($authors as $author)
                        <tr data-status="{{ $author->status ?? 'active' }}" data-name="{{ strtolower($author->name) }}" data-email="{{ strtolower($author->email) }}" data-books="{{ $author->books_count ?? 0 }}">
                            <td>
                                <input type="checkbox" class="form-check-input author-checkbox" value="{{ $author->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="author-avatar me-3">
                                        {{ strtoupper(substr($author->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $author->name }}</div>
                                        <small class="text-muted">ID: #{{ $author->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $author->email }}</td>
                            <td>
                                <div class="bio-info">
                                    <small class="text-muted">{{ Str::limit($author->author_bio ?? 'No bio', 50) }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="books-info">
                                    <div class="fw-semibold">{{ $author->books_count ?? 0 }}</div>
                                    <small class="text-muted">Published</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge status-badge {{ $author->status ?? 'active' }}">
                                    {{ ucfirst($author->status ?? 'active') }}
                                </span>
                            </td>
                            <td>{{ $author->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewAuthor({{ $author->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="editAuthor({{ $author->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-info" onclick="viewBooks({{ $author->id }})" title="View Books">
                                        <i class="fas fa-book"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteAuthor({{ $author->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-user-pen fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No authors found</p>
                                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#createAuthorModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Create First Author
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($authors->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Showing {{ $authors->firstItem() }} to {{ $authors->lastItem() }} of {{ $authors->total() }} authors
            </div>
            <div>
                {{ $authors->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Create Author Modal -->
<div class="modal fade" id="createAuthorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-pen me-2"></i>
                    Create New Author
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.authors.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" name="bio" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create Author
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Author Modal -->
<div class="modal fade" id="editAuthorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-pen me-2"></i>
                    Edit Author
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.authors.update', ':id') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editAuthorId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" id="editName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" id="editEmail" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="editPassword" placeholder="Leave blank to keep current">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" id="editPasswordConfirmation" placeholder="Leave blank to keep current">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="editPhone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="editStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" name="bio" id="editBio" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Author
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Author Modal -->
<div class="modal fade" id="viewAuthorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-pen me-2"></i>
                    Author Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="authorDetails">
                <!-- Author details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAuthorModal" tabindex="-1" aria-labelledby="deleteAuthorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteAuthorModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Delete Author
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger fa-3x"></i>
                    </div>
                    <h6 class="mb-3" id="deleteAuthorMessage">Are you sure? You want to delete this author?</h6>
                    <p class="text-muted mb-0" id="deleteAuthorDescription">This action cannot be undone and will also delete all their books.</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteAuthorBtn">
                    <i class="fas fa-trash me-2"></i>Delete Author
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Statistics Cards - Clean Modern Design */
.stats-card {
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
}

/* Author Avatar Styles */
.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.author-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 28px;
    margin: 0 auto;
}

/* Status Badge Styles */
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-badge.inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-badge.suspended {
    background-color: #fef3c7;
    color: #92400e;
}

/* Bio Info Styles */
.bio-info {
    max-width: 200px;
}

/* Button Group Styles */
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Table Hover Effects */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

/* Modal Styles */
.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

/* Notification Styles */
.alert {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
}

.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
}

.alert-warning {
    background-color: #fef3c7;
    color: #92400e;
}

.alert-info {
    background-color: #e0f2fe;
    color: #075985;
}
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const rows = document.querySelectorAll('#authorsTable tbody tr');
    
    rows.forEach(row => {
        const name = row.dataset.name || '';
        const email = row.dataset.email || '';
        
        if (name.includes(search) || email.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Clear search
function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchInput').dispatchEvent(new Event('input'));
}

// Filter functionality
function filterTable() {
    const status = document.getElementById('statusFilter').value;
    const books = document.getElementById('booksFilter').value;
    const rows = document.querySelectorAll('#authorsTable tbody tr');
    
    rows.forEach(row => {
        const rowStatus = row.dataset.status || 'active';
        const rowBooks = parseInt(row.dataset.books) || 0;
        let show = true;
        
        if (status && rowStatus !== status) {
            show = false;
        }
        
        if (books) {
            if (books === '0' && rowBooks !== 0) show = false;
            if (books === '1-5' && (rowBooks < 1 || rowBooks > 5)) show = false;
            if (books === '6-10' && (rowBooks < 6 || rowBooks > 10)) show = false;
            if (books === '10+' && rowBooks < 10) show = false;
        }
        
        row.style.display = show ? '' : 'none';
    });
}

// Sort functionality
function sortTable() {
    const sortBy = document.getElementById('sortBy').value;
    const tbody = document.querySelector('#authorsTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aVal, bVal;
        
        switch(sortBy) {
            case 'name':
                aVal = a.dataset.name || '';
                bVal = b.dataset.name || '';
                return aVal.localeCompare(bVal);
            case 'books':
                aVal = parseInt(a.dataset.books) || 0;
                bVal = parseInt(b.dataset.books) || 0;
                return bVal - aVal;
            case 'status':
                aVal = a.dataset.status || 'active';
                bVal = b.dataset.status || 'active';
                return aVal.localeCompare(bVal);
            default:
                return 0;
        }
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.author-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// View author
function viewAuthor(id) {
    // Load author details via AJAX
    fetch(`/admin/authors/${id}/details`)
        .then(response => response.json())
        .then(data => {
            const detailsHtml = `
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="author-avatar-large mb-3">
                            ${data.name ? data.name.charAt(0).toUpperCase() : 'A'}
                        </div>
                        <h5 class="mb-1">${data.name || 'N/A'}</h5>
                        <span class="badge bg-${data.status === 'active' ? 'success' : 'secondary'}">
                            ${data.status || 'Unknown'}
                        </span>
                    </div>
                    <div class="col-md-8">
                        <h6 class="mb-3">Author Information</h6>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-2">
                                    <strong>Email:</strong><br>
                                    <span class="text-muted">${data.email || 'N/A'}</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Phone:</strong><br>
                                    <span class="text-muted">${data.phone || 'Not provided'}</span>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-2">
                                    <strong>Books Published:</strong><br>
                                    <span class="text-muted">${data.books_count || 0} books</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Member Since:</strong><br>
                                    <span class="text-muted">${data.created_at ? new Date(data.created_at).toLocaleDateString() : 'N/A'}</span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6>Biography</h6>
                            <p class="text-muted">${data.author_bio || 'No biography available.'}</p>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-${data.approved_by_admin ? 'success' : 'warning'}">
                                ${data.approved_by_admin ? 'Approved' : 'Pending Approval'}
                            </span>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('authorDetails').innerHTML = detailsHtml;
            new bootstrap.Modal(document.getElementById('viewAuthorModal')).show();
        })
        .catch(error => {
            console.error('Error loading author details:', error);
            showNotification('Error loading author details', 'error');
        });
}

// Edit author
function editAuthor(id) {
    // Load author data for editing
    fetch(`/admin/authors/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate edit form
            document.getElementById('editAuthorId').value = data.id;
            document.getElementById('editName').value = data.name || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editPhone').value = data.phone || '';
            document.getElementById('editBio').value = data.author_bio || '';
            document.getElementById('editStatus').value = data.status || 'active';
            
            // Show edit modal
            new bootstrap.Modal(document.getElementById('editAuthorModal')).show();
        })
        .catch(error => {
            console.error('Error loading author data:', error);
            showNotification('Error loading author data', 'error');
        });
}

// View books
function viewBooks(id) {
    // Redirect to author's books or load via AJAX
    window.location.href = `/admin/books?author=${id}`;
}

// Delete author
function deleteAuthor(id) {
    // Fetch author information first
    fetch(`/admin/authors/${id}`)
        .then(response => response.json())
        .then(author => {
            // Show delete confirmation modal with author name
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteAuthorModal'));
            document.getElementById('deleteAuthorMessage').textContent = 
                `Are you sure? You want to delete ${author.name}?`;
            document.getElementById('deleteAuthorDescription').textContent = 
                `This action cannot be undone and will also delete all their books.`;
            
            // Set up confirm button
            document.getElementById('confirmDeleteAuthorBtn').onclick = function() {
                deleteModal.hide();
                performDeleteAuthor(id);
            };
            
            deleteModal.show();
        })
        .catch(error => {
            showNotification('Error fetching author information', 'error');
        });
}

function performDeleteAuthor(id) {
    fetch(`/admin/authors/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Author deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Error deleting author', 'error');
        }
    })
    .catch(error => {
        showNotification('Error deleting author', 'error');
    });
}

// Export authors
function exportAuthors() {
    // Export authors data
    window.location.href = '/admin/authors/export';
}

// Helper function for notifications
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush
