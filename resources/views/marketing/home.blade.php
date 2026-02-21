@extends('layouts.marketing')

@section('title', 'E‑Library')

@section('content')
    <section class="hero">
        <div class="container hero__grid">
            <div class="hero__copy">
                <div class="hero__kicker">Discover a World of Knowledge</div>
                <h1 class="hero__title">Access thousands of books, audiobooks, and articles.</h1>
                <p class="hero__subtitle">Learn and grow anytime, anywhere with a clean reading experience designed for students and lifelong learners.</p>

                <form class="search" action="{{ route('browse') }}" method="get">
                    <label class="sr-only" for="q">Search</label>
                    <input class="search__input" id="q" name="q" type="search" placeholder="Search for books, authors, or topics…" />
                    <button class="btn btn--primary search__btn" type="submit">Search</button>
                </form>

                <div class="hero__cta">
                    <a class="btn btn--primary" href="{{ route('browse') }}">Sign Up for Free</a>
                    @if (Route::has('login'))
                        <a class="btn btn--ghost" href="{{ route('login') }}">Log In</a>
                    @else
                        <a class="btn btn--ghost is-disabled" href="#" aria-disabled="true" tabindex="-1">Log In</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <div class="features-card">
        <div class="container-card">
            <div class="section-header-card">
                <h2 class="section-title-card">Why Choose E-Library?</h2>
                <p class="section-subtitle-card">Everything you need for a perfect reading experience</p>
            </div>

            <div class="features-grid">

                <div class="feature-card">
                    <div class="feature-icon-card">
                        <img src="{{ asset('images/home/library.png') }}" alt="Extensive Library">
                    </div>
                    <h3 class="feature-title-card">Extensive Library</h3>
                    <p class="feature-description-card">
                        Explore a vast collection of books across various genres and topics
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-card">
                        <img src="{{ asset('images/home/feature2.png') }}" alt="Anytime Anywhere">
                    </div>
                    <h3 class="feature-title-card">Anytime, Anywhere</h3>
                    <p class="feature-description-card">
                        Read on any device with our responsive design and clean layout
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-card">
                        <img src="{{ asset('images/home/feature3.png') }}" alt="Join Community">
                    </div>
                    <h3 class="feature-title-card">Join Community</h3>
                    <p class="feature-description-card">
                        Rate and review books, save favorites, and share recommendations
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section-category">
        <div class="container-category">
            <div class="category-my-card">
                <h2 class="headertitle">Browse Popular Categories</h2>
                <p class="categoriessubtitle">Start with a category—then explore more with tags and search.</p>
            </div>

            <div class="homepage-categories">
                @if ($categories->count() > 0)
                    @foreach ($categories->take(6) as $index => $category)
                        <a class="homepage-category {{ $category->color ?? 'category-grad' }}" 
                           href="{{ route('categories') }}"
                           @if ($category->image_cover)
                               @if(str_starts_with($category->image_cover, 'http'))
                                   style="background-image: url('{{ $category->image_cover }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
                               @else
                                   style="background-image: url('{{ asset('storage/' . $category->image_cover) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
                               @endif
                           @endif>
                            <div class="homepage-category-content">
                                <div class="homepage-category-title">{{ $category->name }}</div>
                                <div class="homepage-category-description">{{ $category->description ?? '' }}</div>
                                <div class="homepage-category-hint">{{ $category->books_count ?? 0 }} Books</div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Fallback categories if no data -->
                    <a class="homepage-category grad-a" href="{{ route('categories') }}">
                        <div class="homepage-category-content">
                            <div class="homepage-category-title">Science & Tech</div>
                            <div class="homepage-category-hint">Explore</div>
                        </div>
                    </a>
                    <a class="homepage-category grad-b" href="{{ route('categories') }}">
                        <div class="homepage-category-content">
                            <div class="homepage-category-title">Literature & Fiction</div>
                            <div class="homepage-category-hint">Explore</div>
                        </div>
                    </a>
                    <a class="homepage-category grad-c" href="{{ route('categories') }}">
                        <div class="homepage-category-content">
                            <div class="homepage-category-title">History</div>
                            <div class="homepage-category-hint">Explore</div>
                        </div>
                    </a>
                    <a class="homepage-category grad-d" href="{{ route('categories') }}">
                        <div class="homepage-category-content">
                            <div class="homepage-category-title">Business & Finance</div>
                            <div class="homepage-category-hint">Explore</div>
                        </div>
                    </a>
                    <a class="homepage-category grad-e" href="{{ route('categories') }}">
                        <div class="homepage-category-content">
                            <div class="homepage-category-title">Health & Wellness</div>
                            <div class="homepage-category-hint">Explore</div>
                        </div>
                    </a>
                    <a class="homepage-category grad-f" href="{{ route('categories') }}">
                        <div class="homepage-category-content">
                            <div class="homepage-category-title">Education</div>
                            <div class="homepage-category-hint">Explore</div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <section class="cta">
        <div class="container cta__inner">
            <div>
                <h2 class="cta__title">Start Your Reading Journey Today!</h2>
                <p class="cta__subtitle">Sign up and get unlimited access to thousands of books and resources.</p>
            </div>
            <div class="cta__actions">
                <a class="btn btn--primary" href="{{ route('browse') }}">Sign Up for Free</a>
                @if (Route::has('login'))
                    <a class="btn btn--ghost" href="{{ route('login') }}">Log In</a>
                @else
                    <a class="btn btn--ghost is-disabled" href="#" aria-disabled="true" tabindex="-1">Log In</a>
                @endif
            </div>
        </div>
    </section>
@endsection

