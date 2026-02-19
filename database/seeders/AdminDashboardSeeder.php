<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Review;
use App\Models\Download;
use App\Models\ReadingProgress;
use App\Models\Favorite;
use App\Models\ActivityLog;

class AdminDashboardSeeder extends Seeder
{
    public function run()
    {
        // Create additional users
        $users = [
            [
                'name' => 'Alice Reader',
                'email' => 'alice@elibrary.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // Assuming role_id 3 is for readers
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bob Reader',
                'email' => 'bob@elibrary.com',
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Charlie Reader',
                'email' => 'charlie@elibrary.com',
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Diana Reader',
                'email' => 'diana@elibrary.com',
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Eva Reader',
                'email' => 'eva@elibrary.com',
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Frank Reader',
                'email' => 'frank@elibrary.com',
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        echo "Users created successfully!\n";

        // Create categories (only if they don't exist)
        $categories = [
            ['name' => 'Programming'],
            ['name' => 'Design'],
            ['name' => 'Database'],
            ['name' => 'Mobile Development'],
            ['name' => 'Security'],
            ['name' => 'Cloud Computing'],
            ['name' => 'Artificial Intelligence'],
            ['name' => 'Networking'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(['name' => $categoryData['name']], $categoryData);
        }

        echo "Categories created successfully!\n";

        // Create tags (only if they don't exist)
        $tags = [
            ['name' => 'Laravel'],
            ['name' => 'PHP'],
            ['name' => 'JavaScript'],
            ['name' => 'CSS'],
            ['name' => 'MySQL'],
            ['name' => 'HTML'],
            ['name' => 'Web Design'],
            ['name' => 'API'],
            ['name' => 'JSON'],
            ['name' => 'React'],
            ['name' => 'Vue.js'],
            ['name' => 'Bootstrap'],
            ['name' => 'Tailwind'],
            ['name' => 'Python'],
            ['name' => 'Docker'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate(['name' => $tagData['name']], $tagData);
        }

        echo "Tags created successfully!\n";

        // Create an author record first
        $authorData = [
            'user_id' => 1, // Using admin user as author
            'bio' => 'Experienced author with expertise in web development and programming languages.',
            'approved_by_admin' => true,
        ];
        
        $author = \DB::table('authorse')->insertGetId($authorData);
        echo "Author created with ID: {$author}\n";

        // Create books (only if they don't exist)
        $books = [
            [
                'title' => 'Advanced Laravel Development',
                'description' => 'A comprehensive guide to advanced Laravel development techniques and best practices.',
                'author_id' => $author, // Use the created author ID
                'status' => 1, // published
                'file_path' => 'books/advanced-laravel.pdf',
                'cover_image' => 'covers/advanced-laravel.jpg',
                'access_level' => 0,
            ],
            [
                'title' => 'Modern Web Design',
                'description' => 'Learn modern web design principles and create stunning websites.',
                'author_id' => $author,
                'status' => 1, // published
                'file_path' => 'books/modern-web-design.pdf',
                'cover_image' => 'covers/modern-web-design.jpg',
                'access_level' => 0,
            ],
            [
                'title' => 'Database Management',
                'description' => 'Master database management systems and SQL programming.',
                'author_id' => $author,
                'status' => 0, // draft
                'file_path' => 'books/database-management.pdf',
                'cover_image' => 'covers/database-management.jpg',
                'access_level' => 0,
            ],
            [
                'title' => 'JavaScript Fundamentals',
                'description' => 'Complete guide to JavaScript programming from basics to advanced concepts.',
                'author_id' => $author,
                'status' => 1, // published
                'file_path' => 'books/javascript-fundamentals.pdf',
                'cover_image' => 'covers/javascript-fundamentals.jpg',
                'access_level' => 0,
            ],
            [
                'title' => 'CSS Masterclass',
                'description' => 'Master CSS and create beautiful, responsive web designs.',
                'author_id' => $author,
                'status' => 1, // published
                'file_path' => 'books/css-masterclass.pdf',
                'cover_image' => 'covers/css-masterclass.jpg',
                'access_level' => 0,
            ],
            [
                'title' => 'Python for Beginners',
                'description' => 'Start your Python journey with this comprehensive beginner guide.',
                'author_id' => $author,
                'status' => 1, // published
                'file_path' => 'books/python-beginners.pdf',
                'cover_image' => 'covers/python-beginners.jpg',
                'access_level' => 0,
            ],
            [
                'title' => 'React.js Essentials',
                'description' => 'Build modern web applications with React.js and related technologies.',
                'author_id' => $author,
                'status' => 0, // draft
                'file_path' => 'books/react-essentials.pdf',
                'cover_image' => 'covers/react-essentials.jpg',
                'access_level' => 0,
            ],
            [
                'title' => 'API Design Patterns',
                'description' => 'Learn best practices for designing and building RESTful APIs.',
                'author_id' => $author,
                'status' => 1, // published
                'file_path' => 'books/api-design-patterns.pdf',
                'cover_image' => 'covers/api-design-patterns.jpg',
                'access_level' => 0,
            ],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(['title' => $bookData['title']], $bookData);
        }

        echo "Books created successfully!\n";

        // Get the actual book IDs that were created
        $createdBooks = Book::all();
        $bookIds = $createdBooks->pluck('id')->toArray();
        
        echo "Created book IDs: " . implode(', ', $bookIds) . "\n";

        // Create reviews (using only the books we actually created)
        $reviews = [];
        if (count($bookIds) >= 1) {
            $reviews[] = [
                'book_id' => $bookIds[0], // First created book
                'user_id' => 1,
                'rating' => 5,
                'comment' => 'Excellent book! Very comprehensive and well-written. The examples are practical and easy to follow.',
                'create_at' => Carbon::now()->subDays(2),
            ];
        }
        if (count($bookIds) >= 2) {
            $reviews[] = [
                'book_id' => $bookIds[1], // Second created book
                'user_id' => 2,
                'rating' => 4,
                'comment' => 'Good content but needs more examples. The explanation is clear but practical applications are limited.',
                'create_at' => Carbon::now()->subDays(7),
            ];
        }
        if (count($bookIds) >= 3) {
            $reviews[] = [
                'book_id' => $bookIds[2], // Third created book
                'user_id' => 3,
                'rating' => 5,
                'comment' => 'Amazing JavaScript guide! Covers everything from basics to advanced concepts.',
                'create_at' => Carbon::now()->subDays(5),
            ];
        }
        if (count($bookIds) >= 4) {
            $reviews[] = [
                'book_id' => $bookIds[3], // Fourth created book
                'user_id' => 4,
                'rating' => 4,
                'comment' => 'Decent CSS book but could use more modern techniques.',
                'create_at' => Carbon::now()->subDays(1),
            ];
        }
        if (count($bookIds) >= 5) {
            $reviews[] = [
                'book_id' => $bookIds[4], // Fifth created book
                'user_id' => 5,
                'rating' => 5,
                'comment' => 'Perfect for Python beginners! Very well structured and easy to understand.',
                'create_at' => Carbon::now()->subDays(3),
            ];
        }

        foreach ($reviews as $reviewData) {
            Review::firstOrCreate([
                'book_id' => $reviewData['book_id'],
                'user_id' => $reviewData['user_id']
            ], $reviewData);
        }

        echo "Reviews created successfully!\n";

        // Create downloads (using only the books we actually created)
        $downloads = [];
        if (count($bookIds) >= 1) {
            $downloads[] = [
                'book_id' => $bookIds[0], // First created book
                'user_id' => 1,
                'file_type' => 'PDF',
                'file_size' => 15923840, // 15.2 MB
                'status' => 'completed',
                'downloaded_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 2) {
            $downloads[] = [
                'book_id' => $bookIds[1], // Second created book
                'user_id' => 2,
                'file_type' => 'EPUB',
                'file_size' => 9123840, // 8.7 MB
                'status' => 'completed',
                'downloaded_at' => Carbon::now()->subHours(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 3) {
            $downloads[] = [
                'book_id' => $bookIds[2], // Third created book
                'user_id' => 3,
                'file_type' => 'PDF',
                'file_size' => 13004800, // 12.4 MB
                'status' => 'completed',
                'downloaded_at' => Carbon::now()->subDay(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 4) {
            $downloads[] = [
                'book_id' => $bookIds[3], // Fourth created book
                'user_id' => 4,
                'file_type' => 'ZIP',
                'file_size' => 48043520, // 45.8 MB
                'status' => 'in_progress',
                'downloaded_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 5) {
            $downloads[] = [
                'book_id' => $bookIds[4], // Fifth created book
                'user_id' => 5,
                'file_type' => 'PDF',
                'file_size' => 23185920, // 22.1 MB
                'status' => 'completed',
                'downloaded_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        foreach ($downloads as $downloadData) {
            Download::firstOrCreate([
                'book_id' => $downloadData['book_id'],
                'user_id' => $downloadData['user_id']
            ], $downloadData);
        }

        echo "Downloads created successfully!\n";

        // Create reading progress (using only the books we actually created)
        $readingProgress = [];
        if (count($bookIds) >= 1) {
            $readingProgress[] = [
                'book_id' => $bookIds[0], // First created book
                'user_id' => 1,
                'current_page' => 225,
                'total_pages' => 300,
                'progress_percentage' => 75,
                'status' => 'active',
                'started_at' => Carbon::now()->subDays(3),
                'last_read_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 2) {
            $readingProgress[] = [
                'book_id' => $bookIds[1], // Second created book
                'user_id' => 2,
                'current_page' => 180,
                'total_pages' => 180,
                'progress_percentage' => 100,
                'status' => 'completed',
                'started_at' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(5),
                'last_read_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 3) {
            $readingProgress[] = [
                'book_id' => $bookIds[2], // Third created book
                'user_id' => 3,
                'current_page' => 60,
                'total_pages' => 200,
                'progress_percentage' => 30,
                'status' => 'stalled',
                'started_at' => Carbon::now()->subDays(14),
                'last_read_at' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 4) {
            $readingProgress[] = [
                'book_id' => $bookIds[3], // Fourth created book
                'user_id' => 4,
                'current_page' => 270,
                'total_pages' => 300,
                'progress_percentage' => 90,
                'status' => 'active',
                'started_at' => Carbon::now()->subDays(4),
                'last_read_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        foreach ($readingProgress as $progressData) {
            ReadingProgress::firstOrCreate([
                'book_id' => $progressData['book_id'],
                'user_id' => $progressData['user_id']
            ], $progressData);
        }

        echo "Reading progress created successfully!\n";

        // Create favorites (using only the books we actually created)
        $favorites = [];
        if (count($bookIds) >= 1) {
            $favorites[] = [
                'book_id' => $bookIds[0], // First created book
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 2) {
            $favorites[] = [
                'book_id' => $bookIds[1], // Second created book
                'user_id' => 2,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 3) {
            $favorites[] = [
                'book_id' => $bookIds[2], // Third created book
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now(),
            ];
        }
        if (count($bookIds) >= 5) {
            $favorites[] = [
                'book_id' => $bookIds[4], // Fifth created book
                'user_id' => 5,
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now(),
            ];
        }

        // Create activity logs (only basic ones without book dependency)
        $activities = [
            [
                'user_id' => 1,
                'action' => 'login',
                'description' => 'Alice Reader logged in from IP 192.168.1.100',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => 1,
                'action' => 'settings_updated',
                'description' => 'Admin User updated system settings',
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'action' => 'login_failed',
                'description' => 'Failed login attempt for admin@elibrary.com from IP 192.168.1.200',
                'ip_address' => '192.168.1.200',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDay(),
            ],
        ];

        foreach ($activities as $activityData) {
            ActivityLog::create($activityData);
        }

        echo "Activity logs created successfully!\n";

        echo "Admin dashboard data seeded successfully!\n";
    }
}
