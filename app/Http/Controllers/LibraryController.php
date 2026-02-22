<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Download;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | Access-level constants (matches books.access_level tinyInteger)
    |----------------------------------------------------------------------
    | 0 = Free         – anyone (guest or logged-in) can read & download
    | 1 = Subscription – must be logged in with active subscription
    | 2 = Buy          – must purchase the individual book
    |----------------------------------------------------------------------
    */
    const ACCESS_FREE         = 0;
    const ACCESS_SUBSCRIPTION = 1;
    const ACCESS_BUY          = 2;

    /**
     * Check whether the current visitor may access a book.
     *
     * Returns:
     *   true                    – full access
     *   'login_required'        – guest must log in first
     *   'subscription_required' – user needs an active subscription
     *   'purchase_required'     – user must buy this book
     */
    public static function checkAccess(Book $book)
    {
        $level = (int) $book->access_level;

        // Free books – everyone can access
        if ($level === self::ACCESS_FREE) {
            return true;
        }

        // Both Subscription and Buy require login first
        if (!Auth::check()) {
            return 'login_required';
        }

        // Admins and the book's own author always have full access
        $user = Auth::user();
        if ($user->role === 'admin' || $user->id === $book->author_id) {
            return true;
        }

        // Subscription books – user needs active subscription
        if ($level === self::ACCESS_SUBSCRIPTION) {
            // TODO: check subscription status when payment is integrated
            return 'subscription_required';
        }

        // Buy books – user must have purchased this specific book
        if ($level === self::ACCESS_BUY) {
            // TODO: check purchase record when payment is integrated
            return 'purchase_required';
        }

        return 'login_required';
    }

    // -----------------------------------------------------------------
    //  Personal library pages (auth required – handled by route middleware)
    // -----------------------------------------------------------------

    /**
     * Display user's personal library (books they've interacted with)
     */
    public function index()
    {
        $user = Auth::user();

        $favoriteBookIds = Favorite::where('user_id', $user->id)->pluck('book_id');
        $readingBookIds  = ReadingProgress::where('user_id', $user->id)->pluck('book_id');
        $allBookIds      = $favoriteBookIds->merge($readingBookIds)->unique();

        $books = Book::whereIn('id', $allBookIds)
            ->where('status', 1)
            ->with(['author', 'category', 'reviews'])
            ->latest('create_at')
            ->paginate(12);

        $categories = Category::orderBy('name')->get();

        return view('user.library', compact('books', 'categories'));
    }

    /**
     * User's reading history
     */
    public function readingHistory()
    {
        $readingHistory = ReadingProgress::where('user_id', Auth::id())
            ->with(['book.author', 'book.category'])
            ->latest()
            ->paginate(12);

        return view('user.reading-history', compact('readingHistory'));
    }

    /**
     * User's favorites
     */
    public function favorites()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with(['book.author', 'book.category', 'book.reviews'])
            ->latest('create_at')
            ->paginate(12);

        return view('user.favorites', compact('favorites'));
    }

    /**
     * User's downloads
     */
    public function downloads()
    {
        $downloads = Download::where('user_id', Auth::id())
            ->with(['book.author'])
            ->latest()
            ->paginate(12);

        return view('user.downloads', compact('downloads'));
    }

    /**
     * Add book to favorites
     */
    public function addToFavorites(Request $request, $id)
    {
        $book = Book::where('status', 1)->findOrFail($id);

        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
        ]);

        return redirect()->back()->with('success', 'Book added to favorites!');
    }

    /**
     * Remove book from favorites
     */
    public function removeFromFavorites($id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Book removed from favorites!');
    }

    // -----------------------------------------------------------------
    //  Public book access (read & download) – access_level enforced
    // -----------------------------------------------------------------

    /**
     * Read book (reading interface).
     * Free books: anyone can read.
     * Subscription books: logged-in subscriber required.
     * Buy books: logged-in + purchased required.
     */
    public function read($id)
    {
        $book = Book::where('status', 1)
            ->with(['author', 'category'])
            ->findOrFail($id);

        $access = self::checkAccess($book);

        if ($access === 'login_required') {
            return redirect()->route('login')
                ->with('error', 'Please log in to read this book.')
                ->with('intended_url', route('book.read', $book->id));
        }

        if ($access === 'subscription_required') {
            return redirect()->route('book.show', $book->id)
                ->with('error', 'This book requires an active subscription to read.');
        }

        if ($access === 'purchase_required') {
            return redirect()->route('book.show', $book->id)
                ->with('error', 'You need to buy this book before reading. Price: $' . number_format($book->getPrice(), 2));
        }

        // Record reading progress for logged-in users
        if (Auth::check()) {
            ReadingProgress::updateOrCreate(
                ['user_id' => Auth::id(), 'book_id' => $book->id],
                ['status' => 'reading']
            );
        }

        return view('user.read', compact('book'));
    }

    /**
     * Download book file.
     * Same access rules as reading.
     */
    public function download($id)
    {
        $book = Book::where('status', 1)->findOrFail($id);

        $access = self::checkAccess($book);

        if ($access === 'login_required') {
            return redirect()->route('login')
                ->with('error', 'Please log in to download this book.');
        }

        if ($access === 'subscription_required') {
            return redirect()->route('book.show', $book->id)
                ->with('error', 'This book requires an active subscription to download.');
        }

        if ($access === 'purchase_required') {
            return redirect()->route('book.show', $book->id)
                ->with('error', 'You need to buy this book before downloading. Price: $' . number_format($book->getPrice(), 2));
        }

        if (!$book->file_path) {
            return redirect()->route('book.show', $book->id)
                ->with('error', 'No file available for download.');
        }

        // Record download for logged-in users
        if (Auth::check()) {
            Download::create([
                'user_id'       => Auth::id(),
                'book_id'       => $book->id,
                'file_type'     => pathinfo($book->file_path, PATHINFO_EXTENSION),
                'status'        => 'completed',
                'downloaded_at' => now(),
            ]);
        }

        // Increment download counter on book
        $book->increment('downloads');

        $filePath = storage_path('app/public/' . $book->file_path);

        if (!file_exists($filePath)) {
            return redirect()->route('book.show', $book->id)
                ->with('error', 'File not found on server.');
        }

        return response()->download($filePath, $book->title . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION));
    }
}
