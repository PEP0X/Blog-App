# 🚀 Laravel Blog Application

A modern, feature-rich blog application built with Laravel, PostgreSQL, and Bun. This application provides a platform for users to create, edit, and manage blog posts with features like image uploads, tags, comments, and user authentication. ✨

## 🌟 Features

-   🔐 User Authentication (Register, Login, Logout)
-   ✍️ Create, Edit, and Delete Blog Posts
-   🖼️ Image Upload Support (up to 500MB)
-   ✂️ Image Cropping Functionality
-   🏷️ Tags System
-   💬 Comments and Replies
-   👤 User Profiles
-   🔍 Search Functionality
-   📱 Responsive Design
-   🐘 PostgreSQL Database
-   🎨 Modern UI with Tailwind CSS

## 🛠️ Prerequisites

Before you begin, ensure you have the following installed:

-   🐘 PHP 8.2 or higher
-   📦 Composer
-   🐘 PostgreSQL
-   🚀 Bun (JavaScript runtime)
-   📦 Node.js (for npm)
-   🔧 Git

## 🚀 Installation

1. **Clone the repository** 🏗️

    ```bash
    git clone <repository-url>
    cd blog-app
    ```

2. **Install PHP dependencies** 📦

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies** 🚀

    ```bash
    bun install
    ```

4. **Create environment file** ⚙️

    ```bash
    cp .env.example .env
    ```

5. **Generate application key** 🔑

    ```bash
    php artisan key:generate
    ```

6. **Configure PostgreSQL** 🐘

    - Create a new PostgreSQL database
    - Update the `.env` file with your database credentials:
        ```
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=your_database_name
        DB_USERNAME=your_username
        DB_PASSWORD=your_password
        ```

7. **Run database migrations** 📊

    ```bash
    php artisan migrate
    ```

8. **Create storage link** 📁

    ```bash
    php artisan storage:link
    ```

9. **Build assets** 🎨

    ```bash
    bun run build
    ```

10. **Start the development server** 🚀
    ```bash
    php artisan serve
    ```

## ⚙️ Configuration

### PostgreSQL Setup 🐘

1. Install PostgreSQL if you haven't already
2. Create a new database:
    ```sql
    CREATE DATABASE your_database_name;
    ```
3. Create a new user (optional):
    ```sql
    CREATE USER your_username WITH PASSWORD 'your_password';
    GRANT ALL PRIVILEGES ON DATABASE your_database_name TO your_username;
    ```

### Environment Variables 🔧

Make sure to set these important environment variables in your `.env` file:

```
APP_NAME="Your Blog Name"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

FILESYSTEM_DISK=public
```

## 🎮 Usage

1. **Register a new account** 👤

    - Visit `/register` to create a new account
    - Fill in your details and submit

2. **Create a blog post** ✍️

    - Click "Create Post" in the navigation
    - Fill in the title and content
    - Upload a cover image (optional)
    - Add tags (optional)
    - Click "Publish"

3. **Edit your profile** 👤

    - Click your profile picture in the navigation
    - Select "Profile"
    - Update your information

4. **Manage your posts** 📝
    - Visit your dashboard to see all your posts
    - Edit or delete posts as needed

## 💻 Development

### Running Tests 🧪

```bash
php artisan test
```

### Building Assets 🎨

```bash
bun run dev    # For development
bun run build  # For production
```

### Database Migrations 📊

```bash
php artisan migrate        # Run migrations
php artisan migrate:reset  # Reset database
php artisan migrate:fresh  # Fresh migration with seed
```

## 🔧 Troubleshooting

1. **Database Connection Issues** 🐘

    - Verify PostgreSQL is running
    - Check database credentials in `.env`
    - Ensure database exists

2. **Image Upload Issues** 🖼️

    - Check storage permissions
    - Verify storage link is created
    - Check file size limits in PHP configuration

3. **Asset Compilation Issues** 🎨
    - Clear cache: `php artisan cache:clear`
    - Rebuild assets: `bun run build`

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

Made with ❤️ by Saitama
