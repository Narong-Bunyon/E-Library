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
                        <a href="{{ route('author.books') }}" class="modern-action-btn secondary">
                            <i class="fas fa-list"></i>
                            <span>All Books</span>
                        </a>
                        
                        <a href="{{ route('author.books.published') }}" class="modern-action-btn success">
                            <i class="fas fa-check-circle"></i>
                            <span>Published</span>
                        </a>
                        
                        <a href="{{ route('author.books.create') }}" class="modern-action-btn primary">
                            <i class="fas fa-plus"></i>
                            <span>New Book</span>
                        </a>
                        
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
                    @forelse($books as $book)
                    <div class="drafts-grid">
                        @foreach($books as $book)
                        <div class="draft-book-item">
                            <div class="book-cover">
                                @if($book->cover_image)
                                    <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                @else
                                    <div class="placeholder-cover">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="book-info">
                                <div class="book-header">
                                    <h6>{{ $book->title }}</h6>
                                    <span class="badge bg-warning">Draft</span>
                                </div>
                                <p class="book-description">{{ Str::limit($book->description ?? 'No description', 120) }}</p>
                                
                                <div class="book-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-folder"></i>
                                        <span>{{ $book->category->name ?? 'Uncategorized' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                
                                <div class="book-stats">
                                    <div class="stat">
                                        <i class="fas fa-eye"></i>
                                        <span>{{ number_format($book->views ?? 0) }}</span>
                                    </div>
                                    <div class="stat">
                                        <i class="fas fa-download"></i>
                                        <span>{{ number_format($book->downloads ?? 0) }}</span>
                                    </div>
                                    <div class="stat">
                                        <i class="fas fa-file-alt"></i>
                                        <span>{{ $book->pages ?? 0 }} pages</span>
                                    </div>
                                </div>
                                
                                <div class="book-actions">
                                    <a href="{{ route('author.books.show', $book->id) }}" class="action-btn view">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ route('author.books.edit', $book->id) }}" class="action-btn edit">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('author.books.publish', $book->id) }}" method="POST" class="publish-form">
                                        @csrf
                                        <button type="submit" class="action-btn publish" title="Publish Book">
                                            <i class="fas fa-check"></i>
                                            <span>Publish</span>
                                        </button>
                                    </form>
                                    <button type="button" class="action-btn delete" onclick="confirmDelete({{ $book->id }})" title="Delete Book">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($books->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="pagination-wrapper">
                {{ $books->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function confirmDelete(bookId) {
    if (confirm('Are you sure you want to delete this draft book? This action cannot be undone.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/author/books/${bookId}`;
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
}
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

.drafts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.draft-book-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.draft-book-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.book-cover {
    width: 100%;
    height: 200px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-cover {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.book-info {
    padding: 1.5rem;
}

.book-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.book-header h6 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
    flex: 1;
}

.book-description {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.book-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6c757d;
}

.meta-item i {
    color: #ffc107;
    width: 16px;
}

.book-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding: 1rem 0;
    border-top: 1px solid #f1f3f5;
    border-bottom: 1px solid #f1f3f5;
}

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

.stat i {
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.stat span {
    font-weight: 600;
    color: #2c3e50;
}

.book-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 0.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.action-btn.view {
    background: #007bff;
    color: white;
}

.action-btn.view:hover {
    background: #0056b3;
}

.action-btn.edit {
    background: #ffc107;
    color: #212529;
}

.action-btn.edit:hover {
    background: #e0a800;
}

.action-btn.publish {
    background: #28a745;
    color: white;
}

.action-btn.publish:hover {
    background: #1e7e34;
}

.action-btn.delete {
    background: #dc3545;
    color: white;
}

.action-btn.delete:hover {
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
    
    .drafts-grid {
        grid-template-columns: 1fr;
    }
    
    .book-actions {
        flex-direction: column;
    }
    
    .empty-actions {
        flex-direction: column;
    }
}
</style>
@endsection
