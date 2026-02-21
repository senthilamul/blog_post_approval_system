<?php

namespace App\Enums;

enum PostAction: string
{
    
    case Created = 'created';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Deleted = 'deleted';

    public function label(): string
    {
        return match($this) {
            self::Created => 'Created',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Deleted => 'Deleted',
        };
    }
}
