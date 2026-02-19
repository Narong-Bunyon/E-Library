@extends('layouts.admin')

@section('title', 'All Database Data - E-Library')

@section('page-title', 'All Database Tables Data')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-database me-2"></i>
                        All Database Tables Data
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Users Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-users me-2"></i>
                            Users ({{ $data['users']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['users'] as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td><span class="badge bg-info">{{ $user->role ?? 'User' }}</span></td>
                                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No users found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Authors Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-edit me-2"></i>
                            Authors ({{ $data['authors']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>Bio</th>
                                        <th>Approved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['authors'] as $author)
                                        <tr>
                                            <td>{{ $author->id }}</td>
                                            <td>{{ $author->user_id }}</td>
                                            <td>{{ $author->bio ?? 'No bio' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $author->approved_by_admin ? 'success' : 'warning' }}">
                                                    {{ $author->approved_by_admin ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No authors found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Books Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-book me-2"></i>
                            Books ({{ $data['books']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Downloads</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['books'] as $book)
                                        <tr>
                                            <td>{{ $book->id }}</td>
                                            <td>{{ $book->title }}</td>
                                            <td>{{ $book->author->name ?? 'Unknown' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $book->status == 1 ? 'success' : 'warning' }}">
                                                    {{ $book->status == 1 ? 'Published' : 'Draft' }}
                                                </span>
                                            </td>
                                            <td>{{ $book->totalDownloads() }}</td>
                                            <td>{{ $book->create_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No books found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Categories Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-tags me-2"></i>
                            Categories ({{ $data['categories']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['categories'] as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No categories found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tags Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-hashtag me-2"></i>
                            Tags ({{ $data['tags']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['tags'] as $tag)
                                        <tr>
                                            <td>{{ $tag->id }}</td>
                                            <td>{{ $tag->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No tags found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Reviews Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-comments me-2"></i>
                            Reviews ({{ $data['reviews']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Book</th>
                                        <th>User</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['reviews'] as $review)
                                        <tr>
                                            <td>{{ $review->id }}</td>
                                            <td>{{ $review->book->title ?? 'Unknown Book' }}</td>
                                            <td>{{ $review->user->name ?? 'Unknown User' }}</td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    {{ $review->rating }}/5
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-dark' : 'text-muted' }} fa-sm"></i>
                                                    @endfor
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($review->comment, 100) }}</td>
                                            <td>{{ $review->create_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No reviews found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Downloads Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-download me-2"></i>
                            Downloads ({{ $data['downloads']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Book</th>
                                        <th>User</th>
                                        <th>File Type</th>
                                        <th>File Size</th>
                                        <th>Status</th>
                                        <th>Downloaded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['downloads'] as $download)
                                        <tr>
                                            <td>{{ $download->id }}</td>
                                            <td>{{ $download->book->title ?? 'Unknown Book' }}</td>
                                            <td>{{ $download->user->name ?? 'Unknown User' }}</td>
                                            <td><span class="badge bg-info">{{ $download->file_type }}</span></td>
                                            <td>{{ number_format($download->file_size / 1024 / 1024, 2) }} MB</td>
                                            <td>
                                                <span class="badge bg-{{ $download->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($download->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $download->downloaded_at?->format('Y-m-d H:i') ?? 'Not downloaded' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No downloads found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Reading Progress Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-book-reader me-2"></i>
                            Reading Progress ({{ $data['reading_progress']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Book</th>
                                        <th>User</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Last Read</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['reading_progress'] as $progress)
                                        <tr>
                                            <td>{{ $progress->id }}</td>
                                            <td>{{ $progress->book->title ?? 'Unknown Book' }}</td>
                                            <td>{{ $progress->user->name ?? 'Unknown User' }}</td>
                                            <td>
                                                <div class="progress" style="width: 100px; height: 20px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $progress->progress_percentage }}%">
                                                        {{ $progress->progress_percentage }}%
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $progress->current_page }}/{{ $progress->total_pages }} pages</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $progress->status == 'completed' ? 'success' : ($progress->status == 'active' ? 'info' : 'warning') }}">
                                                    {{ ucfirst($progress->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $progress->last_read_at?->format('Y-m-d H:i') ?? 'Never' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No reading progress found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Favorites Table -->
                    <div class="mb-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-heart me-2"></i>
                            Favorites ({{ $data['favorites']->count() }} records)
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Book</th>
                                        <th>User</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['favorites'] as $favorite)
                                        <tr>
                                            <td>{{ $favorite->id }}</td>
                                            <td>{{ $favorite->book->title ?? 'Unknown Book' }}</td>
                                            <td>{{ $favorite->user->name ?? 'Unknown User' }}</td>
                                            <td>{{ $favorite->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No favorites found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
