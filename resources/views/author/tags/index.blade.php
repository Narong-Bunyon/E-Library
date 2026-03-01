@extends('layouts.author')

@section('title', 'Tags Management - E-Library')

@section('page-title', 'Tags Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Tags Management</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportTags()">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-primary" onclick="showCreateTagModal()">
                <i class="fas fa-plus me-2"></i>New Tag
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>{{ $tagStats['total_tags'] ?? 0 }}</h5>
                    <p>Total Tags</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>{{ $tagStats['most_used']->name ?? 'N/A' }}</h5>
                    <p>Most Used Tag</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tags List -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-tags me-2"></i>Tags</h5>
            <div class="card-actions">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control form-control-sm" placeholder="Search tags..." id="searchInput">
                    <button class="btn btn-outline-secondary" onclick="searchTags()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @forelse ($tags as $tag)
                <div class="tag-item">
                    <div class="tag-info">
                        <div class="tag-details">
                            <h6>
                                <span class="tag-badge" style="background-color: {{ $tag->color ?? '#007bff' }}">
                                    {{ $tag->name }}
                                </span>
                            </h6>
                            <p class="text-muted mb-2">{{ $tag->description ?? 'No description available' }}</p>
                            <div class="tag-stats">
                                <span class="badge bg-primary">{{ $tag->books_count ?? 0 }} books</span>
                                <span class="badge bg-success">{{ $tag->books->where('status', 1)->count() ?? 0 }} published</span>
                                <span class="badge bg-warning">{{ $tag->books->where('status', 0)->count() ?? 0 }} drafts</span>
                            </div>
                        </div>
                        <div class="tag-books">
                            <small class="text-muted mb-2">Recent Books:</small>
                            @foreach($tag->books->take(3) as $book)
                                <div class="mini-book-item">
                                    @if($book->cover_image)
                                        <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="mini-book-cover">
                                    @else
                                        <div class="mini-book-placeholder">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                    <div class="mini-book-info">
                                        <div class="mini-book-title">{{ Str::limit($book->title, 20) }}</div>
                                        <small class="text-muted">{{ $book->status === 1 ? 'Published' : 'Draft' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tag-actions">
                        <button class="btn btn-outline-primary btn-sm" onclick="viewTag({{ $tag->id }})" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="editTag({{ $tag->id }})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteTag({{ $tag->id }}, '{{ $tag->name }}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h6>No tags yet</h6>
                    <p class="text-muted">Start by creating your first tag!</p>
                    <button class="btn btn-primary" onclick="showCreateTagModal()">
                        <i class="fas fa-plus me-2"></i>Create Tag
                    </button>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Pagination -->
    @if($tags->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $tags->links() }}
        </div>
    @endif
</div>

<!-- Create/Edit Tag Modal -->
<div class="modal fade" id="tagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-tags me-2"></i>
                    <span id="tagModalTitle">Create Tag</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="tagForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tagName" class="form-label">Tag Name *</label>
                        <input type="text" class="form-control" id="tagName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="tagDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="tagDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tagColor" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="tagColor" name="color" value="#17a2b8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save me-2"></i>Save Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Tag Modal -->
<div class="modal fade" id="deleteTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Delete Tag
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Deleting a tag will remove it from all books. Books will not be deleted, but they will no longer have this tag assigned.
                </div>
                <p>Are you sure you want to delete the tag "<strong id="deleteTagName"></strong>"?</p>
                <div class="mb-3">
                    <label class="form-label">Type "DELETE" to confirm:</label>
                    <input type="text" class="form-control" id="deleteConfirmText" placeholder="DELETE">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash me-2"></i>Delete Tag
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.tag-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 15px;
    background: #fff;
}

.tag-info {
    display: flex;
    gap: 20px;
    flex: 1;
}

.tag-details {
    flex: 1;
}

.tag-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.tag-stats {
    margin-top: 10px;
}

.tag-stats .badge {
    margin-right: 5px;
    font-size: 0.75rem;
}

.tag-books {
    width: 220px;
}

.mini-book-item {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
    padding: 3px;
    border: 1px solid #f0f0f0;
    border-radius: 2px;
}

.mini-book-cover {
    width: 20px;
    height: 28px;
    object-fit: cover;
    border-radius: 2px;
    margin-right: 4px;
}

.mini-book-placeholder {
    width: 20px;
    height: 28px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 8px;
    margin-right: 4px;
}

.mini-book-info {
    flex: 1;
}

.mini-book-title {
    font-weight: 600;
    font-size: 0.7rem;
    line-height: 1.1;
}

.tag-actions {
    display: flex;
    gap: 5px;
    align-items: center;
}
</style>

<script>
let currentTagId = null;

function showCreateTagModal() {
    currentTagId = null;
    document.getElementById('tagModalTitle').textContent = 'Create Tag';
    document.getElementById('tagForm').reset();
    document.getElementById('tagColor').value = '#17a2b8';
    
    const modal = new bootstrap.Modal(document.getElementById('tagModal'));
    modal.show();
}

function editTag(tagId) {
    currentTagId = tagId;
    document.getElementById('tagModalTitle').textContent = 'Edit Tag';
    
    // Fetch tag data and populate form
    fetch(`/author/tags/${tagId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('tagName').value = data.tag.name;
                document.getElementById('tagDescription').value = data.tag.description || '';
                document.getElementById('tagColor').value = data.tag.color || '#17a2b8';
                
                const modal = new bootstrap.Modal(document.getElementById('tagModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading tag data');
        });
}

function deleteTag(tagId, tagName) {
    currentTagId = tagId;
    document.getElementById('deleteTagName').textContent = tagName;
    document.getElementById('deleteConfirmText').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteTagModal'));
    modal.show();
}

function viewTag(tagId) {
    window.location.href = `/author/tags/${tagId}`;
}

function searchTags() {
    const searchTerm = document.getElementById('searchInput').value;
    const url = new URL(window.location);
    if (searchTerm) {
        url.searchParams.set('search', searchTerm);
    } else {
        url.searchParams.delete('search');
    }
    window.location.href = url.toString();
}

function exportTags() {
    window.location.href = '/author/tags/export';
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
            if (currentTagId && deleteInput.value === 'DELETE') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/author/tags/${currentTagId}`;
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
    
    // Handle tag form submission
    const tagForm = document.getElementById('tagForm');
    if (tagForm) {
        tagForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(tagForm);
            const url = currentTagId ? 
                `/author/tags/${currentTagId}` : 
                '/author/tags';
            
            const method = currentTagId ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(currentTagId ? 'Tag updated successfully!' : 'Tag created successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Error saving tag');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving tag');
            });
        });
    }
});
</script>
@endsection
