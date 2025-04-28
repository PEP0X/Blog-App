<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comment = Comment::inRandomOrder()->first() ?? Comment::factory()->create();

        return [
            'content' => $this->faker->paragraph(rand(1, 2)),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'comment_id' => $comment->id,
            'created_at' => $this->faker->dateTimeBetween($comment->created_at, 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}
