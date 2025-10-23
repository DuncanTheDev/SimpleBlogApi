<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($postID)
    {
        $post = Post::findOrFail($postID);

        $comments = $post->comments()
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Comments retrieved successfully',
            'data' => $comments
        ], 200);
    }

    public function store(Request $request, $postID)
    {
        $post = Post::findOrFail($postID);

        $request->validate([
            'body' => 'required|string'
        ]);

        $comment = new Comment([
            'body' => $request->body,
            'user_id' => Auth::id()
        ]);

        $post->comments()->save($comment);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment->load('user:id,name')
        ]);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to update this comment'
            ], 403);
        }

        $request->validate([
            'body' => 'required|string'
        ]);

        $comment->update(['body' => $request->body]);

        return response()->json([
            'status' => true,
            'message' => "Comment updated successfully",
            'data' => $comment
        ], 200);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to delete this comment'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
