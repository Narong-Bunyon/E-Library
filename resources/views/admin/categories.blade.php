@extends('layouts.admin')

@section('title', 'Categories Management - E-Library')

@section('page-title', 'Categories Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-layer-group me-2"></i>
                Categories Management
            </h4>
            <p class="text-muted mb-0">Manage book categories and organization</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="fas fa-plus me-1"></i>
                Add New Category
            </button>
            <button class="btn btn-outline-danger" onclick="deleteSelectedCategories()" id="bulkDeleteBtn" style="display: none;">
                <i class="fas fa-trash me-1"></i>
                Delete Selected
            </button>
            <button class="btn btn-outline-secondary" onclick="exportCategories()">
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
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $categories->count() }}</h3>
                            <p class="stat-label">Total Categories</p>
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
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $totalBooks }}</h3>
                            <p class="stat-label">Total Books</p>
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
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $avgBooksPerCategory }}</h3>
                            <p class="stat-label">Avg Books/Category</p>
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
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $newCategoriesThisMonth }}</h3>
                            <p class="stat-label">New This Month</p>
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
                <div class="col-md-6">
                    <label class="form-label">Search Categories</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search categories...">
                        <button class="btn btn-outline-secondary" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select class="form-select" id="sortBy">
                        <option value="name">Name</option>
                        <option value="books_count">Books Count</option>
                        <option value="created_at">Date Created</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">View</label>
                    <select class="form-select" id="viewMode">
                        <option value="grid">Grid View</option>
                        <option value="table">Table View</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid View -->
    <div class="card" id="gridView">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-layer-group me-2"></i>
                All Categories
                <span class="badge bg-primary ms-2">{{ $categories->count() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <input type="checkbox" class="form-check-input me-2" id="selectAll" onchange="toggleSelectAll()">
                <small class="text-muted">Select All</small>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4" id="categoriesGrid">
                @forelse ($categories as $category)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="category-card">
                        <div class="category-checkbox">
                            <input type="checkbox" class="form-check-input category-checkbox" value="{{ $category->id }}" onchange="updateBulkDeleteButton()">
                        </div>
                        <div class="category-icon bg-primary">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="category-content">
                            <h6 class="category-title">{{ $category->name }}</h6>
                            <p class="category-stats">{{ $category->books_count ?? 0 }} books</p>
                            <div class="category-actions">
                                <button class="btn btn-sm btn-outline-primary" onclick="editCategory({{ $category->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="viewCategory({{ $category->id }})" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory({{ $category->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No categories found. Create your first category!</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="fas fa-plus me-1"></i>
                        Create First Category
                    </button>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Categories Table View -->
    <div class="card" id="tableView" style="display: none;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-layer-group me-2"></i>
                All Categories
                <span class="badge bg-primary ms-2">{{ $categories->count() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <input type="checkbox" class="form-check-input me-2" id="selectAllTable" onchange="toggleSelectAllTable()">
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
                            <th>Name</th>
                            <th>Books Count</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input category-checkbox" value="{{ $category->id }}" onchange="updateBulkDeleteButton()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="category-icon-sm me-2">
                                        <i class="fas fa-folder text-primary"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $category->books_count ?? 0 }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $category->description ?? 'No description' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $category->created_at?->format('M d, Y') ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editCategory({{ $category->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="viewCategory({{ $category->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteCategory({{ $category->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No categories found. Create your first category!</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Create First Category
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" id="createCategoryForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter category name">
                        <div class="form-text">Choose a descriptive name for your category</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Optional description"></textarea>
                        <div class="form-text">Brief description of what this category contains</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <div class="d-flex gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="primary" id="colorPrimary" checked>
                                <label class="form-check-label" for="colorPrimary">
                                    <span class="badge bg-primary">Primary</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="success" id="colorSuccess">
                                <label class="form-check-label" for="colorSuccess">
                                    <span class="badge bg-success">Success</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="warning" id="colorWarning">
                                <label class="form-check-label" for="colorWarning">
                                    <span class="badge bg-warning">Warning</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="danger" id="colorDanger">
                                <label class="form-check-label" for="colorDanger">
                                    <span class="badge bg-danger">Danger</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" id="editCategoryForm" onsubmit="updateCategory(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editCategoryId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" class="form-control" name="name" id="editCategoryName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="editCategoryDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <div class="d-flex gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="primary" id="editColorPrimary">
                                <label class="form-check-label" for="editColorPrimary">
                                    <span class="badge bg-primary">Primary</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="success" id="editColorSuccess">
                                <label class="form-check-label" for="editColorSuccess">
                                    <span class="badge bg-success">Success</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="warning" id="editColorWarning">
                                <label class="form-check-label" for="editColorWarning">
                                    <span class="badge bg-warning">Warning</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" value="danger" id="editColorDanger">
                                <label class="form-check-label" for="editColorDanger">
                                    <span class="badge bg-danger">Danger</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Category Modal -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="categoryDetails">
                <!-- Category details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="editCategoryFromView()">Edit Category</button>
            </div>
        </div>
    </div>
</div>
@endsection

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

.category-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-color: #667eea;
}

.category-checkbox {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto 15px;
}

.category-icon-sm {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.category-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.category-stats {
    color: #666;
    margin-bottom: 15px;
}

.category-actions {
    display: flex;
    gap: 5px;
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
// View mode toggle
document.getElementById('viewMode').addEventListener('change', function() {
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    
    if (this.value === 'table') {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
    } else {
        gridView.style.display = 'block';
        tableView.style.display = 'none';
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const categoryCards = document.querySelectorAll('#categoriesGrid .category-card');
    
    categoryCards.forEach(card => {
        const title = card.querySelector('.category-title').textContent.toLowerCase();
        card.style.display = title.includes(search) ? '' : 'none';
    });
});

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
    const checkboxes = document.querySelectorAll('#categoriesGrid .category-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function toggleSelectAllTable() {
    const selectAll = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('#tableView .category-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function toggleSelectAllHeader() {
    const selectAll = document.getElementById('selectAllHeader');
    const checkboxes = document.querySelectorAll('#tableView .category-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

// Bulk delete functionality
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.category-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (checkboxes.length > 0) {
        bulkDeleteBtn.style.display = 'inline-block';
    } else {
        bulkDeleteBtn.style.display = 'none';
    }
}

function deleteSelectedCategories() {
    const checkboxes = document.querySelectorAll('.category-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Please select at least one category to delete');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${ids.length} categor${ids.length > 1 ? 'ies' : 'y'}? This action cannot be undone.`)) {
        fetch('/admin/categories/bulk-delete', {
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
                alert('Error deleting categories: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting categories: ' + error.message);
        });
    }
}

// CRUD operations
function editCategory(id) {
    fetch(`/admin/categories/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editCategoryId').value = data.id;
            document.getElementById('editCategoryName').value = data.name;
            document.getElementById('editCategoryDescription').value = data.description || '';
            
            // Set the color radio button
            const color = data.color || 'primary';
            document.getElementById(`editColor${color.charAt(0).toUpperCase() + color.slice(1)}`).checked = true;
            
            // Set the correct form action
            const form = document.getElementById('editCategoryForm');
            form.action = `/admin/categories/${id}`;
            
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        });
}

function updateCategory(event) {
    event.preventDefault();
    
    const form = document.getElementById('editCategoryForm');
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
            // Close modal and reload page to show updated data
            const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
            modal.hide();
            location.reload();
        } else if (data && data.errors) {
            // Handle validation errors
            console.error('Validation errors:', data.errors);
            alert('Please fix the errors in the form.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the category.');
    });
}

function viewCategory(id) {
    fetch(`/admin/categories/${id}`)
        .then(response => response.json())
        .then(data => {
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>ID:</strong> ${data.id}<br>
                        <strong>Name:</strong> ${data.name}<br>
                        <strong>Books Count:</strong> ${data.books_count || 0}
                    </div>
                    <div class="col-md-6">
                        <strong>Description:</strong> ${data.description || 'No description'}<br>
                        <strong>Created:</strong> ${data.created_at || 'N/A'}
                    </div>
                </div>
            `;
            document.getElementById('categoryDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewCategoryModal')).show();
        });
}

function editCategoryFromView() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewCategoryModal'));
    modal.hide();
    
    const id = document.getElementById('editCategoryId').value;
    editCategory(id);
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        fetch(`/admin/categories/${id}`, {
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
                alert('Error deleting category: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting category: ' + error.message);
        });
    }
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchInput').dispatchEvent(new Event('input'));
}

function exportCategories() {
    const url = new URL(window.location);
    url.searchParams.set('export', 'csv');
    window.location.href = url.toString();
}
</script>
@endpush
