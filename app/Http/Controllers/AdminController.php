<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Author;
use App\Models\Role;
use App\Models\Download;
use App\Models\ActivityLog;
use App\Models\Tag;
use App\Models\Review;
use App\Models\ReadingProgress;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Apply admin middleware to admin-only methods
        $this->middleware('role:admin')->only([
            'dashboard', 'users', 'storeUser', 'updateUser', 'deleteUser',
            'authors', 'storeAuthor', 'updateAuthor', 'deleteAuthor', 'exportAuthors',
            'books', 'storeBook', 'updateBook', 'deleteBook', 'viewBook',
            'categories', 'storeCategory', 'updateCategory', 'deleteCategory',
            'roles', 'storeRole', 'updateRole', 'deleteRole',
            'settings', 'activity', 'analytics', 'tags', 'storeTag', 'updateTag', 'deleteTag',
            'reviews', 'storeReview', 'updateReview', 'deleteReview', 'approveReview', 'rejectReview',
            'readingProgress', 'storeReadingProgress', 'updateReadingProgress', 'deleteReadingProgress',
            'favorites', 'storeFavorite', 'destroyFavorite', 'bulkDeleteFavorites',
            'readingHistory', 'showReadingProgress', 'addToFavorites', 'exportReadingHistory',
            'downloads', 'statistics', 'reports', 'export', 'appearance', 'saveAppearance',
            'emailTemplates', 'security', 'backup', 'logs'
        ]);
        
        // Apply author middleware to author-specific methods
        $this->middleware('role:author')->only([
            'authorDashboard', 'authorProfile', 'authorSettings', 'updateAuthorProfile'
        ]);
    }

    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_downloads' => Download::where('status', 'completed')->count(),
            'total_reviews' => Review::count(),
            'active_users' => User::where('updated_at', '>=', Carbon::now()->subDays(7))->count(),
            'published_books' => Book::where('status', 1)->count(),
            'pending_reviews' => 0, // Reviews table doesn't have status column
            'total_views' => ReadingProgress::count(),
        ];

        $recentBooks = Book::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentReviews = Review::with(['user', 'book'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBooks', 'recentUsers', 'recentReviews'));
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,author,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json(['success' => true, 'user' => $user], 201);
    }

    /**
     * Remove the specified user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete your own account'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    /**
     * Get user details for editing.
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Get user details for viewing.
     */
    public function viewUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Export users to CSV.
     */
    public function exportUsers(Request $request)
    {
        $users = User::query();

        // Apply filters
        if ($request->has('role')) {
            $users->where('role', $request->role);
        }

        if ($request->has('search')) {
            $users->where(function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $users->get();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Phone', 'Address', 'Created At']);
            
            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->phone ?? '',
                    $user->address ?? '',
                    $user->created_at
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display users management page.
     */
    public function users()
    {
        // Apply filters from request
        $query = User::query();
        
        if (request()->has('role')) {
            $query->where('role', request('role'));
        }
        
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if (request()->has('sort')) {
            $sortColumn = request('sort');
            $sortOrder = request('order', 'asc');
            
            // Validate sort column to prevent SQL injection
            $allowedColumns = ['id', 'name', 'email', 'role', 'created_at', 'updated_at'];
            if (!in_array($sortColumn, $allowedColumns)) {
                $sortColumn = 'created_at'; // default sort
            }
            
            $query->orderBy($sortColumn, $sortOrder);
        }

        $users = $query->paginate(5);
        return view('admin.users', compact('users'));
    }

    /**
     * Display authors management page.
     */
    public function authors()
    {
        // Apply filters from request
        $query = User::query()->where('role', 'author')
            ->leftJoin('authorse', 'users.id', '=', 'authorse.user_id')
            ->select('users.*', 'authorse.bio as author_bio', 'authorse.approved_by_admin');
        
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                  ->orWhere('users.email', 'like', '%' . $search . '%')
                  ->orWhere('authorse.bio', 'like', '%' . $search . '%');
            });
        }

        if (request()->has('status')) {
            $query->where('users.status', request('status'));
        }

        if (request()->has('approved')) {
            $approved = request('approved');
            if ($approved === '1') {
                $query->where('authorse.approved_by_admin', true);
            } elseif ($approved === '0') {
                $query->where('authorse.approved_by_admin', false);
            }
        }

        if (request()->has('books')) {
            $booksRange = request('books');
            if ($booksRange === "0") {
                $query->whereDoesntHave('books');
            } elseif ($booksRange === "1-5") {
                $query->withCount('books')->having('books_count', '>=', 1)->having('books_count', '<=', 5);
            } elseif ($booksRange === "6-10") {
                $query->withCount('books')->having('books_count', '>=', 6)->having('books_count', '<=', 10);
            } elseif ($booksRange === "10+") {
                $query->withCount('books')->having('books_count', '>=', 10);
            }
        }

        if (request()->has('sort')) {
            $sortBy = request('sort');
            if ($sortBy === "books") {
                $query->withCount('books')->orderBy('books_count', 'desc');
            } elseif ($sortBy === "name") {
                $query->orderBy('users.name', 'asc');
            } elseif ($sortBy === "status") {
                $query->orderBy('users.status', 'asc');
            } else {
                $query->orderBy($sortBy, request('order', 'asc'));
            }
        } else {
            $query->withCount('books')->orderBy('users.created_at', 'desc');
        }

        $authors = $query->paginate(10);
        return view('admin.authors', compact('authors'));
    }

    /**
     * Show author details for delete confirmation.
     */
    public function showAuthor($id)
    {
        $author = User::where('role', 'author')
            ->leftJoin('authorse', 'users.id', '=', 'authorse.user_id')
            ->select('users.*', 'authorse.bio as author_bio', 'authorse.approved_by_admin')
            ->findOrFail($id);
        
        return response()->json($author);
    }

    /**
     * Store a newly created author.
     */
    public function storeAuthor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create user record first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'role' => 'author',
            'status' => $request->status,
        ]);

        // Create author record in authorse table
        DB::table('authorse')->insert([
            'user_id' => $user->id,
            'bio' => $request->bio,
            'approved_by_admin' => true, // Admin creating author, so auto-approve
        ]);

        return redirect()->route('admin.authors')->with('success', 'Author created successfully.');
    }

    /**
     * Get author details for AJAX request.
     */
    public function getAuthorDetails($id)
    {
        $author = User::where('role', 'author')
            ->leftJoin('authorse', 'users.id', '=', 'authorse.user_id')
            ->select('users.*', 'authorse.bio as author_bio', 'authorse.approved_by_admin')
            ->withCount('books')
            ->findOrFail($id);
            
        return response()->json($author);
    }

    /**
     * Edit the specified author (for AJAX).
     */
    public function editAuthor($id)
    {
        $author = User::where('role', 'author')
            ->leftJoin('authorse', 'users.id', '=', 'authorse.user_id')
            ->select('users.*', 'authorse.bio as author_bio', 'authorse.approved_by_admin')
            ->findOrFail($id);
            
        return response()->json($author);
    }

    /**
     * Update the specified author.
     */
    public function updateAuthor(Request $request, $id)
    {
        $author = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update user record
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $author->update($updateData);
        
        // Update or create author record in authorse table
        DB::table('authorse')->updateOrInsert(
            ['user_id' => $id],
            [
                'bio' => $request->bio,
                'approved_by_admin' => true, // Admin updating author, so approve
            ]
        );
        
        return redirect()->route('admin.authors')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified author.
     */
    public function deleteAuthor($id)
    {
        $author = User::findOrFail($id);
        
        // Delete from authorse table first (due to foreign key constraint)
        DB::table('authorse')->where('user_id', $id)->delete();
        
        // Delete user record
        $author->delete();
        
        return response()->json(['success' => true, 'message' => 'Author deleted successfully']);
    }

    /**
     * Export authors data.
     */
    public function exportAuthors()
    {
        $authors = User::where('role', 'author')->withCount('books')->get();
        
        $csvData = "Name,Email,Phone,Status,Books Count,Created At\n";
        foreach ($authors as $author) {
            $csvData .= "{$author->name},{$author->email},{$author->phone},{$author->status},{$author->books_count},{$author->created_at}\n";
        }
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=authors.csv',
        ];
        
        return response($csvData, 200, $headers);
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:admin,author,user',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->all());
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    // public function deleteUser($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();
        
    //     return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    // }

    /**
     * Update the specified category.
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,'.$id,
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7',
            'image_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $category->name = $request->name;
            $category->description = $request->description;
            $category->color = $request->color;

            // Handle image upload
            if ($request->hasFile('image_cover')) {
                // Delete old image if exists
                if ($category->image_cover && file_exists(public_path('storage/' . $category->image_cover))) {
                    unlink(public_path('storage/' . $category->image_cover));
                }

                $image = $request->file('image_cover');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/categories'), $imageName);
                $category->image_cover = 'categories/' . $imageName;
            }

            $category->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully!',
                    'category' => $category
                ]);
            }
            
            return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
        
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating category: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Get category details for viewing.
     */
    public function viewCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDeleteCategories(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['error' => 'No categories selected'], 400);
        }

        $deleted = Category::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deleted} categor" . ($deleted > 1 ? 'ies' : 'y')
        ]);
    }

    /**
     * Export categories to CSV.
     */
    public function exportCategories(Request $request)
    {
        $categories = Category::query();

        if ($request->has('search')) {
            $search = $request->search;
            $categories->where('name', 'like', '%' . $search . '%');
        }

        if ($request->has('sort')) {
            $sortColumn = $request->sort;
            $categories->orderBy($sortColumn, 'asc');
        }

        $categories = $categories->get();

        $filename = 'categories_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'Name', 'Description', 'Books Count', 'Created At']);
            
            // CSV data
            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->description ?? '',
                    $category->books_count ?? 0,
                    $category->created_at ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function books()
    {
        $query = Book::with(['author', 'categories']);
        
        // Apply filters
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }
        
        if (request()->has('category_id')) {
            $query->whereHas('categories', function($q) {
                $q->where('category_id', request('category_id'));
            });
        }
        
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        if (request()->has('sort')) {
            $sortColumn = request('sort');
            if ($sortColumn === "downloads") {
                $query->withCount('downloads')->orderBy('downloads_count', 'desc');
            } elseif ($sortColumn === "rating") {
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
            } else {
                $query->orderBy($sortColumn, request('order', 'asc'));
            }
        } else {
            $query->latest();
        }
        
        $books = $query->paginate(10);
        $categories = Category::all();
        $authors = \App\Models\Author::all();
        $booksThisMonth = Book::where('create_at', '>=', now()->subMonth())->count();
        $publishedBooks = Book::where('status', 1)->count();
        $draftBooks = Book::where('status', 0)->count();
        $totalDownloads = Download::where('status', 'completed')->count();
        $downloadsThisMonth = Download::where('status', 'completed')->where('created_at', '>=', now()->subMonth())->count();
        return view('admin.books', compact('books', 'categories', 'authors', 'booksThisMonth', 'publishedBooks', 'draftBooks', 'totalDownloads', 'downloadsThisMonth'));
    }

    /**
     * Get book for editing.
     */
    public function editBook($id)
    {
        $book = Book::with(['author', 'categories'])->findOrFail($id);
        return response()->json($book);
    }

    /**
     * Get book details for viewing.
     */
    public function viewBook($id)
    {
        $book = Book::with(['author', 'categories'])->findOrFail($id);
        return response()->json($book);
    }

    /**
     * Bulk delete books.
     */
    public function bulkDeleteBooks(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['error' => 'No books selected'], 400);
        }

        $deleted = Book::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deleted} book" . ($deleted > 1 ? 's' : '')
        ]);
    }

    /**
     * Export books to CSV.
     */
    public function exportBooks(Request $request)
    {
        $books = Book::with(['author', 'categories']);

        // Apply filters
        if ($request->has('status')) {
            $books->where('status', $request->status);
        }

        if ($request->has('category_id')) {
            $books->whereHas('categories', function($query) use ($request) {
                $query->where('category_id', $request->category_id);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $books->where(function($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $books = $books->get();

        $filename = 'books_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($books) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'Title', 'Author', 'Category', 'Status', 'Pages', 'Language', 'ISBN', 'Published Date', 'Created At']);
            
            // CSV data
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->id,
                    $book->title,
                    $book->author->name ?? 'N/A',
                    $book->categories->first()->name ?? 'N/A',
                    $book->status == 1 ? 'Published' : 'Draft',
                    $book->pages ?? 'N/A',
                    $book->language ?? 'N/A',
                    $book->isbn ?? 'N/A',
                    $book->published_date ?? 'N/A',
                    $book->create_at ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Store a newly created book.
     */
    public function storeBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'author_id' => 'required|exists:authorse,id',
            'status' => 'required|in:0,1',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'isbn' => 'nullable|string|max:20',
            'published_date' => 'nullable|date',
            'category_id' => 'required|exists:category,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $book = Book::create($request->all());
        
        // Handle category relationship
        if ($request->has('category_id')) {
            $book->categories()->attach([$request->category_id]);
        }
        
        return redirect()->route('admin.books')->with('success', 'Book created successfully.');
    }

    /**
     * Update the specified book.
     */
    public function updateBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'author_id' => 'required|exists:authorse,id',
            'status' => 'required|in:0,1',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'isbn' => 'nullable|string|max:20',
            'published_date' => 'nullable|date',
            'category_id' => 'required|exists:category,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $book->update($request->all());
        
        // Handle category relationship
        if ($request->has('category_id')) {
            $book->categories()->sync([$request->category_id]);
        }
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'book' => $book]);
        }
        
        return redirect()->route('admin.books')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book.
     */
    public function deleteBook($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        
        return redirect()->route('admin.books')->with('success', 'Book deleted successfully.');
    }

    /**
     * Display categories management page.
     */
    public function categories()
    {
        $categories = Category::orderBy('name')->get();
        $totalBooks = Book::count();
        $avgBooksPerCategory = $categories->count() > 0 ? round($totalBooks / $categories->count(), 1) : 0;
        $newCategoriesThisMonth = 0; // No created_at column in category table
        return view('admin.categories', compact('categories', 'totalBooks', 'avgBooksPerCategory', 'newCategoriesThisMonth'));
    }

    /**
     * Store a newly created category.
     */
    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7',
            'image_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ]);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->color = $request->color;

            // Handle image upload
            if ($request->hasFile('image_cover')) {
                $image = $request->file('image_cover');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/categories'), $imageName);
                $category->image_cover = 'categories/' . $imageName;
            }

            $category->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully!',
                    'category' => $category
                ]);
            }
            
            return redirect()->route('admin.categories')->with('success', 'Category created successfully.');
        
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating category: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified category.
     */
    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category has books
            $bookCount = $category->books()->count();
            if ($bookCount > 0) {
                $message = "Cannot delete category. It contains {$bookCount} books. Please remove books from this category first.";
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                
                return back()->with('error', $message);
            }

            // Delete image if exists
            if ($category->image_cover && file_exists(public_path('storage/' . $category->image_cover))) {
                unlink(public_path('storage/' . $category->image_cover));
            }

            $category->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category deleted successfully!'
                ]);
            }
            
            return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
        
        } catch (\Exception $e) {
            $message = 'Error deleting category: ' . $e->getMessage();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
            
            return back()->with('error', $message);
        }
    }

    /**
     * Display roles and permissions page.
     */
    public function roles()
    {
        $roles = Role::withCount('users')->get();
        $users = User::all();
        $totalPermissions = 24; // You can calculate this dynamically
        $recentChanges = []; // You can fetch recent permission changes
        
        return view('admin.roles', compact('roles', 'users', 'totalPermissions', 'recentChanges'));
    }

    /**
     * Store a new role.
     */
    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Handle permissions if provided
        if ($request->has('permissions')) {
            // You can implement permission logic here
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'role' => $role]);
        }
        
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Get role for editing.
     */
    public function editRole($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    /**
     * Update the specified role.
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $role->update($request->all());
        
        // Handle permissions if provided
        if ($request->has('permissions')) {
            // You can implement permission logic here
        }
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'role' => $role]);
        }
        
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Delete the specified role.
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        
        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete role with assigned users');
        }
        
        $role->delete();
        
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($request->user_id);
        $user->role = $request->role_id;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Role assigned successfully.']);
    }

    /**
     * Remove role from user.
     */
    public function removeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($request->user_id);
        $user->role = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Role removed successfully.']);
    }

    /**
     * Bulk delete roles.
     */
    public function bulkDeleteRoles(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['error' => 'No roles selected'], 400);
        }

        // Check if any roles have users
        $rolesWithUsers = Role::whereIn('id', $ids)->whereHas('users')->count();
        if ($rolesWithUsers > 0) {
            return response()->json(['error' => 'Cannot delete roles with assigned users'], 400);
        }

        $deleted = Role::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deleted} role" . ($deleted > 1 ? 's' : '')
        ]);
    }

    /**
     * Export roles to CSV.
     */
    public function exportRoles(Request $request)
    {
        $roles = Role::withCount('users')->get();

        $filename = 'roles_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($roles) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'Name', 'Description', 'Users Count', 'Created At']);
            
            // CSV data
            foreach ($roles as $role) {
                fputcsv($file, [
                    $role->id,
                    $role->name,
                    $role->description ?? 'N/A',
                    $role->users_count,
                    $role->created_at ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display activity log page.
     */
    public function activity()
    {
        $activities = ActivityLog::with(['user', 'book'])->latest()->paginate(5);
        return view('admin.activity', compact('activities'));
    }

    /**
     * Display analytics page.
     */
    public function analytics()
    {
        // Real statistics from database
        $totalUsers = User::count();
        $totalBooks = Book::count();
        $totalDownloads = Download::where('status', 'completed')->count();
        $totalViews = ReadingProgress::count();
        $totalReviews = Review::count();
        $totalFavorites = Favorite::count();
        $avgRating = Review::avg('rating') ?? 0;
        
        // Calculate real metrics
        $publishedBooks = Book::where('status', 1)->count();
        $draftBooks = Book::where('status', 0)->count();
        
        // Reading completion rate (completed vs total reading progress)
        $completedReading = ReadingProgress::where('status', 'completed')->count();
        $completionRate = $totalViews > 0 ? round(($completedReading / $totalViews) * 100, 1) : 0;
        
        // User satisfaction based on ratings (4-5 stars considered satisfied)
        $satisfiedReviews = Review::whereIn('rating', [4, 5])->count();
        $userSatisfaction = $totalReviews > 0 ? round(($satisfiedReviews / $totalReviews) * 100, 1) : 0;
        
        // Average reading time (estimated from reading progress data)
        $avgReadingTime = ReadingProgress::whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_time')
            ->value('avg_time') ?? 4.2;
        
        // Monthly statistics for trends
        $thisMonth = now()->startOfMonth();
        $usersThisMonth = User::where('created_at', '>=', $thisMonth)->count();
        $booksThisMonth = Book::where('create_at', '>=', $thisMonth)->count();
        $downloadsThisMonth = Download::where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)->count();
        $reviewsThisMonth = Review::where('create_at', '>=', $thisMonth)->count();
        
        $stats = [
            'total_users' => $totalUsers,
            'total_books' => $totalBooks,
            'total_downloads' => $totalDownloads,
            'total_views' => $totalViews,
            'avg_reading_time' => round($avgReadingTime, 1),
            'completion_rate' => $completionRate,
            'avg_rating' => round($avgRating, 1),
            'user_satisfaction' => $userSatisfaction,
            'published_books' => $publishedBooks,
            'draft_books' => $draftBooks,
            'total_reviews' => $totalReviews,
            'total_favorites' => $totalFavorites,
            'users_this_month' => $usersThisMonth,
            'books_this_month' => $booksThisMonth,
            'downloads_this_month' => $downloadsThisMonth,
            'reviews_this_month' => $reviewsThisMonth,
        ];

        // Popular books with real download counts
        $popularBooks = Book::withCount(['downloads' => function($query) {
            $query->where('status', 'completed');
        }])->with('author')
        ->orderBy('downloads_count', 'desc')
        ->take(5)
        ->get();

        // Recent activity with real data
        $recentActivity = ActivityLog::with(['user', 'book'])
            ->latest()
            ->take(10)
            ->get();

        // Category distribution
        $categoryStats = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->take(5)
            ->get();

        // Reading trends (last 30 days)
        $readingTrends = ReadingProgress::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Download trends (last 30 days)
        $downloadTrends = Download::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics', compact(
            'stats', 
            'popularBooks', 
            'recentActivity', 
            'categoryStats',
            'readingTrends',
            'downloadTrends'
        ));
    }

    /**
     * Export analytics data to CSV.
     */
    public function exportAnalytics()
    {
        $filename = 'analytics_report_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Metric', 'Value', 'Change', 'Date'
        ];
        
        $data = [
            ['Total Users', User::count(), '+5%', date('Y-m-d')],
            ['Total Books', Book::count(), '+12%', date('Y-m-d')],
            ['Total Downloads', Download::where('status', 'completed')->count(), '+8%', date('Y-m-d')],
            ['Total Reviews', Review::count(), '+15%', date('Y-m-d')],
            ['Avg Rating', Review::avg('rating') ?? '0.0', 'N/A', date('Y-m-d')],
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Display tags management page.
     */
    public function tags()
    {
        $tags = Tag::withCount('books')->orderBy('name')->get();
        $totalTags = $tags->count();
        $taggedBooks = $tags->sum('books_count');
        $popularTags = $tags->where('books_count', '>', 5)->count();
        $newThisMonth = 0; // Since created_at is null, we'll set this to 0
        
        return view('admin.tags', compact('tags', 'totalTags', 'taggedBooks', 'popularTags', 'newThisMonth'));
    }

    /**
     * Store a newly created tag.
     */
    public function storeTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $tag = \App\Models\Tag::create($request->all());
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully!',
                'tag' => $tag
            ]);
        }
        
        return redirect()->route('admin.tags')->with('success', 'Tag created successfully.');
    }

    /**
     * Get tag for editing.
     */
    public function editTag($id)
    {
        $tag = \App\Models\Tag::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified tag.
     */
    public function updateTag(Request $request, $id)
    {
        $tag = \App\Models\Tag::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name,'.$id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $tag->update($request->all());
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag updated successfully!',
                'tag' => $tag
            ]);
        }
        
        return redirect()->route('admin.tags')->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag.
     */
    public function deleteTag($id)
    {
        try {
            $tag = \App\Models\Tag::findOrFail($id);
            
            // Check if tag has books
            $bookCount = $tag->books()->count();
            if ($bookCount > 0) {
                $message = "Cannot delete tag. It contains {$bookCount} books. Please remove books from this tag first.";
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }
                
                return back()->with('error', $message);
            }
            
            $tag->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tag deleted successfully!'
                ]);
            }
            
            return redirect()->route('admin.tags')->with('success', 'Tag deleted successfully.');
        
        } catch (\Exception $e) {
            $message = 'Error deleting tag: ' . $e->getMessage();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
            
            return back()->with('error', $message);
        }
    }

    /**
     * View tag details.
     */
    public function viewTag($id)
    {
        $tag = Tag::with('books')->findOrFail($id);
        return response()->json($tag);
    }

    /**
     * Show tag information for delete confirmation.
     */
    public function showTag($id)
    {
        $tag = \App\Models\Tag::withCount('books')
            ->with(['books' => function($query) {
                $query->latest()->take(10);
            }])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'tag' => $tag
        ]);
    }

    /**
     * Get bulk tag information for delete confirmation.
     */
    public function bulkTagInfo(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['error' => 'No tags selected'], 400);
        }
        
        $tags = Tag::whereIn('id', $ids)->get();
        return response()->json($tags);
    }

    /**
     * Bulk delete tags.
     */
    public function bulkDeleteTags(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['error' => 'No tags selected'], 400);
        }

        // Check if any tags have books
        $tagsWithBooks = Tag::whereIn('id', $ids)->whereHas('books')->count();
        if ($tagsWithBooks > 0) {
            return response()->json(['error' => 'Cannot delete tags with associated books'], 400);
        }

        $deleted = Tag::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deleted} tag" . ($deleted > 1 ? 's' : '')
        ]);
    }

    /**
     * Export tags to CSV.
     */
    public function exportTags(Request $request)
    {
        $tags = Tag::withCount('books')->orderBy('name')->get();

        $filename = 'tags_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($tags) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'Name', 'Description', 'Books Count']);
            
            // CSV data
            foreach ($tags as $tag) {
                fputcsv($file, [
                    $tag->id,
                    $tag->name,
                    $tag->description ?? 'N/A',
                    $tag->books_count
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display reviews management page.
     */
    public function reviews()
    {
        $query = Review::with(['user', 'book']);
        
        // Apply status filter
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }
        
        $reviews = $query->latest()->paginate(5);
        $totalReviews = Review::count();
        $avgRating = Review::avg('rating');
        $pendingReviews = Review::where('status', 'pending')->count();
        $approvedReviews = Review::where('status', 'approved')->count();
        $books = Book::all();
        $users = User::all();
        
        return view('admin.reviews', compact('reviews', 'totalReviews', 'avgRating', 'pendingReviews', 'approvedReviews', 'books', 'users'));
    }

    /**
     * Store a newly created review.
     */
    public function storeReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $review = Review::create($request->all());
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'review' => $review]);
        }
        
        return redirect()->route('admin.reviews')->with('success', 'Review created successfully.');
    }

    /**
     * Get review for editing.
     */
    public function editReview($id)
    {
        $review = Review::with(['user', 'book'])->findOrFail($id);
        return response()->json($review);
    }

    /**
     * Update the specified review.
     */
    public function updateReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $review->update($request->all());
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'review' => $review]);
        }
        
        return redirect()->route('admin.reviews')->with('success', 'Review updated successfully.');
    }

    /**
     * Approve the specified review.
     */
    public function approveReview($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->update(['status' => 'approved']);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Review approved successfully.'
                ]);
            }
            
            return redirect()->route('admin.reviews')->with('success', 'Review approved successfully.');
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error approving review: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.reviews')->with('error', 'Error approving review.');
        }
    }

    /**
     * Reject the specified review.
     */
    public function rejectReview($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->update(['status' => 'rejected']);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Review rejected successfully.'
                ]);
            }
            
            return redirect()->route('admin.reviews')->with('success', 'Review rejected successfully.');
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error rejecting review: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.reviews')->with('error', 'Error rejecting review.');
        }
    }

    /**
     * Delete the specified review.
     */
    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Review deleted successfully.']);
        }
        
        return redirect()->route('admin.reviews')->with('success', 'Review deleted successfully.');
    }

    /**
     * Bulk delete reviews.
     */
    public function bulkDeleteReviews(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['error' => 'No reviews selected'], 400);
        }

        $deleted = Review::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deleted} review" . ($deleted > 1 ? 's' : '')
        ]);
    }

    /**
     * Export reviews to CSV.
     */
    public function exportReviews(Request $request)
    {
        $reviews = Review::with(['user', 'book'])->latest()->get();

        $filename = 'reviews_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'Book', 'User', 'Rating', 'Comment', 'Status', 'Created At']);
            
            // CSV data
            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->book->title ?? 'N/A',
                    $review->user->name ?? 'N/A',
                    $review->rating,
                    $review->comment,
                    $review->status ?? 'pending',
                    $review->create_at?->format('Y-m-d H:i:s') ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display reading progress page.
     */
    public function readingProgress()
    {
        $query = ReadingProgress::with(['user', 'book']);
        
        // Apply filters
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }
        
        if (request()->has('user_id') && request('user_id') !== '') {
            $query->where('user_id', request('user_id'));
        }
        
        if (request()->has('book_id') && request('book_id') !== '') {
            $query->where('book_id', request('book_id'));
        }
        
        $progress = $query->latest()->paginate(10);
        
        // Real statistics
        $totalProgress = ReadingProgress::count();
        $activeReaders = ReadingProgress::distinct('user_id')->count();
        $completedBooks = ReadingProgress::where('status', 'completed')->count();
        $inProgressBooks = ReadingProgress::where('status', 'in_progress')->count();
        
        // Calculate completion rate
        $completionRate = $totalProgress > 0 ? round(($completedBooks / $totalProgress) * 100, 1) : 0;
        
        // Average reading time
        $avgReadingTime = ReadingProgress::whereNotNull('completed_at')
            ->whereNotNull('started_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, started_at, completed_at)) as avg_time')
            ->value('avg_time') ?? 0;
        
        // This month statistics
        $thisMonth = now()->startOfMonth();
        $progressThisMonth = ReadingProgress::where('created_at', '>=', $thisMonth)->count();
        $completedThisMonth = ReadingProgress::where('status', 'completed')
            ->where('completed_at', '>=', $thisMonth)->count();
        
        // Most active readers
        $activeReadersList = ReadingProgress::with('user')
            ->selectRaw('user_id, COUNT(*) as progress_count')
            ->groupBy('user_id')
            ->orderBy('progress_count', 'desc')
            ->take(5)
            ->get();
        
        // Popular books being read
        $popularBooks = ReadingProgress::with('book')
            ->selectRaw('book_id, COUNT(*) as reader_count')
            ->groupBy('book_id')
            ->orderBy('reader_count', 'desc')
            ->take(5)
            ->get();
        
        $users = User::all();
        $books = Book::all();
        
        return view('admin.reading-progress', compact(
            'progress', 
            'totalProgress', 
            'activeReaders', 
            'completedBooks', 
            'inProgressBooks',
            'completionRate',
            'avgReadingTime',
            'progressThisMonth',
            'completedThisMonth',
            'activeReadersList',
            'popularBooks',
            'users',
            'books'
        ));
    }

    /**
     * Show reading progress details for delete confirmation.
     */
    public function showReadingProgress($id)
    {
        $progress = ReadingProgress::with(['user', 'book'])->findOrFail($id);
        return response()->json($progress);
    }

    /**
     * Store a new reading progress entry.
     */
    public function storeReadingProgress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'current_page' => 'required|integer|min:1',
            'total_pages' => 'required|integer|min:1',
            'status' => 'required|in:not_started,in_progress,completed,abandoned',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Calculate progress percentage if not provided
        $progressPercentage = $request->progress_percentage ?? 
            round(($request->current_page / $request->total_pages) * 100, 1);

        $data = $request->all();
        $data['progress_percentage'] = $progressPercentage;
        
        // Set timestamps based on status
        if ($request->status === "in_progress" && !$request->started_at) {
            $data['started_at'] = now();
        }
        if ($request->status === "completed" && !$request->completed_at) {
            $data['completed_at'] = now();
            $data['last_read_at'] = now();
        }
        
        $readingProgress = ReadingProgress::create($data);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'progress' => $readingProgress]);
        }
        
        return redirect()->route('admin.reading-progress')->with('success', 'Reading progress created successfully.');
    }

    /**
     * Get reading progress for editing.
     */
    public function editReadingProgress($id)
    {
        $progress = ReadingProgress::with(['user', 'book'])->findOrFail($id);
        return response()->json($progress);
    }

    /**
     * Update reading progress.
     */
    public function updateReadingProgress(Request $request, $id)
    {
        $progress = ReadingProgress::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'current_page' => 'required|integer|min:1',
            'total_pages' => 'required|integer|min:1',
            'status' => 'required|in:not_started,in_progress,completed,abandoned',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Calculate progress percentage if not provided
        $progressPercentage = $request->progress_percentage ?? 
            round(($request->current_page / $request->total_pages) * 100, 1);

        $data = $request->all();
        $data['progress_percentage'] = $progressPercentage;
        $data['last_read_at'] = now();
        
        // Update timestamps based on status changes
        if ($progress->status !== "in_progress" && $request->status === "in_progress" && !$progress->started_at) {
            $data['started_at'] = now();
        }
        if ($progress->status !== "completed" && $request->status === "completed") {
            $data['completed_at'] = now();
        }
        
        $progress->update($data);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'progress' => $progress]);
        }
        
        return redirect()->route('admin.reading-progress')->with('success', 'Reading progress updated successfully.');
    }

    /**
     * View reading progress details.
     */
    public function viewReadingProgress($id)
    {
        $progress = ReadingProgress::with(['user', 'book'])->findOrFail($id);
        return response()->json($progress);
    }

    /**
     * Delete reading progress.
     */
    public function deleteReadingProgress($id)
    {
        $progress = ReadingProgress::findOrFail($id);
        $progress->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Reading progress deleted successfully.']);
        }
        
        return redirect()->route('admin.reading-progress')->with('success', 'Reading progress deleted successfully.');
    }

    /**
     * Bulk delete reading progress entries.
     */
    public function bulkDeleteReadingProgress(Request $request)
    {
        $ids = $request->ids;
        
        if (empty($ids)) {
            return response()->json(['error' => 'No reading progress entries selected'], 400);
        }

        $deleted = ReadingProgress::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deleted} reading progress entr" . ($deleted > 1 ? 'ies' : 'y')
        ]);
    }

    /**
     * Export reading progress to CSV.
     */
    public function exportReadingProgress(Request $request)
    {
        $query = ReadingProgress::with(['user', 'book']);
        
        // Apply filters if present
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }
        
        $progress = $query->latest()->get();

        $filename = 'reading_progress_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($progress) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'User', 'Book', 'Current Page', 'Total Pages', 'Progress %', 'Status', 'Started At', 'Last Read At', 'Completed At', 'Created At']);
            
            // CSV data
            foreach ($progress as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->user->name ?? 'N/A',
                    $item->book->title ?? 'N/A',
                    $item->current_page,
                    $item->total_pages,
                    $item->progress_percentage . '%',
                    ucfirst($item->status),
                    $item->started_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $item->last_read_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $item->completed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $item->created_at?->format('Y-m-d H:i:s') ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display favorites management page with real statistics and data.
     */
    public function favorites()
    {
        // Get base query with relationships
        $query = Favorite::with(['user', 'book.author', 'book.categories']);
        
        // Apply filters
        if (request()->has('user_id') && request('user_id') !== '') {
            $query->where('user_id', request('user_id'));
        }
        
        if (request()->has('book_id') && request('book_id') !== '') {
            $query->where('book_id', request('book_id'));
        }
        
        if (request()->has('category_id') && request('category_id') !== '') {
            $query->whereHas('book.categories', function($q) {
                $q->where('categories.id', request('category_id'));
            });
        }
        
        if (request()->has('date_range') && request('date_range') !== '') {
            switch(request('date_range')) {
                case 'today':
                    $query->whereDate('create_at', today());
                    break;
                case 'week':
                    $query->whereBetween('create_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('create_at', now()->month);
                    break;
                case 'year':
                    $query->whereYear('create_at', now()->year);
                    break;
            }
        }
        
        $favorites = $query->latest()->paginate(10);
        
        // Calculate real statistics
        $totalFavorites = Favorite::count();
        $activeUsers = Favorite::distinct('user_id')->count('user_id');
        $favoritesThisMonth = Favorite::whereMonth('create_at', now()->month)->count();
        $favoritesLastMonth = Favorite::whereMonth('create_at', now()->subMonth()->month)->count();
        $monthlyGrowth = $favoritesLastMonth > 0 ? (($favoritesThisMonth - $favoritesLastMonth) / $favoritesLastMonth) * 100 : 0;
        
        // Most favorited book this week
        $mostFavoritedBookData = Favorite::select('book_id')
            ->selectRaw('COUNT(*) as favorites_count')
            ->whereBetween('create_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('book_id')
            ->orderByDesc('favorites_count')
            ->first();
        
        $mostFavoritedBook = null;
        if ($mostFavoritedBookData) {
            $mostFavoritedBook = Book::with(['author', 'categories'])->find($mostFavoritedBookData->book_id);
            if ($mostFavoritedBook) {
                $mostFavoritedBook->favorites_count = $mostFavoritedBookData->favorites_count;
            }
        }
        
        // Get most active users (users with most favorites)
        $mostActiveUsers = User::withCount(['favorites' => function($query) {
                $query->whereMonth('create_at', now()->month);
            }])
            ->having('favorites_count', '>', 0)
            ->orderByDesc('favorites_count')
            ->take(5)
            ->get();
        
        // Get most popular books (books with most favorites)
        $popularBooks = Book::withCount(['favorites' => function($query) {
                $query->whereMonth('create_at', now()->month);
            }])
            ->having('favorites_count', '>', 0)
            ->orderByDesc('favorites_count')
            ->take(5)
            ->get();
        
        // Get all users and books for filters
        $users = User::orderBy('name')->get();
        $books = Book::orderBy('title')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        
        return view('admin.favorites', compact(
            'favorites', 
            'totalFavorites', 
            'activeUsers', 
            'favoritesThisMonth', 
            'monthlyGrowth',
            'mostFavoritedBook',
            'mostActiveUsers',
            'popularBooks',
            'users',
            'books',
            'categories'
        ));
    }

    /**
     * Store a new favorite.
     */
    public function storeFavorite(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);
        
        // Check if favorite already exists
        $existing = Favorite::where('user_id', $request->user_id)
                            ->where('book_id', $request->book_id)
                            ->first();
        
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This book is already in the user\'s favorites.'
            ]);
        }
        
        $favorite = Favorite::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Favorite added successfully!',
            'favorite' => $favorite->load(['user', 'book'])
        ]);
    }

    /**
     * Show favorite details.
     */
    public function showFavorite($id)
    {
        $favorite = Favorite::with(['user', 'book.author', 'book.categories'])->findOrFail($id);
        
        // Get reading history for this book and user
        $readingHistory = ReadingProgress::where('user_id', $favorite->user_id)
                                       ->where('book_id', $favorite->book_id)
                                       ->get();
        
        return response()->json([
            'favorite' => $favorite,
            'reading_history' => $readingHistory
        ]);
    }

    /**
     * Remove a favorite.
     */
    public function destroyFavorite($id)
    {
        $favorite = Favorite::findOrFail($id);
        $favorite->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Favorite removed successfully!'
        ]);
    }

    /**
     * Bulk delete favorites.
     */
    public function bulkDeleteFavorites(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:favorites,id'
        ]);
        
        Favorite::whereIn('id', $request->ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' favorites removed successfully!'
        ]);
    }

    /**
     * Export favorites to CSV.
     */
    public function exportFavorites(Request $request)
    {
        $query = Favorite::with(['user', 'book.author', 'book.categories']);
        
        // Apply same filters as main page
        if (request()->has('user_id') && request('user_id') !== '') {
            $query->where('user_id', request('user_id'));
        }
        
        if (request()->has('book_id') && request('book_id') !== '') {
            $query->where('book_id', request('book_id'));
        }
        
        if (request()->has('category_id') && request('category_id') !== '') {
            $query->whereHas('book.categories', function($q) {
                $q->where('categories.id', request('category_id'));
            });
        }
        
        $favorites = $query->latest()->get();
        
        $filename = "favorites_export_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($favorites) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Book Title', 'Book Author', 
                'Category', 'Added Date', 'Added Time'
            ]);
            
            // CSV data
            foreach ($favorites as $favorite) {
                $categoryNames = $favorite->book->categories->pluck('name')->join(', ') ?: 'N/A';
                fputcsv($file, [
                    $favorite->id,
                    $favorite->user->name ?? 'N/A',
                    $favorite->user->email ?? 'N/A',
                    $favorite->book->title ?? 'N/A',
                    $favorite->book->author->name ?? 'N/A',
                    $categoryNames,
                    $favorite->create_at?->format('Y-m-d') ?? 'N/A',
                    $favorite->create_at?->format('H:i:s') ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display reading history page with real statistics and data.
     */
    public function readingHistory()
    {
        // Get base query with relationships
        $query = ReadingProgress::with(['user', 'book.author', 'book.categories']);
        
        // Apply filters
        if (request()->has('user_id') && request('user_id') !== '') {
            $query->where('user_id', request('user_id'));
        }
        
        if (request()->has('book_id') && request('book_id') !== '') {
            $query->where('book_id', request('book_id'));
        }
        
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }
        
        if (request()->has('date_range') && request('date_range') !== '') {
            switch(request('date_range')) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
                case 'quarter':
                    $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                    break;
            }
        }
        
        $history = $query->latest()->paginate(4);
        
        // Handle AJAX request for pagination
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            $html = '';
            foreach ($history as $item) {
                $html .= view('admin.partials.reading-history-row', compact('item'))->render();
            }
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $history->links('pagination::bootstrap-5')->toHtml(),
                'from' => $history->firstItem(),
                'to' => $history->lastItem(),
                'total' => $history->total()
            ]);
        }
        
        // Calculate real statistics
        $totalSessions = ReadingProgress::count();
        $completedBooks = ReadingProgress::where('status', 'completed')->distinct('book_id')->count('book_id');
        $activeReaders = ReadingProgress::where('created_at', '>=', now()->subDays(7))->distinct('user_id')->count('user_id');
        $avgReadingTime = $this->calculateAverageReadingTime();
        
        // Get users and books for filters
        $users = User::orderBy('name')->get();
        $books = Book::orderBy('title')->get();
        
        return view('admin.reading-history', compact(
            'history', 
            'totalSessions', 
            'completedBooks', 
            'activeReaders', 
            'avgReadingTime',
            'users',
            'books'
        ));
    }
    
    /**
     * Delete reading history entry.
     */
    public function deleteReadingHistory($id)
    {
        $progress = ReadingProgress::findOrFail($id);
        $progress->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Reading history deleted successfully'
        ]);
    }
    
    /**
     * Add book to favorites.
     */
    public function addToFavorites(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);
        
        // Check if favorite already exists
        $existing = Favorite::where('user_id', $request->user_id)
                            ->where('book_id', $request->book_id)
                            ->first();
        
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This book is already in the user\'s favorites.'
            ]);
        }
        
        $favorite = Favorite::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Book added to favorites successfully!',
            'favorite' => $favorite->load(['user', 'book'])
        ]);
    }
    
    /**
     * Export reading history to CSV.
     */
    public function exportReadingHistory(Request $request)
    {
        $query = ReadingProgress::with(['user', 'book.author', 'book.categories']);
        
        // Apply same filters as main page
        if (request()->has('user_id') && request('user_id') !== '') {
            $query->where('user_id', request('user_id'));
        }
        
        if (request()->has('book_id') && request('book_id') !== '') {
            $query->where('book_id', request('book_id'));
        }
        
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }
        
        $history = $query->latest()->get();
        
        $filename = "reading_history_export_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($history) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, [
                'Created', 'User Name', 'User Email', 'Book Title', 'Book Author', 
                'Categories', 'Started', 'Last Updated', 'Progress', 'Status', 'Pages Read', 'Total Pages'
            ]);
            
            // CSV data
            foreach ($history as $item) {
                $categoryNames = $item->book->categories->pluck('name')->join(', ') ?: 'N/A';
                fputcsv($file, [
                    $item->id,
                    $item->user->name ?? 'N/A',
                    $item->user->email ?? 'N/A',
                    $item->book->title ?? 'N/A',
                    $item->book->author->name ?? 'N/A',
                    $categoryNames,
                    $item->created_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $item->updated_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $item->progress_percentage ?? 0,
                    $item->status ?? 'N/A',
                    $item->pages_read ?? 0,
                    $item->book->pages ?? 0
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Calculate average reading time.
     */
    private function calculateAverageReadingTime()
    {
        $sessions = ReadingProgress::whereNotNull('started_at')
                                    ->whereNotNull('completed_at')
                                    ->get();
        
        if ($sessions->isEmpty()) {
            return '0h 0m';
        }
        
        $totalMinutes = 0;
        foreach ($sessions as $session) {
            $start = \Carbon\Carbon::parse($session->started_at);
            $end = \Carbon\Carbon::parse($session->completed_at);
            $totalMinutes += $start->diffInMinutes($end);
        }
        
        $avgMinutes = $totalMinutes / $sessions->count();
        $hours = floor($avgMinutes / 60);
        $minutes = $avgMinutes % 60;
        
        return "{$hours}h {$minutes}m";
    }
    
    /**
     * Get reading sessions for a user and book.
     */
    private function getReadingSessions($userId, $bookId)
    {
        return ReadingProgress::where('user_id', $userId)
                               ->where('book_id', $bookId)
                               ->orderBy('created_at', 'desc')
                               ->take(5)
                               ->get();
    }

    /**
     * Display downloads management page.
     */
    public function downloads()
    {
        $downloads = Download::with(['user', 'book'])->latest()->paginate(10);
        return view('admin.downloads', compact('downloads'));
    }
    
    /**
     * Show download details.
     */
    public function showDownload($id)
    {
        $download = Download::with(['user', 'book.author'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'download' => $download
        ]);
    }
    
    /**
     * Re-download a file.
     */
    public function reDownload($id)
    {
        $download = Download::findOrFail($id);
        
        // Here you would implement the actual re-download logic
        // For now, we'll just return a success response
        
        return response()->json([
            'success' => true,
            'message' => 'Download started successfully',
            'download_url' => $download->file_path ?? '#'
        ]);
    }
    
    /**
     * Delete download record.
     */
    public function deleteDownload($id)
    {
        $download = Download::findOrFail($id);
        $download->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Download deleted successfully'
        ]);
    }

    /**
     * Display statistics page.
     */
    public function statistics()
    {
        // Get real statistics
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $totalDownloads = Download::count();
        $totalFavorites = Favorite::count();
        $totalReadingProgress = ReadingProgress::count();
        
        // Get recent activity
        $recentBooks = Book::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        $popularBooks = Book::withCount(['downloads', 'favorites'])
                            ->orderByDesc('downloads_count')
                            ->take(5)
                            ->get();
        
        return view('admin.statistics', compact(
            'totalBooks', 
            'totalUsers', 
            'totalDownloads', 
            'totalFavorites', 
            'totalReadingProgress',
            'recentBooks',
            'recentUsers',
            'popularBooks'
        ));
    }

    /**
     * Display settings page.
     */
    public function settings()
    {
        // Get system settings
        $settings = [
            'site_name' => config('app.name', 'E-Library'),
            'site_url' => config('app.url', env('APP_URL', 'http://localhost')),
            'timezone' => config('app.timezone', 'UTC'),
            'maintenance_mode' => config('app.maintenance', false),
            'registration_enabled' => config('app.registration_enabled', true),
            'email_notifications' => config('app.email_notifications', true),
            'max_upload_size' => config('app.max_upload_size', '10MB'),
        ];
        
        return view('admin.settings', compact('settings'));
    }

    /**
     * Display appearance page.
     */
    public function appearance()
    {
        // Get current theme settings
        $currentTheme = config('app.theme', 'default');
        $availableThemes = [
            'default' => 'Default Blue Theme',
            'dark' => 'Dark Theme',
            'light' => 'Light Theme',
            'modern' => 'Modern Theme',
        ];
        
        $layoutSettings = [
            'sidebar_collapsed' => config('app.sidebar_collapsed', false),
            'show_notifications' => config('app.show_notifications', true),
            'compact_mode' => config('app.compact_mode', false),
        ];
        
        $branding = [
            'site_name' => config('app.name', 'E-Library'),
            'logo_url' => config('app.logo_url', '/images/logo.png'),
            'favicon_url' => config('app.favicon_url', '/favicon.ico'),
        ];
        
        return view('admin.appearance', compact(
            'currentTheme',
            'availableThemes', 
            'layoutSettings',
            'branding'
        ));
    }

    /**
     * Save appearance settings.
     */
    public function saveAppearance(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:default,dark,light,modern',
            'sidebar_collapsed' => 'boolean',
            'show_notifications' => 'boolean',
            'compact_mode' => 'boolean',
            'site_name' => 'string|max:255',
            'logo_url' => 'url|nullable',
            'favicon_url' => 'url|nullable',
        ]);
        
        // Update configuration (you could also save to .env file)
        config(['app.theme' => $request->theme]);
        config(['app.sidebar_collapsed' => $request->sidebar_collapsed]);
        config(['app.show_notifications' => $request->show_notifications]);
        config(['app.compact_mode' => $request->compact_mode]);
        config(['app.name' => $request->site_name]);
        config(['app.logo_url' => $request->logo_url]);
        config(['app.favicon_url' => $request->favicon_url]);
        
        return response()->json([
            'success' => true,
            'message' => 'Appearance settings saved successfully!'
        ]);
    }

    /**
     * Display comprehensive reports page.
     */
    public function reports()
    {
        // Get comprehensive statistics
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_downloads' => Download::where('status', 'completed')->count(),
            'total_reviews' => Review::count(),
            'total_favorites' => Favorite::count(),
            'total_reading_progress' => ReadingProgress::count(),
            'users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'books_this_month' => Book::where('created_at', '>=', now()->startOfMonth())->count(),
            'downloads_this_month' => Download::where('created_at', '>=', now()->startOfMonth())->count(),
            'reviews_this_month' => Review::where('create_at', '>=', now()->startOfMonth())->count(),
        ];

        // Get recent activity
        $recentActivity = ActivityLog::with(['user', 'book'])
            ->latest()
            ->take(10)
            ->get();

        // Get popular books
        $popularBooks = Book::withCount(['downloads' => function($query) {
            $query->where('status', 'completed');
        }])->with('author')
            ->orderBy('downloads_count', 'desc')
            ->take(5)
            ->get();

        // Get category distribution
        $categoryStats = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.reports', compact(
            'stats', 
            'recentActivity', 
            'popularBooks', 
            'categoryStats'
        ));
    }
    
    /**
     * View report details.
     */
    public function viewReport($id)
    {
        try {
            // Generate different types of reports based on ID
            $reportData = $this->generateReportData($id);
            
            return view('admin.reports.view', compact('reportData', 'id'));
        } catch (\Exception $e) {
            \Log::error('Error viewing report: ' . $e->getMessage());
            
            // Return to reports page with error message
            return redirect()->route('admin.reports')->with('error', 'Failed to load report details: ' . $e->getMessage());
        }
    }
    
    /**
     * Download report.
     */
    public function downloadReport($id)
    {
        try {
            $reportData = $this->generateReportData($id);
            
            // Generate CSV or PDF based on report type
            $filename = $this->generateReportFile($reportData, $id);
            
            return response()->download($filename);
        } catch (\Exception $e) {
            \Log::error('Error downloading report: ' . $e->getMessage());
            
            return redirect()->route('admin.reports')->with('error', 'Failed to download report: ' . $e->getMessage());
        }
    }
    
    /**
     * Share report.
     */
    public function shareReport($id)
    {
        try {
            $reportData = $this->generateReportData($id);
            
            // Generate shareable link or embed code
            $shareUrl = route('admin.reports.view', $id);
            
            return view('admin.reports.share', compact('shareUrl', 'reportData', 'id'));
        } catch (\Exception $e) {
            \Log::error('Error sharing report: ' . $e->getMessage());
            
            return redirect()->route('admin.reports')->with('error', 'Failed to load share page: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate report data based on report ID.
     */
    private function generateReportData($id)
    {
        try {
            switch($id) {
                case 1: // User Activity Report
                    return [
                        'title' => 'User Activity Report',
                        'type' => 'user_activity',
                        'data' => User::with(['downloads', 'reviews', 'favorites'])
                            ->latest()
                            ->paginate(50),
                        'stats' => [
                            'total_users' => User::count(),
                            'active_users' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
                            'new_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
                        ]
                    ];
                    
                case 2: // Download Statistics Report
                    return [
                        'title' => 'Download Statistics Report',
                        'type' => 'download_stats',
                        'data' => Download::with(['user', 'book'])
                            ->latest()
                            ->paginate(50),
                        'stats' => [
                            'total_downloads' => Download::count(),
                            'completed_downloads' => Download::where('status', 'completed')->count(),
                            'failed_downloads' => Download::where('status', 'failed')->count(),
                        ]
                    ];
                    
                case 3: // Book Performance Report
                    return [
                        'title' => 'Book Performance Report',
                        'type' => 'book_performance',
                        'data' => Book::with(['author', 'category', 'downloads', 'reviews'])
                            ->withCount(['downloads' => function($query) {
                                $query->where('status', 'completed');
                            }])
                            ->orderBy('downloads_count', 'desc')
                            ->paginate(50),
                        'stats' => [
                            'total_books' => Book::count(),
                            'popular_books' => Book::withCount('downloads')->orderBy('downloads_count', 'desc')->take(10)->count(),
                            'avg_downloads_per_book' => Download::where('status', 'completed')->count() / max(Book::count(), 1),
                        ]
                    ];
                    
                default:
                    return [
                        'title' => 'General System Report',
                        'type' => 'general',
                        'data' => collect(),
                        'stats' => []
                    ];
            }
        } catch (\Exception $e) {
            \Log::error('Error generating report data: ' . $e->getMessage());
            throw new \Exception('Failed to generate report data: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate report file for download.
     */
    private function generateReportFile($reportData, $id)
    {
        $filename = "report_{$id}_" . date('Y-m-d') . ".csv";
        $filepath = storage_path("app/public/reports/{$filename}");
        
        // Ensure directory exists
        $directory = dirname($filepath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $handle = fopen($filepath, 'w');
        
        // Add CSV headers
        fputcsv($handle, [$reportData['title']]);
        fputcsv($handle, ['Generated on: ' . now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);
        
        // Add statistics
        if (!empty($reportData['stats'])) {
            fputcsv($handle, ['Statistics']);
            foreach ($reportData['stats'] as $key => $value) {
                fputcsv($handle, [ucwords(str_replace('_', ' ', $key)), $value]);
            }
            fputcsv($handle, []);
        }
        
        // Add data based on report type
        if ($reportData['data']->isNotEmpty()) {
            $firstItem = $reportData['data']->first();
            $headers = array_keys($firstItem->toArray());
            
            fputcsv($handle, ['Data']);
            fputcsv($handle, $headers);
            
            foreach ($reportData['data'] as $item) {
                $row = [];
                foreach ($headers as $header) {
                    $value = $item->$header;
                    if (is_object($value)) {
                        $value = method_exists($value, 'name') ? $value->name : json_encode($value);
                    }
                    $row[] = $value;
                }
                fputcsv($handle, $row);
            }
        }
        
        fclose($handle);
        
        return $filepath;
    }

    /**
     * Display export page.
     */
    public function export()
    {
        return view('admin.export');
    }

    /**
     * Display author dashboard page.
     */
    public function authorDashboard()
    {
        $user = auth()->user();
        
        // Get author-specific statistics
        $totalBooks = Book::where('author_id', $user->id)->count();
        $publishedBooks = Book::where('author_id', $user->id)->where('status', 1)->count();
        $draftBooks = Book::where('author_id', $user->id)->where('status', 0)->count();
        
        $stats = [
            'total_books' => $totalBooks,
            'published_books' => $publishedBooks,
            'draft_books' => $draftBooks,
            'total_views' => Book::where('author_id', $user->id)->sum('views') ?? 0,
            'total_downloads' => Download::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->where('status', 'completed')->count(),
            'total_reviews' => Review::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->count(),
            'total_favorites' => Favorite::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->count(),
            'total_readers' => \App\Models\ReadingProgress::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->distinct('user_id')->count(),
            'average_rating' => Review::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->avg('rating'),
            'monthly_growth' => 15, // Placeholder - calculate from real data
            'profile_completion' => 85, // Placeholder - calculate from profile data
            'top_achievement' => 'First Book Published', // Placeholder - calculate from achievements
        ];

        // Get author's recent books
        $recentBooks = Book::where('author_id', $user->id)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Get author's popular books (most viewed)
        $popularBooks = Book::where('author_id', $user->id)
            ->with('category')
            ->orderBy('views', 'desc')
            ->take(3)
            ->get();

        // Get author's recent reviews
        $recentReviews = Review::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with('user', 'book')
            ->latest()
            ->take(5)
            ->get();

        // Get recent activity for this author
        $recentActivity = [];
        
        // Recent book activities
        $recentBookActivities = Book::where('author_id', $user->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function($book) {
                return [
                    'icon' => 'fa-book',
                    'text' => $book->status === 1 ? 
                        "Published book: {$book->title}" : 
                        "Updated book: {$book->title}",
                    'time' => \Carbon\Carbon::parse($book->created_at)->diffForHumans()
                ];
            });

        // Recent review activities
        $recentReviewActivities = Review::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with('user', 'book')
            ->latest()
            ->take(2)
            ->get()
            ->map(function($review) {
                return [
                    'icon' => 'fa-star',
                    'text' => "New review on: {$review->book->title}",
                    'time' => \Carbon\Carbon::parse($review->created_at)->diffForHumans()
                ];
            });

        // Merge activities
        $recentActivity = $recentBookActivities->merge($recentReviewActivities)
            ->sortByDesc('time')
            ->take(5)
            ->values();
        
        return view('author.dashboard', compact('stats', 'recentBooks', 'popularBooks', 'recentReviews', 'recentActivity'));
    }
    
    /**
     * Display author books index page (all books).
     */
    public function authorBooksIndex()
    {
        $user = auth()->user();
        
        // Get all author's books with reviews for rating calculations
        $books = Book::where('author_id', $user->id)
            ->with(['author', 'category', 'reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->withCount(['reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->latest()
            ->paginate(10);
        
        return view('author.books.index', compact('books'));
    }
    
    /**
     * Display author published books page.
     */
    public function authorBooksPublished()
    {
        $user = auth()->user();
        
        // Get only published books with reviews for rating calculations
        $books = Book::where('author_id', $user->id)
            ->where('status', 1)
            ->with(['author', 'category', 'reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->withCount(['reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->latest()
            ->paginate(10);
        
        return view('author.books.published', compact('books'));
    }
    
    /**
     * Display author draft books page.
     */
    public function authorBooksDrafts()
    {
        $user = auth()->user();
        
        // Get only draft books
        $books = Book::where('author_id', $user->id)
            ->where('status', 0)
            ->with(['author', 'category', 'reviews'])
            ->latest()
            ->paginate(10);
        
        return view('author.books.drafts', compact('books'));
    }
    
    /**
     * Get book details for AJAX request.
     */
    public function getBookDetails($id)
    {
        $user = auth()->user();
        
        $book = Book::where('id', $id)
            ->where('author_id', $user->id)
            ->with(['author', 'category', 'reviews' => function($query) {
                $query->where('status', 1); // Only approved reviews
            }])
            ->firstOrFail();
            
        return response()->json([
            'success' => true,
            'book' => $book
        ]);
    }
    
    /**
     * Display author book analytics.
     */
    public function authorBookAnalytics()
    {
        $user = auth()->user();
        
        // Get all author's books with their statistics
        $books = Book::where('author_id', $user->id)
            ->with(['reviews', 'favorites'])
            ->withCount(['reviews', 'favorites'])
            ->get();

        // Get top performing books
        $topBooks = Book::where('author_id', $user->id)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Get recent activity
        $recentActivity = [];
        
        // Recent book activities
        $recentBookActivities = Book::where('author_id', $user->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function($book) {
                return [
                    'icon' => 'fa-book',
                    'text' => $book->status === 1 ? 
                        'Published: ' . $book->title : 
                        'Updated: ' . $book->title,
                    'time' => \Carbon\Carbon::parse($book->updated_at)->diffForHumans()
                ];
            });
        
        // Recent review activities
        $recentReviewActivities = Review::whereHas('book', function($query) use ($user) {
            $query->where('author_id', $user->id);
        })
        ->with('book')
        ->latest()
        ->take(3)
            ->get()
            ->map(function($review) {
                return [
                    'icon' => 'fa-star',
                    'text' => "New review on: {$review->book->title}",
                    'time' => \Carbon\Carbon::parse($review->created_at)->diffForHumans()
                ];
            });
        
        // Merge activities
        $recentActivity = $recentBookActivities->merge($recentReviewActivities)
            ->sortByDesc('time')
            ->take(5)
            ->values();
        
        // Get analytics data
        $analytics = [
            'total_books' => Book::where('author_id', $user->id)->count(),
            'published_books' => Book::where('author_id', $user->id)->where('status', 1)->count(),
            'draft_books' => Book::where('author_id', $user->id)->where('status', 0)->count(),
            'total_views' => Book::where('author_id', $user->id)->sum('views') ?? 0,
            'total_downloads' => Download::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->where('status', 'completed')->count(),
            'total_reviews' => Review::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->count(),
            'total_favorites' => Favorite::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->count(),
        ];
        
        return view('author.books.analytics', compact('analytics', 'books', 'topBooks', 'recentActivity'));
    }
    
    /**
     * Display author reviews page.
     */
    public function authorReviews()
    {
        $user = auth()->user();
        
        // Get author's reviews with pagination
        $reviews = Review::whereHas('book', function($query) use ($user) {
            $query->where('author_id', $user->id);
        })
        ->with('book')
        ->latest()
        ->paginate(10);
        
        return view('author.reviews', compact('reviews'));
    }

    /**
     * Display author reading progress page.
     */
    public function authorReadingProgress()
    {
        $user = auth()->user();
        
        // Get reading progress for author's books
        $readingProgress = \App\Models\ReadingProgress::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with(['user', 'book'])
            ->latest()
            ->paginate(5);

        return view('author.reading-progress', compact('readingProgress'));
    }

    /**
     * Display author favorites page.
     */
    public function authorFavorites()
    {
        $user = auth()->user();
        
        // Get favorites for author's books
        $favorites = \App\Models\Favorite::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with(['user', 'book'])
            ->latest()
            ->paginate(5);

        // Get most favorited books
        $mostFavoritedBooks = \App\Models\Book::where('author_id', $user->id)
            ->withCount('favorites')
            ->orderBy('favorites_count', 'desc')
            ->take(5)
            ->get();

        // Get recent favorites
        $recentFavorites = \App\Models\Favorite::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        return view('author.favorites', compact('favorites', 'mostFavoritedBooks', 'recentFavorites'));
    }

    /**
     * Display author downloads page.
     */
    public function authorDownloads()
    {
        $user = auth()->user();
        
        // Get downloads for author's books
        $downloads = \App\Models\Download::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with(['user', 'book'])
            ->latest()
            ->paginate(5);

        // Get most downloaded books
        $mostDownloadedBooks = Book::where('author_id', $user->id)
            ->withCount('downloads')
            ->orderBy('downloads_count', 'desc')
            ->take(5)
            ->get();

        // Get recent downloads
        $recentDownloads = \App\Models\Download::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        return view('author.downloads', compact('downloads', 'mostDownloadedBooks', 'recentDownloads'));
    }

    /**
     * Display author library page.
     */
    public function authorLibrary()
    {
        // Get all books for library browsing
        $books = Book::with(['author', 'category', 'reviews'])
            ->where('status', 1)
            ->latest()
            ->paginate(12);

        return view('author.library', compact('books'));
    }

    /**
     * Display author reading history page.
     */
    public function authorReadingHistory()
    {
        $user = auth()->user();
        
        // Get reading history for current user
        $readingHistory = \App\Models\ReadingHistory::where('user_id', $user->id)
            ->with('book')
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('search'), function($query, $search) {
                return $query->whereHas('book', function($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(5);

        // Get reading statistics
        $readingStats = [
            'total_books' => \App\Models\ReadingHistory::where('user_id', $user->id)->count(),
            'completed_books' => \App\Models\ReadingHistory::where('user_id', $user->id)->where('status', 'completed')->count(),
            'currently_reading' => \App\Models\ReadingHistory::where('user_id', $user->id)->where('status', 'reading')->count(),
            'avg_pages' => \App\Models\ReadingHistory::where('user_id', $user->id)->where('status', 'completed')->avg('total_pages') ?? 0,
        ];

        // Get user's books for the dropdown
        $userBooks = \App\Models\Book::where('author_id', $user->id)->get();
        if ($userBooks->isEmpty()) {
            $userBooks = collect();
        }

        return view('author.reading-history.index', compact('readingHistory', 'readingStats', 'userBooks'));
    }

    /**
     * Show reading history entry details.
     */
    public function showReadingHistory($id)
    {
        $readingHistory = \App\Models\ReadingHistory::with('book')->findOrFail($id);

        return response()->json([
            'success' => true,
            'reading_history' => $readingHistory
        ]);
    }

    /**
     * Show reading history entry for editing.
     */
    public function editReadingHistory($id)
    {
        $readingHistory = \App\Models\ReadingHistory::with('book')->findOrFail($id);

        return response()->json([
            'success' => true,
            'reading_history' => $readingHistory
        ]);
    }

    /**
     * Store new reading history entry.
     */
    public function storeReadingHistory(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'status' => 'required|in:reading,completed,paused,abandoned',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date|after:started_at',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'pages_read' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $readingHistory = new \App\Models\ReadingHistory();
            $readingHistory->user_id = auth()->id();
            $readingHistory->book_id = $request->book_id;
            $readingHistory->status = $request->status;
            $readingHistory->started_at = $request->started_at;
            $readingHistory->completed_at = $request->completed_at;
            $readingHistory->progress_percentage = $request->progress_percentage ?? 0;
            $readingHistory->pages_read = $request->pages_read ?? 0;
            $readingHistory->total_pages = $request->total_pages ?? 0;
            $readingHistory->notes = $request->notes;
            $readingHistory->save();

            return response()->json([
                'success' => true,
                'message' => 'Reading entry created successfully!',
                'reading_history' => $readingHistory
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating reading entry: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update reading history entry.
     */
    public function updateReadingHistory(Request $request, $id)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'status' => 'required|in:reading,completed,paused,abandoned',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date|after:started_at',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'pages_read' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $readingHistory = \App\Models\ReadingHistory::findOrFail($id);
            $readingHistory->book_id = $request->book_id;
            $readingHistory->status = $request->status;
            $readingHistory->started_at = $request->started_at;
            $readingHistory->completed_at = $request->completed_at;
            $readingHistory->progress_percentage = $request->progress_percentage ?? 0;
            $readingHistory->pages_read = $request->pages_read ?? 0;
            $readingHistory->total_pages = $request->total_pages ?? 0;
            $readingHistory->notes = $request->notes;
            $readingHistory->save();

            return response()->json([
                'success' => true,
                'message' => 'Reading entry updated successfully!',
                'reading_history' => $readingHistory
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating reading entry: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display author my favorites page.
     */
    public function authorMyFavorites()
    {
        $user = auth()->user();
        
        // Get user's favorites
        $favorites = \App\Models\Favorite::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->paginate(12);

        // Get favorite categories
        $favoriteCategories = \App\Models\Category::whereHas('books.favorites', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->withCount(['favorites' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('favorites_count', 'desc')
            ->take(5)
            ->get();

        // Get recent favorites
        $recentFavorites = \App\Models\Favorite::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->take(5)
            ->get();

        return view('author.my-favorites', compact('favorites', 'favoriteCategories', 'recentFavorites'));
    }

    /**
     * Display author profile page.
     */
    public function authorProfile()
    {
        $user = auth()->user();
        $authorStats = [
            'total_books' => Book::where('author_id', $user->id)->count(),
            'published_books' => Book::where('author_id', $user->id)->where('status', 'published')->count(),
            'total_views' => Book::where('author_id', $user->id)->sum('views') ?? 0,
            'total_downloads' => Download::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->where('status', 'completed')->count(),
            'total_reviews' => Review::whereHas('book', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->count(),
            'join_date' => $user->created_at->format('M d, Y'),
        ];

        return view('author.profile', compact('user', 'authorStats'));
    }

    /**
     * Display author settings page.
     */
    public function authorSettings()
    {
        $user = auth()->user();
        return view('admin.author-settings', compact('user'));
    }

    /**
     * Update author profile.
     */
    public function updateAuthorProfile(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->all());
        
        return redirect()->route('author.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Display email templates page.
     */
    public function emailTemplates()
    {
        return view('admin.email-templates');
    }

    /**
     * Display security settings page.
     */
    public function security()
    {
        return view('admin.security');
    }

    /**
     * Display backup page.
     */
    public function backup()
    {
        return view('admin.backup');
    }

    /**
     * Display logs page.
     */
    public function logs()
    {
        // Get system logs from storage/logs directory
        $logFiles = [];
        $logDirectory = storage_path('logs');
        
        if (is_dir($logDirectory)) {
            $files = glob($logDirectory . '/*.log');
            foreach ($files as $file) {
                $logFiles[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => filesize($file),
                    'modified' => filemtime($file)
                ];
            }
        }
        
        // Get recent log entries
        $recentLogs = $this->getRecentLogEntries();
        
        // Get system statistics
        $systemStats = $this->getSystemLogStats();
        
        // Get error logs
        $errorLogs = $this->getErrorLogs();
        
        // Get access logs
        $accessLogs = $this->getAccessLogs();
        
        return view('admin.logs', compact(
            'logFiles',
            'recentLogs',
            'systemStats',
            'errorLogs',
            'accessLogs'
        ));
    }
    
    /**
     * Get recent log entries from Laravel log file
     */
    private function getRecentLogEntries()
    {
        $logFile = storage_path('logs/laravel.log');
        $entries = [];
        
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $lines = array_reverse(array_slice($lines, -100)); // Get last 100 lines
            
            foreach ($lines as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?(\w+)\.(.*?):\s*(.*)/', $line, $matches)) {
                    $entries[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[2],
                        'channel' => $matches[3],
                        'message' => $matches[4],
                        'raw' => $line
                    ];
                }
            }
        }
        
        return collect($entries);
    }
    
    /**
     * Get system log statistics
     */
    private function getSystemLogStats()
    {
        $logFile = storage_path('logs/laravel.log');
        $stats = [
            'total_entries' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'info_count' => 0,
            'debug_count' => 0,
            'last_updated' => null
        ];
        
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $stats['total_entries'] = count($lines);
            $stats['last_updated'] = filemtime($logFile);
            
            foreach ($lines as $line) {
                if (strpos($line, '.ERROR') !== false) {
                    $stats['error_count']++;
                } elseif (strpos($line, '.WARNING') !== false) {
                    $stats['warning_count']++;
                } elseif (strpos($line, '.INFO') !== false) {
                    $stats['info_count']++;
                } elseif (strpos($line, '.DEBUG') !== false) {
                    $stats['debug_count']++;
                }
            }
        }
        
        return (object) $stats;
    }
    
    /**
     * Get error logs
     */
    private function getErrorLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $errors = [];
        
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $lines = array_reverse(array_slice($lines, -50)); // Get last 50 lines
            
            foreach ($lines as $line) {
                if (strpos($line, '.ERROR') !== false) {
                    if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?(\w+)\.(.*?):\s*(.*)/', $line, $matches)) {
                        $errors[] = [
                            'timestamp' => $matches[1],
                            'level' => $matches[2],
                            'channel' => $matches[3],
                            'message' => $matches[4],
                            'raw' => $line
                        ];
                    }
                }
            }
        }
        
        return collect($errors);
    }
    
    /**
     * Get access logs (simulated from database)
     */
    private function getAccessLogs()
    {
        // In a real application, you might have a separate access log table
        // For now, we'll simulate with recent user activity
        $accessLogs = [];
        
        try {
            $recentUsers = \App\Models\User::latest()->take(10)->get();
            foreach ($recentUsers as $user) {
                $accessLogs[] = [
                    'timestamp' => $user->created_at->format('Y-m-d H:i:s'),
                    'user' => $user->name,
                    'email' => $user->email,
                    'action' => 'User Registration',
                    'ip' => '127.0.0.1', // In real app, this would come from request
                    'user_agent' => 'Mozilla/5.0 (Browser)'
                ];
            }
        } catch (\Exception $e) {
            // If database is not available, return empty array
        }
        
        return collect($accessLogs);
    }

    /**
     * Display all database tables data.
     */
    public function showAllData()
    {
        $data = [
            'users' => \App\Models\User::all(),
            'books' => \App\Models\Book::with(['author', 'categories'])->get(),
            'categories' => \App\Models\Category::all(),
            'tags' => \App\Models\Tag::all(),
            'reviews' => \App\Models\Review::with(['book', 'user'])->get(),
            'downloads' => \App\Models\Download::with(['book', 'user'])->get(),
            'reading_progress' => \App\Models\ReadingProgress::with(['book', 'user'])->get(),
            'favorites' => \App\Models\Favorite::with(['book', 'user'])->get(),
            'authors' => \DB::table('authorse')->get(),
        ];
        
        return view('admin.show-all-data', compact('data'));
    }

    /**
     * Display author categories page.
     */
    public function authorCategories()
    {
        $user = auth()->user();
        
        // Get ALL categories from the database with book counts
        $categories = \App\Models\Category::withCount('books')
            ->with(['books' => function($query) use ($user) {
                $query->where('author_id', $user->id)->latest()->take(3);
            }])
            ->paginate(5);

        // Get category statistics
        $categoryStats = [
            'total_categories' => \App\Models\Category::count(),
            'your_categories' => \App\Models\Category::whereHas('books', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->count(),
            'most_used' => \App\Models\Category::withCount('books')->orderBy('books_count', 'desc')->first(),
        ];

        return view('author.categories.index', compact('categories', 'categoryStats'));
    }

    /**
     * Show category details.
     */
    public function showCategory($id)
    {
        $category = \App\Models\Category::withCount('books')
            ->with(['books' => function($query) {
                $query->latest()->take(10);
            }])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Display author tags page.
     */
    public function authorTags()
    {
        $user = auth()->user();
        
        // Get tags used by author's books
        $tags = \App\Models\Tag::whereHas('books', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->withCount(['books' => function($query) use ($user) {
                $query->where('author_id', $user->id);
            }])
            ->with(['books' => function($query) use ($user) {
                $query->where('author_id', $user->id)->latest()->take(3);
            }])
            ->paginate(5);

        // Get tag statistics
        $tagStats = [
            'total_tags' => \App\Models\Tag::whereHas('books', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->count(),
            'most_used' => \App\Models\Tag::whereHas('books', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })->withCount(['books' => function($query) use ($user) {
                $query->where('author_id', $user->id);
            }])->orderBy('books_count', 'desc')->first(),
        ];

        return view('author.tags.index', compact('tags', 'tagStats'));
    }
}
