@extends('layouts.user')

@section('title', 'E-Library - Your Digital Library')
@section('page-title', 'E-Library')

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <div class="hero-section mb-5">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold text-white mb-4">
                            Welcome to E-Library
                        </h1>
                        <p class="lead text-white mb-4">
                            Discover amazing books and expand your knowledge
                        </p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('browse') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-search me-2"></i>
                                Browse Books
                            </a>
                            <a href="{{ route('user.library') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-book me-2"></i>
                                My Library
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center">
                        <i class="fas fa-book-open" style="font-size: 15rem; color: rgba(255,255,255,0.1);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container mb-5">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold mb-3">Why Choose E-Library?</h2>
                <p class="text-muted">Discover the best features of our digital library</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h4>Read Anywhere</h4>
                    <p class="text-muted">Access your books from any device, anywhere, anytime</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>Rate & Review</h4>
                    <p class="text-muted">Share your thoughts and help others discover great books</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Save Favorites</h4>
                    <p class="text-muted">Build your personal collection of favorite books</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Books Section -->
    <div class="container mb-5">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold mb-3">Popular Books</h3>
                <p class="text-muted">Discover what others are reading</p>
            </div>
        </div>
        <div class="row g-4">
            <!-- Sample Book Cards -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="book-card-simple">
                    <div class="book-cover-simple">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-info-simple">
                        <h6>Sample Book Title</h6>
                        <p class="text-muted small">Author Name</p>
                        <div class="book-rating">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i>
                            <span class="small text-muted">(4.0)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="book-card-simple">
                    <div class="book-cover-simple">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-info-simple">
                        <h6>Another Book</h6>
                        <p class="text-muted small">Another Author</p>
                        <div class="book-rating">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <span class="small text-muted">(5.0)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="book-card-simple">
                    <div class="book-cover-simple">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-info-simple">
                        <h6>Third Book</h6>
                        <p class="text-muted small">Third Author</p>
                        <div class="book-rating">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i>
                            <span class="small text-muted">(4.2)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="book-card-simple">
                    <div class="book-cover-simple">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-info-simple">
                        <h6>Fourth Book</h6>
                        <p class="text-muted small">Fourth Author</p>
                        <div class="book-rating">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i>
                            <span class="small text-muted">(3.8)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('browse') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>
                Browse All Books
            </a>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="container mb-5">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold mb-3">Browse by Category</h3>
                <p class="text-muted">Find books in your favorite genres</p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="#" class="category-card">
                    <i class="fas fa-dragon"></i>
                    <span>Fantasy</span>
                </a>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="#" class="category-card">
                    <i class="fas fa-rocket"></i>
                    <span>Sci-Fi</span>
                </a>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="#" class="category-card">
                    <i class="fas fa-user-secret"></i>
                    <span>Mystery</span>
                </a>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="#" class="category-card">
                    <i class="fas fa-heart"></i>
                    <span>Romance</span>
                </a>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="#" class="category-card">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Education</span>
                </a>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="#" class="category-card">
                    <i class="fas fa-briefcase"></i>
                    <span>Business</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cta-section text-center p-5">
                    <h3 class="fw-bold mb-3">Ready to Start Reading?</h3>
                    <p class="text-muted mb-4">Join thousands of readers discovering amazing books every day</p>
                    <a href="{{ route('browse') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search me-2"></i>
                        Explore Books Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.hero-content h1 {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.feature-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 2rem;
}

.book-card-simple {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.book-card-simple:hover {
    transform: translateY(-5px);
}

.book-cover-simple {
    width: 100px;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 2rem;
}

.book-info-simple h6 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.book-rating {
    margin-top: 0.5rem;
}

.category-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    text-decoration: none;
    color: #333;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-3px);
    color: #667eea;
    text-decoration: none;
}

.category-card i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #667eea;
}

.cta-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    border: 1px solid #dee2e6;
}
</style>
@endsection
