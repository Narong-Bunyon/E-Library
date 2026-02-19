<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Marketing Routes
Route::controller(MarketingController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/browse', 'browse')->name('browse');
    Route::get('/categories', 'categories')->name('categories');
    Route::get('/about', 'about')->name('about');
});

// Authentication Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    Route::post('/logout', 'logout')->name('logout');
});

// Language Switch Route
Route::get('/locale/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
})->name('locale.switch')->where('locale', 'en|kh');

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('register.submit');
});

// =====================================================
// USER ROUTES (Regular authenticated users - Simple Website)
// =====================================================
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    // Main Pages (like a regular website)
    Route::get('/', [UserController::class, 'dashboard'])->name('home'); // Changed to home
    Route::get('/library', [LibraryController::class, 'index'])->name('library');
    Route::get('/browse', [LibraryController::class, 'browse'])->name('browse');
    Route::get('/categories', [LibraryController::class, 'categories'])->name('categories');
    Route::get('/book/{id}', [LibraryController::class, 'show'])->name('book.show');
    Route::get('/read/{id}', [LibraryController::class, 'read'])->name('book.read');
    
    // User Personal Pages (simple, not dashboard-style)
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/favorites', [LibraryController::class, 'favorites'])->name('favorites');
    Route::get('/reading-history', [LibraryController::class, 'readingHistory'])->name('reading-history');
    Route::get('/downloads', [LibraryController::class, 'downloads'])->name('downloads');
    
    // User Actions
    Route::post('/favorites/{id}/add', [LibraryController::class, 'addToFavorites'])->name('favorites.add');
    Route::delete('/favorites/{id}/remove', [LibraryController::class, 'removeFromFavorites'])->name('favorites.remove');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Settings (simple)
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
});

// =====================================================
// AUTHOR ROUTES (Author role access only)
// =====================================================
Route::middleware(['auth', 'role:author'])->prefix('author')->name('author.')->group(function () {
    // Author Dashboard
    Route::get('/dashboard', [AdminController::class, 'authorDashboard'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'authorProfile'])->name('profile');
    Route::get('/settings', [AdminController::class, 'authorSettings'])->name('settings');
    Route::post('/profile/update', [AdminController::class, 'updateAuthorProfile'])->name('profile.update');
    Route::get('/analytics', [AdminController::class, 'authorBookAnalytics'])->name('analytics');
    Route::get('/reviews', [AdminController::class, 'authorReviews'])->name('reviews');
    Route::get('/reading-progress', [AdminController::class, 'authorReadingProgress'])->name('reading-progress');
    Route::get('/favorites', [AdminController::class, 'authorFavorites'])->name('favorites');
    Route::get('/downloads', [AdminController::class, 'authorDownloads'])->name('downloads');
    Route::get('/library', [AdminController::class, 'authorLibrary'])->name('library');
    Route::get('/reading-history', [AdminController::class, 'authorReadingHistory'])->name('reading-history');
    Route::get('/my-favorites', [AdminController::class, 'authorMyFavorites'])->name('my-favorites');
    
    // Author Book Management Routes
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/', [BookController::class, 'store'])->name('store');
        Route::get('/{id}', [BookController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BookController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BookController::class, 'update'])->name('update');
        Route::delete('/{id}', [BookController::class, 'destroy'])->name('destroy');
        Route::get('/published', [BookController::class, 'publishedBooks'])->name('published');
        Route::get('/drafts', [BookController::class, 'draftBooks'])->name('drafts');
        Route::post('/{id}/publish', [BookController::class, 'publishBook'])->name('publish');
        Route::post('/{id}/unpublish', [BookController::class, 'unpublishBook'])->name('unpublish');
        Route::post('/{id}/duplicate', [BookController::class, 'duplicateBook'])->name('duplicate');
    });
});

// =====================================================
// ADMIN ROUTES (Admin role access only)
// =====================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::post('/', [AdminController::class, 'storeUser'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateUser'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('delete');
        Route::get('/{id}', [AdminController::class, 'viewUser'])->name('view');
        Route::delete('/bulk-delete', [AdminController::class, 'bulkDeleteUsers'])->name('bulk-delete');
        Route::get('/export', [AdminController::class, 'exportUsers'])->name('export');
    });
    
    // Author Management
    Route::prefix('authors')->name('authors.')->group(function () {
        Route::get('/', [AdminController::class, 'authors'])->name('index');
        Route::post('/', [AdminController::class, 'storeAuthor'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editAuthor'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateAuthor'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deleteAuthor'])->name('delete');
        Route::delete('/bulk-delete', [AdminController::class, 'bulkDeleteAuthors'])->name('bulk-delete');
        Route::get('/export', [AdminController::class, 'exportAuthors'])->name('export');
    });
    
    // Admin Book Management
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [AdminController::class, 'books'])->name('index');
        Route::post('/', [AdminController::class, 'storeBook'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editBook'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateBook'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deleteBook'])->name('delete');
        Route::get('/{id}', [AdminController::class, 'viewBook'])->name('view');
        Route::delete('/bulk-delete', [AdminController::class, 'bulkDeleteBooks'])->name('bulk-delete');
        Route::get('/export', [AdminController::class, 'exportBooks'])->name('export');
    });
    
    // Category Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminController::class, 'categories'])->name('index');
        Route::post('/', [AdminController::class, 'storeCategory'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editCategory'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateCategory'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deleteCategory'])->name('delete');
        Route::get('/{id}', [AdminController::class, 'viewCategory'])->name('view');
        Route::delete('/bulk-delete', [AdminController::class, 'bulkDeleteCategories'])->name('bulk-delete');
        Route::get('/export', [AdminController::class, 'exportCategories'])->name('export');
    });
    
    // Role Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [AdminController::class, 'roles'])->name('index');
        Route::post('/', [AdminController::class, 'storeRole'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editRole'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateRole'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deleteRole'])->name('delete');
        Route::post('/assign', [AdminController::class, 'assignRole'])->name('assign');
        Route::post('/remove', [AdminController::class, 'removeRole'])->name('remove');
        Route::delete('/bulk-delete', [AdminController::class, 'bulkDeleteRoles'])->name('bulk-delete');
        Route::get('/export', [AdminController::class, 'exportRoles'])->name('export');
    });
    
    // Other Admin Routes
    Route::get('/tags', [AdminController::class, 'tags'])->name('tags');
    Route::post('/tags', [AdminController::class, 'storeTag'])->name('tags.store');
    Route::get('/tags/{id}/edit', [AdminController::class, 'editTag'])->name('tags.edit');
    Route::put('/tags/{id}', [AdminController::class, 'updateTag'])->name('tags.update');
    Route::delete('/tags/{id}', [AdminController::class, 'deleteTag'])->name('tags.delete');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::post('/reviews', [AdminController::class, 'storeReview'])->name('reviews.store');
    Route::get('/reviews/{id}/edit', [AdminController::class, 'editReview'])->name('reviews.edit');
    Route::put('/reviews/{id}', [AdminController::class, 'updateReview'])->name('reviews.update');
    Route::get('/reviews/{id}', [AdminController::class, 'viewReview'])->name('reviews.view');
    Route::post('/reviews/{id}/approve', [AdminController::class, 'approveReview'])->name('reviews.approve');
    Route::post('/reviews/{id}/reject', [AdminController::class, 'rejectReview'])->name('reviews.reject');
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');
    Route::delete('/reviews/bulk-delete', [AdminController::class, 'bulkDeleteReviews'])->name('reviews.bulk-delete');
    Route::get('/reviews/export', [AdminController::class, 'exportReviews'])->name('reviews.export');
    
    Route::get('/activity', [AdminController::class, 'activity'])->name('activity');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/show-all-data', [AdminController::class, 'showAllData'])->name('show-all-data');
    
    Route::get('/reading-progress', [AdminController::class, 'readingProgress'])->name('reading-progress');
    Route::post('/reading-progress', [AdminController::class, 'storeReadingProgress'])->name('reading-progress.store');
    Route::get('/reading-progress/{id}/edit', [AdminController::class, 'editReadingProgress'])->name('reading-progress.edit');
    Route::put('/reading-progress/{id}', [AdminController::class, 'updateReadingProgress'])->name('reading-progress.update');
    Route::get('/reading-progress/{id}', [AdminController::class, 'viewReadingProgress'])->name('reading-progress.view');
    Route::delete('/reading-progress/{id}', [AdminController::class, 'deleteReadingProgress'])->name('reading-progress.delete');
    Route::delete('/reading-progress/bulk-delete', [AdminController::class, 'bulkDeleteReadingProgress'])->name('reading-progress.bulk-delete');
    Route::get('/reading-progress/export', [AdminController::class, 'exportReadingProgress'])->name('reading-progress.export');
    
    Route::get('/favorites', [AdminController::class, 'favorites'])->name('favorites');
    Route::post('/favorites', [AdminController::class, 'storeFavorite'])->name('favorites.store');
    Route::get('/favorites/{id}', [AdminController::class, 'showFavorite'])->name('favorites.show');
    Route::delete('/favorites/{id}', [AdminController::class, 'destroyFavorite'])->name('favorites.destroy');
    Route::delete('/favorites/bulk-delete', [AdminController::class, 'bulkDeleteFavorites'])->name('favorites.bulk-delete');
    Route::get('/favorites/export', [AdminController::class, 'exportFavorites'])->name('favorites.export');
    Route::get('/reading-history', [AdminController::class, 'readingHistory'])->name('reading-history');
    Route::get('/reading-history/{id}', [AdminController::class, 'showReadingProgress'])->name('reading-history.show');
    Route::post('/reading-history/add-progress', [AdminController::class, 'storeReadingProgress'])->name('reading-history.add-progress');
    Route::post('/reading-history/add-to-favorites', [AdminController::class, 'addToFavorites'])->name('reading-history.add-to-favorites');
    Route::get('/reading-history/export', [AdminController::class, 'exportReadingHistory'])->name('reading-history.export');
    Route::get('/downloads', [AdminController::class, 'downloads'])->name('downloads');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
    Route::get('/appearance', [AdminController::class, 'appearance'])->name('appearance');
    Route::post('/appearance/save', [AdminController::class, 'saveAppearance'])->name('appearance.save');
    Route::get('/email-templates', [AdminController::class, 'emailTemplates'])->name('email-templates');
    Route::get('/security', [AdminController::class, 'security'])->name('security');
    Route::get('/backup', [AdminController::class, 'backup'])->name('backup');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
    Route::get('/author-dashboard', [AdminController::class, 'authorDashboard'])->name('author-dashboard');
});

// Library Routes (All authenticated users)
Route::middleware('auth')->prefix('library')->name('library.')->group(function () {
    Route::get('/', [LibraryController::class, 'index'])->name('index');
    Route::get('/browse', [LibraryController::class, 'browse'])->name('browse');
    Route::get('/categories', [LibraryController::class, 'categories'])->name('categories');
    Route::get('/book/{id}', [LibraryController::class, 'show'])->name('show');
    Route::get('/read/{id}', [LibraryController::class, 'read'])->name('read');
    Route::get('/reading-history', [LibraryController::class, 'readingHistory'])->name('reading-history');
    Route::get('/favorites', [LibraryController::class, 'favorites'])->name('favorites');
    Route::post('/favorites/{id}/add', [LibraryController::class, 'addToFavorites'])->name('favorites.add');
    Route::delete('/favorites/{id}/remove', [LibraryController::class, 'removeFromFavorites'])->name('favorites.remove');
});
