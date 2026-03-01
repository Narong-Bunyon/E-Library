<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReadingHistory;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class ReadingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users to create reading history for each
        $users = User::take(5)->get(); // Get first 5 users
        
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please create users first.');
            return;
        }

        // Get some books to use for reading history
        $books = Book::take(10)->get();
        
        if ($books->isEmpty()) {
            $this->command->error('No books found. Please create some books first.');
            return;
        }

        // Sample reading history data
        $readingHistoryData = [
            [
                'status' => 'completed',
                'progress_percentage' => 100,
                'pages_read' => 250,
                'total_pages' => 250,
                'notes' => 'Great book! Really enjoyed the storyline and character development.',
                'started_at' => Carbon::now()->subDays(30),
                'completed_at' => Carbon::now()->subDays(15),
            ],
            [
                'status' => 'reading',
                'progress_percentage' => 65,
                'pages_read' => 130,
                'total_pages' => 200,
                'notes' => 'Currently reading this one. Very interesting plot so far.',
                'started_at' => Carbon::now()->subDays(10),
                'completed_at' => null,
            ],
            [
                'status' => 'completed',
                'progress_percentage' => 100,
                'pages_read' => 180,
                'total_pages' => 180,
                'notes' => 'Finished this yesterday. The ending was unexpected!',
                'started_at' => Carbon::now()->subDays(20),
                'completed_at' => Carbon::now()->subDays(1),
            ],
            [
                'status' => 'paused',
                'progress_percentage' => 35,
                'pages_read' => 70,
                'total_pages' => 200,
                'notes' => 'Taking a break from this one. Will continue later.',
                'started_at' => Carbon::now()->subDays(25),
                'completed_at' => null,
            ],
            [
                'status' => 'completed',
                'progress_percentage' => 100,
                'pages_read' => 320,
                'total_pages' => 320,
                'notes' => 'Excellent technical book. Learned a lot from it.',
                'started_at' => Carbon::now()->subDays(45),
                'completed_at' => Carbon::now()->subDays(30),
            ],
        ];

        // Create reading history entries for each user
        foreach ($users as $user) {
            foreach ($readingHistoryData as $index => $data) {
                $book = $books->get($index % $books->count()); // Cycle through available books
                
                ReadingHistory::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'status' => $data['status'],
                    'progress_percentage' => $data['progress_percentage'],
                    'pages_read' => $data['pages_read'],
                    'total_pages' => $data['total_pages'],
                    'notes' => $data['notes'],
                    'started_at' => $data['started_at'],
                    'completed_at' => $data['completed_at'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            $this->command->info('Created 5 reading history entries for user ID: ' . $user->id);
        }
    }
}
