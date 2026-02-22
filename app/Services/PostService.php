<?php

namespace App\Services;

use App\Enums\PostAction;
use App\Enums\PostStatus;
use App\Enums\Role;
use App\Models\Post;
use App\Models\PostLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function getPosts()
    {
        $user = Auth::user();
        
        if ($user->isAuthor()) {
            return Post::with('author')
                ->where('author_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return Post::with('author', 'approver')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createPost(array $data): Post
    {
        $user = Auth::user();
        
        $postData = [
            'title' => $data['title'],
            'body' => $data['body'],
            'author_id' => $user->id,
        ];
        
        // Handle optional fields
        if (isset($data['status'])) {
            $postData['status'] = PostStatus::from($data['status']);
        } else {
            $postData['status'] = PostStatus::Pending;
        }
        
        if (isset($data['approved_by'])) {
            $postData['approved_by'] = $data['approved_by'];
        }
        
        if (isset($data['rejected_reason'])) {
            $postData['rejected_reason'] = $data['rejected_reason'];
        }
        
        $post = Post::create($postData);

        PostLog::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'action' => PostAction::Created,
            'meta' => ['title' => $post->title, 'status' => $post->status->value],
            'created_at' => now(),
        ]);

        return $post;
    }

    public function approvePost(Post $post): Post
    {
        $user = Auth::user();
        
        if (!$user->canApprove()) {
            throw new \Exception('Unauthorized to approve posts.');
        }

        if (!$post->isPending()) {
            throw new \Exception('Only pending posts can be approved.');
        }

        $post->update([
            'status' => PostStatus::Approved,
            'approved_by' => $user->id,
        ]);

        PostLog::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'action' => PostAction::Approved,
            'meta' => ['approved_by' => $user->name],
            'created_at' => now(),
        ]);

        return $post->fresh();
    }

    public function rejectPost(Post $post, string $reason): Post
    {
        $user = Auth::user();
        
        if (!$user->canApprove()) {
            throw new \Exception('Unauthorized to reject posts.');
        }

        if (!$post->isPending()) {
            throw new \Exception('Only pending posts can be rejected.');
        }

        $post->update([
            'status' => PostStatus::Rejected,
            'approved_by' => $user->id,
            'rejected_reason' => $reason,
        ]);

        PostLog::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'action' => PostAction::Rejected,
            'meta' => ['reason' => $reason],
            'created_at' => now(),
        ]);

        return $post->fresh();
    }

    public function deletePost(Post $post): void
    {
        $user = Auth::user();
        
        if (!$user->canDelete($post)) {
            throw new \Exception('Unauthorized to delete posts.');
        }

        PostLog::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'action' => PostAction::Deleted,
            'meta' => ['deleted_title' => $post->title],
            'created_at' => now(),
        ]);

        $post->delete();
    }

    public function getPostLogs(Post $post)
    {
        return PostLog::with('user')
            ->where('post_id', $post->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
