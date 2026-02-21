<?php

namespace App\Models;

use App\Enums\PostAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'user_id',
        'action',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'action' => PostAction::class,
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public $incrementing = true;

    protected $table = 'post_logs';

    const UPDATED_AT = null;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
