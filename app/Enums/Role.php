<?php

namespace App\Enums;

enum Role: string
{
    case Author = 'author';
    case Manager = 'manager';
    case Admin = 'admin';

    public function label(): string
    {
        return match($this) {
            self::Author => 'Author',
            self::Manager => 'Manager',
            self::Admin => 'Admin',
        };
    }

    public function isAuthor(): bool
    {
        return $this === self::Author;
    }

    public function isManager(): bool
    {
        return $this === self::Manager;
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
