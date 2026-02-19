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

    <section class="section">
        <div class="container">
            <div class="section__head">
                <h2 class="section__title">Features</h2>
                <p class="section__subtitle">Everything you need to discover, read, and save your favorites.</p>
            </div>

            <div class="grid3">
                <div class="card">
                    <div class="card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6 4h11a2 2 0 0 1 2 2v14a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2V6a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 8h8M8 12h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="card__title">Extensive Library</div>
                    <div class="muted">Explore a vast collection of books across various genres and topics.</div>
                </div>

                <div class="card">
                    <div class="card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M9 6h6M9 18h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="card__title">Anytime, Anywhere</div>
                    <div class="muted">Read on any device. Clean layout with comfortable typography.</div>
                </div>

                <div class="card">
                    <div class="card__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M17 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M7 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M3 22a6 6 0 0 1 12 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M13 22a6 6 0 0 1 11-3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="card__title">Join the Community</div>
                    <div class="muted">Rate and review books. Save favorites and share recommendations.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--soft">
        <div class="container">
            <div class="section__head">
                <h2 class="section__title">Browse Popular Categories</h2>
                <p class="section__subtitle">Start with a category—then explore more with tags and search.</p>
            </div>

            @php
                $fallback = [
                    ['Science & Tech', 'grad-a'],
                    ['Literature & Fiction', 'grad-b'],
                    ['History', 'grad-c'],
                    ['Business & Finance', 'grad-d'],
                    ['Health & Wellness', 'grad-e'],
                    ['Education', 'grad-f'],
                ];
                $cards = $categories->count()
                    ? $categories->take(6)->map(fn($c, $i) => [$c->name, $fallback[$i % count($fallback)][1]])
                    : collect($fallback);
            @endphp

            <div class="cats">
                @foreach ($cards as $c)
                    @php
                        $name = is_array($c) ? $c[0] : $c[0];
                        $grad = is_array($c) ? $c[1] : $c[1];
                    @endphp
                    <a class="cat {{ $grad }}" href="{{ route('categories') }}">
                        <div class="cat__title">{{ $name }}</div>
                        <div class="cat__hint">Explore</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

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

