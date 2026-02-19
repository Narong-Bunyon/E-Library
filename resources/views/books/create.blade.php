@extends('layouts.author')

@section('title', 'Create New Book - E-Library')

@section('page-title', 'Create New Book')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Book Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('author.books.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Book Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="isbn" name="isbn">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="publisher" class="form-label">Publisher</label>
                                <input type="text" class="form-control" id="publisher" name="publisher">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pages" class="form-label">Number of Pages</label>
                                <input type="number" class="form-control" id="pages" name="pages" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-control" id="language" name="language">
                                    <option value="en">English</option>
                                    <option value="kh">Khmer</option>
                                    <option value="fr">French</option>
                                    <option value="zh">Chinese</option>
                                    <option value="es">Spanish</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="published_date" class="form-label">Published Date</label>
                                <input type="date" class="form-control" id="published_date" name="published_date">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Brief description of the book..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                                <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="file_path" class="form-label">Book File</label>
                                <input type="file" class="form-control" id="file_path" name="file_path" accept=".pdf,.epub,.mobi">
                                <small class="form-text text-muted">Allowed formats: PDF, EPUB, MOBI. Max size: 10MB</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="access_level" class="form-label">Access Level</label>
                            <select class="form-control" id="access_level" name="access_level">
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="0">Draft</option>
                                <option value="1">Published</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Book
                            </button>
                            <a href="{{ route('author.books') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Publishing Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>Book Requirements</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Complete book information</li>
                        <li><i class="fas fa-check text-success me-2"></i>High quality cover image</li>
                        <li><i class="fas fa-check text-success me-2"></i>Proper file format</li>
                        <li><i class="fas fa-check text-success me-2"></i>Accurate description</li>
                    </ul>
                    
                    <h6 class="mt-3">File Specifications</h6>
                    <ul class="list-unstyled">
                        <li><strong>Cover:</strong> JPG, PNG, GIF (Max 2MB)</li>
                        <li><strong>Book File:</strong> PDF, EPUB, MOBI (Max 10MB)</li>
                        <li><strong>Recommended:</strong> PDF for best compatibility</li>
                    </ul>
                    
                    <h6 class="mt-3">Tips</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-lightbulb text-warning me-2"></i>Use professional cover image</li>
                        <li><i class="fas fa-lightbulb text-warning me-2"></i>Write compelling description</li>
                        <li><i class="fas fa-lightbulb text-warning me-2"></i>Choose appropriate category</li>
                        <li><i class="fas fa-lightbulb text-warning me-2"></i>Set proper access level</li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('author.books') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Books
                        </a>
                        <a href="{{ route('author.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-dashboard me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
