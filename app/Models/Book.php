<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    public const CREATED_AT = 'create_at';
    public const UPDATED_AT = null;

    protected $fillable = [
        'title',
        'description',
        'author_id',
        'category_id',
        'status',
        'file_path',
        'cover_image',
        'access_level',
        'pages',
        'language',
        'isbn',
        'published_date',
        'views',
        'downloads',
        'rating',
        'rating_count',
    ];

    protected $casts = [
        'create_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'book_tags');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    public function readingProgress(): HasMany
    {
        return $this->hasMany(ReadingProgress::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function readingPermissions(): HasMany
    {
        return $this->hasMany(ReadingPermission::class, 'book_id');
    }

    public function bookTags(): HasMany
    {
        return $this->hasMany(BookTag::class, 'book_id');
    }

    public function bookCategories(): HasMany
    {
        return $this->hasMany(BookCategory::class, 'book_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_categories', 'book_id', 'category_id');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function totalDownloads()
    {
        return $this->downloads()->where('status', 'completed')->count();
    }

    public function totalViews()
    {
        return $this->readingProgress()->count();
    }
}
