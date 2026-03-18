<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Download;
use App\Models\Book;
use App\Models\User;

class DownloadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users and books
        $users = User::take(5)->get();
        $books = Book::take(3)->get();

        if ($users->isEmpty() || $books->isEmpty()) {
            echo "No users or books found. Please run user and book seeders first.\n";
            return;
        }

        // Create sample downloads
        $downloads = [];
        $now = now();
        
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $book = $books->random();
            $createdAt = $now->copy()->subDays(rand(0, 30));
            
            $downloads[] = [
                'user_id' => $user->id,
                'book_id' => $book->id,
                'file_type' => ['PDF', 'EPUB', 'MOBI'][rand(0, 2)],
                'file_size' => rand(1000000, 5000000), // 1-5 MB
                'status' => rand(0, 1) ? 'completed' : 'in_progress',
                'downloaded_at' => rand(0, 1) ? $createdAt : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        Download::insert($downloads);
        
        echo "Created " . count($downloads) . " download records successfully!\n";
        echo "Total Downloads: " . Download::count() . "\n";
        echo "Completed Downloads: " . Download::where('status', 'completed')->count() . "\n";
        echo "Unique Users: " . Download::distinct('user_id')->count() . "\n";
    }
}
