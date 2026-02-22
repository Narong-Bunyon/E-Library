@extends('layouts.marketing')

@section('title', $book->title . ' - E‑Library')

@section('content')
    <div class="book-detail">
        <div class="container">
            <div class="book-detail__grid">
                {{-- Cover --}}
                <div class="book-detail__cover">
                    @if ($book->cover_image)
                        <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                    @else
                        <div class="book-detail__cover-placeholder">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="book-detail__info">
                    <h1 class="book-detail__title">{{ $book->title }}</h1>
                    <p class="book-detail__author">
                        <i class="fas fa-user-edit"></i>
                        by {{ $book->author->name ?? 'Unknown Author' }}
                    </p>

                    {{-- Meta --}}
                    <div class="book-detail__meta">
                        @if ($book->reviews_avg_rating)
                            <div class="book-detail__meta-item">
                                <i class="fas fa-star" style="color: #f59e0b"></i>
                                {{ round($book->reviews_avg_rating, 1) }} ({{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }})
                            </div>
                        @endif
                        @if ($book->pages)
                            <div class="book-detail__meta-item">
                                <i class="fas fa-file-alt"></i> {{ $book->pages }} pages
                            </div>
                        @endif
                        @if ($book->language)
                            <div class="book-detail__meta-item">
                                <i class="fas fa-globe"></i> {{ $book->language }}
                            </div>
                        @endif
                        @if ($book->category)
                            <div class="book-detail__meta-item">
                                <i class="fas fa-folder"></i> {{ $book->category->name }}
                            </div>
                        @endif
                        <div class="book-detail__meta-item">
                            <i class="fas fa-heart"></i> {{ $book->favorites_count }} favorites
                        </div>
                        <div class="book-detail__meta-item">
                            <i class="fas fa-download"></i> {{ $book->downloads_count }} downloads
                        </div>
                    </div>

                    {{-- Description --}}
                    @if ($book->description)
                        <div class="book-detail__description">
                            {!! nl2br(e($book->description)) !!}
                        </div>
                    @endif

                    {{-- Access Level Badge --}}
                    @php
                        $access = \App\Http\Controllers\LibraryController::checkAccess($book);
                    @endphp
                    <div style="margin-bottom:1rem">
                        @if ($book->isFree())
                            <span class="access-badge access-badge--free"><i class="fas fa-unlock"></i> Free</span>
                        @elseif ($book->isSubscription())
                            <span class="access-badge access-badge--subscription"><i class="fas fa-crown"></i> Subscription</span>
                        @elseif ($book->isBuy())
                            <span class="access-badge access-badge--buy"><i class="fas fa-tag"></i> {{ $book->formatted_price }}</span>
                        @endif
                    </div>

                    {{-- Flash messages --}}
                    @if (session('error'))
                        <div class="access-notice access-notice--locked" style="margin-bottom:1rem">
                            <p class="access-notice__title"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="book-detail__actions">
                        @if ($access === true)
                            {{-- Full access: show read + download --}}
                            <a href="{{ route('book.read', $book->id) }}" class="btn btn--primary">
                                <i class="fas fa-book-open"></i> Read Now
                            </a>
                            @if ($book->file_path)
                                <a href="{{ route('book.download', $book->id) }}" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @endif
                            @auth
                                @if ($isFavorited)
                                    <form method="POST" action="{{ route('user.favorites.remove', $book->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                            <i class="fas fa-heart" style="color:#ef4444"></i> Remove from Favorites
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('user.favorites.add', $book->id) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                            <i class="far fa-heart"></i> Add to Favorites
                                        </button>
                                    </form>
                                @endif
                            @endauth

                        @elseif ($access === 'login_required')
                            {{-- Guest: must log in first --}}
                            <div class="access-notice access-notice--locked">
                                <div class="access-notice__icon"><i class="fas fa-lock"></i></div>
                                <p class="access-notice__title">Login Required</p>
                                <p class="access-notice__text">
                                    @if ($book->isSubscription())
                                        Sign in and subscribe to read this book.
                                    @elseif ($book->isBuy())
                                        Sign in and purchase this book for {{ $book->formatted_price }}.
                                    @else
                                        Sign in to read and download this book.
                                    @endif
                                </p>
                                <a href="{{ route('login') }}" class="btn btn--primary">
                                    <i class="fas fa-sign-in-alt"></i> Log In
                                </a>
                                <a href="{{ route('register') }}" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                    Sign Up Free
                                </a>
                            </div>

                        @elseif ($access === 'subscription_required')
                            {{-- Logged in but no subscription --}}
                            @php $plans = config('book_pricing.subscription_plans', []); @endphp
                            <div class="access-notice access-notice--subscription">
                                <div class="access-notice__icon"><i class="fas fa-crown"></i></div>
                                <p class="access-notice__title">Subscription Required</p>
                                <p class="access-notice__text">Subscribe to unlock this book and all subscription content.</p>
                                @if (count($plans) > 0)
                                    <div class="subscription-plans">
                                        @foreach ($plans as $plan)
                                            <div class="subscription-plan">
                                                <span class="subscription-plan__name">{{ $plan['name'] }}</span>
                                                <span class="subscription-plan__price">${{ number_format($plan['price'], 2) }}/{{ $plan['period'] }}</span>
                                                <button class="btn btn--primary btn--sm" disabled>
                                                    Subscribe (Coming Soon)
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <button class="btn btn--primary" disabled>
                                        <i class="fas fa-crown"></i> Subscribe (Coming Soon)
                                    </button>
                                @endif
                                @if ($isFavorited)
                                    <form method="POST" action="{{ route('user.favorites.remove', $book->id) }}" style="display:inline; margin-top:0.75rem">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                            <i class="fas fa-heart" style="color:#ef4444"></i> Remove from Favorites
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('user.favorites.add', $book->id) }}" style="display:inline; margin-top:0.75rem">
                                        @csrf
                                        <button type="submit" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                            <i class="far fa-heart"></i> Add to Favorites
                                        </button>
                                    </form>
                                @endif
                            </div>

                        @elseif ($access === 'purchase_required')
                            {{-- Logged in but hasn't purchased --}}
                            <div class="access-notice access-notice--buy">
                                <div class="access-notice__icon"><i class="fas fa-shopping-cart"></i></div>
                                <p class="access-notice__title">Purchase Required</p>
                                <p class="access-notice__text">Buy this book for <strong>{{ $book->formatted_price }}</strong> to read and download.</p>
                                <button class="btn btn--primary" disabled>
                                    <i class="fas fa-shopping-cart"></i> Buy for {{ $book->formatted_price }} (Coming Soon)
                                </button>
                                @if ($isFavorited)
                                    <form method="POST" action="{{ route('user.favorites.remove', $book->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                            <i class="fas fa-heart" style="color:#ef4444"></i> Remove from Favorites
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('user.favorites.add', $book->id) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn--ghost" style="border:1px solid #e5e7eb">
                                            <i class="far fa-heart"></i> Add to Favorites
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Tags --}}
                    @if ($book->tags && $book->tags->count() > 0)
                        <div class="book-detail__tags">
                            @foreach ($book->tags as $tag)
                                <span class="book-detail__tag">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Reviews --}}
            @if ($book->reviews && $book->reviews->count() > 0)
                <div class="reviews-section">
                    <h2 class="reviews-section__title">Reviews ({{ $book->reviews->count() }})</h2>
                    @foreach ($book->reviews->take(5) as $review)
                        <div class="review-card">
                            <div class="review-card__header">
                                <div class="review-card__user">
                                    <div class="review-card__avatar">{{ substr($review->user->name ?? 'U', 0, 1) }}</div>
                                    <span class="review-card__name">{{ $review->user->name ?? 'Anonymous' }}</span>
                                </div>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i <= ($review->rating ?? 0) ? '#f59e0b' : '#d1d5db' }}; font-size: 0.75rem"></i>
                                    @endfor
                                    <span class="review-card__date">{{ $review->created_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="review-card__body">
                                {{ $review->comment ?? $review->content ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Related Books --}}
            @if ($relatedBooks->count() > 0)
                <div class="related-books">
                    <h2 class="related-books__title">Related Books</h2>
                    <div class="book-grid">
                        @foreach ($relatedBooks as $related)
                            <x-book-card :book="$related" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
