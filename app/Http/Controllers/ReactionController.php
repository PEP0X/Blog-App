<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,dislike,love,wow,clap,encourage',
            'reactable_type' => 'required|in:App\Models\Post,App\Models\Comment,App\Models\Reply',
            'reactable_id' => 'required|integer',
        ]);

        // Delete any existing reaction from this user
        Reaction::where('user_id', Auth::id())
            ->where('reactable_type', $validated['reactable_type'])
            ->where('reactable_id', $validated['reactable_id'])
            ->delete();

        // Create new reaction
        $reaction = new Reaction($validated);
        $reaction->user_id = Auth::id();
        $reaction->save();

        return response()->json([
            'success' => true,
            'reaction' => $reaction,
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'reactable_type' => 'required|in:App\Models\Post,App\Models\Comment,App\Models\Reply',
            'reactable_id' => 'required|integer',
        ]);

        Reaction::where('user_id', Auth::id())
            ->where('reactable_type', $validated['reactable_type'])
            ->where('reactable_id', $validated['reactable_id'])
            ->delete();

        return response()->json(['success' => true]);
    }
} 