<?php

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;

it('can render articles index page', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ListArticles::class)
        ->assertSuccessful();
});

it('can list articles', function () {
    $user = User::factory()->create();
    $articles = Article::factory()->count(3)->create();

    $this->actingAs($user);

    Livewire::test(ListArticles::class)
        ->assertCanSeeTableRecords($articles);
});

it('can search articles by title', function () {
    $user = User::factory()->create();
    $searchableArticle = Article::factory()->create(['title' => 'Laravel Guide']);
    $otherArticle = Article::factory()->create(['title' => 'React Tutorial']);

    $this->actingAs($user);

    Livewire::test(ListArticles::class)
        ->searchTable('Laravel')
        ->assertCanSeeTableRecords([$searchableArticle])
        ->assertCanNotSeeTableRecords([$otherArticle]);
});

it('can search articles by slug', function () {
    $user = User::factory()->create();
    $searchableArticle = Article::factory()->create(['slug' => 'unique-article-slug']);
    $otherArticle = Article::factory()->create(['slug' => 'different-article-slug']);

    $this->actingAs($user);

    Livewire::test(ListArticles::class)
        ->searchTable('unique-article-slug')
        ->assertCanSeeTableRecords([$searchableArticle])
        ->assertCanNotSeeTableRecords([$otherArticle]);
});

it('can filter articles by category', function () {
    $user = User::factory()->create();
    $category1 = Category::factory()->create(['name' => 'Technology']);
    $category2 = Category::factory()->create(['name' => 'Sports']);
    $techArticle = Article::factory()->create(['category_id' => $category1->id]);
    $sportsArticle = Article::factory()->create(['category_id' => $category2->id]);

    $this->actingAs($user);

    Livewire::test(ListArticles::class)
        ->filterTable('category', $category1->id)
        ->assertCanSeeTableRecords([$techArticle])
        ->assertCanNotSeeTableRecords([$sportsArticle]);
});

it('can filter articles by author', function () {
    $user = User::factory()->create();
    $author1 = User::factory()->create(['name' => 'Author One']);
    $author2 = User::factory()->create(['name' => 'Author Two']);
    $article1 = Article::factory()->create(['author_id' => $author1->id]);
    $article2 = Article::factory()->create(['author_id' => $author2->id]);

    $this->actingAs($user);

    Livewire::test(ListArticles::class)
        ->filterTable('author', $author1->id)
        ->assertCanSeeTableRecords([$article1])
        ->assertCanNotSeeTableRecords([$article2]);
});

it('can filter articles by published status', function () {
    $user = User::factory()->create();
    $publishedArticle = Article::factory()->published()->create();
    $draftArticle = Article::factory()->draft()->create();

    $this->actingAs($user);

    Livewire::test(ListArticles::class)
        ->filterTable('published_at', true)
        ->assertCanSeeTableRecords([$publishedArticle])
        ->assertCanNotSeeTableRecords([$draftArticle]);
});

it('can render create article page', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateArticle::class)
        ->assertSuccessful();
});

it('can create an article', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $author = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(CreateArticle::class)
        ->fillForm([
            'title' => 'New Article Title',
            'slug' => 'new-article-title',
            'content' => 'This is the article content.',
            'excerpt' => 'This is the article excerpt.',
            'category_id' => $category->id,
            'author_id' => $author->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('articles', [
        'title' => 'New Article Title',
        'slug' => 'new-article-title',
    ]);
});

it('validates required fields when creating article', function () {
    $this->actingAs(User::factory()->create());

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

it('validates unique slug when creating article', function () {
    $this->actingAs(User::factory()->create());
    $existingArticle = Article::factory()->create(['slug' => 'existing-slug']);
    $category = Category::factory()->create();
    $author = User::factory()->create();

    Livewire::test(CreateArticle::class)
        ->fillForm([
            'title' => 'New Article',
            'slug' => 'existing-slug',
            'content' => 'Content here',
            'excerpt' => 'Excerpt here',
            'category_id' => $category->id,
            'author_id' => $author->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

it('can render edit article page', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditArticle::class, [
        'record' => $article->slug,
    ])
        ->assertSuccessful();
});

it('can update an article', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditArticle::class, [
        'record' => $article->slug,
    ])
        ->fillForm([
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => 'Updated Title',
        'slug' => 'updated-slug',
    ]);
});

it('can delete an article', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditArticle::class, [
        'record' => $article->slug,
    ])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('articles', [
        'id' => $article->id,
    ]);
});

it('has correct navigation icon', function () {
    expect(ArticleResource::getNavigationIcon())->not->toBeNull();
});

it('uses correct model', function () {
    expect(ArticleResource::getModel())->toBe(Article::class);
});
