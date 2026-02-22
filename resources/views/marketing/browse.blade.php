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
                <div class="book-grid">
                    @foreach ($books as $book)
                        <x-book-card :book="$book" :showRating="true" />
                    @endforeach
                </div>

                <div class="pagination-wrapper" style="margin-top:1.5rem">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

