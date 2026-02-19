<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_tags', 'tag_id', 'book_id');
    }
}
