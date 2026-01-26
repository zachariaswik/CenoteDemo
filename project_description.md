# Cenote: A Distraction-Less Learning Environment

## Project Overview

Cenote is a fullscreen, kiosk designed to create a focused distraction-free learning evnironment. This project is aimed at creating a website for Cenote, where people can learn more about the project, through a public-facing website.

## Technical Architecture

### Frontend Stack
- **Framework**: React via Inertia.js v2 (TypeScript)
- **Bundler**: Vite
- **Type Safety**: Wayfinder (generates type-safe route functions)
- **Styling**: Tailwind CSS
- **State Management**: Inertia's built-in reactivity

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

### Core Architecture Pattern: MVC + Inertia

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

### Frontend Architecture: Inertia + React

Inertia bridges the gap between Laravel and React. Instead of building an API, we pass data directly to React components:

```php
// Backend (routes/web.php)
Route::get('/articles', function () {
    return Inertia::render('Articles/Index', [
        'articles' => Article::with('category', 'author')->paginate(),
    ]);
});
```

```typescript
// Frontend (resources/js/Pages/Articles/Index.tsx)
export default function Index({ articles }) {
    return <div>{articles.map(article => ...)}</div>
}
```

**Why Inertia?** It gives you SPA-like interactivity without the complexity of a separate API. One language for routing, one mental model, and automatic type-safety via Wayfinder.

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
| Filament | 3.3 | Admin panel |
| Inertia | v2 | SPA bridge |
| React | Latest | Frontend framework |
| Pest | 4 | Testing framework |
| Tailwind CSS | Latest | Styling |
| Vite | Latest | Build tool |
| Livewire | 3.7 | Admin panel interactivity |
| Laravel Herd | macOS | Local development |

## Development Workflow

### Starting a Feature
1. **Create the database**: `php artisan make:migration create_table_name`
2. **Create the model**: `php artisan make:model ModelName --factory`
3. **Update the factory**: Add realistic fake data generators
4. **Create tests**: `php artisan make:test FeatureTest --pest`
5. **Build the controller**: Use `Inertia::render()` for responses
6. **Create the React component**: In `resources/js/Pages/`
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

## Admin Panel with Filament

We've implemented a full-featured admin panel using [Filament PHP](https://filamentphp.com/), a powerful admin panel framework that integrates seamlessly with Laravel and Livewire.

### What Filament Gives You

Filament is like having a seasoned admin panel developer on your team. It provides:

1. **CRUD Resources**: Auto-generated create, read, update, delete interfaces
2. **Table Management**: Sortable, searchable, filterable data tables out of the box
3. **Form Builder**: Elegant form components with built-in validation display
4. **Authentication**: Integrated login page with your Laravel auth

### Architecture Overview

```
app/Filament/
├── Resources/
│   ├── UserResource.php           # Manages users
│   │   └── Pages/
│   │       ├── CreateUser.php     # Create form with validation
│   │       ├── EditUser.php       # Edit form with validation
│   │       └── ListUsers.php      # Index with search & filters
│   ├── ArticleResource.php        # Manages articles
│   │   └── Pages/
│   │       ├── CreateArticle.php
│   │       ├── EditArticle.php
│   │       └── ListArticles.php
│   └── CategoryResource.php       # Manages categories
│       └── Pages/
│           ├── CreateCategory.php
│           ├── EditCategory.php
│           └── ListCategories.php
├── Providers/Filament/
│   └── AdminPanelProvider.php     # Panel configuration
```

### Key Features Implemented

#### 1. User Management
- **Index View**: Searchable by name and email, filterable by email verification status
- **Create/Edit**: Name, email, and password fields with proper validation
- **Security**: Password hashing handled automatically

#### 2. Article Management
- **Index View**: 
  - Searchable by title, category, and author
  - Filterable by category, author, published/draft status
- **Create/Edit**: 
  - Auto-generated slug from title
  - Rich text editor for content
  - Category and author dropdown selects with search
  - Date picker for publish date

#### 3. Category Management
- **Index View**: Searchable by name and slug, shows article count
- **Create/Edit**: Auto-generated slug from name

### Using Laravel Form Requests for Validation

The issue requirement was to "Use the Request object of Laravel for the validation of the data." Here's how we implemented this:

**Form Request Classes:**
```php
// app/Http/Requests/StoreUserRequest.php
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
```

**Integration in Filament Pages:**
```php
// app/Filament/Resources/UserResource/Pages/CreateUser.php
protected function mutateFormDataBeforeCreate(array $data): array
{
    $request = new StoreUserRequest;
    $validator = Validator::make($data, $request->rules());
    $validator->validate();  // Throws ValidationException on failure

    return $data;
}
```

**Why this approach?**
1. **Separation of Concerns**: Validation logic lives in dedicated Request classes
2. **Reusability**: Same rules can be used for API endpoints if needed
3. **Consistency**: Single source of truth for validation rules
4. **Testability**: Request classes can be unit tested independently

### Testing the Admin Panel

Filament provides excellent testing utilities through Livewire. Our tests verify:

- **List Operations**: Records appear in tables, search works, filters work
- **Create Operations**: Valid data succeeds, invalid data shows errors
- **Edit Operations**: Existing data loads correctly, updates persist
- **Delete Operations**: Records are removed from database

**Example Test:**
```php
it('can search articles by title', function () {
    $articles = Article::factory()->count(10)->create();
    $searchArticle = $articles->first();

    Livewire::test(ListArticles::class)
        ->searchTable($searchArticle->title)
        ->assertCanSeeTableRecords([$searchArticle]);
});
```

### Lessons Learned

1. **Filament's Learning Curve is Worth It**: Initial setup takes an hour, but you save days of building admin interfaces.

2. **Form Request Integration Takes Thought**: Filament has its own validation, but integrating Laravel Request classes requires manual wiring in the `mutateFormDataBefore*` methods.

3. **Livewire Testing is Powerful**: The `assertCanSeeTableRecords()`, `searchTable()`, and `filterTable()` methods make testing table functionality a breeze.

4. **Auto-Slug Generation**: Using `live(onBlur: true)` with `afterStateUpdated()` creates a great UX for slug fields:
   ```php
   Forms\Components\TextInput::make('title')
       ->live(onBlur: true)
       ->afterStateUpdated(fn ($state, Forms\Set $set) => 
           $set('slug', Str::slug($state)))
   ```

5. **RichEditor vs Textarea**: Filament's `RichEditor` component provides formatting options out of the box—no external WYSIWYG integration needed.

---

**Cenote represents a clean, modern Laravel application.** It demonstrates best practices in database design, testing strategy, and developer experience. Every decision was made with production readiness and team scalability in mind.

