<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function home()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->limit(6)
            ->get();

        $latestBooks = Book::query()
            ->with(['author'])
            ->latest('create_at')
            ->limit(6)
            ->get();

        return view('marketing.home', [
            'categories' => $categories,
            'latestBooks' => $latestBooks,
        ]);
    }

    public function browse(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $booksQuery = Book::query()
            ->with(['author', 'tags', 'categories'])
            ->latest('create_at');

        if ($q !== '') {
            $booksQuery->where('title', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        }

        return view('marketing.browse', [
            'q' => $q,
            'books' => $booksQuery->paginate(12)->withQueryString(),
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
}

