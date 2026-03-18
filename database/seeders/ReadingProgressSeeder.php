<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReadingProgress;
use App\Models\Book;
use App\Models\User;

class ReadingProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get any user and book, or create sample ones
        $user = User::first() ?? User::create([
            'name' => 'Sample User',
            'email' => 'sample@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'status' => 1,
        ]);

        $book = Book::first() ?? Book::create([
            'title' => 'Sample Book',
            'description' => 'A sample book for testing reading progress',
            'author_id' => 1,
            'pages' => 100,
            'status' => 1,
        ]);

        if ($user && $book) {
            // Create reading progress with the specified data (10% progress)
            ReadingProgress::create([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'current_page' => 10,
                'total_pages' => 100,
                'progress_percentage' => 10,
                'status' => 'in_progress',
                'started_at' => now(),
                'completed_at' => null,
                'last_read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "Reading progress entry created successfully!\n";
            echo "User: {$user->name} (ID: {$user->id})\n";
            echo "Book: {$book->title} (ID: {$book->id})\n";
            echo "Progress: 10% (Page 10 of 100)\n";
        } else {
            echo "No users or books found. Please run user and book seeders first.\n";
        }
    }
}
