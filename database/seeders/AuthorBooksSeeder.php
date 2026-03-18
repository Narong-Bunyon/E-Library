<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthorBooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the author user with ID 2
        $author = User::find(2);
        
        if (!$author) {
            echo "Author with ID 2 not found!\n";
            return;
        }

        // Create 5 sample books for this author
        $books = [
            [
                'title' => 'The Art of Programming',
                'description' => 'A comprehensive guide to modern programming practices and software development methodologies. This book covers everything from basic concepts to advanced techniques used by professional developers worldwide. Learn how to write clean, efficient, and maintainable code.',
                'author_id' => 2,
                'category_id' => null,
                'access_level' => 1, // Private
                'status' => 0, // Draft
                'pages' => 450,
                'language' => 'en',
                'isbn' => '978-1-2345-6789-0',
                'published_date' => '2024-01-15',
                'views' => 0,
                'downloads' => 0,
                'file_path' => '',
                'cover_image' => '',
                'create_at' => now(),
                'created_at' => now(),
            ],
            [
                'title' => 'Web Development Mastery',
                'description' => 'Master the art of web development with this complete guide. From HTML and CSS basics to advanced JavaScript frameworks, this book takes you on a journey to become a full-stack web developer.',
                'author_id' => 2,
                'category_id' => null,
                'access_level' => 1, // Private
                'status' => 1, // Published
                'pages' => 380,
                'language' => 'en',
                'isbn' => '978-1-2345-6789-1',
                'published_date' => '2024-02-20',
                'views' => 25,
                'downloads' => 12,
                'file_path' => '',
                'cover_image' => '',
                'create_at' => now(),
                'created_at' => now(),
            ],
            [
                'title' => 'Database Design Patterns',
                'description' => 'Explore advanced database design patterns and best practices for building scalable and efficient database systems. This book is essential for anyone working with data-intensive applications.',
                'author_id' => 2,
                'category_id' => null,
                'access_level' => 0, // Public
                'status' => 1, // Published
                'pages' => 320,
                'language' => 'en',
                'isbn' => '978-1-2345-6789-2',
                'published_date' => '2024-03-10',
                'views' => 156,
                'downloads' => 89,
                'file_path' => '',
                'cover_image' => '',
                'create_at' => now(),
                'created_at' => now(),
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Build amazing mobile applications for iOS and Android using modern frameworks and tools. This comprehensive guide covers everything from UI design to app deployment.',
                'author_id' => 2,
                'category_id' => null,
                'access_level' => 2, // Premium
                'status' => 0, // Draft
                'pages' => 420,
                'language' => 'en',
                'isbn' => '978-1-2345-6789-3',
                'published_date' => '2024-04-05',
                'views' => 0,
                'downloads' => 0,
                'file_path' => '',
                'cover_image' => '',
                'create_at' => now(),
                'created_at' => now(),
            ],
            [
                'title' => 'Cloud Computing Essentials',
                'description' => 'Understanding cloud computing architecture, services, and deployment strategies. Learn how to leverage cloud platforms like AWS, Azure, and Google Cloud for your applications.',
                'author_id' => 2,
                'category_id' => null,
                'access_level' => 1, // Private
                'status' => 1, // Published
                'pages' => 280,
                'language' => 'en',
                'isbn' => '978-1-2345-6789-4',
                'published_date' => '2024-05-12',
                'views' => 78,
                'downloads' => 34,
                'file_path' => '',
                'cover_image' => '',
                'create_at' => now(),
                'created_at' => now(),
            ],
        ];

        // Insert books into database
        foreach ($books as $book) {
            Book::create($book);
        }

        echo "Successfully created 5 books for author ID 2\n";
    }
}
