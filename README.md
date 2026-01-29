# Cenote

> A knowledge-sharing platform where educators and experts share insights, tutorials, and educational resources.

## Overview

Cenote is a fullscreen, kiosk-style learning environment designed to create a focused, distraction-free experience for learners. This public-facing website allows people to discover educational content, browse articles by category, and access resources curated by experienced educators and industry experts.

## Features

- **📚 Quality Content**: Curated articles from experienced educators and industry experts
- **🏷️ Organized Categories**: Well-organized category system for easy content discovery
- **🔍 Search Functionality**: Integrated DuckDuckGo search with real-time results
- **👥 Community Driven**: Platform for knowledge sharing and community learning
- **🔐 Authentication**: Secure user authentication with Laravel Fortify (including 2FA support)
- **📱 Responsive Design**: Modern, mobile-friendly interface built with Tailwind CSS
- **🌓 Dark Mode**: Built-in dark mode support for comfortable reading

## Tech Stack

### Frontend
- **Framework**: Blade templates (Laravel views)
- **Styling**: Tailwind CSS v4
- **Bundler**: Vite
- **Interactivity**: Vanilla JavaScript

### Backend
- **Framework**: Laravel 12
- **PHP Version**: 8.4.16
- **Database**: SQLite (development), adaptable to PostgreSQL/MySQL
- **Authentication**: Laravel Fortify
- **ORM**: Eloquent
- **Testing**: Pest 4 (unit & browser tests)
- **Code Quality**: Laravel Pint for formatting

### Additional Tools
- **Admin Panel**: Filament 5
- **Development**: Laravel Herd (macOS), Laravel Sail (Docker)
- **Queue Management**: Laravel Queue
- **Logging**: Laravel Pail

## Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- SQLite (or MySQL/PostgreSQL)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/zachariaswik/CenoteDemo.git
   cd CenoteDemo
   ```

2. **Install dependencies**
   ```bash
   composer setup
   ```
   
   This command will:
   - Install PHP dependencies
   - Copy `.env.example` to `.env`
   - Generate application key
   - Run database migrations
   - Install Node.js dependencies
   - Build frontend assets

3. **Configure environment**
   
   Edit `.env` file if needed to customize your database or other settings.

4. **Seed the database** (optional)
   ```bash
   php artisan migrate:fresh --seed
   ```
   
   This creates sample articles, categories, and test users.

### Development

Start the development server:

```bash
composer dev
```

This command runs:
- PHP development server (port 8000)
- Queue worker
- Log viewer (Laravel Pail)
- Vite development server (for hot module replacement)

Alternatively, you can run services individually:

```bash
# Start Laravel server
php artisan serve

# Watch and compile assets
npm run dev

# Run queue worker
php artisan queue:listen
```

Visit `http://localhost:8000` to view the application.

## Development Workflow

### Running Tests

```bash
# Run all tests
composer test

# Run tests with Pest
php artisan test

# Run specific test
php artisan test --filter=ArticleTest
```

### Code Formatting

```bash
# Format code with Laravel Pint
composer lint

# Check formatting without making changes
composer test:lint
```

### Database Management

```bash
# Reset database and run all migrations
php artisan migrate:fresh

# Reset and seed database
php artisan migrate:fresh --seed

# Run seeders only
php artisan db:seed
```

## Project Structure

```
├── app/
│   ├── Models/              # Eloquent models (User, Article, Category)
│   ├── Http/
│   │   ├── Controllers/     # Request handlers
│   │   ├── Requests/        # Form validation classes
│   │   └── Middleware/      # Request/response middleware
│   ├── Providers/           # Service providers
│   └── Actions/             # Fortify authentication actions
├── database/
│   ├── factories/           # Model factories for testing
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   └── css/                 # Stylesheets
├── routes/
│   ├── web.php              # Web routes
│   └── settings.php         # Settings routes
└── tests/                   # Pest tests
```

## Key Features

### Article Management

Articles are the core content type in Cenote:
- Created by authenticated users (authors)
- Organized by categories
- Support for draft/published states
- SEO-friendly slugs
- Full-text content with excerpts

### Category System

Categories help organize content:
- Hierarchical organization
- Unique slugs for URLs
- Track article counts
- Custom styling options

### Search

Integrated search functionality:
- DuckDuckGo search integration
- Real-time results
- Pagination support
- Clean, focused interface

### Authentication

Secure authentication powered by Laravel Fortify:
- User registration and login
- Email verification
- Password reset functionality
- Two-factor authentication (2FA) support
- Session management

## Contributing

This is a demo project, but contributions are welcome! Please follow Laravel's coding standards and ensure all tests pass before submitting pull requests.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Links

- **Repository**: [https://github.com/zachariaswik/CenoteDemo](https://github.com/zachariaswik/CenoteDemo)
- **Laravel Documentation**: [https://laravel.com/docs](https://laravel.com/docs)
- **Tailwind CSS**: [https://tailwindcss.com](https://tailwindcss.com)
- **Filament**: [https://filamentphp.com](https://filamentphp.com)

---

**Cenote** - Creating focused, distraction-free learning environments.
