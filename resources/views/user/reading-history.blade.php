@extends('layouts.marketing')

@section('title', 'Reading History - E‑Library')

@section('content')
<div class="home-content">
    <div class="container">
        <div class="home-section__header" style="margin-bottom:1.5rem">
            <h2 class="home-section__title">Reading History</h2>
            <a href="{{ route('browse') }}" class="btn btn--primary btn--sm">
                <i class="fas fa-search"></i> Browse Books
            </a>
        </div>

        @if ($readingHistory->count() > 0)
            <div class="book-grid">
                @foreach ($readingHistory as $progress)
                    @if ($progress->book)
                        <x-book-card :book="$progress->book" />
                    @endif
                @endforeach
            </div>

            <div style="margin-top:1.5rem">
                {{ $readingHistory->links() }}
            </div>
        @else
            <div style="text-align:center; padding:4rem 2rem; background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08)">
                <i class="fas fa-history" style="font-size:3rem; color:#d1d5db; margin-bottom:1rem; display:block"></i>
                <h3 style="font-size:1.25rem; font-weight:600; margin-bottom:0.5rem">No reading history yet</h3>
                <p class="text-muted" style="margin-bottom:1.5rem">Start reading books to build your reading history.</p>
                <a href="{{ route('browse') }}" class="btn btn--primary">Browse Books</a>
            </div>
        @endif
    </div>
</div>
@endsection
