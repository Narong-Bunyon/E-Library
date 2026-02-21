@extends('layouts.marketing')

@section('title', 'Categories - E-Library')

@section('content')
<section class="cat-section">
    <div class="container">
        <!-- Header Section -->
        <div class="cat-header">
            <h1 class="cat-title">Browse Categories</h1>
            <p class="cat-subtitle">Discover books organized by your favorite topics</p>
        </div>

        <!-- Search Box -->
        <div class="cat-search">
            <div class="cat-search-container">
                <i class="fas fa-search cat-search-icon"></i>
                <input type="text" class="cat-search-input" placeholder="Search categories..." id="categorySearch">
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="cat-grid" id="categoriesContainer">
            @if ($categories->count() > 0)
                @foreach ($categories as $index => $category)
                    <div class="cat-card {{ $category->color ?? 'color-1' }} cat-item" data-category="{{ $category->name }}">
                        <div class="cat-card-content">
                            <div class="cat-icon-section">
                                @if ($category->image_cover)
                                    <div class="cat-image-wrapper">
                                        <img src="{{ $category->image_cover }}" alt="{{ $category->name }}">
                                    </div>
                                @else
                                    <div class="cat-icon-placeholder">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="cat-info">
                                <h3 class="cat-name">{{ $category->name }}</h3>
                                <p class="cat-description">{{ $category->description ?? 'Explore amazing books in this category' }}</p>
                                <div class="cat-stats">
                                    <span class="cat-count">{{ $category->books_count ?? 0 }} Books</span>
                                    <span class="cat-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="cat-empty">
                    <div class="cat-empty-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 class="cat-empty-title">No Categories Found</h3>
                    <p class="cat-empty-description">There are no book categories available at the moment.</p>
                    <a href="{{ route('home') }}" class="cat-btn-back">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Home
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="cat-pagination">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
// Search functionality
document.getElementById('categorySearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const categoryItems = document.querySelectorAll('.cat-item');
    
    categoryItems.forEach(item => {
        const categoryName = item.dataset.category.toLowerCase();
        if (categoryName.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Add click handler to category cards
document.querySelectorAll('.cat-card').forEach(card => {
    card.addEventListener('click', function() {
        const categoryName = this.dataset.category;
        // Navigate to browse page with category filter
        window.location.href = `/browse?category=${encodeURIComponent(categoryName)}`;
    });
});
</script>
@endpush
@endsection
