@extends('layouts.marketing')

@section('title', 'My Favorites - E‑Library')

@section('content')
<div class="home-content">
    <div class="container">
        <div class="home-section__header" style="margin-bottom:1.5rem">
            <h2 class="home-section__title">My Favorites</h2>
            <a href="{{ route('browse') }}" class="btn btn--primary btn--sm">
                <i class="fas fa-search"></i> Browse Books
            </a>
        </div>

        @if ($favorites->count() > 0)
            <div class="book-grid">
                @foreach ($favorites as $fav)
                    @if ($fav->book)
                        <x-book-card :book="$fav->book" />
                    @endif
                @endforeach
            </div>

            <div style="margin-top:1.5rem">
                {{ $favorites->links() }}
            </div>
        @else
            <div style="text-align:center; padding:4rem 2rem; background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08)">
                <i class="fas fa-heart" style="font-size:3rem; color:#d1d5db; margin-bottom:1rem; display:block"></i>
                <h3 style="font-size:1.25rem; font-weight:600; margin-bottom:0.5rem">No favorites yet</h3>
                <p class="text-muted" style="margin-bottom:1.5rem">Start adding books to your favorites to see them here.</p>
                <a href="{{ route('browse') }}" class="btn btn--primary">Browse Books</a>
            </div>
        @endif
    </div>
</div>
@endsection
