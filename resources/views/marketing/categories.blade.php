@extends('layouts.marketing')

@section('title', 'Categories - E-Library')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-layer-group me-2"></i>
                        Book Categories
                    </h1>
                    <p class="text-muted mb-0">Explore our collection of books by category</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search categories..." id="categorySearch">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row" id="categoriesContainer">
        @if ($categories->count() > 0)
            @foreach ($categories as $category)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 category-item" data-category="{{ $category->name }}">
                    <div class="card category-card h-100">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3">
                                <i class="fas 
                                    @switch($category->name)
                                        @case('Programming') fa-code
                                        @case('Design') fa-palette
                                        @case('Database') fa-database
                                        @case('Web Development') fa-globe
                                        @case('Mobile') fa-mobile-alt
                                        @case('DevOps') fa-server
                                        @case('Security') fa-shield-alt
                                        @case('AI/ML') fa-brain
                                        @case('Business') fa-briefcase
                                        @case('Science') fa-flask
                                        @case('Mathematics') fa-calculator
                                        @case('History') fa-landmark
                                        @case('Literature') fa-book-open
                                        @case('Art') fa-paint-brush
                                        @case('Music') fa-music
                                        @case('Sports') fa-football-ball
                                        @case('Technology') fa-microchip
                                        @default fa-folder
                                    @endswitch
                                "></i>
                            </div>
                            <h5 class="category-title">{{ $category->name }}</h5>
                            <p class="category-description text-muted">{{ $category->description ?? 'Explore books in this category' }}</p>
                            <div class="category-stats">
                                <span class="badge bg-primary">
                                    <i class="fas fa-book me-1"></i>
                                    {{ $category->books_count ?? 0 }} Books
                                </span>
                            </div>
                            <a href="{{ route('browse') }}?category={{ $category->id }}" class="btn btn-outline-primary btn-sm mt-3">
                                <i class="fas fa-arrow-right me-1"></i>
                                Browse Books
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Categories Found</h4>
                    <p class="text-muted">There are no book categories available at the moment.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-1"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if ($categories->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.category-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 24px;
    color: white;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.category-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1rem;
}

.category-description {
    font-size: 0.9rem;
    line-height: 1.4;
    min-height: 40px;
}

.category-stats {
    margin-top: 1rem;
}

.category-item {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.input-group {
    max-width: 300px;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('categorySearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const categoryItems = document.querySelectorAll('.category-item');
    
    categoryItems.forEach(item => {
        const categoryName = item.dataset.category.toLowerCase();
        if (categoryName.includes(searchTerm)) {
            item.style.display = 'block';
            item.classList.add('fade-in');
        } else {
            item.style.display = 'none';
        }
    });
});

// Add animation to categories on page load
document.addEventListener('DOMContentLoaded', function() {
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
@endsection
