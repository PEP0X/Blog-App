# Blog App Seed Data

This document provides instructions on how to generate dummy blog posts and related data for the Blog App.

## Available Seeders and Commands

### 1. Database Seeder

The main database seeder will create:
- 10 users (including an admin user)
- 15 tags
- 30 blog posts with random tags
- Comments, replies, and reactions

To run the database seeder:

```bash
php artisan db:seed
```

### 2. Generate Dummy Blogs Command

This command generates blog posts with cover images fetched from [Lorem Picsum](https://picsum.photos/).

```bash
# Generate 10 blog posts (default)
php artisan app:generate-dummy-blogs

# Generate a specific number of blog posts
php artisan app:generate-dummy-blogs 20
```

## Admin User Credentials

After running the seeder, you can log in with the following admin credentials:

- **Email**: admin@example.com
- **Password**: password

## Data Structure

The seeder creates the following data:

1. **Users**: 10 users including an admin user
2. **Tags**: 15 predefined tags
3. **Posts**: 30 blog posts with:
   - Random title and content
   - 1-3 tags per post
   - Association with random users
4. **Comments**: 0-5 comments per post
5. **Replies**: 0-3 replies per comment
6. **Reactions**: Various reactions (like, love, etc.) on posts and comments

## Storage Setup

Before running the seeders, make sure your storage is linked:

```bash
php artisan storage:link
```

This ensures that the uploaded images are accessible from the web.

## Customization

If you want to modify the seed data:

1. Edit `database/seeders/BlogSeeder.php` to change the number of posts, comments, etc.
2. Edit `app/Console/Commands/GenerateDummyBlogs.php` to customize the blog post generation.

## Clearing Seed Data

To clear all seed data and start fresh:

```bash
php artisan migrate:fresh
```

Then run the seeders again as needed.
