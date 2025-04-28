# Blog App

A simple blog application built with Laravel, Blade, and TailwindCSS.

## Features

-   User authentication (register/login)
-   CRUD operations for blog posts
-   Responsive design with TailwindCSS
-   PostgreSQL database
-   Authorization (users can only edit/delete their own posts)

## Requirements

-   PHP 8.1 or higher
-   Composer
-   Node.js and NPM
-   PostgreSQL
-   Laravel Valet (for local development)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/blog-app.git
cd blog-app
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install NPM dependencies:

```bash
npm install
```

4. Create a PostgreSQL database named `blog_app`

5. Copy the environment file:

```bash
cp .env.example .env
```

6. Update the `.env` file with your database credentials:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=blog_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Generate application key:

```bash
php artisan key:generate
```

8. Run migrations:

```bash
php artisan migrate
```

9. Build assets:

```bash
npm run build
```

10. Start the development server:

```bash
php artisan serve
```

## Usage

1. Register a new account or login
2. Create, read, update, and delete blog posts
3. View all posts on the homepage
4. Manage your posts in the dashboard

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
