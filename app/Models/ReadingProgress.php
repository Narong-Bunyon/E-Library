<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingProgress extends Model
{
    use HasFactory;

    protected $table = 'reading_progress';

    protected $fillable = [
        'book_id',
        'user_id',
        'current_page',
        'total_pages',
        'progress_percentage',
        'status',
        'started_at',
        'completed_at',
        'last_read_at',
    ];

    protected $casts = [
        'current_page' => 'integer',
        'total_pages' => 'integer',
        'progress_percentage' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
