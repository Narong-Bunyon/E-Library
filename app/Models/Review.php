<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    public const CREATED_AT = 'create_at';
    public const UPDATED_AT = null;

    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'comment',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'create_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
