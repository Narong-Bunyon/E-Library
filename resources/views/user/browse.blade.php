@extends('layouts.user')

@section('title', 'Browse Books')
@section('page-title', 'Browse Books')

@section('content')
<div class="container-fluid p-0">
    <!-- Browse Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Browse Books</h2>
                    <p class="text-muted mb-0">Discover new books and expand your reading horizons.</p>
                </div>
                <div>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search books...">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="section-card mb-4">
        <div class="section-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select">
                        <option>All Categories</option>
                        <option>Fiction</option>
                        <option>Non-Fiction</option>
                        <option>Science Fiction</option>
                        <option>Fantasy</option>
                        <option>Mystery</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select class="form-select">
                        <option>Most Popular</option>
                        <option>Newest First</option>
                        <option>Title A-Z</option>
                        <option>Author A-Z</option>
                        <option>Highest Rated</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Language</label>
                    <select class="form-select">
                        <option>All Languages</option>
                        <option>English</option>
                        <option>Spanish</option>
                        <option>French</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rating</label>
                    <select class="form-select">
                        <option>All Ratings</option>
                        <option>5 Stars</option>
                        <option>4+ Stars</option>
                        <option>3+ Stars</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="section-card">
        <div class="section-header">
            <h5 class="section-title">Available Books</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary active">
                    <i class="fas fa-th"></i>
                </button>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        <div class="section-body">
            <div class="book-grid">
                <!-- Sample Book Cards -->
                <div class="book-card">
                    <div class="book-cover">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-title">Sample Book Title</div>
                    <div class="book-author">Author Name</div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </small>
                            <small class="text-muted">4.0</small>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-plus me-1"></i>
                            Add to Library
                        </button>
                    </div>
                </div>

                <!-- More sample books... -->
                <div class="book-card">
                    <div class="book-cover">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-title">Another Book</div>
                    <div class="book-author">Another Author</div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </small>
                            <small class="text-muted">5.0</small>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-plus me-1"></i>
                            Add to Library
                        </button>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-cover">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-title">Third Book</div>
                    <div class="book-author">Third Author</div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </small>
                            <small class="text-muted">4.2</small>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-plus me-1"></i>
                            Add to Library
                        </button>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-cover">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-title">Fourth Book</div>
                    <div class="book-author">Fourth Author</div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </small>
                            <small class="text-muted">3.8</small>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-plus me-1"></i>
                            Add to Library
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
