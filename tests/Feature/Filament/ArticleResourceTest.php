<?php

use App\Filament\Resources\ArticleResource\Pages\CreateArticle;
use App\Filament\Resources\ArticleResource\Pages\EditArticle;
use App\Filament\Resources\ArticleResource\Pages\ListArticles;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

it('can list articles', function () {
    $articles = Article::factory()->count(10)->create();

    Livewire::test(ListArticles::class)
        ->assertCanSeeTableRecords($articles);
});

it('can search articles by title', function () {
    $articles = Article::factory()->count(10)->create();
    $searchArticle = $articles->first();

    Livewire::test(ListArticles::class)
        ->searchTable($searchArticle->title)
        ->assertCanSeeTableRecords([$searchArticle]);
});

it('can filter articles by category', function () {
    $category = Category::factory()->create();
    $articlesInCategory = Article::factory()->count(3)->create(['category_id' => $category->id]);
    $otherArticles = Article::factory()->count(3)->create();

    Livewire::test(ListArticles::class)
        ->filterTable('category', $category->id)
        ->assertCanSeeTableRecords($articlesInCategory)
        ->assertCanNotSeeTableRecords($otherArticles);
});

it('can filter articles by author', function () {
    $author = User::factory()->create();
    $articlesByAuthor = Article::factory()->count(3)->create(['author_id' => $author->id]);
    $otherArticles = Article::factory()->count(3)->create();

    Livewire::test(ListArticles::class)
        ->filterTable('author', $author->id)
        ->assertCanSeeTableRecords($articlesByAuthor)
        ->assertCanNotSeeTableRecords($otherArticles);
});

it('can filter published articles', function () {
    $publishedArticles = Article::factory()->count(3)->published()->create();
    $draftArticles = Article::factory()->count(3)->draft()->create();

    Livewire::test(ListArticles::class)
        ->filterTable('published')
        ->assertCanSeeTableRecords($publishedArticles)
        ->assertCanNotSeeTableRecords($draftArticles);
});

it('can filter draft articles', function () {
    $publishedArticles = Article::factory()->count(3)->published()->create();
    $draftArticles = Article::factory()->count(3)->draft()->create();

    Livewire::test(ListArticles::class)
        ->filterTable('draft')
        ->assertCanSeeTableRecords($draftArticles)
        ->assertCanNotSeeTableRecords($publishedArticles);
});

it('can create an article with valid data', function () {
    $category = Category::factory()->create();
    $author = User::factory()->create();
    $title = 'Test Article Title';
    $slug = Str::slug($title);

    Livewire::test(CreateArticle::class)
        ->fillForm([
            'title' => $title,
            'slug' => $slug,
            'content' => 'Test article content',
            'excerpt' => 'Test article excerpt',
            'category_id' => $category->id,
            'author_id' => $author->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Article::class, [
        'title' => $title,
        'slug' => $slug,
        'category_id' => $category->id,
        'author_id' => $author->id,
    ]);
});

it('validates required fields on create', function () {
    Livewire::test(CreateArticle::class)
        ->fillForm([
            'title' => '',
            'slug' => '',
            'content' => '',
            'excerpt' => '',
            'category_id' => null,
            'author_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['title', 'slug', 'content', 'excerpt', 'category_id', 'author_id']);
});

it('validates unique slug on create', function () {
    $existingArticle = Article::factory()->create();
    $category = Category::factory()->create();

    Livewire::test(CreateArticle::class)
        ->fillForm([
            'title' => 'Test Article',
            'slug' => $existingArticle->slug,
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => $category->id,
            'author_id' => $this->admin->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

it('can retrieve article data for editing', function () {
    $article = Article::factory()->create();

    Livewire::test(EditArticle::class, ['record' => $article->getRouteKey()])
        ->assertFormSet([
            'title' => $article->title,
            'slug' => $article->slug,
            'category_id' => $article->category_id,
            'author_id' => $article->author_id,
        ]);
});

it('can update an article', function () {
    $article = Article::factory()->create();
    $newCategory = Category::factory()->create();
    $newTitle = 'Updated Article Title';
    $newSlug = Str::slug($newTitle);

    Livewire::test(EditArticle::class, ['record' => $article->getRouteKey()])
        ->fillForm([
            'title' => $newTitle,
            'slug' => $newSlug,
            'content' => 'Updated content',
            'excerpt' => 'Updated excerpt',
            'category_id' => $newCategory->id,
            'author_id' => $article->author_id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Article::class, [
        'id' => $article->id,
        'title' => $newTitle,
        'slug' => $newSlug,
        'category_id' => $newCategory->id,
    ]);
});

it('can delete an article', function () {
    $article = Article::factory()->create();

    Livewire::test(EditArticle::class, ['record' => $article->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($article);
});
