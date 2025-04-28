<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reactionTypes = ['like', 'dislike', 'love', 'wow', 'clap', 'encourage'];
        $reactableTypes = [
            Post::class => Post::class,
            Comment::class => Comment::class,
            Reply::class => Reply::class,
        ];

        $reactableType = $this->faker->randomElement(array_keys($reactableTypes));
        $reactable = $reactableType::inRandomOrder()->first() ?? $reactableType::factory()->create();

        return [
            'type' => $this->faker->randomElement($reactionTypes),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'reactable_type' => $reactableType,
            'reactable_id' => $reactable->id,
            'created_at' => $this->faker->dateTimeBetween($reactable->created_at, 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}
