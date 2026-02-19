<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    use HasFactory;

    protected $table = 'downloads';

    protected $fillable = [
        'book_id',
        'user_id',
        'file_type',
        'file_size',
        'status',
        'downloaded_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'downloaded_at' => 'datetime',
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
