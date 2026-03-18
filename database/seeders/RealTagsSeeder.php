<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class RealTagsSeeder extends Seeder
{
    public function run()
    {
        // Real book tags with descriptions and colors
        $realTags = [
            [
                'name' => 'Bestseller',
                'description' => 'Books that have achieved significant commercial success and popularity',
                'color' => '#28a745'
            ],
            [
                'name' => 'Award Winner',
                'description' => 'Books that have won prestigious literary awards',
                'color' => '#ffc107'
            ],
            [
                'name' => 'Classic',
                'description' => 'Timeless literary works of enduring quality and significance',
                'color' => '#6f42c1'
            ],
            [
                'name' => 'Contemporary',
                'description' => 'Modern fiction written in recent decades',
                'color' => '#17a2b8'
            ],
            [
                'name' => 'Historical Fiction',
                'description' => 'Stories set in historical periods with fictional characters',
                'color' => '#fd7e14'
            ],
            [
                'name' => 'Science Fiction',
                'description' => 'Fiction based on imagined future scientific or technological advances',
                'color' => '#20c997'
            ],
            [
                'name' => 'Fantasy',
                'description' => 'Stories featuring magical elements, mythical creatures, and imaginary worlds',
                'color' => '#6610f2'
            ],
            [
                'name' => 'Mystery',
                'description' => 'Stories involving puzzles, crimes, and investigations',
                'color' => '#343a40'
            ],
            [
                'name' => 'Thriller',
                'description' => 'Suspenseful stories designed to create excitement and tension',
                'color' => '#dc3545'
            ],
            [
                'name' => 'Romance',
                'description' => 'Stories centered on romantic relationships and love',
                'color' => '#e83e8c'
            ],
            [
                'name' => 'Young Adult',
                'description' => 'Books written specifically for teenagers and young adults',
                'color' => '#fd7e14'
            ],
            [
                'name' => 'Children',
                'description' => 'Books appropriate for children and young readers',
                'color' => '#20c997'
            ],
            [
                'name' => 'Non-Fiction',
                'description' => 'Factual books based on real events, people, and information',
                'color' => '#007bff'
            ],
            [
                'name' => 'Biography',
                'description' => 'Life stories of real people written by others',
                'color' => '#6c757d'
            ],
            [
                'name' => 'Autobiography',
                'description' => 'Life stories written by the subjects themselves',
                'color' => '#495057'
            ],
            [
                'name' => 'Self-Help',
                'description' => 'Books designed to help readers improve themselves',
                'color' => '#28a745'
            ],
            [
                'name' => 'Business',
                'description' => 'Books about business, entrepreneurship, and management',
                'color' => '#007bff'
            ],
            [
                'name' => 'Psychology',
                'description' => 'Books exploring human behavior and mental processes',
                'color' => '#6f42c1'
            ],
            [
                'name' => 'Philosophy',
                'description' => 'Books dealing with fundamental questions about existence and knowledge',
                'color' => '#343a40'
            ],
            [
                'name' => 'Dystopian',
                'description' => 'Stories set in oppressive, futuristic societies',
                'color' => '#6c757d'
            ],
            [
                'name' => 'Adventure',
                'description' => 'Exciting stories involving journeys and dangerous exploits',
                'color' => '#fd7e14'
            ],
            [
                'name' => 'Horror',
                'description' => 'Stories designed to frighten and unsettle readers',
                'color' => '#dc3545'
            ],
            [
                'name' => 'Humor',
                'description' => 'Books written to entertain and amuse readers',
                'color' => '#ffc107'
            ],
            [
                'name' => 'Poetry',
                'description' => 'Literary works expressed in verse and rhythmic language',
                'color' => '#e83e8c'
            ],
            [
                'name' => 'Drama',
                'description' => 'Stories focused on serious and emotional themes',
                'color' => '#17a2b8'
            ],
            [
                'name' => 'Epic',
                'description' => 'Long, grand stories of heroic deeds and adventures',
                'color' => '#6610f2'
            ],
            [
                'name' => 'Series',
                'description' => 'Books that are part of a multi-volume series',
                'color' => '#20c997'
            ],
            [
                'name' => 'Standalone',
                'description' => 'Complete stories that are not part of any series',
                'color' => '#007bff'
            ],
            [
                'name' => 'Short Story',
                'description' => 'Collections of brief fictional narratives',
                'color' => '#6f42c1'
            ],
            [
                'name' => 'Graphic Novel',
                'description' => 'Stories told through sequential art and illustrations',
                'color' => '#fd7e14'
            ],
            [
                'name' => 'Literary Fiction',
                'description' => 'Fiction focused on artistic merit and character development',
                'color' => '#495057'
            ]
        ];

        // Delete all existing tags
        Tag::query()->delete();

        // Create new tags with real data
        foreach ($realTags as $tagData) {
            Tag::create([
                'name' => $tagData['name'],
                'description' => $tagData['description'],
                'color' => $tagData['color']
            ]);
        }

        $this->command->info('Successfully created ' . count($realTags) . ' real tags!');
    }
}
