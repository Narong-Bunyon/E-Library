<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class RealBooksSeeder extends Seeder
{
    public function run()
    {
        // Real book data
        $realBooks = [
            [
                'title' => 'The Great Gatsby',
                'description' => 'A classic American novel set in the Jazz Age, exploring themes of wealth, love, and the American Dream through the mysterious Jay Gatsby.',
                'pages' => 180,
                'isbn' => '978-0-7432-7356-5',
                'language' => 'English',
                'publication_date' => '1925-04-10',
                'author_name' => 'F. Scott Fitzgerald',
                'author_email' => 'f.scott.fitzgerald@example.com',
                'categories' => ['Fiction', 'Classic Literature', 'Romance']
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'description' => 'A powerful story of racial injustice and childhood innocence in the American South during the 1930s, told through the eyes of Scout Finch.',
                'pages' => 324,
                'isbn' => '978-0-06-112008-4',
                'language' => 'English',
                'publication_date' => '1960-07-11',
                'author_name' => 'Harper Lee',
                'author_email' => 'harper.lee@example.com',
                'categories' => ['Fiction', 'Classic Literature', 'Drama']
            ],
            [
                'title' => '1984',
                'description' => 'A dystopian social science fiction novel that explores the dangers of totalitarianism, mass surveillance, and repressive regimentation.',
                'pages' => 328,
                'isbn' => '978-0-452-28423-4',
                'language' => 'English',
                'publication_date' => '1949-06-08',
                'author_name' => 'George Orwell',
                'author_email' => 'george.orwell@example.com',
                'categories' => ['Fiction', 'Science Fiction', 'Dystopian']
            ],
            [
                'title' => 'Pride and Prejudice',
                'description' => 'A romantic novel of manners that charts the emotional development of Elizabeth Bennet and her relationship with the aristocratic Mr. Darcy.',
                'pages' => 432,
                'isbn' => '978-0-14-143951-8',
                'language' => 'English',
                'publication_date' => '1813-01-28',
                'author_name' => 'Jane Austen',
                'author_email' => 'jane.austen@example.com',
                'categories' => ['Fiction', 'Romance', 'Classic Literature']
            ],
            [
                'title' => 'The Catcher in the Rye',
                'description' => 'The story of Holden Caulfield, a teenager who leaves his prep school and wanders around New York City for several days, dealing with alienation and growing up.',
                'pages' => 234,
                'isbn' => '978-0-316-76948-0',
                'language' => 'English',
                'publication_date' => '1951-07-16',
                'author_name' => 'J.D. Salinger',
                'author_email' => 'jd.salinger@example.com',
                'categories' => ['Fiction', 'Coming-of-age', 'Classic Literature']
            ],
            [
                'title' => 'The Hobbit',
                'description' => 'A fantasy novel about the adventures of hobbit Bilbo Baggins, who is thrust into an epic quest to reclaim the lost Dwarf Kingdom of Erebor.',
                'pages' => 310,
                'isbn' => '978-0-547-92822-7',
                'language' => 'English',
                'publication_date' => '1937-09-21',
                'author_name' => 'J.R.R. Tolkien',
                'author_email' => 'jrr.tolkien@example.com',
                'categories' => ['Fantasy', 'Adventure', 'Classic Literature']
            ],
            [
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'description' => 'The first book in the beloved series about a young wizard who discovers his magical heritage and begins his education at Hogwarts School of Witchcraft and Wizardry.',
                'pages' => 309,
                'isbn' => '978-0-439-70818-8',
                'language' => 'English',
                'publication_date' => '1997-06-26',
                'author_name' => 'J.K. Rowling',
                'author_email' => 'jk.rowling@example.com',
                'categories' => ['Fantasy', 'Young Adult', 'Adventure']
            ],
            [
                'title' => 'The Da Vinci Code',
                'description' => 'A mystery thriller that follows symbologist Robert Langdon as he investigates a murder in the Louvre Museum and discovers a battle between the Priory of Sion and Opus Dei.',
                'pages' => 689,
                'isbn' => '978-0-385-50420-5',
                'language' => 'English',
                'publication_date' => '2003-03-18',
                'author_name' => 'Dan Brown',
                'author_email' => 'dan.brown@example.com',
                'categories' => ['Mystery', 'Thriller', 'Fiction']
            ],
            [
                'title' => 'The Alchemist',
                'description' => 'A philosophical book that tells the story of Santiago, an Andalusian shepherd boy who dreams of finding treasure in the Egyptian pyramids.',
                'pages' => 208,
                'isbn' => '978-0-06-250217-4',
                'language' => 'English',
                'publication_date' => '1988-05-01',
                'author_name' => 'Paulo Coelho',
                'author_email' => 'paulo.coelho@example.com',
                'categories' => ['Fiction', 'Philosophy', 'Adventure']
            ],
            [
                'title' => 'The Hunger Games',
                'description' => 'A dystopian novel set in Panem, where a boy and girl from each of the twelve districts are chosen to participate in a televised death match called The Hunger Games.',
                'pages' => 374,
                'isbn' => '978-0-439-02348-1',
                'language' => 'English',
                'publication_date' => '2008-09-14',
                'author_name' => 'Suzanne Collins',
                'author_email' => 'suzanne.collins@example.com',
                'categories' => ['Young Adult', 'Dystopian', 'Science Fiction']
            ],
            [
                'title' => 'The Lord of the Rings',
                'description' => 'An epic high-fantasy novel following the hobbit Frodo Baggins as he and the Fellowship embark on a quest to destroy the One Ring and defeat the Dark Lord Sauron.',
                'pages' => 1216,
                'isbn' => '978-0-544-00341-5',
                'language' => 'English',
                'publication_date' => '1954-07-29',
                'author_name' => 'J.R.R. Tolkien',
                'author_email' => 'jrr.tolkien.lotr@example.com',
                'categories' => ['Fantasy', 'Epic', 'Adventure']
            ],
            [
                'title' => 'Brave New World',
                'description' => 'A dystopian novel set in a futuristic World State where citizens are environmentally conditioned into an intelligence-based social hierarchy.',
                'pages' => 311,
                'isbn' => '978-0-06-085052-4',
                'language' => 'English',
                'publication_date' => '1932-08-30',
                'author_name' => 'Aldous Huxley',
                'author_email' => 'aldous.huxley@example.com',
                'categories' => ['Science Fiction', 'Dystopian', 'Classic Literature']
            ],
            [
                'title' => 'The Kite Runner',
                'description' => 'A story of friendship, betrayal, and redemption set in Afghanistan, following the life of Amir from his childhood in Kabul to his adult life in America.',
                'pages' => 371,
                'isbn' => '978-1-59448-000-3',
                'language' => 'English',
                'publication_date' => '2003-05-29',
                'author_name' => 'Khaled Hosseini',
                'author_email' => 'khaled.hosseini@example.com',
                'categories' => ['Fiction', 'Drama', 'Contemporary']
            ],
            [
                'title' => 'Life of Pi',
                'description' => 'A philosophical novel about an Indian boy named Pi who survives 227 days after a shipwreck while stranded on a lifeboat in the Pacific Ocean with a Bengal tiger.',
                'pages' => 352,
                'isbn' => '978-0-15-100811-7',
                'language' => 'English',
                'publication_date' => '2001-09-11',
                'author_name' => 'Yann Martel',
                'author_email' => 'yann.martel@example.com',
                'categories' => ['Fiction', 'Adventure', 'Philosophy']
            ],
            [
                'title' => 'The Girl with the Dragon Tattoo',
                'description' => 'A psychological thriller about journalist Mikael Blomkvist and hacker Lisbeth Salander who investigate the disappearance of a wealthy industrialist\'s niece.',
                'pages' => 672,
                'isbn' => '978-0-307-47347-9',
                'language' => 'English',
                'publication_date' => '2005-08-01',
                'author_name' => 'Stieg Larsson',
                'author_email' => 'stieg.larsson@example.com',
                'categories' => ['Mystery', 'Thriller', 'Crime Fiction']
            ],
            [
                'title' => 'Gone Girl',
                'description' => 'A psychological thriller about the disappearance of Amy Dunne and the media frenzy that ensues, with her husband Nick becoming the primary suspect.',
                'pages' => 432,
                'isbn' => '978-0-307-58837-1',
                'language' => 'English',
                'publication_date' => '2012-06-05',
                'author_name' => 'Gillian Flynn',
                'author_email' => 'gillian.flynn@example.com',
                'categories' => ['Mystery', 'Thriller', 'Psychological Fiction']
            ],
            [
                'title' => 'The Fault in Our Stars',
                'description' => 'A young adult novel about two teenagers with cancer who fall in love and deal with their illness while exploring life, love, and mortality.',
                'pages' => 313,
                'isbn' => '978-0-525-47881-2',
                'language' => 'English',
                'publication_date' => '2012-01-10',
                'author_name' => 'John Green',
                'author_email' => 'john.green@example.com',
                'categories' => ['Young Adult', 'Romance', 'Contemporary Fiction']
            ],
            [
                'title' => 'The Martian',
                'description' => 'A science fiction novel about astronaut Mark Watney who becomes stranded alone on Mars and must improvise to survive until a rescue mission can reach him.',
                'pages' => 369,
                'isbn' => '978-0-8041-3902-1',
                'language' => 'English',
                'publication_date' => '2011-09-27',
                'author_name' => 'Andy Weir',
                'author_email' => 'andy.weir@example.com',
                'categories' => ['Science Fiction', 'Adventure', 'Survival']
            ],
            [
                'title' => 'Wonder',
                'description' => 'A children\'s novel about Auggie Pullman, a boy with facial differences, who attends mainstream school for the first time and learns about friendship and acceptance.',
                'pages' => 320,
                'isbn' => '978-0-375-86690-2',
                'language' => 'English',
                'publication_date' => '2012-02-14',
                'author_name' => 'R.J. Palacio',
                'author_email' => 'rj.palacio@example.com',
                'categories' => ['Children\'s Literature', 'Contemporary Fiction', 'Inspirational']
            ]
        ];

        // Get or create users/authors
        $authors = [];
        foreach ($realBooks as $bookData) {
            if (!isset($authors[$bookData['author_email']])) {
                $user = User::firstOrCreate([
                    'email' => $bookData['author_email']
                ], [
                    'name' => $bookData['author_name'],
                    'password' => bcrypt('password'),
                    'role' => 'author',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Create author record
                \DB::table('authorse')->insert([
                    'user_id' => $user->id,
                    'bio' => 'Famous author known for their literary contributions.',
                    'approved_by_admin' => true
                ]);

                $authors[$bookData['author_email']] = $user;
            }
        }

        // Get or create categories
        $allCategories = [];
        foreach ($realBooks as $bookData) {
            foreach ($bookData['categories'] as $categoryName) {
                if (!isset($allCategories[$categoryName])) {
                    $category = Category::firstOrCreate([
                        'name' => $categoryName
                    ]);
                    $allCategories[$categoryName] = $category;
                }
            }
        }

        // Delete all existing books
        Book::query()->delete();

        // Create new books with real data
        foreach ($realBooks as $bookData) {
            $author = $authors[$bookData['author_email']];
            
            $book = Book::create([
                'title' => $bookData['title'],
                'description' => $bookData['description'],
                'pages' => $bookData['pages'],
                'isbn' => $bookData['isbn'],
                'language' => $bookData['language'],
                'published_date' => $bookData['publication_date'],
                'author_id' => $author->id,
                'status' => 1, // Published
                'cover_image' => null,
                'file_path' => 'books/sample.pdf' // Add default file path
            ]);

            // Attach categories
            foreach ($bookData['categories'] as $categoryName) {
                $book->categories()->attach($allCategories[$categoryName]->id);
            }
        }

        $this->command->info('Successfully created ' . count($realBooks) . ' real books!');
    }
}
