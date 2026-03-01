<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    // Constructor middleware removed since routes already handle authentication and authorization

    /**
     * Display a listing of books
     */
    public function index()
    {
        $query = Book::where('author_id', auth()->user()->id)
            ->with('category');

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Apply category filter
        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        // Apply status filter
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Apply sorting
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            case 'downloads':
                $query->orderBy('downloads', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default:
                $query->latest();
        }

        $books = $query->paginate(12);
        
        // Always use author view for author routes
        return view('author.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category_id' => 'nullable|exists:category,id',
            'isbn' => 'nullable|string|max:20',
            'publisher' => 'nullable|string|max:255',
            'published_date' => 'nullable|date',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:10',
            'excerpt' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'file_path' => 'nullable|file|mimes:pdf,epub,mobi|max:10240',
            'access_level' => 'nullable|in:public,private,premium',
            'status' => 'required|integer|in:0,1',
        ], [
            'title.required' => 'Book title is required.',
            'description.required' => 'Book description is required.',
            'description.min' => 'Description must be at least 50 characters.',
            'category_id.exists' => 'Selected category is invalid.',
            'cover_image.image' => 'Cover image must be a valid image file.',
            'cover_image.mimes' => 'Cover image must be a JPEG, JPG, PNG, or GIF file.',
            'cover_image.max' => 'Cover image must not exceed 2MB.',
            'file_path.file' => 'Book file must be a valid file.',
            'file_path.mimes' => 'Book file must be a PDF, EPUB, or MOBI file.',
            'file_path.max' => 'Book file must not exceed 10MB.',
            'pages.min' => 'Pages must be at least 1.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either Draft or Published.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bookData = $request->except(['cover_image', 'file_path']);
            $bookData['author_id'] = auth()->user()->id;
            
            // Convert access_level string to integer
            $accessLevelMap = [
                'public' => 0,
                'private' => 1, 
                'premium' => 2
            ];
            $bookData['access_level'] = $accessLevelMap[$request->access_level] ?? 0;

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                $coverPath = $coverImage->store('covers', 'public');
                $bookData['cover_image'] = $coverPath;
            }

            // Handle book file upload
            if ($request->hasFile('file_path')) {
                $bookFile = $request->file('file_path');
                $filePath = $bookFile->store('books', 'public');
                $bookData['file_path'] = $filePath;
            }

            $book = Book::create($bookData);

            return response()->json([
                'success' => true,
                'message' => 'Book created successfully!',
                'book' => $book->load('category')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error creating book: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating book: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified book
     */
    public function show($id)
    {
        $book = Book::where('id', $id)
            ->where('author_id', auth()->user()->id)
            ->with(['author', 'category', 'reviews.user'])
            ->firstOrFail();

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book
     */
    public function edit($id)
    {
        $book = Book::where('id', $id)
            ->where('author_id', auth()->user()->id)
            ->firstOrFail();
            
        $categories = Category::all();
        
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, $id)
    {
        $book = Book::where('id', $id)
            ->where('author_id', auth()->user()->id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category_id' => 'nullable|exists:category,id',
            'isbn' => 'nullable|string|max:20',
            'publisher' => 'nullable|string|max:255',
            'published_date' => 'nullable|date',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:10',
            'excerpt' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'file_path' => 'nullable|file|mimes:pdf,epub,mobi|max:10240',
            'access_level' => 'nullable|in:public,private,premium',
            'status' => 'required|integer|in:0,1',
        ], [
            'title.required' => 'Book title is required.',
            'description.required' => 'Book description is required.',
            'description.min' => 'Description must be at least 50 characters.',
            'category_id.exists' => 'Selected category is invalid.',
            'cover_image.image' => 'Cover image must be a valid image file.',
            'cover_image.mimes' => 'Cover image must be a JPEG, JPG, PNG, or GIF file.',
            'cover_image.max' => 'Cover image must not exceed 2MB.',
            'file_path.file' => 'Book file must be a valid file.',
            'file_path.mimes' => 'Book file must be a PDF, EPUB, or MOBI file.',
            'file_path.max' => 'Book file must not exceed 10MB.',
            'pages.min' => 'Pages must be at least 1.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either Draft or Published.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bookData = $request->except(['cover_image', 'file_path']);
            
            // Convert access_level string to integer
            $accessLevelMap = [
                'public' => 0,
                'private' => 1, 
                'premium' => 2
            ];
            $bookData['access_level'] = $accessLevelMap[$request->access_level] ?? 0;

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                // Delete old cover image if exists
                if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                    Storage::disk('public')->delete($book->cover_image);
                }
                
                $coverImage = $request->file('cover_image');
                $coverPath = $coverImage->store('covers', 'public');
                $bookData['cover_image'] = $coverPath;
            }

            // Handle book file upload
            if ($request->hasFile('file_path')) {
                // Delete old file if exists
                if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                    Storage::disk('public')->delete($book->file_path);
                }
                
                $bookFile = $request->file('file_path');
                $filePath = $bookFile->store('books', 'public');
                $bookData['file_path'] = $filePath;
            }

            $book->update($bookData);

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully!',
                'book' => $book->load('category')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating book: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating book: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified book
     */
    public function destroy($id)
    {
        $book = Book::where('id', $id)
            ->where('author_id', auth()->user()->id)
            ->firstOrFail();

        $book->delete();

        return redirect()->route('author.books.index')->with('success', 'Book deleted successfully!');
    }

    /**
     * Get books for current author
     */
    public function myBooks()
    {
        $books = Book::where('author_id', auth()->user()->id)
            ->with('category')
            ->latest()
            ->paginate(10);
        return view('books.my-books', compact('books'));
    }

    /**
     * Get published books for current author
     */
    public function publishedBooks()
    {
        // Debug: Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = auth()->user();
        
        // Debug: Check if user is author
        if (!$user->isAuthor() && !$user->isAdmin()) {
            abort(403, 'Unauthorized access. Author privileges required.');
        }

        // Debug: Get all user books first to see what exists
        $allBooks = Book::where('author_id', $user->id)->get();
        \Log::info('All books for user ' . $user->id . ': ' . $allBooks->count());
        
        // Debug: Get published books
        $publishedBooks = Book::where('author_id', $user->id)->where('status', 1)->get();
        \Log::info('Published books for user ' . $user->id . ': ' . $publishedBooks->count());

        $query = Book::where('author_id', $user->id)
            ->where('status', 1)
            ->with(['category', 'reviews']);

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Apply category filter
        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        // Apply sorting
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            case 'downloads':
                $query->orderBy('downloads', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default:
                $query->latest();
        }

        $books = $query->paginate(12);
        
        // Debug: Log the books count
        \Log::info('Paginated published books count: ' . $books->total());
        
        // Always use author view for author routes
        return view('author.books.published', compact('books'));
    }

    /**
     * Get draft books for current author
     */
    public function draftBooks()
    {
        // Simple debug version
        try {
            // Check authentication
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            $user = auth()->user();
            
            // Check role
            if (!$user->isAuthor() && !$user->isAdmin()) {
                abort(403, 'Unauthorized access. Author privileges required.');
            }

            // Simple query for draft books
            $books = Book::where('author_id', $user->id)
                ->where('status', 0)
                ->with(['category'])
                ->latest()
                ->paginate(12);

            // Debug logging
            \Log::info('Draft books query executed successfully. Count: ' . $books->total());
            
            // Return view
            return view('author.books.drafts', compact('books'));
            
        } catch (\Exception $e) {
            \Log::error('Error in draftBooks: ' . $e->getMessage());
            return redirect()->route('author.dashboard')->with('error', 'Error loading draft books: ' . $e->getMessage());
        }
    }

    /**
     * Get book analytics for current author
     */
    public function bookAnalytics()
    {
        $books = Book::where('author_id', auth()->user()->id)
            ->with(['reviews', 'favorites'])
            ->withCount(['reviews', 'favorites'])
            ->get();

        return view('author.books.analytics', compact('books'));
    }

    /**
     * Publish a book
     */
    public function publishBook($id)
    {
        $book = Book::where('id', $id)
            ->where('author_id', auth()->user()->id)
            ->firstOrFail();

        $book->update(['status' => 1]);

        return redirect()->route('author.books.index')->with('success', 'Book published successfully!');
    }

    /**
     * Unpublish a book
     */
    public function unpublishBook($id)
    {
        $book = Book::where('id', $id)
            ->where('author_id', auth()->user()->id)
            ->firstOrFail();

        $book->update(['status' => 0]);

        return redirect()->route('author.books.index')->with('success', 'Book unpublished successfully!');
    }

    /**
     * Duplicate a book
     */
    public function duplicateBook($id)
    {
        $book = Book::where('id', $id)
            ->where('author_id', auth()->user()->id)
            ->firstOrFail();

        $newBook = $book->replicate();
        $newBook->title = $book->title . ' (Copy)';
        $newBook->status = 0; // Set as draft
        $newBook->save();

        return redirect()->route('author.books.index')->with('success', 'Book duplicated successfully!');
    }
}
