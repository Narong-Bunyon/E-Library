<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Favorite;
use App\Models\ReadingProgress;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function home()
    {
        // Categories for the categories section
        $categories = Category::query()
            ->withCount('books')
            ->orderBy('name')
            ->limit(8)
            ->get();

        // Featured books (published, public, highest rated)
        $featuredBooks = Book::query()
            ->where('status', 1)
            ->with(['author', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->limit(4)
            ->get();

        // Recently added books (published)
        $recentBooks = Book::query()
            ->where('status', 1)
            ->with(['author', 'reviews'])
            ->latest('create_at')
            ->limit(4)
            ->get();

        // Popular authors (users with role=author who have the most books)
        $popularAuthors = User::query()
            ->where('role', 'author')
            ->withCount('books')
            ->orderByDesc('books_count')
            ->limit(4)
            ->get();

        // Auth-conditional data
        $readingProgress = null;
        $recommendedBooks = collect([]);

        if (Auth::check()) {
            $user = Auth::user();

            // Current reading progress
            $readingProgress = ReadingProgress::where('user_id', $user->id)
                ->where('status', '!=', 'completed')
                ->with('book.author')
                ->latest()
                ->first();

            // Recommended books (books the user hasn't read yet, published)
            $readBookIds = ReadingProgress::where('user_id', $user->id)->pluck('book_id');
            $recommendedBooks = Book::query()
                ->where('status', 1)
                ->whereNotIn('id', $readBookIds)
                ->with(['author', 'reviews'])
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('marketing.home', [
            'categories' => $categories,
            'featuredBooks' => $featuredBooks,
            'recentBooks' => $recentBooks,
            'popularAuthors' => $popularAuthors,
            'readingProgress' => $readingProgress,
            'recommendedBooks' => $recommendedBooks,
        ]);
    }

    public function browse(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $booksQuery = Book::query()
            ->where('status', 1)
            ->with(['author', 'tags', 'categories', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest('create_at');

        if ($q !== '') {
            $booksQuery->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category !== '') {
            $booksQuery->where('category_id', $request->category);
        }

        $categories = Category::orderBy('name')->get();

        return view('marketing.browse', [
            'q' => $q,
            'books' => $booksQuery->paginate(12)->withQueryString(),
            'categories' => $categories,
        ]);
    }

    public function categories()
    {
        $categories = Category::query()
            ->withCount('books')
            ->orderBy('name')
            ->paginate(24);

        return view('marketing.categories', [
            'categories' => $categories,
        ]);
    }

    public function about()
    {
        return view('marketing.about');
    }

    /**
     * Show public book detail page.
     * Accessible to both guests and logged-in users.
     */
    public function showBook($id)
    {
        $book = Book::where('status', 1)
            ->with(['author', 'category', 'categories', 'tags', 'reviews.user'])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'favorites', 'downloads'])
            ->findOrFail($id);

        // Related books (same category)
        $relatedBooks = Book::where('status', 1)
            ->where('id', '!=', $book->id)
            ->where('category_id', $book->category_id)
            ->with(['author', 'reviews'])
            ->limit(4)
            ->get();

        // Check if current user has favorited this book
        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = Favorite::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->exists();
        }

        return view('marketing.book-detail', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
            'isFavorited' => $isFavorited,
        ]);
    }
}

