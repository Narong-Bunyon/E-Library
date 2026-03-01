@extends('layouts.admin')

@section('title', 'Favorites Management - E-Library')

@section('page-title', 'Favorites Management')

@php
// Helper functions for display
$getCategoryColor = function($categoryName) {
    $colors = [
        'Programming' => '#007bff',
        'Design' => '#28a745',
        'Database' => '#ffc107',
        'Web Development' => '#17a2b8',
        'Mobile' => '#6f42c1',
        'DevOps' => '#fd7e14',
        'Security' => '#dc3545',
        'AI/ML' => '#20c997',
    ];
    return $colors[$categoryName] ?? '#6c757d';
};
@endphp

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $totalFavorites }}</h3>
                            <p class="stat-label">Total Favorites</p>
                            @if($monthlyGrowth > 0)
                                <small class="text-success">
                                    <i class="fas fa-arrow-up"></i> +{{ round($monthlyGrowth, 1) }}% this month
                                </small>
                            @endif
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
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $activeUsers }}</h3>
                            <p class="stat-label">Active Users</p>
                            <small class="text-muted">With favorites</small>
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
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $favoritesThisMonth }}</h3>
                            <p class="stat-label">This Month</p>
                            <small class="text-muted">New favorites</small>
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
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-value">{{ $mostFavoritedBook ? $mostFavoritedBook->favorites_count : 0 }}</h3>
                            <p class="stat-label">Most Favorited</p>
                            <small class="text-muted">Book this week</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-heart me-2"></i>
                Favorites Management
            </h4>
            <p class="text-muted mb-0">Manage user favorite books and reading preferences</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFavoriteModal">
                <i class="fas fa-plus me-1"></i>
                Add Favorite
            </button>
            <button class="btn btn-outline-danger" onclick="deleteSelectedFavorites()" id="bulkDeleteBtn" style="display: none;">
                <i class="fas fa-trash me-1"></i>
                Delete Selected
            </button>
            <button class="btn btn-outline-secondary" onclick="exportFavorites()">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Favorites Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                All Favorites
                <span class="badge bg-primary ms-2">{{ $favorites->count() }}</span>
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-2" id="selectAll" onchange="toggleSelectAll()">
                    <small class="text-muted">Select All</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" id="dateFilter" onchange="filterFavorites()">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                    <select class="form-select form-select-sm" id="userFilter" onchange="filterFavorites()">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-select form-select-sm" id="bookFilter" onchange="filterFavorites()">
                        <option value="">All Books</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                    <select class="form-select form-select-sm" id="categoryFilter" onchange="filterFavorites()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAllTable" onchange="toggleSelectAllTable()">
                            </th>
                            <th>User</th>
                            <th>Book</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($favorites as $favorite)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input favorite-checkbox" value="{{ $favorite->id }}" onchange="updateBulkDeleteButton()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">{{ substr($favorite->user->name ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $favorite->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $favorite->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-cover-sm me-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $favorite->book->title ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $favorite->book->author->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $favorite->book->author->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($favorite->book->categories->count() > 0)
                                    @foreach($favorite->book->categories as $category)
                                        <span class="badge me-1" style="background-color: {{ getCategoryColor($category->name) }};">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $favorite->create_at?->diffForHumans() ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info btn-sm" onclick="viewFavorite({{ $favorite->id }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteFavorite({{ $favorite->id }})" title="Remove">
                                        <i class="fas fa-heart-broken"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No favorites found.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFavoriteModal">
                                    <i class="fas fa-plus me-1"></i>
                                    Add First Favorite
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($favorites->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Showing {{ $favorites->firstItem() }} to {{ $favorites->lastItem() }} of {{ $favorites->total() }} entries
                        </small>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $favorites->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Side Stats -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Most Active Users
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($mostActiveUsers as $user)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <small class="text-muted d-block">{{ $user->favorites_count }} favorites this month</small>
                        </div>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar" style="width: {{ min(($user->favorites_count / $mostActiveUsers->first()->favorites_count) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">No active users found</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire me-2"></i>
                        Popular Books
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($popularBooks as $book)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $book->title }}</strong>
                            <small class="text-muted d-block">{{ $book->favorites_count }} favorites this month</small>
                        </div>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar" style="width: {{ min(($book->favorites_count / $popularBooks->first()->favorites_count) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">No popular books found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Favorite Modal -->
<div class="modal fade" id="addFavoriteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Favorite</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.favorites.store') }}" method="POST" id="addFavoriteForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">User *</label>
                        <select class="form-select" name="user_id" required>
                            <option value="">Select a user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book *</label>
                        <select class="form-select" name="book_id" required>
                            <option value="">Select a book</option>
                            @foreach ($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-heart me-1"></i>
                        Add Favorite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Favorite Modal -->
<div class="modal fade" id="viewFavoriteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Favorite Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="favoriteDetails">
                <!-- Favorite details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteFavoriteModal" tabindex="-1" aria-labelledby="deleteFavoriteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteFavoriteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirm Remove Favorite
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="mb-3">
                        <i class="fas fa-heart-broken text-danger fa-3x"></i>
                    </div>
                    <h6 class="mb-3" id="deleteFavoriteMessage">Are you sure? You want to remove this favorite?</h6>
                    <p class="text-muted mb-0" id="deleteFavoriteDescription">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteFavoriteBtn">
                    <i class="fas fa-heart-broken me-2"></i>Remove Favorite
                </button>
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
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.stat-card.success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    color: white;
}

.stat-card.warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
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

.user-avatar-sm, .book-cover-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    color: #6c757d;
}

.pagination-wrapper .pagination {
    margin-bottom: 0;
}

.pagination-wrapper .page-link {
    color: #007bff;
    border-color: #dee2e6;
    padding: 0.5rem 0.75rem;
}

.pagination-wrapper .page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination-wrapper .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.pagination-wrapper .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.favorite-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function toggleSelectAllTable() {
    const selectAll = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('.favorite-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

// Bulk delete functionality
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.favorite-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (checkboxes.length > 0) {
        bulkDeleteBtn.style.display = 'inline-block';
    } else {
        bulkDeleteBtn.style.display = 'none';
    }
}

function deleteSelectedFavorites() {
    const checkboxes = document.querySelectorAll('.favorite-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        showNotification('Please select at least one favorite to delete', 'warning');
        return;
    }
    
    // Show delete confirmation modal with favorite count
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteFavoriteModal'));
    document.getElementById('deleteFavoriteMessage').textContent = 
        `Are you sure? You want to remove these ${ids.length} favorite${ids.length > 1 ? 's' : ''}?`;
    document.getElementById('deleteFavoriteDescription').textContent = 
        `This action cannot be undone. ${ids.length} favorite${ids.length > 1 ? 's' : ''} will be permanently removed.`;
    
    // Set up confirm button
    document.getElementById('confirmDeleteFavoriteBtn').onclick = function() {
        deleteModal.hide();
        performBulkDeleteFavorites(ids);
    };
    
    deleteModal.show();
}

function performBulkDeleteFavorites(ids) {
    fetch('/admin/favorites/bulk-delete', {
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
            showNotification(`${ids.length} favorite${ids.length > 1 ? 's' : ''} removed successfully`, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Error removing favorites', 'error');
        }
    })
    .catch(error => {
        showNotification('Error removing favorites', 'error');
    });
}

// CRUD operations
function viewFavorite(id) {
    fetch(`/admin/favorites/${id}`)
        .then(response => response.json())
        .then(data => {
            const categories = data.favorite.book.categories && data.favorite.book.categories.length > 0 
                ? data.favorite.book.categories.map(cat => cat.name).join(', ')
                : 'N/A';
                
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>User:</strong> ${data.favorite.user?.name || 'N/A'} (${data.favorite.user?.email || 'N/A'})<br>
                        <strong>Book:</strong> ${data.favorite.book?.title || 'N/A'}<br>
                        <strong>Author:</strong> ${data.favorite.book?.author?.name || 'N/A'}<br>
                        <strong>Categories:</strong> ${categories}<br>
                        <strong>Added:</strong> ${data.favorite.create_at || 'N/A'}
                    </div>
                    <div class="col-md-6">
                        <strong>Book Description:</strong><br>
                        <p>${data.favorite.book?.description || 'No description available'}</p>
                        <strong>Reading History:</strong><br>
                        ${data.reading_history.length > 0 ? 
                            data.reading_history.map(h => `Progress: ${h.progress_percentage}% (${h.status})`).join('<br>') : 
                            'No reading history found'
                        }
                    </div>
                </div>
            `;
            document.getElementById('favoriteDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewFavoriteModal')).show();
        });
}

function deleteFavorite(id) {
    // Fetch favorite information first
    fetch(`/admin/favorites/${id}`)
        .then(response => response.json())
        .then(favorite => {
            // Show delete confirmation modal with favorite details
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteFavoriteModal'));
            document.getElementById('deleteFavoriteMessage').textContent = 
                `Are you sure? You want to remove this favorite?`;
            document.getElementById('deleteFavoriteDescription').textContent = 
                `This action cannot be undone. The favorite for "${favorite.book_title || 'Unknown Book'}" by ${favorite.user_name || 'Unknown User'} will be permanently removed.`;
            
            // Set up confirm button
            document.getElementById('confirmDeleteFavoriteBtn').onclick = function() {
                deleteModal.hide();
                performDeleteFavorite(id);
            };
            
            deleteModal.show();
        })
        .catch(error => {
            showNotification('Error fetching favorite information', 'error');
        });
}

function performDeleteFavorite(id) {
    fetch(`/admin/favorites/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Favorite removed successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification('Error removing favorite', 'error');
        }
    })
    .catch(error => {
        showNotification('Error removing favorite', 'error');
    });
}

function exportFavorites() {
    window.location.href = '/admin/favorites/export';
}

function filterFavorites() {
    const dateRange = document.getElementById('dateFilter').value;
    const userId = document.getElementById('userFilter').value;
    const bookId = document.getElementById('bookFilter').value;
    const categoryId = document.getElementById('categoryFilter').value;
    
    const url = new URL(window.location);
    if (dateRange) url.searchParams.set('date_range', dateRange);
    if (userId) url.searchParams.set('user_id', userId);
    if (bookId) url.searchParams.set('book_id', bookId);
    if (categoryId) url.searchParams.set('category_id', categoryId);
    
    window.location.href = url.toString();
}

// Handle form submission
document.getElementById('addFavoriteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('addFavoriteModal'));
            modal.hide();
            location.reload();
        } else {
            alert(data.message || 'Error adding favorite');
        }
    })
    .catch(error => {
        alert('Error adding favorite: ' + error.message);
    });
});
</script>
@endpush
@endsection
