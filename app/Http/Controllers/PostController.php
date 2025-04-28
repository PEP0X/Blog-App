<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'tags'])->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(content) LIKE ?', ["%{$search}%"])
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                  });
            });
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', strtolower($request->tag));
            });
        }

        $posts = $query->paginate(9);
        $tags = Tag::all();

        return view('posts.index', compact('posts', 'tags'));
    }

    public function dashboard()
    {
        $posts = auth()->user()->posts()->with(['tags'])->latest()->paginate(10);
        return view('posts.dashboard', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:512000',
            'cropped_image' => 'nullable|string'
        ]);

        $post = new Post($validated);
        $post->user_id = auth()->id();

        if ($request->has('cropped_image') && $request->cropped_image) {
            $imageData = $request->cropped_image;
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);

            $filename = time() . '_cropped.jpg';
            $path = 'post-covers/' . $filename;

            Storage::disk('public')->put($path, $imageData);
            $post->cover_image = $path;
        } elseif ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = 'post-covers/' . $filename;

            // Store the image directly without processing
            Storage::disk('public')->put($path, file_get_contents($image->getRealPath()));

            $post->cover_image = $path;
        }

        $post->save();

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'tags']);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:512000',
            'cropped_image' => 'nullable|string'
        ]);

        if ($request->has('cropped_image') && $request->cropped_image) {
            // Delete old cover image if exists
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $imageData = $request->cropped_image;
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);

            $filename = time() . '_cropped.jpg';
            $path = 'post-covers/' . $filename;

            Storage::disk('public')->put($path, $imageData);
            $post->cover_image = $path;
        } elseif ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $image = $request->file('cover_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = 'post-covers/' . $filename;

            // Store the image directly without processing
            Storage::disk('public')->put($path, file_get_contents($image->getRealPath()));

            $post->cover_image = $path;
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete cover image if exists
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}

