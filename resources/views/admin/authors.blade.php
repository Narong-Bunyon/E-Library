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
                            <td colspan="7" class="text-center py-5">
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
            <form action="{{ route('admin.authors.update') }}" method="POST">
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
@endsection

@push('styles')
<style>
/* Statistics Cards - Clean Modern Design */
.stats-card {
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    transition: all 0.3s ease !important;
    height: 100% !important;
    display: flex !important;
    align-items: center !important;
    padding: 20px !important;
    position: relative !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: none !important;
    border: none !important;
}

.stats-icon {
    width: 50px !important;
    height: 50px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 20px !important;
    color: white !important;
    margin-right: 15px !important;
    flex-shrink: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-card:nth-child(1) .stats-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
}

.stats-card:nth-child(2) .stats-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    border: none !important;
}

.stats-card:nth-child(3) .stats-icon {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
    border: none !important;
}

.stats-card:nth-child(4) .stats-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    border: none !important;
}

.stats-info {
    flex: 1 !important;
    min-width: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-number {
    font-size: 28px !important;
    font-weight: 700 !important;
    color: #1a202c !important;
    line-height: 1 !important;
    margin-bottom: 4px !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.stats-label {
    font-size: 13px !important;
    color: #64748b !important;
    font-weight: 500 !important;
    text-transform: capitalize !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Author Avatar Styles */
.author-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

/* Books Info */
.books-info {
    text-align: center;
}

/* Status Badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.status-badge.inactive {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.status-badge.suspended {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-card {
        padding: 16px !important;
    }
    
    .stats-icon {
        width: 44px !important;
        height: 44px !important;
        font-size: 18px !important;
        margin-right: 12px !important;
    }
    
    .stats-number {
        font-size: 24px !important;
    }
    
    .stats-label {
        font-size: 12px !important;
    }
}

@media (max-width: 576px) {
    .stats-card {
        padding: 14px !important;
    }
    
    .stats-icon {
        width: 40px !important;
        height: 40px !important;
        font-size: 16px !important;
        margin-right: 10px !important;
    }
    
    .stats-number {
        font-size: 20px !important;
    }
    
    .stats-label {
        font-size: 11px !important;
    }
}
</style>
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
    console.log('View author:', id);
}

// Edit author
function editAuthor(id) {
    // Load author data for editing
    console.log('Edit author:', id);
}

// View books
function viewBooks(id) {
    // Redirect to author's books
    console.log('View books for author:', id);
}

// Delete author
function deleteAuthor(id) {
    if (confirm('Are you sure you want to delete this author?')) {
        // Delete author via AJAX
        console.log('Delete author:', id);
    }
}

// Export authors
function exportAuthors() {
    // Export authors data
    console.log('Export authors');
}
</script>
@endpush
