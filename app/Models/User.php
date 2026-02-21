<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function approvedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'approved_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PostLog::class);
    }

    public function isAuthor(): bool
    {
        return $this->role === Role::Author;
    }

    public function isManager(): bool
    {
        return $this->role === Role::Manager;
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function canApprove(): bool
    {
        return $this->isManager() || $this->isAdmin();
    }

    public function canDelete(Post $post): bool
    {
        return $this->isAdmin();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role instanceof \BackedEnum ? $this->role->value : $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
