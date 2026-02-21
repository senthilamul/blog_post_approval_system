<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\RejectPostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $posts = $this->postService->getPosts();
            return response()->json(['data' => $posts]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        try {
            $post = $this->postService->createPost($request->validated());
            return response()->json([
                'message' => 'Post created and is pending review.',
                'data' => $post->load('author'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, Post $post): JsonResponse
    {
        try {
            $user = $request->user();
            if ($user->isAuthor() && $post->author_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return response()->json(['data' => $post->load('author', 'approver')]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function approve(Post $post): JsonResponse
    {
        try {
            $post = $this->postService->approvePost($post);
            return response()->json([
                'message' => 'Post approved.',
                'data' => $post->load('author', 'approver'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function reject(Post $post, RejectPostRequest $request): JsonResponse
    {
        try {
            $post = $this->postService->rejectPost($post, $request->reason);
            return response()->json([
                'message' => 'Post rejected.',
                'data' => $post->load('author', 'approver'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Post $post): JsonResponse
    {
        try {
            $this->postService->deletePost($post);
            return response()->json(['message' => 'Post deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function logs(Post $post): JsonResponse
    {
        try {
            $logs = $this->postService->getPostLogs($post);
            return response()->json(['data' => $logs]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
