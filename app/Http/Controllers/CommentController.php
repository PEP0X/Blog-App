<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment($validated);
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->save();

        return back()->with('success', 'Comment added successfully.');
    }

    public function reply(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = new \App\Models\Reply($validated);
        $reply->user_id = Auth::id();
        $reply->comment_id = $comment->id;
        $reply->save();

        return back()->with('success', 'Reply added successfully.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully.');
    }
} 