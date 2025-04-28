<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\Reply;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create directory for post covers if it doesn't exist
        Storage::disk('public')->makeDirectory('post-covers');

        // Create users if none exist
        if (User::count() === 0) {
            // Create admin user
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);

            // Create regular users
            User::factory(9)->create();
        }

        // Create tags
        $tags = [
            'Technology', 'Health', 'Travel', 'Food', 'Lifestyle',
            'Business', 'Science', 'Sports', 'Entertainment', 'Education',
            'Art', 'Fashion', 'Environment', 'Politics', 'Personal Development'
        ];

        foreach ($tags as $tagName) {
            Tag::factory()->create(['name' => $tagName]);
        }

        // Create posts with tags
        $posts = Post::factory(30)->create();

        // Attach tags to posts
        $allTags = Tag::all();

        foreach ($posts as $post) {
            // Attach 1-3 random tags to each post
            $post->tags()->attach(
                $allTags->random(rand(1, 3))->pluck('id')->toArray()
            );

            // Create 0-5 comments for each post
            $comments = Comment::factory(rand(0, 5))->create([
                'post_id' => $post->id,
            ]);

            // Create 0-3 replies for each comment
            foreach ($comments as $comment) {
                Reply::factory(rand(0, 3))->create([
                    'comment_id' => $comment->id,
                ]);

                // Add 0-3 reactions to each comment
                for ($i = 0; $i < rand(0, 3); $i++) {
                    $this->createUniqueReaction(Comment::class, $comment->id);
                }
            }

            // Add 0-10 reactions to each post
            for ($i = 0; $i < rand(0, 10); $i++) {
                $this->createUniqueReaction(Post::class, $post->id);
            }
        }
    }

    /**
     * Create a unique reaction (one per user per item)
     */
    private function createUniqueReaction(string $reactableType, int $reactableId): void
    {
        $users = User::all();
        $reactionTypes = ['like', 'dislike', 'love', 'wow', 'clap', 'encourage'];

        // Find a user who hasn't reacted to this item yet
        $existingReactionUserIds = Reaction::where('reactable_type', $reactableType)
            ->where('reactable_id', $reactableId)
            ->pluck('user_id')
            ->toArray();

        $availableUsers = $users->whereNotIn('id', $existingReactionUserIds);

        if ($availableUsers->count() > 0) {
            $user = $availableUsers->random();

            Reaction::create([
                'user_id' => $user->id,
                'reactable_type' => $reactableType,
                'reactable_id' => $reactableId,
                'type' => $reactionTypes[array_rand($reactionTypes)],
            ]);
        }
    }
}
