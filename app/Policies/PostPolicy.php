<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        if ($user->isAuthor()) {
            return $post->author_id === $user->id;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAuthor();
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->isAuthor()) {
            return $post->author_id === $user->id && $post->isPending();
        }
        return false;
    }

    public function approve(User $user, Post $post): bool
    {
        return $user->canApprove() && $post->isPending();
    }

    public function reject(User $user, Post $post): bool
    {
        return $user->canApprove() && $post->isPending();
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }
}
