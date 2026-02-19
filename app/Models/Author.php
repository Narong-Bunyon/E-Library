<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    // ERD table name is "authorse"
    protected $table = 'authorse';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'bio',
        'approved_by_admin',
    ];

    protected $casts = [
        'approved_by_admin' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'author_id');
    }
}
