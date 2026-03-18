<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Models\Book;

echo "Checking book covers...\n\n";

$books = Book::all(['id', 'title', 'cover_image']);

foreach ($books as $book) {
    $status = $book->cover_image ? 'HAS COVER' : 'MISSING COVER';
    echo sprintf(
        "ID: %-3d | Title: %-40s | %s\n",
        $book->id,
        substr($book->title, 0, 40),
        $status
    );
    
    if (!$book->cover_image) {
        echo "  Missing cover image for: {$book->title}\n";
    }
}

echo "\nSummary:\n";
$withCovers = $books->where('cover_image', '!=', '')->count();
$withoutCovers = $books->where('cover_image', '=', '')->count();

echo "Books with covers: {$withCovers}\n";
echo "Books without covers: {$withoutCovers}\n";
