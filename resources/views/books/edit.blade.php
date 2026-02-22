@extends('layouts.author')

@section('title', 'Edit Book - E-Library')

@section('page-title', 'Edit Book')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Book Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('books.update', $book->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Book Title *</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $book->title }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="isbn" name="isbn" value="{{ $book->isbn ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="publisher" class="form-label">Publisher</label>
                                <input type="text" class="form-control" id="publisher" name="publisher" value="{{ $book->publisher ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pages" class="form-label">Number of Pages</label>
                                <input type="number" class="form-control" id="pages" name="pages" value="{{ $book->pages ?? '' }}" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-control" id="language" name="language">
                                    <option value="en" {{ ($book->language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="kh" {{ ($book->language ?? '') == 'kh' ? 'selected' : '' }}>Khmer</option>
                                    <option value="fr" {{ ($book->language ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                                    <option value="zh" {{ ($book->language ?? '') == 'zh' ? 'selected' : '' }}>Chinese</option>
                                    <option value="es" {{ ($book->language ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="published_date" class="form-label">Published Date</label>
                                <input type="date" class="form-control" id="published_date" name="published_date" value="{{ $book->published_date ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ ($book->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="6" required>{{ $book->description ?? '' }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Brief description of the book...">{{ $book->excerpt ?? '' }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                                @if($book->cover_image)
                                    <small class="form-text text-muted">Current: {{ $book->cover_image }}</small>
                                @endif
                                <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="file_path" class="form-label">Book File</label>
                                <input type="file" class="form-control" id="file_path" name="file_path" accept=".pdf,.epub,.mobi">
                                @if($book->file_path)
                                    <small class="form-text text-muted">Current: {{ $book->file_path }}</small>
                                @endif
                                <small class="form-text text-muted">Allowed formats: PDF, EPUB, MOBI. Max size: 10MB</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="access_level" class="form-label">Access Level</label>
                            <select class="form-control" id="access_level" name="access_level">
                                <option value="public" {{ ($book->access_level ?? 'public') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ ($book->access_level ?? '') == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="premium" {{ ($book->access_level ?? '') == 'premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="0" {{ ($book->status ?? 0) == 0 ? 'selected' : '' }}>Draft</option>
                                <option value="1" {{ ($book->status ?? 0) == 1 ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Book
                            </button>
                            <a href="{{ route('books.index') }}" class="btn btn-secondary">
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
                    <h5 class="card-title mb-0">Current Book Info</h5>
                </div>
                <div class="card-body">
                    @if($book->cover_image)
                        <div class="text-center mb-3">
                            <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid" style="max-height: 200px;">
                        </div>
                    @endif
                    
                    <h6>{{ $book->title }}</h6>
                    <p class="text-muted">{{ \Str::limit($book->description, 100) }}</p>
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge {{ $book->status === 1 ? 'bg-success' : 'bg-warning' }}">
                            {{ $book->status === 1 ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Views:</strong> {{ number_format($book->views ?? 0) }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Downloads:</strong> {{ number_format($book->downloads ?? 0) }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong> {{ \Carbon\Carbon::parse($book->created_at)->format('M d, Y') }}
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>View Book
                        </a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
