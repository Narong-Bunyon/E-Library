@extends('layouts.marketing')

@section('title', 'Browse • E‑Library')

@section('content')
    <section class="pagehead">
        <div class="container pagehead__inner">
            <div>
                <div class="pagehead__kicker">Browse</div>
                <h1 class="pagehead__title">Find your next read</h1>
                <p class="pagehead__subtitle">Search books by title or description.</p>
            </div>

            <form class="search search--compact" action="{{ route('browse') }}" method="get">
                <label class="sr-only" for="q2">Search</label>
                <input class="search__input" id="q2" name="q" type="search" value="{{ $q }}" placeholder="Search for books…" />
                <button class="btn btn--primary search__btn" type="submit">Search</button>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($books->count() === 0)
                <div class="empty">
                    <div class="empty__title">No books yet</div>
                    <div class="muted">When you add books to the database, they will show up here.</div>
                </div>
            @else
                <div class="grid3">
                    @foreach ($books as $book)
                        <article class="book">
                            <div class="book__cover" aria-hidden="true">
                                <div class="book__shine"></div>
                                <div class="book__title">{{ Str::limit($book->title, 40) }}</div>
                            </div>
                            <div class="book__meta">
                                <div class="book__name">{{ $book->title }}</div>
                                <div class="muted">
                                    {{ optional(optional($book->author)->user)->name ?? 'Unknown author' }}
                                </div>
                                @if ($book->tags->count())
                                    <div class="chips">
                                        @foreach ($book->tags->take(3) as $tag)
                                            <span class="chip">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

