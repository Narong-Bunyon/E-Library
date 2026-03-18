<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RealReviewsSeeder extends Seeder
{
    public function run()
    {
        // Get all books and users
        $books = Book::all();
        $users = User::where('role', 'user')->orWhere('role', 'author')->get();

        if ($books->isEmpty() || $users->isEmpty()) {
            $this->command->info('No books or users found for creating reviews');
            return;
        }

        // Book-specific review data with authentic comments
        $bookSpecificReviews = [
            'The Great Gatsby' => [
                "Fitzgerald's prose is absolutely mesmerizing. The way he captures the decadence of the Jazz Age while exploring the corruption of the American Dream is masterful. Gatsby's tragic quest for Daisy feels both heartbreaking and inevitable.",
                "The green light symbolism alone makes this a masterpiece. Every read reveals new layers of meaning about wealth, love, and the impossibility of recreating the past. Simply brilliant.",
                "While the writing is beautiful, I found the characters somewhat unlikable. Still, the social commentary and the tragic ending make this a must-read classic that deserves its place in literary canon.",
                "This book perfectly captures the illusion of the American Dream. Gatsby's parties, the mysterious background, and the ultimate tragedy create a powerful narrative that resonates even today."
            ],
            'To Kill a Mockingbird' => [
                "Harper Lee's portrayal of childhood innocence面对 racial injustice is absolutely powerful. Scout's perspective makes complex moral issues accessible without oversimplifying them. A true American classic.",
                "Atticus Finch remains one of literature's greatest heroes. His defense of Tom Robinson and his lessons to Scout about empathy and courage changed how I view justice and morality.",
                "The way this book handles difficult topics through a child's eyes is remarkable. It's heartbreaking yet hopeful, and every reading reveals new depths about human nature and social justice.",
                "More than just a story about racial injustice, this is a profound exploration of growing up, understanding others, and standing up for what's right even when it's hard."
            ],
            '1984' => [
                "Orwell's vision of totalitarianism feels terrifyingly relevant today. The concepts of doublethink, thoughtcrime, and constant surveillance are more prophetic than ever. Essential reading.",
                "The psychological horror of this book is unparalleled. Winston's struggle against the system and his ultimate breakdown show how totalitarian regimes destroy not just bodies but minds.",
                "This book gave me nightmares, but in the best way. The world-building is so complete and the themes so relevant that it feels like a warning rather than fiction.",
                "Big Brother is watching, and after reading this, you'll understand why that phrase sends chills down your spine. A masterpiece of dystopian fiction that's unfortunately timeless."
            ],
            'Pride and Prejudice' => [
                "Austen's wit and social commentary are absolutely brilliant. Elizabeth Bennet is one of literature's most compelling heroines - intelligent, flawed, and wonderfully human.",
                "The dialogue sparkles with intelligence and humor. The slow burn between Elizabeth and Darcy is incredibly satisfying, and the social satire remains sharp and relevant.",
                "This is the ultimate enemies-to-lovers story. The character development, the witty banter, and the satisfying resolution make it a perfect romance that never gets old.",
                "Beyond the romance, this is a brilliant critique of class, marriage, and social expectations. Austen's understanding of human nature makes every character feel real and relatable."
            ],
            'The Catcher in the Rye' => [
                "Holden Caulfield's voice is absolutely authentic - angsty, confused, and vulnerable. His struggle with growing up and the phoniness of adult world resonates deeply.",
                "Some find Holden annoying, but I think he perfectly captures teenage alienation. His journey through New York City is both heartbreaking and darkly funny.",
                "This book speaks to anyone who's ever felt like an outsider. Holden's voice stays with you long after finishing, and his observations about adulthood are painfully accurate.",
                "The raw honesty of Holden's narration makes this unforgettable. It's not about plot but about the feeling of being lost and trying to find your place in the world."
            ],
            'The Hobbit' => [
                "Tolkien's ability to create an entire world is astounding. Bilbo's journey from comfort-loving hobbit to brave adventurer is wonderfully told and perfectly paced.",
                "The dragon Smaug is one of literature's greatest villains - clever, arrogant, and terrifying. The riddle game with Gollum alone makes this worth reading.",
                "This is the perfect portal fantasy. The journey feels epic yet personal, and Bilbo's growth from reluctant hero to genuine adventurer is beautifully handled.",
                "Tolkien's love of language and mythology shines through every page. The world-building feels ancient and real, making Middle Earth feel like a place you could actually visit."
            ],
            'Harry Potter and the Sorcerer\'s Stone' => [
                "The moment Harry discovers he's a wizard is pure magic. Rowling created a world that feels both fantastic and familiar, making readers believe they too might get a Hogwarts letter.",
                "The friendship between Harry, Ron, and Hermione is the heart of this series. Their dynamic feels genuine and their adventures at Hogwarts are absolutely enchanting.",
                "This book reignited my love for reading. The blend of everyday school life with magical elements creates a world that's both wondrous and relatable.",
                "Rowling's imagination is boundless. From the sorting hat to the moving staircases, every detail of Hogwarts feels carefully crafted and endlessly fascinating."
            ],
            'The Da Vinci Code' => [
                "A perfectly paced thriller that keeps you turning pages late into the night. The blend of art history, religious conspiracy, and murder mystery is absolutely addictive.",
                "Brown's research into art and religious history adds fascinating depth to this thriller. Even when you know it's fiction, the historical details make you question everything.",
                "This book is like a treasure hunt through European history. The puzzles, the chase scenes, and the revelations create a reading experience that's both intellectual and thrilling.",
                "The controversy around this book only adds to its appeal. Whether you believe the theories or not, it's a brilliantly constructed mystery that challenges conventional thinking."
            ],
            'The Alchemist' => [
                "A simple story with profound wisdom about following your dreams. Santiago's journey teaches that the treasure we seek is often found in the journey itself.",
                "This book changed my perspective on life and destiny. The idea that when you want something, the universe conspires to help you achieve it is both comforting and inspiring.",
                "A beautiful allegory about finding your purpose. The writing is simple but the message is deep - a reminder that the greatest treasures are often found within.",
                "Coelho's wisdom about life, love, and destiny feels both ancient and immediate. This is the kind of book that speaks to you exactly when you need to hear its message."
            ],
            'The Hunger Games' => [
                "Katniss is one of the strongest heroines in YA literature. Her struggle to survive while maintaining her humanity in the face of cruelty is absolutely compelling.",
                "The premise is terrifying but brilliant. The social commentary on reality TV and violence as entertainment is sharp and relevant, making this much more than just an adventure story.",
                "I couldn't put this down. The tension, the moral complexity, and Katniss's fight against an oppressive system create a story that's both thrilling and thought-provoking.",
                "Collins created a dystopian world that feels terrifyingly possible. The games are brutal, but the real horror is the society that allows them to happen."
            ],
            'The Lord of the Rings' => [
                "The epic fantasy to end all epics. Tolkien's world-building is unmatched, and the journey of the Fellowship feels both mythic and deeply personal.",
                "Frodo's burden and Sam's loyalty represent the best of friendship under pressure. The scale of this story is breathtaking, but the human moments make it truly great.",
                "This isn't just fantasy - it's mythology. The languages, histories, and cultures Tolkien created feel real, making Middle Earth the most fully realized fictional world ever written.",
                "The battle between good and evil has never been more compelling. The sacrifice, courage, and hope in the face of overwhelming darkness make this truly timeless."
            ],
            'Brave New World' => [
                "Huxley's vision of a society controlled by pleasure and conditioning is more subtle and perhaps more realistic than Orwell's. The question of whether we'd choose comfort over freedom is terrifying.",
                "The concept of conditioning people to love their servitude is brilliant and disturbing. This book challenges our ideas about happiness, freedom, and what it means to be human.",
                "Unlike other dystopias, this world doesn't use force but pleasure to control people. The question of whether we'd choose ignorant bliss over painful truth is deeply unsettling.",
                "Huxley's critique of consumerism and instant gratification feels more relevant than ever. The society he created is frightening because it's seductive rather than oppressive."
            ],
            'The Kite Runner' => [
                "A heartbreaking story of friendship, betrayal, and redemption. Amir's journey from cowardice to courage is beautifully and painfully rendered.",
                "The line 'For you, a thousand times over' absolutely destroyed me. This book explores guilt, forgiveness, and the possibility of redemption with incredible emotional depth.",
                "Hosseini's portrayal of Afghanistan before and during the Taliban is both loving and devastating. The personal story set against historical tragedy makes this unforgettable.",
                "This book broke my heart and put it back together. The exploration of father-son relationships, friendship, and the weight of the past is absolutely masterful."
            ],
            'Life of Pi' => [
                "A philosophical adventure that challenges our understanding of truth and belief. Pi's survival story with Richard Parker is both harrowing and strangely beautiful.",
                "The question of which story is true - the one with animals or the one without - makes this book brilliant. Martel explores faith, storytelling, and the nature of reality.",
                "The relationship between Pi and Richard Parker is fascinating. The survival story is thrilling, but the philosophical questions about faith and truth make it profound.",
                "This book works on so many levels - adventure story, philosophical meditation, exploration of faith. The ending forces you to reconsider everything you've read."
            ],
            'The Girl with the Dragon Tattoo' => [
                "Lisbeth Salander is one of the most compelling characters in modern fiction. The combination of mystery, family secrets, and social commentary is absolutely addictive.",
                "The dark atmosphere of Sweden combined with the complex mystery creates a thriller that's both intellectual and visceral. Larsson's writing is sharp and unflinching.",
                "This isn't just a mystery - it's a powerful critique of violence against women and corruption in Swedish society. The thriller elements serve a deeper social purpose.",
                "The partnership between Mikael and Lisbeth is fascinating. Two damaged people finding common ground while solving a decades-old mystery makes for compelling reading."
            ],
            'Gone Girl' => [
                "A psychological thriller that completely messes with your expectations. The unreliable narrators and plot twists create a reading experience that's both disorienting and brilliant.",
                "Amy Dunne is one of the most terrifying villains I've ever encountered. Flynn's exploration of marriage, media, and the dark side of relationships is absolutely chilling.",
                "This book changes gears halfway through in the most shocking way. The commentary on marriage, gender roles, and media manipulation is sharp and deeply unsettling.",
                "Flynn's understanding of the dark undercurrents in relationships is terrifyingly accurate. The way she plays with reader expectations makes this a masterclass in psychological suspense."
            ],
            'The Fault in Our Stars' => [
                "A beautiful, heartbreaking story about love and life in the face of mortality. Hazel and Augustus's relationship feels authentic and their philosophical conversations about life and death are profound.",
                "The humor in the face of tragedy makes this book special. Green's ability to find joy and meaning in difficult circumstances without being sentimental is remarkable.",
                "This book made me laugh and cry, sometimes on the same page. The exploration of teenage love, illness, and the meaning of a good life is both beautiful and devastating.",
                "The wit and intelligence of the teenage characters elevate this beyond typical YA romance. Their philosophical discussions about life, death, and legacy are surprisingly deep."
            ],
            'The Martian' => [
                "Mark Watney's humor and scientific problem-solving make survival on Mars absolutely compelling. The blend of hard science and witty narration is perfectly balanced.",
                "The science in this book makes the story feel real and urgent. Watney's creative solutions to impossible problems make for thrilling reading.",
                "This is the ultimate problem-solving story. The combination of scientific accuracy, humor, and genuine suspense makes it one of the best science fiction novels I've ever read.",
                "Watney's optimistic humor in the face of certain death makes him incredibly relatable. The science is fascinating but the human story is what makes this unforgettable."
            ],
            'Wonder' => [
                "A beautiful, important book about kindness and acceptance. Auggie's journey teaches profound lessons about empathy, courage, and what it means to be human.",
                "The multiple perspectives work brilliantly to show how one person's difference affects an entire community. This book should be required reading for empathy.",
                "Auggie's voice is authentic and heartwarming. The exploration of bullying, friendship, and family love handles difficult topics with grace and wisdom.",
                "This book reminds us that everyone is fighting a hard battle. The message about choosing kindness over being right is simple but profound and much needed."
            ]
        ];

        // Generic reviews for books not in the specific list
        $genericReviews = [
            "This book absolutely captivated me from the first page. The author's writing style is both elegant and accessible, making complex themes feel personal and relatable.",
            "A masterpiece of modern literature. The character development is exceptional, and the plot twists kept me guessing throughout.",
            "While the premise was intriguing, I found the pacing to be uneven in places. The strong ending redeemed the overall experience.",
            "I've read this book multiple times, and each time I discover something new. The layers of meaning and symbolism are incredible.",
            "The author has a unique voice that really shines through in this work. The dialogue feels authentic, and the descriptions are vivid.",
            "Couldn't get into this one. The writing style felt pretentious, and the plot was predictable. Maybe it's just not my cup of tea.",
            "This book changed my perspective on so many things. The way the author handles difficult topics with grace and insight is remarkable.",
            "A solid read with engaging characters and a well-crafted plot. Thoroughly enjoyable and perfect for a weekend escape.",
            "The world-building in this book is absolutely phenomenal. The attention to detail and the rich, immersive setting make the story come alive.",
            "I wanted to love this book, but it just didn't work for me. The pacing felt off, and I couldn't connect with any of the characters."
        ];

        // Delete all existing reviews
        DB::table('reviews')->delete();

        // Create realistic reviews
        $createdReviews = 0;
        foreach ($books as $book) {
            // Create 2-4 reviews per book
            $reviewsPerBook = rand(2, 4);
            
            for ($i = 0; $i < $reviewsPerBook; $i++) {
                // Select appropriate review comments based on book title
                $availableComments = [];
                
                if (isset($bookSpecificReviews[$book->title])) {
                    $availableComments = $bookSpecificReviews[$book->title];
                } else {
                    $availableComments = $genericReviews;
                }
                
                // Select a random comment from available comments
                $comment = $availableComments[array_rand($availableComments)];
                
                // Generate realistic rating based on comment sentiment
                $rating = $this->generateRatingFromComment($comment);
                
                $user = $users->random();
                
                // Create the review with realistic timing
                $daysAgo = rand(1, 365);
                
                Review::create([
                    'book_id' => $book->id,
                    'user_id' => $user->id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'status' => 'approved',
                    'create_at' => now()->subDays($daysAgo)
                ]);
                
                $createdReviews++;
            }
        }

        $this->command->info("Successfully created {$createdReviews} real reviews!");
    }

    /**
     * Generate rating based on comment sentiment
     */
    private function generateRatingFromComment($comment)
    {
        // Positive sentiment keywords
        $positiveWords = ['brilliant', 'masterpiece', 'excellent', 'beautiful', 'amazing', 'perfect', 'love', 'wonderful', 'fantastic', 'captivated', 'mesmerizing', 'heartbreaking', 'unforgettable', 'compelling', 'authentic', 'remarkable', 'masterful', 'brilliantly', 'essential', 'timeless', 'profound', 'changed', 'restored', 'pure'];
        
        // Negative sentiment keywords  
        $negativeWords = ['disappointed', 'couldn\'t', 'predictable', 'pretentious', 'annoying', 'couldn\'t get into', 'didn\'t work', 'unlikable', 'rushed', 'amateurish', 'serviceable', 'forgettable', 'inspired'];
        
        // Neutral/mixed sentiment keywords
        $neutralWords = ['solid', 'good', 'decent', 'fine', 'adequate', 'pleasant', 'competent', 'interesting', 'promising', 'uneven', 'inconsistent'];
        
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;
        
        // Count sentiment words
        foreach ($positiveWords as $word) {
            if (stripos($comment, $word) !== false) {
                $positiveCount++;
            }
        }
        
        foreach ($negativeWords as $word) {
            if (stripos($comment, $word) !== false) {
                $negativeCount++;
            }
        }
        
        foreach ($neutralWords as $word) {
            if (stripos($comment, $word) !== false) {
                $neutralCount++;
            }
        }
        
        // Generate rating based on sentiment analysis
        if ($positiveCount > $negativeCount && $positiveCount > 1) {
            // Strong positive sentiment - 4 or 5 stars
            return rand(4, 5);
        } elseif ($negativeCount > $positiveCount && $negativeCount > 0) {
            // Negative sentiment - 2 or 3 stars
            return rand(2, 3);
        } elseif ($neutralCount > 0 || ($positiveCount === $negativeCount)) {
            // Mixed or neutral sentiment - 3 or 4 stars
            return rand(3, 4);
        } else {
            // Default to positive for neutral comments
            return rand(3, 5);
        }
    }
}
