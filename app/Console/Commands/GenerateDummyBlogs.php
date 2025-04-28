<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateDummyBlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-dummy-blogs {count=10 : Number of blog posts to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy blog posts with cover images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');

        $this->info("Generating {$count} dummy blog posts with cover images...");

        // Make sure we have users
        if (User::count() === 0) {
            $this->error('No users found. Please run the database seeder first.');
            return 1;
        }

        // Make sure we have tags
        if (Tag::count() === 0) {
            $this->error('No tags found. Please run the database seeder first.');
            return 1;
        }

        // Create directory for post covers if it doesn't exist
        Storage::disk('public')->makeDirectory('post-covers');

        $users = User::all();
        $tags = Tag::all();
        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        DB::beginTransaction();

        try {
            for ($i = 0; $i < $count; $i++) {
                // Create post
                $post = new Post([
                    'title' => $this->generateTitle(),
                    'content' => $this->generateContent(),
                    'user_id' => $users->random()->id,
                ]);

                // Download and save a random image
                $imageUrl = "https://picsum.photos/1200/800";
                $response = Http::get($imageUrl);

                if ($response->successful()) {
                    $filename = time() . '_' . rand(1000, 9999) . '.jpg';
                    $path = 'post-covers/' . $filename;

                    // Store the image directly without processing
                    Storage::disk('public')->put($path, $response->body());

                    $post->cover_image = $path;
                }

                $post->save();

                // Attach 1-3 random tags
                $post->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                $progressBar->advance();

                // Add a small delay to avoid rate limiting
                usleep(100000); // 0.1 seconds
            }

            DB::commit();
            $progressBar->finish();
            $this->newLine();
            $this->info('Dummy blog posts generated successfully!');

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine();
            $this->error('Error generating dummy blog posts: ' . $e->getMessage());

            return 1;
        }
    }

    /**
     * Generate a random blog post title
     */
    private function generateTitle(): string
    {
        $titles = [
            'The Ultimate Guide to %s',
            '%s: A Comprehensive Overview',
            'How to Master %s in 30 Days',
            '10 Essential Tips for %s',
            'The Future of %s: Trends and Predictions',
            'Why %s Matters More Than Ever',
            'Understanding %s: A Beginner\'s Guide',
            'The Hidden Secrets of %s',
            '%s: Myths and Facts',
            'Exploring the World of %s',
            'The Art and Science of %s',
            'Revolutionizing %s: New Approaches',
            '%s Mastery: From Novice to Expert',
            'The Evolution of %s Over the Years',
            'Breaking Down %s: A Step-by-Step Analysis',
        ];

        $subjects = [
            'Web Development',
            'Digital Marketing',
            'Artificial Intelligence',
            'Sustainable Living',
            'Remote Work',
            'Personal Finance',
            'Healthy Eating',
            'Fitness',
            'Mental Wellness',
            'Travel Photography',
            'Home Decoration',
            'Creative Writing',
            'Mobile App Design',
            'Data Science',
            'Blockchain Technology',
            'Social Media Strategy',
            'Content Creation',
            'E-commerce',
            'Mindfulness',
            'Career Development',
        ];

        $title = $titles[array_rand($titles)];
        $subject = $subjects[array_rand($subjects)];

        return sprintf($title, $subject);
    }

    /**
     * Generate random blog post content
     */
    private function generateContent(): string
    {
        $paragraphs = rand(4, 8);
        $content = '';

        for ($i = 0; $i < $paragraphs; $i++) {
            $content .= $this->generateParagraph() . "\n\n";
        }

        return trim($content);
    }

    /**
     * Generate a random paragraph
     */
    private function generateParagraph(): string
    {
        $sentences = rand(3, 8);
        $paragraph = '';

        for ($i = 0; $i < $sentences; $i++) {
            $paragraph .= $this->generateSentence() . ' ';
        }

        return trim($paragraph);
    }

    /**
     * Generate a random sentence
     */
    private function generateSentence(): string
    {
        $phrases = [
            'In today\'s rapidly evolving world',
            'Many experts believe',
            'Research has shown',
            'It\'s important to understand',
            'One of the most significant developments',
            'Contrary to popular belief',
            'The latest trends indicate',
            'According to recent studies',
            'When considering this topic',
            'From a historical perspective',
            'Looking at the data',
            'Industry professionals often emphasize',
            'A common misconception is',
            'The fundamental principle behind',
            'What many people don\'t realize is',
        ];

        $middles = [
            'that the implementation of effective strategies',
            'the integration of various methodologies',
            'a comprehensive understanding of the subject',
            'the careful analysis of available information',
            'the adoption of innovative approaches',
            'the consideration of multiple perspectives',
            'the development of specialized skills',
            'the application of theoretical knowledge',
            'the recognition of underlying patterns',
            'the identification of key factors',
        ];

        $endings = [
            'can lead to significant improvements in outcomes.',
            'is essential for achieving optimal results.',
            'plays a crucial role in long-term success.',
            'has transformed how we approach these challenges.',
            'continues to shape industry standards.',
            'remains a priority for forward-thinking individuals.',
            'offers valuable insights into future developments.',
            'provides a foundation for continued growth.',
            'represents a paradigm shift in conventional thinking.',
            'highlights the importance of adaptability in changing environments.',
        ];

        return $phrases[array_rand($phrases)] . ' ' .
               $middles[array_rand($middles)] . ' ' .
               $endings[array_rand($endings)];
    }
}
