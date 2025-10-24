<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $post = Post::with(['user:id,name', 'category:id,name'])
            ->withCount('comments', 'likes')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($post, 200);
    }

    public function show($id)
    {
        $post = Post::with(['user:id,name', 'category:id,name', 'comments.user:id,name', 'likes'])
            ->findOrFail($id);

        return response()->json($post, 200);
    }

    public function getByCategory($categoryID)
    {
        $post = Post::with(['user', 'category'])
            ->where('category_id', $categoryID)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($post->isEmpty()) {
            return response()->json(['message' => 'No posts found for this category'], 404);
        }

        return response()->json($post, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();

        $post = Post::create($validated);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'sometimes|string',
            'body' => 'sometimes|string',
            'image' => 'nullable|string'
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function toggleLikes($postID)
    {
        $post = Post::findOrFail($postID);
        $user = Auth::user();

        $existingLike = $post->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');

            return response()->json([
                'status' => true,
                'message' => 'Post unliked successfully'
            ], 200);
        }

        $post->likes()->create(['user_id' => $user->id]);
        $post->increment('likes_count');

        return response()->json([
            'status' => true,
            'message' => 'Post liked successfully'
        ], 201);
    }
}
