<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\ReadingProgress;
use App\Models\Favorite;
use App\Models\Download;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get user-specific statistics (simplified for now)
        $stats = [
            'total_books_read' => 0,
            'currently_reading' => 0,
            'total_favorites' => 0,
            'total_downloads' => 0,
            'total_reviews' => 0,
            'reading_streak' => 0,
            'pages_read' => 0,
            'books_this_month' => 0,
        ];

        // Empty collections for now (to avoid model relationship issues)
        $recentBooks = collect([]);
        $favoriteBooks = collect([]);
        $currentlyReading = collect([]);
        $popularBooks = collect([]);

        return view('user.dashboard', compact(
            'stats',
            'recentBooks',
            'favoriteBooks',
            'currentlyReading',
            'popularBooks'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        
        // Get user's reading statistics (simplified for now)
        $readingStats = [
            'total_books' => 0,
            'total_pages' => 0,
            'favorite_genres' => 'Fiction, Mystery, Science Fiction',
            'reading_streak' => 0,
            'joined_date' => $user->created_at->format('F j, Y'),
        ];

        return view('user.profile', compact('user', 'readingStats'));
    }

    public function settings()
    {
        $user = auth()->user();
        return view('user.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'favorite_genres' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'favorite_genres' => $request->favorite_genres,
        ]);

        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }
}
