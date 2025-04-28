<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $post = Post::inRandomOrder()->first() ?? Post::factory()->create();

        return [
            'content' => $this->faker->paragraph(rand(1, 3)),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'post_id' => $post->id,
            'created_at' => $this->faker->dateTimeBetween($post->created_at, 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}
