@extends('layouts.marketing')

@section('title', 'Browse • E‑Library')

@section('content')
    <section class="pagehead">
        <div class="container pagehead__inner">
            <div>
                <div class="pagehead__kicker">Browse</div>
                <h1 class="pagehead__title">Find your next read</h1>
            </div>

            <form class="search search--compact" action="{{ route('browse') }}" method="get">
                <label class="sr-only" for="q2">Search</label>
                <input class="search__input" id="q2" name="q" type="search" value="{{ $q }}" placeholder="Search for books…" />
                <button class="btn btn--primary search__btn" type="submit">Search</button>
            </form>

            @if ($books->count() === 0)
                <div class="empty-state">
                    <div class="empty-icon">📚</div>
                    <div class="empty-title">No books yet</div>
                    <div class="empty-description">When you add books to the database, they will show up here.</div>
                </div>
            @else
                <div class="books-grid">
                    @foreach ($books as $book)
                        <article class="book-card">
                            <div class="book-cover">
                                <div class="book-image">
                                    @if ($book->cover_image)
                                        <img src="{{ $book->cover_image }}" alt="{{ $book->title }}">
                                    @else
                                        <div class="book-placeholder">
                                            <span class="book-icon">📖</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="book-overlay">
                                    <div class="book-actions">
                                        <button class="btn-view">View Details</button>
                                    </div>
                                </div>
                            </div>
                            <div class="book-info">
                                <h3 class="book-title">{{ $book->title }}</h3>
                                <p class="book-author">
                                    {{ optional(optional($book->author)->user)->name ?? 'Unknown author' }}
                                </p>
                                @if ($book->tags->count())
                                    <div class="book-tags">
                                        @foreach ($book->tags->take(3) as $tag)
                                            <span class="tag">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

