<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\User;

it('lists all published articles', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create();

    Article::factory()
        ->published()
        ->for($author, 'author')
        ->for($category, 'category')
        ->count(5)
        ->create();

    Article::factory()
        ->draft()
        ->for($author, 'author')
        ->for($category, 'category')
        ->create();

    $response = $this->getJson('/api/v1/articles');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'excerpt',
                    'content',
                ],
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'from', 'last_page', 'per_page', 'to', 'total'],
        ])
        ->assertJsonCount(5, 'data');
});

it('returns 404 for draft articles in list', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create();

    Article::factory()
        ->draft()
        ->for($author, 'author')
        ->for($category, 'category')
        ->create();

    $response = $this->getJson('/api/v1/articles');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

it('shows a single article by slug', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create();

    $article = Article::factory()
        ->published()
        ->for($author, 'author')
        ->for($category, 'category')
        ->create();

    $response = $this->getJson("/api/v1/articles/{$article->slug}");

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'excerpt',
                'content',
            ],
        ])
        ->assertJsonPath('data.slug', $article->slug)
        ->assertJsonPath('data.title', $article->title);
});

it('returns 404 for non-existent article', function () {
    $response = $this->getJson('/api/v1/articles/non-existent-slug');

    $response->assertNotFound();
});

it('returns 404 for draft article by slug', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create();

    $article = Article::factory()
        ->draft()
        ->for($author, 'author')
        ->for($category, 'category')
        ->create();

    $response = $this->getJson("/api/v1/articles/{$article->slug}");

    $response->assertNotFound();
});
