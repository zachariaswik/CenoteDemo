# Cenote

A distraction-free knowledge-sharing platform for educators and learners.

## Features

- 📚 Curated educational articles organized by categories
- 🔍 DuckDuckGo search integration
- 🔐 Authentication with 2FA support (Laravel Fortify)
- 📱 Responsive design with dark mode (Tailwind CSS v4)
- 👨‍💼 Admin panel (Filament 5)

## Tech Stack

**Frontend:** Blade templates, Tailwind CSS v4, Vite, Vanilla JavaScript  
**Backend:** Laravel 12, PHP 8.4, SQLite/MySQL/PostgreSQL, Eloquent ORM  
**Testing:** Pest 4, Laravel Pint

## Quick Start

```bash
git clone https://github.com/zachariaswik/CenoteDemo.git
cd CenoteDemo
composer setup
php artisan migrate:fresh --seed
composer dev
```

Visit `http://localhost:8000`

## Common Commands

```bash
composer test              # Run tests
composer lint              # Format code
php artisan migrate:fresh --seed  # Reset database
```

## Project Structure

- `app/Models/` - User, Article, Category models
- `app/Http/Controllers/` - Request handlers
- `resources/views/` - Blade templates
- `database/migrations/` - Database schema
- `tests/` - Pest tests

## Core Features

**Articles:** Content with categories, slugs, draft/published states  
**Categories:** Hierarchical organization with article counts  
**Search:** Real-time DuckDuckGo integration with pagination  
**Auth:** Registration, login, email verification, password reset, 2FA

## License

MIT License - see [LICENSE](https://opensource.org/licenses/MIT)

## Links

[Repository](https://github.com/zachariaswik/CenoteDemo) · [Laravel Docs](https://laravel.com/docs) · [Tailwind CSS](https://tailwindcss.com) · [Filament](https://filamentphp.com)
