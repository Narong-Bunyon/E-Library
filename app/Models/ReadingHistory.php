<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'started_at',
        'completed_at',
        'progress_percentage',
        'pages_read',
        'total_pages',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer',
        'pages_read' => 'integer',
        'total_pages' => 'integer',
        'status' => 'string',
    ];

    /**
     * Get the user that owns the reading history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book associated with the reading history.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Scope a query to only include reading entries.
     */
    public function scopeReading($query)
    {
        return $query->where('status', 'reading');
    }

    /**
     * Scope a query to only include completed entries.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include paused entries.
     */
    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    /**
     * Scope a query to only include abandoned entries.
     */
    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }
}
