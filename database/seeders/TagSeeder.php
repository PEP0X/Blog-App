<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Technology',
            'Programming',
            'Web Development',
            'Mobile Development',
            'Design',
            'Business',
            'Marketing',
            'Productivity',
            'Lifestyle',
            'Travel',
            'Food',
            'Health',
            'Fitness',
            'Education',
            'Science',
            'Art',
            'Music',
            'Photography',
            'Writing',
            'Personal Development'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => \Illuminate\Support\Str::slug($tag)
            ]);
        }
    }
}
