@props(['book', 'showRating' => false, 'showAction' => false])

<a href="{{ route('book.show', $book->id) }}" class="book-card">
    <div class="book-card__cover">
        {{-- Access badge --}}
        @if ($book->isBuy())
            <span class="book-card__badge book-card__badge--buy">
                <i class="fas fa-tag"></i> {{ $book->formatted_price }}
            </span>
        @elseif ($book->isSubscription())
            <span class="book-card__badge book-card__badge--subscription">
                <i class="fas fa-crown"></i> Subscription
            </span>
        @else
            <span class="book-card__badge book-card__badge--free">Free</span>
        @endif

        @if ($book->cover_image)
            <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
        @else
            <div class="book-card__placeholder">
                <i class="fas fa-book"></i>
            </div>
        @endif

        {{-- Hover overlay --}}
        <div class="book-card__cover-overlay">
            <span class="overlay-btn">View Details</span>
        </div>
    </div>
    <div class="book-card__info">
        <h3 class="book-card__title">{{ Str::limit($book->title, 30) }}</h3>
        <p class="book-card__author">
            <i class="fas fa-user-edit"></i>
            {{ $book->author->name ?? 'Unknown' }}
        </p>
        @if ($showRating)
            <div class="book-card__rating">
                @php $avg = round($book->reviews_avg_rating ?? 0, 1); @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star" style="color: {{ $i <= $avg ? '#f59e0b' : '#d1d5db' }};font-size:0.65rem"></i>
                @endfor
                <span class="book-card__rating-num">{{ $avg }}</span>
            </div>
        @endif
    </div>
    @if ($showAction)
        <span class="book-card__action btn btn--primary btn--sm">
            @if ($book->isBuy())
                Buy {{ $book->formatted_price }} &rsaquo;
            @elseif ($book->isSubscription())
                Subscribe &rsaquo;
            @else
                Read Free &rsaquo;
            @endif
        </span>
    @endif
</a>
