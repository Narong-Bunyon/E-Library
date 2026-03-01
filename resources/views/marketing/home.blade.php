@extends('layouts.marketing')

@section('title', 'E‑Library')

@section('content')
    {{-- Hero Section --}}
    <section class="hero">
        <div class="container hero__grid">
            <div class="hero__copy">
                <h1 class="hero__title">Welcome to E-Library</h1>
                <p class="hero__subtitle">Explore a world of knowledge at your fingertips.</p>

                <form class="search" action="{{ route('browse') }}" method="get">
                    <label class="sr-only" for="q">Search</label>
                    <input class="search__input" id="q" name="q" type="search" placeholder="Search for books, authors, or topics…" />
                    <button class="btn btn--primary search__btn" type="submit">Search</button>
                </form>
            </div>
        </div>
    </section>

    <div class="home-content">
        <div class="container">
            <div class="home-grid">
                {{-- Main Column --}}
                <div class="home-main">

                    {{-- Featured Books --}}
                    <section class="home-section">
                        <div class="home-section__header">
                            <h2 class="home-section__title">Featured Books</h2>
                            <a href="{{ route('browse') }}" class="home-section__link">View All &rsaquo;</a>
                        </div>
                        <div class="book-grid">
                            @forelse ($featuredBooks as $book)
                                @auth
                                    <x-book-card :book="$book" :showRating="true" :showAction="true" />
                                @else
                                    <x-book-card :book="$book" :showRating="true" :showAction="false" />
                                @endauth
                            @empty
                                <p class="text-muted">No featured books yet.</p>
                            @endforelse
                        </div>
                    </section>

                    {{-- Categories --}}
                    <section class="home-section">
                        <h2 class="home-section__title">Categories</h2>
                        <div class="category-chips">
                            @foreach ($categories as $category)
                                <a href="{{ route('browse', ['category' => $category->id]) }}" class="category-chip">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </section>

                    {{-- Recently Added --}}
                    <section class="home-section">
                        <div class="home-section__header">
                            <h2 class="home-section__title">Recently Added</h2>
                            <a href="{{ route('browse') }}" class="home-section__link">View All &rsaquo;</a>
                        </div>
                        <div class="book-grid">
                            @forelse ($recentBooks as $book)
                                @auth
                                    <x-book-card :book="$book" :showRating="true" :showAction="true" />
                                @else
                                    <x-book-card :book="$book" :showRating="true" :showAction="false" />
                                @endauth
                            @empty
                                <p class="text-muted">No books added yet.</p>
                            @endforelse
                        </div>
                    </section>

                    @auth
                        @if ($recommendedBooks->count() > 0)
                        <section class="home-section">
                            <h2 class="home-section__title">Recommended for You</h2>
                            <div class="book-grid">
                                @foreach ($recommendedBooks as $book)
                                    <x-book-card :book="$book" />
                                @endforeach
                            </div>
                        </section>
                        @endif
                    @endauth
                </div>

                {{-- Sidebar --}}
                <aside class="home-sidebar">
                    {{-- Reading Progress (logged-in only) --}}
                    @auth
                        @if ($readingProgress)
                        <div class="sidebar-card">
                            <h3 class="sidebar-card__title">My Reading Progress</h3>
                            <div class="reading-progress-widget">
                                <div class="reading-progress-widget__circle">
                                    <span class="reading-progress-widget__pct">{{ $readingProgress->progress_percentage ?? 0 }}%</span>
                                </div>
                                <div class="reading-progress-widget__info">
                                    <p class="reading-progress-widget__label">Currently Reading</p>
                                    <p class="reading-progress-widget__book">{{ $readingProgress->book->title ?? 'Unknown' }}</p>
                                    <a href="{{ route('book.read', $readingProgress->book_id) }}" class="btn btn--primary btn--sm">Continue Reading</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endauth

                    {{-- Popular Authors --}}
                    <div class="sidebar-card">
                        <h3 class="sidebar-card__title">Popular Authors</h3>
                        <div class="author-list">
                            @forelse ($popularAuthors as $author)
                                <div class="author-list__item">
                                    <div class="author-list__avatar">{{ substr($author->name, 0, 1) }}</div>
                                    <div class="author-list__info">
                                        <span class="author-list__name">{{ $author->name }}</span>
                                        <span class="author-list__count">{{ $author->books_count }} {{ Str::plural('book', $author->books_count) }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No authors yet.</p>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    {{-- CTA Section (guests only) --}}
    @guest
    <section class="cta">
        <div class="container cta__inner">
            <div>
                <h2 class="cta__title">Start Your Reading Journey Today!</h2>
                <p class="cta__subtitle">Sign up and get unlimited access to thousands of books and resources.</p>
            </div>
            <div class="cta__actions">
                <a href="{{ route('register') }}" class="btn btn--primary btn--lg">Sign Up Free</a>
                <a href="{{ route('login') }}" class="btn btn--outline btn--lg">Sign In</a>
            </div>
        </div>
    </section>
    @endguest
@endsection

