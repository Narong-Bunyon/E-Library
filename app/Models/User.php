<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === "admin";
    }

    /**
     * Check if user is author
     */
    public function isAuthor(): bool
    {
        return $this->role === "author";
    }

    /**
     * Check if user is regular User
     */
    public function isUser(): bool
    {
        return $this->role === "user";
    }

    /**
     * Check if user can read books (all roles can read)
     */
    public function canReadBooks(): bool
    {
        return true; // All roles can read books
    }

    /**
     * Check if user can manage books (admin and author)
     */
    public function canManageBooks(): bool
    {
        return $this->isAdmin() || $this->isAuthor();
    }

    /**
     * Check if user can manage users (admin only)
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Get user role label
     */
    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'author' => 'Author',
            'user' => 'Reader',
            default => 'Unknown',
        };
    }

    /**
     * Get user's favorites
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get user's books (for authors)
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'author_id');
    }
}
