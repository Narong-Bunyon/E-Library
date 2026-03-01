@extends('layouts.author')

@section('title', 'Categories Management - E-Library')

@section('page-title', 'Categories Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Categories Management</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportCategories()">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-primary" onclick="showCreateCategoryModal()">
                <i class="fas fa-plus me-2"></i>New Category
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Category::count() }}</h5>
                    <p>Total Categories</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Category::whereHas('books', function($query) {
                        $query->where('author_id', auth()->user()->id);
                    })->count() }}</h5>
                    <p>Your Categories</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ $categoryStats['most_used']->name ?? 'N/A' }}</h5>
                    <p>Most Used</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>{{ \App\Models\Category::withCount('books')->orderBy('books_count', 'desc')->first()->books_count ?? 0 }}</h5>
                    <p>Highest Book Count</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Categories Table -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-layer-group me-2"></i>All Categories</h5>
            <div class="card-actions">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control form-control-sm" placeholder="Search categories..." id="searchInput">
                    <button class="btn btn-outline-secondary" onclick="searchCategories()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Total Books</th>
                            <th>Published</th>
                            <th>Drafts</th>
                            <th>Color</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    @if($category->image_cover)
                                        <img src="{{ Str::startsWith($category->image_cover, ['http://', 'https://']) ? $category->image_cover : asset('storage/' . $category->image_cover) }}" 
                                             alt="{{ $category->name }}" 
                                             class="category-cover-img">
                                    @else
                                        <div class="category-cover-placeholder">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="category-badge" style="background-color: {{ $category->color ?? '#007bff' }}; color: #000 !important;">
                                            {{ $category->name }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ Str::limit($category->description ?? 'No description', 50) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->books_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $category->books->where('status', 1)->count() ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $category->books->where('status', 0)->count() ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="color-preview" style="background-color: {{ $category->color ?? '#007bff' }}"></div>
                                        <small class="text-muted">{{ $category->color ?? '#007bff' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($category->created_at)->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewCategory({{ $category->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="reviewCategory({{ $category->id }})" title="Review Category">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="editCategory({{ $category->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                        <h6>No categories found</h6>
                                        <p class="text-muted">Start by creating your first category!</p>
                                        <button class="btn btn-primary" onclick="showCreateCategoryModal()">
                                            <i class="fas fa-plus me-2"></i>Create Category
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<!-- Category Details Modal -->
<div class="modal fade" id="categoryDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-layer-group me-2"></i>Category Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="categoryDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromDetailsBtn">Edit Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-layer-group me-2"></i>
                    <span id="categoryModalTitle">Create Category</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required style="color: #333 !important; background-color: #fff !important;">
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3" style="color: #333 !important; background-color: #fff !important;"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categoryColor" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="categoryColor" name="color" value="#007bff">
                    </div>
                    <div class="mb-3">
                        <label for="categoryImageCover" class="form-label">Category Cover Image</label>
                        <input type="file" class="form-control" id="categoryImageCover" name="image_cover" accept="image/*">
                        <div class="form-text">Upload an image to represent this category (JPG, PNG, GIF - Max 2MB)</div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Delete Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Deleting a category will remove it from all books. Books will not be deleted, but they will no longer have this category assigned.
                </div>
                <p>Are you sure you want to delete the category "<strong id="deleteCategoryName"></strong>"?</p>
                <div class="mb-3">
                    <label class="form-label">Type "DELETE" to confirm:</label>
                    <input type="text" class="form-control" id="deleteConfirmText" placeholder="DELETE">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash me-2"></i>Delete Category
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.category-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 15px;
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
}

.category-cover-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.category-cover-placeholder {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    border: 2px solid #e9ecef;
}

.color-preview {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: 1px solid #ddd;
    margin-right: 8px;
}

.table th {
    font-weight: 600;
    border-top: none;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin: 0 1px;
}

.empty-state {
    padding: 40px;
}

.category-stats {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.category-books-list {
    max-height: 200px;
    overflow-y: auto;
}

.book-item {
    display: flex;
    align-items: center;
    padding: 8px;
    border: 1px solid #f0f0f0;
    border-radius: 4px;
    margin-bottom: 8px;
}

.book-cover {
    width: 40px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 10px;
}

.book-placeholder {
    width: 40px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    margin-right: 10px;
}
</style>

<script>
let currentCategoryId = null;

function showCreateCategoryModal() {
    currentCategoryId = null;
    document.getElementById('categoryModalTitle').textContent = 'Create Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryColor').value = '#007bff';
    document.getElementById('imagePreview').innerHTML = '';
    
    const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
    modal.show();
}

function viewCategory(categoryId) {
    currentCategoryId = categoryId;
    
    // Fetch category details
    fetch(`/author/categories/${categoryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const category = data.category;
                let content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Category Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>${category.id}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td><span class="category-badge" style="background-color: ${category.color || '#007bff'}; color: #000 !important;">${category.name}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>${category.description || 'No description'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Color:</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="color-preview" style="background-color: ${category.color || '#007bff'}"></div>
                                            ${category.color || '#007bff'}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>${new Date(category.created_at).toLocaleDateString()}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Statistics</h6>
                            <div class="category-stats">
                                <span class="badge bg-primary">Total: ${category.books_count || 0}</span>
                                <span class="badge bg-success">Published: ${category.books?.filter(b => b.status === 1).length || 0}</span>
                                <span class="badge bg-warning">Drafts: ${category.books?.filter(b => b.status === 0).length || 0}</span>
                            </div>
                        </div>
                    </div>
                `;
                
                if (category.books && category.books.length > 0) {
                    content += `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Recent Books</h6>
                                <div class="category-books-list">
                    `;
                    
                    category.books.slice(0, 5).forEach(book => {
                        content += `
                            <div class="book-item">
                                ${book.cover_image ? 
                                    `<img src="${book.cover_image.startsWith('http') ? book.cover_image : '/storage/' + book.cover_image}" alt="${book.title}" class="book-cover">` :
                                    `<div class="book-placeholder"><i class="fas fa-book"></i></div>`
                                }
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">${book.title}</div>
                                    <small class="text-muted">${book.status === 1 ? 'Published' : 'Draft'}</small>
                                </div>
                            </div>
                        `;
                    });
                    
                    content += `
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                document.getElementById('categoryDetailsContent').innerHTML = content;
                
                const modal = new bootstrap.Modal(document.getElementById('categoryDetailsModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading category details');
        });
}

function reviewCategory(categoryId) {
    // This function can be used to review the category in detail
    // For now, it will open the view modal
    viewCategory(categoryId);
}

function editCategory(categoryId) {
    currentCategoryId = categoryId;
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('imagePreview').innerHTML = '';
    
    // Fetch category data and populate form
    fetch(`/author/categories/${categoryId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('categoryName').value = data.category.name;
                document.getElementById('categoryDescription').value = data.category.description || '';
                document.getElementById('categoryColor').value = data.category.color || '#007bff';
                
                // Show current image if exists
                if (data.category.image_cover) {
                    const imageUrl = data.category.image_cover.startsWith('http') ? 
                        data.category.image_cover : 
                        `/storage/${data.category.image_cover}`;
                    document.getElementById('imagePreview').innerHTML = 
                        `<img src="${imageUrl}" alt="Current cover" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`;
                }
                
                const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading category data');
        });
}

function deleteCategory(categoryId, categoryName) {
    currentCategoryId = categoryId;
    document.getElementById('deleteCategoryName').textContent = categoryName;
    document.getElementById('deleteConfirmText').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
    modal.show();
}

function searchCategories() {
    const searchTerm = document.getElementById('searchInput').value;
    const url = new URL(window.location);
    if (searchTerm) {
        url.searchParams.set('search', searchTerm);
    } else {
        url.searchParams.delete('search');
    }
    window.location.href = url.toString();
}

function exportCategories() {
    window.location.href = '/author/categories/export';
}

// Handle delete confirmation
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const imageInput = document.getElementById('categoryImageCover');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image size must be less than 2MB');
                    e.target.value = '';
                    preview.innerHTML = '';
                    return;
                }
                
                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image file');
                    e.target.value = '';
                    preview.innerHTML = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });
    }
    
    const deleteInput = document.getElementById('deleteConfirmText');
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    
    if (deleteInput && deleteBtn) {
        deleteInput.addEventListener('input', function(e) {
            deleteBtn.disabled = e.target.value !== 'DELETE';
        });
        
        deleteBtn.addEventListener('click', function() {
            if (currentCategoryId && deleteInput.value === 'DELETE') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/author/categories/${currentCategoryId}`;
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
    
    // Handle edit from details modal
    const editFromDetailsBtn = document.getElementById('editFromDetailsBtn');
    if (editFromDetailsBtn) {
        editFromDetailsBtn.addEventListener('click', function() {
            const detailsModal = bootstrap.Modal.getInstance(document.getElementById('categoryDetailsModal'));
            detailsModal.hide();
            editCategory(currentCategoryId);
        });
    }
    
    // Handle category form submission
    const categoryForm = document.getElementById('categoryForm');
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(categoryForm);
            const url = currentCategoryId ? 
                `/author/categories/${currentCategoryId}` : 
                '/author/categories';
            
            const method = currentCategoryId ? 'POST' : 'POST'; // Use POST for both, handle method in controller
            
            // Add _method field for PUT if editing
            if (currentCategoryId) {
                formData.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(currentCategoryId ? 'Category updated successfully!' : 'Category created successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Error saving category');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving category');
            });
        });
    }
});
</script>
@endsection
