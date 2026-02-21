<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'status',
        'author_id',
        'approved_by',
        'rejected_reason',
    ];

    protected $casts = [
        'status' => PostStatus::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function logs()
    {
        return $this->hasMany(PostLog::class);
    }

    public function isPending(): bool
    {
        return $this->status === PostStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->status === PostStatus::Approved;
    }

    public function isRejected(): bool
    {
        return $this->status === PostStatus::Rejected;
    }
}
