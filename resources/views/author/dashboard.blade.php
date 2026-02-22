@extends('layouts.author')

@section('title', 'Author Dashboard - E-Library')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="welcome-text">
                        <h4>Welcome back, {{ auth()->user()->name }}!</h4>
                        <p class="text-muted">Here's what's happening with your books today</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['total_books'] ?? 0 }}</div>
                        <div class="stat-label">Total Books</div>
                    </div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['published_books'] ?? 0 }}</div>
                        <div class="stat-label">Published</div>
                    </div>
                </div>
                
                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['draft_books'] ?? 0 }}</div>
                        <div class="stat-label">Drafts</div>
                    </div>
                </div>
                
                <div class="stat-card info">
                    <div class="stat-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_views'] ?? 0) }}</div>
                        <div class="stat-label">Total Views</div>
                    </div>
                </div>
                
                <div class="stat-card secondary">
                    <div class="stat-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_downloads'] ?? 0) }}</div>
                        <div class="stat-label">Downloads</div>
                    </div>
                </div>
                
                <div class="stat-card rating">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['average_rating'] ? number_format($stats['average_rating'], 1) : '0.0' }}</div>
                        <div class="stat-label">Avg Rating</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="quick-actions-card">
                <div class="card-header">
                    <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="action-grid">
                        <a href="{{ route('author.books.create') }}" class="action-btn primary">
                            <i class="fas fa-plus"></i>
                            <span>New Book</span>
                        </a>
                        
                        <a href="{{ route('author.books') }}" class="action-btn secondary">
                            <i class="fas fa-list"></i>
                            <span>My Books</span>
                        </a>
                        
                        <a href="{{ route('author.books.published') }}" class="action-btn success">
                            <i class="fas fa-check-circle"></i>
                            <span>Published</span>
                        </a>
                        
                        <a href="{{ route('author.books.drafts') }}" class="action-btn warning">
                            <i class="fas fa-edit"></i>
                            <span>Drafts</span>
                        </a>
                        
                        <a href="{{ route('author.analytics') }}" class="action-btn info">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-8">
            <div class="activity-card">
                <div class="card-header">
                    <h5><i class="fas fa-clock me-2"></i>Recent Books</h5>
                    <a href="{{ route('author.books') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentBooks) && $recentBooks->count() > 0)
                        <div class="recent-books-list">
                            @foreach($recentBooks->take(5) as $book)
                            <div class="recent-book-item">
                                <div class="book-cover">
                                    @if($book->cover_image)
                                        <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                    @else
                                        <div class="placeholder-cover">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="book-info">
                                    <h6>{{ $book->title }}</h6>
                                    <div class="book-meta">
                                        <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $book->status === 1 ? 'Published' : 'Draft' }}
                                        </span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}</small>
                                    </div>
                                    <div class="book-stats">
                                        <span><i class="fas fa-eye"></i> {{ number_format($book->views ?? 0) }}</span>
                                        <span><i class="fas fa-download"></i> {{ number_format($book->downloads ?? 0) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h6>No books yet</h6>
                            <p class="text-muted">Start by creating your first book!</p>
                            <a href="{{ route('author.books.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Book
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="overview-card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Overview</h5>
                </div>
                <div class="card-body">
                    <div class="overview-item">
                        <div class="overview-label">This Month</div>
                        <div class="overview-value">
                            <i class="fas fa-arrow-up text-success"></i>
                            +{{ $stats['monthly_growth'] ?? 0 }}%
                        </div>
                    </div>
                    
                    <div class="progress-item">
                        <div class="progress-label">Profile Completion</div>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $stats['profile_completion'] ?? 0 }}%"></div>
                        </div>
                        <div class="progress-value">{{ $stats['profile_completion'] ?? 0 }}%</div>
                    </div>
                    
                    <div class="achievement-item">
                        <div class="achievement-label">Top Achievement</div>
                        <div class="achievement-value">
                            <i class="fas fa-trophy text-warning"></i>
                            {{ $stats['top_achievement'] ?? 'First Book Published' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.welcome-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.welcome-avatar {
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card.primary { border-left: 4px solid #007bff; }
.stat-card.success { border-left: 4px solid #28a745; }
.stat-card.warning { border-left: 4px solid #ffc107; }
.stat-card.info { border-left: 4px solid #17a2b8; }
.stat-card.secondary { border-left: 4px solid #6c757d; }
.stat-card.rating { border-left: 4px solid #fd7e14; }

.stat-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #6c757d;
}

.stat-card.primary .stat-icon { color: #007bff; }
.stat-card.success .stat-icon { color: #28a745; }
.stat-card.warning .stat-icon { color: #ffc107; }
.stat-card.info .stat-icon { color: #17a2b8; }
.stat-card.secondary .stat-icon { color: #6c757d; }
.stat-card.rating .stat-icon { color: #fd7e14; }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.quick-actions-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.quick-actions-card .card-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.quick-actions-card .card-body {
    padding: 1.5rem;
}

.action-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.action-btn.primary {
    background: #007bff;
    color: white;
}

.action-btn.primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

.action-btn.secondary {
    background: #6c757d;
    color: white;
}

.action-btn.secondary:hover {
    background: #545b62;
    transform: translateY(-2px);
}

.action-btn.success {
    background: #28a745;
    color: white;
}

.action-btn.success:hover {
    background: #1e7e34;
    transform: translateY(-2px);
}

.action-btn.warning {
    background: #ffc107;
    color: #212529;
}

.action-btn.warning:hover {
    background: #e0a800;
    transform: translateY(-2px);
}

.action-btn.info {
    background: #17a2b8;
    color: white;
}

.action-btn.info:hover {
    background: #138496;
    transform: translateY(-2px);
}

.action-btn i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.activity-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.activity-card .card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.activity-card .card-body {
    padding: 1.5rem;
}

.recent-books-list {
    display: grid;
    gap: 1rem;
}

.recent-book-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 8px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.recent-book-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.book-cover {
    width: 60px;
    height: 80px;
    border-radius: 6px;
    overflow: hidden;
    flex-shrink: 0;
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-cover {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.book-info {
    flex: 1;
}

.book-info h6 {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    color: #2c3e50;
}

.book-meta {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-bottom: 0.5rem;
}

.book-stats {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: #6c757d;
}

.overview-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.overview-card .card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.overview-card .card-body {
    padding: 1.5rem;
}

.overview-item, .progress-item, .achievement-item {
    padding: 1rem 0;
    border-bottom: 1px solid #f1f3f5;
}

.overview-item:last-child, .progress-item:last-child, .achievement-item:last-child {
    border-bottom: none;
}

.overview-label, .progress-label, .achievement-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.overview-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
}

.progress {
    background: #e9ecef;
    border-radius: 10px;
    height: 8px;
    margin: 0.5rem 0;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #20c997);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.progress-value {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.achievement-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #fd7e14;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

@media (max-width: 768px) {
    .welcome-content {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
