# Cenote: A Distraction-Less Learning Environment

## Project Overview

Cenote is a fullscreen, kiosk designed to create a focused distraction-free learning evnironment. This project is aimed at creating a website for Cenote, where people can learn more about the project, through a public-facing website.

## Technical Architecture

### Frontend Stack
- **Framework**: Blade templates (Laravel views)
- **Bundler**: Vite (CSS assets)
- **Styling**: Tailwind CSS
- **Interactivity**: Vanilla JavaScript where needed

### Backend Stack
- **Framework**: Laravel 12 (PHP 8.4)
- **Database**: SQLite (for development, adaptable to PostgreSQL/MySQL)
- **Authentication**: Laravel Fortify
- **ORM**: Eloquent
- **API/Routing**: RESTful controllers with named routes
- **Development Tools**: 
  - Pest 4 for testing (unit & browser tests)
  - Laravel Pint for code formatting
  - Laravel Boost for MCP integration
  - Laravel Tinker for interactive debugging

### Deployment Environment
- **Local Development**: Laravel Herd (macOS)
- **HTTPS**: Available via Herd's automatic SSL

## Codebase Structure

### Core Architecture Pattern: MVC + Blade

```
app/
├── Models/              # Eloquent models (User, Article, Category)
├── Http/
│   ├── Controllers/     # Request handlers
│   ├── Requests/        # Form request validation classes
│   └── Middleware/      # Request/response middleware
├── Providers/           # Service providers (AppServiceProvider, FortifyServiceProvider)
└── Actions/             # Fortify authentication actions
```

**Why this structure?** Laravel's streamlined structure (introduced in v11) removes boilerplate. It's clean, convention-over-configuration, and gets out of your way. Perfect for rapid development without losing organization.

### Database Layer: Eloquent Relationships

The application uses three core models with relationships:

1. **User** — The actor (teacher or student)
   - Authenticates via Fortify (with 2FA support)
   - Creates articles as an author
   - Manages sessions and interactions

2. **Category** — Content organization
   - Has many Articles
   - Used to organize educational content (e.g., "Mathematics", "Literature")
   - Slug-based for SEO-friendly URLs

3. **Article** — Educational content
   - Belongs to a Category
   - Belongs to a User (author)
   - Published_at datetime for draft/published states
   - Long-form content with excerpts

**Relationship Flow:**
```
User (author) --has many--> Article --belongs to--> Category
```

### Frontend Architecture: Blade Views

Server-rendered Blade views keep the stack simple and fast while still allowing progressive enhancement with JavaScript.

```php
// Backend (routes/web.php)
Route::get('/articles', function () {
  return view('articles.index', [
    'articles' => Article::with('category', 'author')->paginate(),
  ]);
});
```

```blade
{{-- Frontend (resources/views/articles/index.blade.php) --}}
@foreach ($articles as $article)
  <div>{{ $article->title }}</div>
@endforeach
```

**Why Blade?** Server-rendered pages are straightforward to maintain, work well with Laravel's routing, and keep the UI lightweight.

## Technical Decisions & Rationale

### 1. SQLite for Development
- **Decision**: Use SQLite as the default database
- **Rationale**: Zero-setup, file-based, perfect for local development and testing
- **Trade-off**: File locking on concurrent writes (fine for 1-10 users locally)
- **Lesson Learned**: When you pivot to production, switch to PostgreSQL or MySQL—migration is seamless via Laravel migrations

### 2. Eloquent Over Raw SQL
- **Decision**: Use Eloquent relationships exclusively
- **Rationale**: 
  - Prevents SQL injection
  - Auto-loads relationships (N+1 prevention via eager loading)
  - Type hints make code self-documenting
- **Example**:
  ```php
  // Good: Prevents N+1, eager loads category & author
  Article::with('category', 'author')->get()
  ```

### 3. Method-Based Casts Over Properties
- **Decision**: Use `protected function casts(): array` instead of `protected $casts = []`
- **Rationale**: Enables conditional logic and feels more OOP
- **Example**:
  ```php
  protected function casts(): array {
      return ['published_at' => 'datetime'];
  }
  ```

### 4. Pest 4 Browser Testing
- **Decision**: Use Pest 4's browser testing instead of traditional unit tests alone
- **Rationale**: Real browser testing catches UI/JS issues that unit tests miss
- **Trade-off**: Slower execution, but catches integration bugs
- **Lesson Learned**: Browser tests are worth the wait—they've saved us from production bugs

### 5. Factory-Driven Development
- **Decision**: Always create factories alongside models
- **Rationale**: 
  - Tests become readable: `Article::factory()->published()->create()`
  - Seeding is trivial: `Article::factory(20)->create()`
  - Prevents test brittleness from hardcoded data
- **Example from ArticleSeeder:**
  ```php
  Article::factory(20)->make()->each(function ($article) use ($authors, $categories) {
      $article->category_id = $categories->random()->id;
      $article->author_id = $authors->random()->id;
      $article->save();
  });
  ```

## Key Features Implemented

### 1. News & Educational Content System
- **Models**: Article, Category
- **Relationships**: Articles belong to Categories and Authors
- **Features**:
  - Draft/published states via nullable `published_at`
  - Unique slugs for SEO-friendly URLs
  - Auto-generated content via Faker for development
  - Type-safe content querying via Eloquent

### 2. Authentication & Authorization
- **Via**: Laravel Fortify
- **Features**: Password resets, 2FA support, email verification
- **Pattern**: Actions in `app/Actions/Fortify/` handle auth flow

### 3. Seeding System
- **DatabaseSeeder**: Creates test users and calls ArticleSeeder
- **ArticleSeeder**: Creates 20 articles, 3-5 categories, and 3-8 authors
- **Key Feature**: Truncates tables on reseed to avoid unique constraint violations
- **Usage**: `php artisan migrate:fresh --seed`

## Bugs Encountered & Solutions

### Bug #1: Unique Constraint Violations on Re-seeding
**Problem**: Running `php artisan db:seed` twice failed because categories and articles had duplicate slugs.

**Root Cause**: Faker generates the same values when seeded with the same randomizer state. Unique constraints on `slug` columns prevented re-running.

**Solution**: 
```php
DB::table('articles')->truncate();
DB::table('categories')->truncate();
```
Truncate before each seed cycle to allow fresh data generation.

**Lesson**: Always plan for re-seeding in development. Idempotent seeders are a gift to your team.

### Bug #2: User Email Unique Constraint on Test Users
**Problem**: Creating a test user with `test@example.com` failed on subsequent runs.

**Solution**: Use `firstOrCreate()` instead of `create()`:
```php
User::firstOrCreate(
    ['email' => 'test@example.com'],
    ['name' => 'Test User', 'password' => bcrypt('password')]
);
```

**Lesson**: Distinguish between "seed this exact data" vs. "ensure this data exists." Use `firstOrCreate()` for the latter.

## Best Practices & Patterns Used

### 1. Constructor Property Promotion (PHP 8)
```php
public function __construct(public GitHub $github) { }
```
Instead of:
```php
private GitHub $github;
public function __construct(GitHub $github) {
    $this->github = $github;
}
```
**Benefit**: Less boilerplate, more readable. PHP 8's syntax sugar reduces typing by 50%.

### 2. Explicit Return Types
```php
public function articles(): HasMany { ... }
protected function casts(): array { ... }
```
**Benefit**: Self-documenting code. No guessing what a method returns.

### 3. PHPDoc for Complex Types
```php
/**
 * @var list<string>
 */
protected $fillable = ['title', 'slug'];
```
**Benefit**: Type checking in editors, prevents accidental bugs.

### 4. Named Routes Over Hardcoded URLs
```php
// Good
route('articles.show', $article)

// Bad
'/articles/' . $article->id
```
**Benefit**: Refactor URLs in one place. Prevents 404s from typos.

### 5. Eager Loading to Prevent N+1
```php
// Bad: Loops run 21 queries (1 for articles + 20 for authors)
Article::all();
foreach ($articles as $article) {
    echo $article->author->name;
}

// Good: Runs 2 queries
Article::with('author')->get();
```

## Technologies & Versions

| Package | Version | Purpose |
|---------|---------|---------|
| Laravel | 12 | Backend framework |
| PHP | 8.4.16 | Runtime language |
| Pest | 4 | Testing framework |
| Tailwind CSS | Latest | Styling |
| Vite | Latest | Build tool |
| Laravel Herd | macOS | Local development |

## Development Workflow

### Starting a Feature
1. **Create the database**: `php artisan make:migration create_table_name`
2. **Create the model**: `php artisan make:model ModelName --factory`
3. **Update the factory**: Add realistic fake data generators
4. **Create tests**: `php artisan make:test FeatureTest --pest`
5. **Build the controller**: Return a Blade view with data
6. **Create the Blade view**: In `resources/views/`
7. **Run tests**: `php artisan test --compact`
8. **Format code**: `vendor/bin/pint --dirty`

### Seeding Data
```bash
# Fresh database with seeded data
php artisan migrate:fresh --seed

# Just reseed existing database
php artisan db:seed
```

### Viewing the Database
- **GUI Option**: Download SQLiteStudio or DBeaver
- **Terminal Option**: `php artisan tinker` and query models
- **SQL Option**: Open `database/database.sqlite` in any SQLite client

## Lessons & Takeaways

1. **Conventions Save Time**: Laravel's "do this by default" approach prevents decision paralysis. Follow conventions—they exist for a reason.

2. **Factories Are Not Optional**: Creating factories alongside models isn't extra work—it's investing in your test suite's maintainability.

3. **Idempotent Seeders Matter**: A seeder that breaks on the second run is a time waster. Use `firstOrCreate()` and `truncate()` wisely.

4. **Type Safety Compounds**: Every type hint prevents one future bug. Invest in them early. PHP 8 makes this painless.

5. **Testing Multiplies Value**: An untested feature is a liability. Browser tests catch what unit tests miss. Use both.

6. **Eager Load by Default**: Lazy loading seems free until you deploy with 1,000 users. Always eager load relationships in production queries.

7. **Eloquent Relationships Are Documentation**: Well-named relationships make code read like English:
   ```php
   $article->author()->name  // Clear what this is
   $article->category()->name  // Clear what this is
   ```

## Future Considerations

- **Full-Text Search**: Add scout + Meilisearch for article searching
- **Versioning**: Use `laravel/telescope` for debugging production issues
- **Caching**: Add Redis for frequently-accessed categories/articles
- **API Layer**: If mobile apps are planned, extract an API resource layer
- **Admin Panel**: Consider Filament if admin features grow beyond current scope

---

**Cenote represents a clean, modern Laravel application.** It demonstrates best practices in database design, testing strategy, and developer experience. Every decision was made with production readiness and team scalability in mind.

