@extends('layouts.marketing')

@section('title', 'My Library - E‑Library')

@section('content')
<div class="home-content">
    <div class="container">
        <div class="home-section__header" style="margin-bottom:1.5rem">
            <h2 class="home-section__title">My Library</h2>
            <a href="{{ route('browse') }}" class="btn btn--primary btn--sm">
                <i class="fas fa-plus"></i> Browse Books
            </a>
        </div>

        @if ($books->count() > 0)
            <div class="book-grid">
                @foreach ($books as $book)
                    <x-book-card :book="$book" :showAction="true" />
                @endforeach
            </div>

            <div style="margin-top:1.5rem">
                {{ $books->links() }}
            </div>
        @else
            <div style="text-align:center; padding:4rem 2rem; background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08)">
                <i class="fas fa-book-open" style="font-size:3rem; color:#d1d5db; margin-bottom:1rem; display:block"></i>
                <h3 style="font-size:1.25rem; font-weight:600; margin-bottom:0.5rem">Your library is empty</h3>
                <p class="text-muted" style="margin-bottom:1.5rem">Start reading or adding favorites to build your personal library.</p>
                <a href="{{ route('browse') }}" class="btn btn--primary">Browse Books</a>
            </div>
        @endif
    </div>
</div>
@endsection
