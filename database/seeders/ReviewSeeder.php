<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Book;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing books and users
        $books = Book::all();
        $users = User::all();
        
        if ($books->isEmpty() || $users->isEmpty()) {
            echo "No books or users found. Please run BookSeeder and UserSeeder first.\n";
            return;
        }

        // Sample review comments
        $comments = [
            "Excellent book! Very comprehensive and well-written. The examples are practical and easy to follow.",
            "Great content but needs more examples. The explanation is clear but practical applications are limited.",
            "Good introduction to database concepts. The SQL examples are helpful for beginners.",
            "Amazing resource for web development! Covers all the modern frameworks and best practices.",
            "Well-structured and informative. Perfect for both beginners and experienced developers.",
            "The author explains complex topics in a simple way. Highly recommended for students.",
            "Comprehensive guide with real-world examples. Helped me understand the concepts better.",
            "Could be more detailed in some chapters, but overall a good reference book.",
            "Outstanding! The practical exercises and case studies make learning much easier.",
            "A must-read for anyone interested in this field. Clear explanations and good examples.",
            "Some chapters are too advanced for beginners, but overall it's a valuable resource.",
            "Perfect balance between theory and practice. The code examples work flawlessly.",
            "Engaging writing style and thorough coverage of the topic. Worth every penny!",
            "Good for quick reference, but lacks depth in some areas. Still useful overall.",
            "Exceptional quality! The author's expertise shines through in every chapter.",
            "Decent content but the organization could be better. Some topics feel out of place.",
            "Very practical approach with step-by-step tutorials. Great for hands-on learning.",
            "Comprehensive coverage with modern best practices. Updated with latest standards.",
            "Clear explanations but some examples are outdated. Still a solid foundation.",
            "Excellent for self-study. The exercises reinforce learning effectively."
        ];

        // Create 25 reviews
        for ($i = 1; $i <= 25; $i++) {
            $book = $books->random();
            $user = $users->random();
            
            Review::create([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'rating' => rand(3, 5), // Random rating between 3 and 5
                'comment' => $comments[array_rand($comments)],
                'status' => rand(0, 2) === 0 ? 'pending' : (rand(0, 1) === 0 ? 'approved' : 'rejected'),
                'create_at' => now()->subDays(rand(1, 30)), // Random date within last 30 days
            ]);
        }

        echo "Created 25 sample reviews successfully!\n";
    }
}
